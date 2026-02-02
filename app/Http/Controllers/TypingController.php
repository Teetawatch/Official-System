<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypingAssignment;
use App\Models\TypingSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TypingController extends Controller
{
    /**
     * Get effective deadline for an assignment.
     * If due_date is set at midnight (00:00:00), treat it as end of day (23:59:59).
     * This ensures that setting a due date of "January 20" means the entire day is valid.
     *
     * @param TypingAssignment $assignment
     * @return \Carbon\Carbon|null
     */
    private function getEffectiveDeadline($assignment)
    {
        if (!$assignment->due_date) {
            return null;
        }

        $dueDate = $assignment->due_date;

        // If time is exactly 00:00:00, treat it as end of day
        if ($dueDate->format('H:i:s') === '00:00:00') {
            return $dueDate->copy()->endOfDay();
        }

        return $dueDate;
    }

    /**
     * Check if an assignment deadline has passed.
     *
     * @param TypingAssignment $assignment
     * @return bool
     */
    private function isDeadlinePassed($assignment): bool
    {
        $effectiveDeadline = $this->getEffectiveDeadline($assignment);

        if (!$effectiveDeadline) {
            return false;
        }

        return now()->greaterThan($effectiveDeadline);
    }
    /**
     * Show the typing practice interface for a specific assignment.
     */
    public function practice($id)
    {
        // In a real app, validation and authorization would go here.
        // For Phase 1-2 demo, we might mock data if DB is empty, 
        // but since we have DB tables now, let's try to use them or fallback to mock.

        $assignment = TypingAssignment::findOrFail($id);

        return view('typing.practice', compact('assignment'));
    }

    /**
     * Display student dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get user's submissions with scores
        $submissions = TypingSubmission::where('user_id', $user->id)
            ->with('assignment')
            ->whereNotNull('score')
            ->latest()
            ->get();

        // Calculate stats
        $totalScore = $submissions->sum('score');
        $avgScore = $submissions->avg('score') ?? 0;
        $submittedCount = TypingSubmission::where('user_id', $user->id)->count();
        $totalAssignments = TypingAssignment::where('is_active', true)->count();

        // Get pending assignments
        $pendingAssignments = TypingAssignment::where('is_active', true)
            ->whereDoesntHave('submissions', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(3)
            ->get();

        // Get leaderboard (top 5)
        $leaderboard = User::where('role', 'student')
            ->withSum('typingSubmissions', 'score')
            ->with(['equippedFrame', 'equippedNameColor']) // Eager load for mini leaderboard
            ->orderByDesc('typing_submissions_sum_score')
            ->take(5)
            ->get();

        // Get user rank
        $userRank = User::where('role', 'student')
            ->withSum('typingSubmissions', 'score')
            ->get()
            ->sortByDesc('typing_submissions_sum_score')
            ->values()
            ->search(function ($u) use ($user) {
                return $u->id === $user->id;
            }) + 1;

        $totalStudents = User::where('role', 'student')->count();


        // Prepare Chart Data (Last 10 graded submissions)
        $chartSubmissions = TypingSubmission::where('user_id', $user->id)
            ->whereNotNull('score')
            ->latest()
            ->take(10)
            ->get()
            ->sortBy('created_at'); // Sort by date ascending for chart

        $chartData = [
            'labels' => $chartSubmissions->map(function ($sub) {
                return $sub->created_at->format('d/m');
            })->values(),
            'wpm' => $chartSubmissions->pluck('wpm')->values(),
            'accuracy' => $chartSubmissions->pluck('accuracy')->values(),
            'scores' => $chartSubmissions->pluck('score')->values(),
        ];

        return view('typing.student.dashboard', compact(
            'user',
            'submissions',
            'totalScore',
            'avgScore',
            'submittedCount',
            'totalAssignments',
            'pendingAssignments',
            'leaderboard',
            'userRank',
            'totalStudents',
            'chartData'
        ));
    }

    /**
     * Display list of assignments for student.
     */
    public function assignments()
    {
        $userId = Auth::id();

        $assignments = TypingAssignment::where('is_active', true)
            ->with([
                'submissions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->where(function ($q) use ($userId) {
                // Show if:
                // 1. Due date is not set (null) OR Future date
                // 2. OR User has already submitted it (even if expired)
                $q->whereNull('due_date')
                    ->orWhere('due_date', '>=', now())
                    ->orWhereHas('submissions', function ($subQ) use ($userId) {
                    $subQ->where('user_id', $userId);
                });
            })
            ->orderByRaw("CAST(regexp_replace(chapter, '[^0-9]', '') AS UNSIGNED) ASC")
            ->orderBy('chapter', 'asc')
            ->latest()
            ->get();

        // Calculate stats
        $totalAssignments = TypingAssignment::where('is_active', true)->count();
        $submittedCount = TypingSubmission::where('user_id', $userId)->count();
        $pendingCount = $totalAssignments - $submittedCount;
        $waitingGrade = TypingSubmission::where('user_id', $userId)->whereNull('score')->count();
        $gradedCount = TypingSubmission::where('user_id', $userId)->whereNotNull('score')->count();

        return view('typing.student.assignments', compact(
            'assignments',
            'totalAssignments',
            'pendingCount',
            'waitingGrade',
            'gradedCount'
        ));
    }

    /**
     * Display student submissions history.
     */
    public function submissions()
    {
        $userId = Auth::id(); // Fallback for dev
        $submissions = TypingSubmission::where('user_id', $userId)
            ->with([
                'assignment' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->latest()
            ->paginate(20);

        return view('typing.student.submissions', compact('submissions'));
    }

    /**
     * Display specific submission details.
     */
    public function showSubmission($id)
    {
        $userId = Auth::id();
        $submission = TypingSubmission::where('user_id', $userId)
            ->with(['assignment'])
            ->findOrFail($id);

        return view('typing.student.submission-detail', compact('submission'));
    }

    /**
     * Display student grades/performance.
     */
    public function grades()
    {
        $user = Auth::user();
        $userId = $user->id;

        $submissions = TypingSubmission::where('user_id', $userId)
            ->with(['assignment'])
            ->whereNotNull('score')
            ->latest()
            ->get();

        // Calculate stats
        $totalScore = $submissions->sum('score');
        $avgScore = $submissions->avg('score') ?? 0;
        $avgWpm = $submissions->avg('wpm') ?? 0;
        $avgAccuracy = $submissions->avg('accuracy') ?? 0;

        // Get user rank
        $userRank = User::where('role', 'student')
            ->withSum('typingSubmissions', 'score')
            ->get()
            ->sortByDesc('typing_submissions_sum_score')
            ->values()
            ->search(function ($u) use ($userId) {
                return $u->id === $userId;
            }) + 1;

        $totalStudents = User::where('role', 'student')->count();

        // Calculate scores by chapter
        $chapterScores = $submissions->groupBy(function ($submission) {
            return $submission->assignment->chapter ?? 'General';
        })->map(function ($group) {
            return [
                'total' => $group->sum('score'),
                'max' => $group->sum(function ($s) {
                    return $s->assignment->max_score;
                }),
                'count' => $group->count(),
                'avg' => $group->avg('score'),
                'submissions' => $group
            ];
        });

        return view('typing.student.grades', compact(
            'submissions',
            'totalScore',
            'avgScore',
            'avgWpm',
            'avgAccuracy',
            'userRank',
            'totalStudents',
            'chapterScores'
        ));
    }

    /**
     * Store the typing submission results.
     */
    public function store(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'wpm' => 'required|integer',
            'accuracy' => 'required|numeric',
            'time_taken' => 'required|integer',
            'score' => 'required|numeric',
            'keystrokes_data' => 'nullable|json'
        ]);

        // Create submission
        // Note: For now we might not have a logged in user if testing without auth,
        // so we'll check Auth::id() or use a dummy ID if needed for the demo.
        $userId = Auth::id(); // Fallback to ID 1 for testing

        // Ensure user exists if we are using dummy ID 1
        if (!Auth::check() && \App\Models\User::find(1) === null) {
            // Create a dummy user if not exists (should be done in seeder really)
            // But for controller safety:
            // return response()->json(['error' => 'User not found'], 404);
        }

        $assignment = TypingAssignment::findOrFail($id);
        if ($this->isDeadlinePassed($assignment)) {
            return response()->json([
                'success' => false,
                'message' => 'หมดเวลาส่งงานแล้ว (Deadline Exceeded)'
            ], 403);
        }

        $submission = TypingSubmission::create([
            'user_id' => $userId,
            'assignment_id' => $id,
            'wpm' => $validated['wpm'],
            'accuracy' => $validated['accuracy'],
            'time_taken' => $validated['time_taken'],
            'score' => $validated['score'],
            'keystrokes_data' => $validated['keystrokes_data'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'บันทึกผลการพิมพ์เรียบร้อยแล้ว',
            'submission_id' => $submission->id
        ]);
    }

    /**
     * Display leaderboard with all students ranked by score.
     */
    public function leaderboard(Request $request)
    {
        $user = Auth::user();
        $totalAssignments = TypingAssignment::where('is_active', true)->count();

        // Get all unique classes from students
        $classes = User::where('role', 'student')
            ->whereNotNull('class_name')
            ->distinct()
            ->pluck('class_name')
            ->sort()
            ->values();

        // Build query with filters
        $query = User::where('role', 'student')
            ->withSum('typingSubmissions', 'score')
            ->withCount('typingSubmissions')
            ->with(['equippedFrame', 'equippedTheme', 'equippedTitle', 'equippedNameColor', 'equippedProfileBg']);

        // Search filter
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Class filter
        $classFilter = $request->input('class');
        if ($classFilter && $classFilter !== 'all') {
            $query->where('class_name', $classFilter);
        }

        $students = $query->orderByDesc('typing_submissions_sum_score')
            ->paginate(20)
            ->withQueryString(); // Preserve query string for pagination

        // Get top 3 for podium (always unfiltered)
        $top3 = User::where('role', 'student')
            ->withSum('typingSubmissions', 'score')
            ->with(['equippedFrame', 'equippedTheme', 'equippedTitle', 'equippedNameColor', 'equippedProfileBg'])
            ->orderByDesc('typing_submissions_sum_score')
            ->take(3)
            ->get();

        return view('typing.leaderboard', compact(
            'user',
            'students',
            'top3',
            'totalAssignments',
            'classes',
            'search',
            'classFilter'
        ));
    }

    /**
     * Show file upload form for file-type assignments.
     */
    public function showUpload($id)
    {
        $assignment = TypingAssignment::findOrFail($id);

        // Check if assignment is file type
        if ($assignment->submission_type !== 'file') {
            return redirect()->route('typing.student.practice', $id);
        }

        // Check if already submitted
        $existingSubmission = TypingSubmission::where('user_id', Auth::id())
            ->where('assignment_id', $id)
            ->first();

        return view('typing.student.upload', compact('assignment', 'existingSubmission'));
    }

    /**
     * Store uploaded file submission.
     */
    /**
     * Extract metadata from DOCX file
     */
    private function extractDocxMetadata($filePath)
    {
        $metadata = [];
        $zip = new \ZipArchive;

        if ($zip->open($filePath) === TRUE) {
            // Read Core Properties (Author, Dates, Revision)
            if (($index = $zip->locateName('docProps/core.xml')) !== false) {
                $xmlData = $zip->getFromIndex($index);
                $xml = simplexml_load_string($xmlData);

                // Register namespaces to access dc and cp and dcterms
                $namespaces = $xml->getNamespaces(true);
                $dc = $xml->children($namespaces['dc'] ?? null);
                $cp = $xml->children($namespaces['cp'] ?? null);
                $dcterms = $xml->children($namespaces['dcterms'] ?? null);

                $metadata['creator'] = (string) ($dc->creator ?? '');
                $metadata['lastModifiedBy'] = (string) ($cp->lastModifiedBy ?? '');
                $metadata['revision'] = (string) ($cp->revision ?? '0');
                $metadata['created'] = (string) ($dcterms->created ?? '');
                $metadata['modified'] = (string) ($dcterms->modified ?? '');
            }

            // Read App Properties (Total Editing Time, Pages, Words)
            if (($index = $zip->locateName('docProps/app.xml')) !== false) {
                $xmlData = $zip->getFromIndex($index);
                $xml = simplexml_load_string($xmlData);

                $metadata['totalTime'] = (string) ($xml->TotalTime ?? '0');
                $metadata['pages'] = (string) ($xml->Pages ?? '0');
                $metadata['words'] = (string) ($xml->Words ?? '0');
            }

            $zip->close();
        }

        return $metadata;
    }

    /**
     * Extract plain text content from DOCX file
     */
    private function extractDocxText($filePath)
    {
        $content = '';
        $zip = new \ZipArchive;

        if ($zip->open($filePath) === TRUE) {
            // Read Main Document Content
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $xmlData = $zip->getFromIndex($index);
                // Strip tags is a naive but effective way to get Body Text for hashing
                // We prefer strip_tags for speed over full DOM parsing for this purpose
                $content = strip_tags($xmlData);
            }
            $zip->close();
        }

        return $content;
    }

    /**
     * Store uploaded file submission.
     */
    public function storeUpload(Request $request, $id)
    {
        $assignment = TypingAssignment::findOrFail($id);

        // Validate file
        $request->validate([
            'file' => 'required|file|mimes:docx,pdf|max:10240', // Max 10MB
        ]);

        if ($this->isDeadlinePassed($assignment)) {
            return back()->withErrors(['file' => 'หมดเวลาส่งงานแล้ว (Deadline Exceeded)']);
        }

        $userId = Auth::id();
        $user = Auth::user();

        // Check if already submitted
        $existingSubmission = TypingSubmission::where('user_id', $userId)
            ->where('assignment_id', $id)
            ->first();

        if ($existingSubmission) {
            // Delete old file if exists
            if ($existingSubmission->file_path && file_exists(public_path($existingSubmission->file_path))) {
                unlink(public_path($existingSubmission->file_path));
            }
        }

        // Create directory if not exists
        $uploadPath = public_path('uploads/submissions');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $filename = 'submission_' . $userId . '_' . $id . '_' . time() . '.' . $extension;

        // Generate file hash for integrity check
        $tempPath = $file->getRealPath();
        $fileHash = md5_file($tempPath);

        // --- STRICT SECURITY CHECKS START ---

        // Level 1: Strict Hash Check (Exact Duplicate Check)
        $isDuplicateHash = TypingSubmission::where('assignment_id', $id)
            ->where('file_hash', $fileHash)
            ->where('user_id', '!=', $userId)
            ->exists();

        if ($isDuplicateHash) {
            return back()->withInput()->withErrors([
                'file' => 'ไม่สามารถส่งไฟล์นี้ได้ เนื่องจากระบบตรวจพบว่าไฟล์นี้มีเนื้อหาเหมือนกับไฟล์ที่มีในระบบแล้ว (Duplicate File Signature)'
            ]);
        }

        // Initialize extended metadata
        $docMetadata = [];
        $contentHash = null;

        if (strtolower($extension) === 'docx') {
            try {
                // Extract Metadata
                $docMetadata = $this->extractDocxMetadata($tempPath);

                // Extract Plain Text Content (for Content-Based Similarity Check)
                $plainText = $this->extractDocxText($tempPath);
                // Hash the content (Ignore spaces/newlines to catch reformatting)
                $contentHash = md5(preg_replace('/\s+/', '', $plainText));

                // Add content hash to metadata for storage
                $docMetadata['content_hash'] = $contentHash;

                // Fetch all other submissions for this assignment to compare
                $otherSubmissions = TypingSubmission::where('assignment_id', $id)
                    ->where('user_id', '!=', $userId)
                    ->whereNotNull('file_metadata')
                    ->get();

                foreach ($otherSubmissions as $other) {
                    $otherMeta = $other->file_metadata;

                    if (!is_array($otherMeta)) {
                        continue;
                    }

                    // Level 2: Realistic Typing Speed Check (Anti-Copy-Paste)
                    // If content is substantial (>300 words) but Total Editing Time is suspiciously low (<= 1 min)
                    // This implies the user copied text from elsewhere and pasted it into a new file.
                    if (isset($docMetadata['words']) && isset($docMetadata['totalTime'])) {
                        $wordCount = (int) $docMetadata['words'];
                        $editTime = (int) $docMetadata['totalTime']; // In minutes

                        if ($wordCount > 300 && $editTime <= 1) {
                            return back()->withInput()->withErrors([
                                'file' => 'ปฏิเสธการส่ง: ตรวจพบระยะเวลาการทำงาน (Editing Time) น้อยผิดปกติสำหรับการพิมพ์เนื้อหาจำนวนมาก (เข้าข่าย Copy-Paste)'
                            ]);
                        }
                    }

                    // Level 3: Forensic Metadata Match (Deep Properties)
                    $otherDocx = $otherMeta['docx_metadata'] ?? [];

                    // Check A: Exact Modified Time Match
                    if (isset($docMetadata['modified']) && isset($otherDocx['modified'])) {
                        if ($docMetadata['modified'] === $otherDocx['modified'] && !empty($docMetadata['modified'])) {
                            return back()->withInput()->withErrors([
                                'file' => 'ปฏิเสธการส่ง: ตรวจพบ Meta Data (Timestamp) ตรงกับนักเรียนคนอื่น'
                            ]);
                        }
                    }

                    // Check B: Exact Creation Time Match
                    // Probably impossible for two students to create file at exact same second unless copied.
                    if (isset($docMetadata['created']) && isset($otherDocx['created'])) {
                        if ($docMetadata['created'] === $otherDocx['created'] && !empty($docMetadata['created'])) {
                            return back()->withInput()->withErrors([
                                'file' => 'ปฏิเสธการส่ง: ตรวจพบเวลาสร้างไฟล์ (Creation Time) ตรงกับไฟล์ของคนอื่น'
                            ]);
                        }
                    }

                    // Check C: Statistical Fingerprint (Revision + EditingTime)
                    // If Revision count is identical AND Total Editing Time is identical, it's a clone.
                    if (isset($docMetadata['revision']) && isset($docMetadata['totalTime'])) {
                        if (
                            ($docMetadata['revision'] == ($otherDocx['revision'] ?? -1)) &&
                            ($docMetadata['totalTime'] == ($otherDocx['totalTime'] ?? -1)) &&
                            ($docMetadata['revision'] > 1)
                        ) { // Only check if revision > 1 to avoid fresh files coincidence
                            return back()->withInput()->withErrors([
                                'file' => 'ปฏิเสธการส่ง: ข้อมูลสถิติการแก้ไข (Revision & Editing Time) ซ้ำกับไฟล์ของคนอื่น'
                            ]);
                        }
                    }

                    // Level 4: Identity Check
                    if (isset($docMetadata['lastModifiedBy'])) {
                        $lastMod = trim($docMetadata['lastModifiedBy']);
                        $otherStudent = User::find($other->user_id);
                        if ($otherStudent && strtolower($lastMod) === strtolower($otherStudent->name)) {
                            return back()->withInput()->withErrors([
                                'file' => "ปฏิเสธการส่ง: ไฟล์นี้ถูกแก้ไขล่าสุดโดย {$lastMod} (นักเรียนคนอื่น)"
                            ]);
                        }

                        // Check Creator if available
                        if (isset($docMetadata['creator']) && $otherStudent) {
                            $creator = trim($docMetadata['creator']);
                            if (strtolower($creator) === strtolower($otherStudent->name)) {
                                return back()->withInput()->withErrors([
                                    'file' => "ปฏิเสธการส่ง: ไฟล์นี้ต้นฉบับถูกสร้างโดย {$creator} (นักเรียนคนอื่น)"
                                ]);
                            }
                        }
                    }
                }

            } catch (\Exception $e) {
                // Log error if needed, but allow upload if extraction fails to avoid blocking valid files with weird structure
                // \Log::error($e->getMessage());
            }
        }
        // --- STRICT SECURITY CHECKS END ---

        // 2. Metadata Inspection (For DOCX)
        $docMetadata = [];
        if (strtolower($extension) === 'docx') {
            try {
                $docMetadata = $this->extractDocxMetadata($tempPath);

                // Fetch all other submissions for this assignment to compare
                $otherSubmissions = TypingSubmission::where('assignment_id', $id)
                    ->where('user_id', '!=', $userId)
                    ->whereNotNull('file_metadata')
                    ->get();

                foreach ($otherSubmissions as $other) {
                    $otherMeta = $other->file_metadata;

                    if (!is_array($otherMeta)) {
                        continue;
                    }

                    // Check A: Exact Modified Time Match (Highly suspicious for copy-paste file without edit)
                    if (isset($docMetadata['modified']) && isset($otherMeta['docx_metadata']['modified'])) {
                        if ($docMetadata['modified'] === $otherMeta['docx_metadata']['modified'] && !empty($docMetadata['modified'])) {
                            return back()->withInput()->withErrors([
                                'file' => 'ปฏิเสธการส่ง: ตรวจพบ Meta Data (Timestamp) ตรงกับนักเรียนคนอื่น กรุณาสร้างไฟล์ด้วยตนเอง'
                            ]);
                        }
                    }

                    // Check B: Author/LastModifiedBy Identity Check
                    if (isset($docMetadata['lastModifiedBy'])) {
                        $lastMod = trim($docMetadata['lastModifiedBy']);
                        // Check if 'lastModifiedBy' equals the Other Student's Name in DB
                        $otherStudent = User::find($other->user_id);
                        if ($otherStudent && strtolower($lastMod) === strtolower($otherStudent->name)) {
                            return back()->withInput()->withErrors([
                                'file' => "ปฏิเสธการส่ง: ไฟล์นี้ถูกแก้ไขล่าสุดโดย {$lastMod} ซึ่งเป็นชื่อของนักเรียนคนอื่นในระบบ"
                            ]);
                        }

                        // Check C: Creator Identity Check
                        if (isset($docMetadata['creator']) && $otherStudent) {
                            $creator = trim($docMetadata['creator']);
                            // If creator is another student in the system (and not the teacher/admin/common name)
                            // We compare strictly with the other student's registered name
                            if (strtolower($creator) === strtolower($otherStudent->name)) {
                                return back()->withInput()->withErrors([
                                    'file' => "ปฏิเสธการส่ง: ไฟล์นี้ถูกสร้างโดย {$creator} ซึ่งเป็นชื่อของนักเรียนคนอื่นในระบบ"
                                ]);
                            }
                        }
                    }
                }

                // Check C: Self-Correction (Ensure LastModifiedBy roughly matches current user?)
                // Optional: Might be strict if they use nickname on PC. Skip for now.

            } catch (\Exception $e) {
                // Determine if we should fail or just warn. For strict mode, maybe log.
                // We'll proceed but log error if needed.
            }
        }
        // --- STRICT SECURITY CHECKS END ---

        // Extract basic metadata
        $metadata = [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
            'uploaded_at' => now()->toDateTimeString(),
            'docx_metadata' => $docMetadata // Add the deep metadata
        ];

        // Move file
        $file->move($uploadPath, $filename);

        // Create or update submission
        $submissionData = [
            'user_id' => $userId,
            'assignment_id' => $id,
            'file_path' => 'uploads/submissions/' . $filename,
            'file_name' => $file->getClientOriginalName(),
            'file_hash' => $fileHash,
            'file_metadata' => $metadata, // Will be cast to JSON automatically if model has cast or by Laravel
            'wpm' => 0,
            'accuracy' => 0,
            'time_taken' => 0,
        ];

        if ($existingSubmission) {
            $existingSubmission->update($submissionData);
            $submission = $existingSubmission;
        } else {
            $submission = TypingSubmission::create($submissionData);
        }

        // Notify Admins
        \App\Models\User::where('role', 'admin')->get()->each(function ($admin) use ($submission) {
            $admin->notify(new \App\Notifications\SubmissionReceived($submission));
        });

        return redirect()->route('typing.student.assignments')
            ->with('success', 'อัปโหลดไฟล์เรียบร้อยแล้ว รอการตรวจจากอาจารย์');
    }

    /**
     * Show online editor for assignments.
     */
    public function showEditor($id)
    {
        $assignment = TypingAssignment::findOrFail($id);

        // Check if assignment allows file/text submission
        // For simplicity, we allow editor for 'file' type as an alternative
        if ($assignment->submission_type !== 'file' && $assignment->submission_type !== 'text') {
            return redirect()->route('typing.student.assignments');
        }

        // Check if already submitted
        $existingSubmission = TypingSubmission::where('user_id', Auth::id())
            ->where('assignment_id', $id)
            ->first();

        return view('typing.student.editor', compact('assignment', 'existingSubmission'));
    }

    /**
     * Store online editor submission.
     */
    public function storeEditor(Request $request, $id)
    {
        $assignment = TypingAssignment::findOrFail($id);

        $request->validate([
            'content' => 'required|string',
        ]);

        if ($this->isDeadlinePassed($assignment)) {
            return response()->json(['success' => false, 'message' => 'หมดเวลาส่งงานแล้ว'], 403);
        }

        $userId = Auth::id();

        // Generate a HTML file from the content
        $content = $request->input('content');
        $htmlContent = "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Submission</title></head><body>" . $content . "</body></html>";

        $filename = 'submission_editor_' . $userId . '_' . $id . '_' . time() . '.html';
        $uploadPath = public_path('uploads/submissions');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        file_put_contents($uploadPath . '/' . $filename, $htmlContent);

        // Check submission
        $existingSubmission = TypingSubmission::where('user_id', $userId)
            ->where('assignment_id', $id)
            ->first();

        $submissionData = [
            'user_id' => $userId,
            'assignment_id' => $id,
            'file_path' => 'uploads/submissions/' . $filename,
            'file_name' => 'Online_Document.html',
            'wpm' => 0,
            'accuracy' => 0,
            'time_taken' => 0,
        ];

        if ($existingSubmission) {
            $existingSubmission->update($submissionData);
        } else {
            TypingSubmission::create($submissionData);
        }

        return response()->json(['success' => true, 'redirect' => route('typing.student.assignments')]);
    }
}
