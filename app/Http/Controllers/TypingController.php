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
            ->whereDoesntHave('submissions', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(3)
            ->get();
        
        // Get leaderboard (top 5)
        $leaderboard = User::where('role', 'student')
            ->withSum('typingSubmissions', 'score')
            ->orderByDesc('typing_submissions_sum_score')
            ->take(5)
            ->get();
        
        // Get user rank
        $userRank = User::where('role', 'student')
            ->withSum('typingSubmissions', 'score')
            ->get()
            ->sortByDesc('typing_submissions_sum_score')
            ->values()
            ->search(function($u) use ($user) {
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
            'labels' => $chartSubmissions->map(function($sub) {
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
            ->with(['submissions' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->latest()
            ->paginate(10);
        
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
            ->with(['assignment' => function($query) {
                $query->withTrashed();
            }])
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
            ->search(function($u) use ($userId) {
                return $u->id === $userId;
            }) + 1;
        
        $totalStudents = User::where('role', 'student')->count();
        
        return view('typing.student.grades', compact(
            'submissions', 
            'totalScore', 
            'avgScore', 
            'avgWpm', 
            'avgAccuracy',
            'userRank',
            'totalStudents'
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
        if ($assignment->due_date && now()->greaterThan($assignment->due_date)) {
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
            ->withCount('typingSubmissions');
        
        // Search filter
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
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
    public function storeUpload(Request $request, $id)
    {
        $assignment = TypingAssignment::findOrFail($id);
        
        // Validate file
        $request->validate([
            'file' => 'required|file|mimes:docx,pdf|max:10240', // Max 10MB
        ]);

        if ($assignment->due_date && now()->greaterThan($assignment->due_date)) {
            return back()->withErrors(['file' => 'หมดเวลาส่งงานแล้ว (Deadline Exceeded)']);
        }
        
        $userId = Auth::id();
        
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
        $filename = 'submission_' . $userId . '_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Generate file hash for integrity check
        $tempPath = $file->getRealPath();
        $fileHash = md5_file($tempPath);

        // Check for duplicate files (Plagiarism Protection)
        // Check if any OTHER student has submitted the exact same file hash for this assignment
        $isDuplicate = TypingSubmission::where('assignment_id', $id)
            ->where('file_hash', $fileHash)
            ->where('user_id', '!=', $userId)
            ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors([
                'file' => 'ไม่สามารถส่งไฟล์นี้ได้ เนื่องจากระบบตรวจพบไฟล์ซ้ำกับนักเรียนคนอื่น (Duplicate File Detected)'
            ]);
        }

        // Extract basic metadata
        $metadata = [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
            'uploaded_at' => now()->toDateTimeString(),
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
            'file_metadata' => $metadata,
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
        \App\Models\User::where('role', 'admin')->get()->each(function($admin) use ($submission) {
            $admin->notify(new \App\Notifications\SubmissionReceived($submission));
        });
        
        return redirect()->route('typing.student.assignments')
            ->with('success', 'อัปโหลดไฟล์เรียบร้อยแล้ว รอการตรวจจากอาจารย์');
    }
}
