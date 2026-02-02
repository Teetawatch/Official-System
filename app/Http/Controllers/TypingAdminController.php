<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TypingAssignment;
use App\Models\TypingSubmission;

class TypingAdminController extends Controller
{
    public function dashboard()
    {
        // Calculate real stats
        $totalStudents = User::where('role', 'student')->count();
        $totalAssignments = TypingAssignment::count();
        $totalSubmissions = TypingSubmission::count();
        $pendingSubmissions = TypingSubmission::whereNull('score')->count();
        $averageScore = TypingSubmission::whereNotNull('score')->avg('score') ?? 0;
        $closedAssignments = TypingAssignment::where('is_active', false)->count();

        // Get recent submissions for the table
        $recentSubmissions = TypingSubmission::with(['user', 'assignment'])
            ->latest()
            ->take(5)
            ->get();

        // Get assignments for status section
        $assignments = TypingAssignment::withCount('submissions')
            ->latest()
            ->take(3)
            ->get();

        return view('typing.admin.dashboard', compact(
            'totalStudents',
            'totalAssignments',
            'totalSubmissions',
            'pendingSubmissions',
            'averageScore',
            'closedAssignments',
            'recentSubmissions',
            'assignments'
        ));
    }

    public function students(Request $request)
    {
        $query = User::where('role', 'student');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class')) {
            $query->where('class_name', $request->class);
        }

        $students = $query->withCount('typingSubmissions')
            ->with([
                'typingSubmissions' => function ($q) {
                    $q->whereNotNull('score')->select('user_id', 'score');
                }
            ])
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Get all unique class names from the database (sorted)
        $classes = User::where('role', 'student')
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        return view('typing.admin.students', compact('students', 'classes'));
    }

    public function submissions(Request $request)
    {
        $query = TypingSubmission::with(['user', 'assignment'])
            ->select('typing_submissions.*') // Avoid column collisions
            ->join('users', 'typing_submissions.user_id', '=', 'users.id'); // Join for name sorting/searching

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Assignment Filter
        if ($request->filled('assignment_id')) {
            $query->where('assignment_id', $request->assignment_id);
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereNull('score');
            } elseif ($request->status === 'graded') {
                $query->whereNotNull('score');
            }
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('users.name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('users.name', 'desc');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $submissions = $query->paginate(20)->withQueryString();

        // Stats for the view
        $totalSubmissions = TypingSubmission::count();
        $pendingSubmissions = TypingSubmission::whereNull('score')->count();
        $gradedSubmissions = TypingSubmission::whereNotNull('score')->count();
        $averageScore = TypingSubmission::whereNotNull('score')->avg('score') ?? 0;

        // Get assignments for filter dropdown
        $allAssignments = TypingAssignment::select('id', 'title')->get();

        return view('typing.admin.submissions', compact(
            'submissions',
            'totalSubmissions',
            'pendingSubmissions',
            'gradedSubmissions',
            'averageScore',
            'allAssignments'
        ));
    }

    public function grades(Request $request)
    {
        $query = User::where('role', 'student')
            ->withSum([
                'typingSubmissions as total_score' => function ($q) {
                    $q->whereNotNull('score');
                }
            ], 'score');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Class Filter
        if ($request->filled('class')) {
            $query->where('class_name', $request->class);
        }

        // Sorting
        $sort = $request->input('sort', 'total_desc');
        switch ($sort) {
            case 'total_desc':
                $query->orderByDesc('total_score');
                break;
            case 'total_asc':
                $query->orderBy('total_score', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'id':
                $query->orderBy('student_id', 'asc');
                break;
            default:
                $query->orderByDesc('total_score');
                break;
        }

        // Get students with their submissions
        $students = $query->with([
            'typingSubmissions' => function ($q) {
                $q->with('assignment')->whereNotNull('score')->latest();
            }
        ])
            ->paginate(50) // Increased per page for grades view
            ->withQueryString();

        // Calculate summary stats (respecting filters for consistency)
        $summaryStats = User::where('role', 'student')
            ->withSum([
                'typingSubmissions as student_total_score' => function ($q) {
                    $q->whereNotNull('score');
                }
            ], 'score');

        if ($request->filled('search')) {
            $search = $request->search;
            $summaryStats->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class')) {
            $summaryStats->where('class_name', $request->class);
        }

        $allStudentsData = $summaryStats->get();
        $totalScoresList = $allStudentsData->pluck('student_total_score')->map(fn($s) => $s ?? 0);

        $totalStudents = $allStudentsData->count();
        $averageScore = $totalScoresList->avg() ?? 0;
        $maxScore = $totalScoresList->max() ?? 0;
        $minScore = $totalScoresList->min() ?? 0;

        // Calculate passing rate (passing = total score > 50)
        $passingStudents = $totalScoresList->filter(fn($score) => $score > 50)->count();
        $passingRate = $totalStudents > 0 ? round(($passingStudents / $totalStudents) * 100) : 0;

        // Get assignments for columns
        $assignments = TypingAssignment::select('id', 'title', 'max_score')
            ->orderBy('created_at')
            ->get();

        // Get all unique class names for the filter
        $classes = User::where('role', 'student')
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        return view('typing.admin.grades', compact(
            'students',
            'totalStudents',
            'averageScore',
            'maxScore',
            'minScore',
            'passingRate',
            'assignments',
            'classes'
        ));
    }

    public function updateScore(Request $request, $id)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100', // adjust max if needed check assignment max_score
            'feedback' => 'nullable|string'
        ]);

        $submission = TypingSubmission::findOrFail($id);
        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback']
        ]);

        // Notify Student
        if ($submission->user) {
            $submission->user->notify(new \App\Notifications\AssignmentGraded($submission));
        }

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'บันทึกคะแนนเรียบร้อยแล้ว',
                'score' => $submission->score
            ]);
        }

        return back()->with('success', 'บันทึกคะแนนเรียบร้อยแล้ว');
    }
    public function createStudent()
    {
        return view('typing.admin.students.create');
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'student_id' => 'required|string|max:20|unique:users',
            'class_name' => 'required|string|max:50',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'student',
            'student_id' => $validated['student_id'],
            'class_name' => $validated['class_name'],
        ]);

        return redirect()->route('typing.admin.students.index')
            ->with('success', 'เพิ่มนักเรียนใหม่เรียบร้อยแล้ว');
    }

    public function editStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return view('typing.admin.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'student_id' => 'required|string|max:20|unique:users,student_id,' . $id,
            'class_name' => 'required|string|max:50',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'student_id' => $validated['student_id'],
            'class_name' => $validated['class_name'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $student->update($updateData);

        return redirect()->route('typing.admin.students.index')
            ->with('success', 'อัปเดตข้อมูลนักเรียนเรียบร้อยแล้ว');
    }

    public function destroyStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->delete();

        return redirect()->route('typing.admin.students.index')
            ->with('success', 'ลบข้อมูลนักเรียนเรียบร้อยแล้ว');
    }

    /**
     * Export grades to CSV
     */
    public function exportGradesCsv()
    {
        $students = User::where('role', 'student')
            ->with([
                'typingSubmissions' => function ($q) {
                    $q->with('assignment')->whereNotNull('score');
                }
            ])
            ->orderBy('name')
            ->get();

        $assignments = TypingAssignment::select('id', 'title')->orderBy('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="grades_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($students, $assignments) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            $header = ['ลำดับ', 'รหัสนักเรียน', 'ชื่อ-นามสกุล', 'ห้อง'];
            foreach ($assignments as $assignment) {
                $header[] = $assignment->title;
            }
            $header[] = 'คะแนนรวม';
            $header[] = 'คะแนนเฉลี่ย';
            fputcsv($file, $header);

            // Data rows
            $rowNum = 1;
            foreach ($students as $student) {
                $row = [
                    $rowNum++,
                    $student->student_id ?? '-',
                    $student->name,
                    $student->class_name ?? '-',
                ];

                $totalScore = 0;
                $scoreCount = 0;

                foreach ($assignments as $assignment) {
                    $submission = $student->typingSubmissions->firstWhere('assignment_id', $assignment->id);
                    $score = $submission ? $submission->score : '-';
                    $row[] = $score;

                    if ($submission && $submission->score !== null) {
                        $totalScore += $submission->score;
                        $scoreCount++;
                    }
                }

                $row[] = $totalScore;
                $row[] = $scoreCount > 0 ? round($totalScore / $scoreCount, 2) : '-';

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export submissions as ZIP
     */
    public function exportSubmissionsZip(Request $request)
    {
        $assignmentId = $request->assignment_id;
        if (!$assignmentId) {
            return back()->with('error', 'กรุณาเลือกงานที่ต้องการดาวน์โหลด');
        }

        $assignment = TypingAssignment::findOrFail($assignmentId);
        $submissions = TypingSubmission::where('assignment_id', $assignmentId)
            ->whereNotNull('file_path')
            ->with('user')
            ->get();

        if ($submissions->isEmpty()) {
            return back()->with('error', 'ไม่พบไฟล์งานสำหรับงานนี้');
        }

        $zipFileName = 'submission_files_' . $assignmentId . '_' . date('Ymd_His') . '.zip';
        $zipPath = public_path('downloads/' . $zipFileName);

        // Ensure directory exists
        if (!file_exists(public_path('downloads'))) {
            mkdir(public_path('downloads'), 0755, true);
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            $addedFiles = [];

            foreach ($submissions as $submission) {
                $filePath = public_path($submission->file_path);
                if (file_exists($filePath)) {
                    // Create a nice filename: Class_No_Name_OriginalName
                    $extension = pathinfo($submission->file_name, PATHINFO_EXTENSION);
                    $student = $submission->user;

                    // Sanitize filename
                    $safeName = preg_replace('/[^a-zA-Z0-9ก-๙\-_]/u', '_', $student->name);
                    $safeId = $student->student_id ?? 'NoID';

                    $newFilename = "{$safeId}_{$safeName}.{$extension}";

                    // Handle duplicate filenames
                    $counter = 1;
                    while (in_array($newFilename, $addedFiles)) {
                        $newFilename = "{$safeId}_{$safeName}_{$counter}.{$extension}";
                        $counter++;
                    }

                    $zip->addFile($filePath, $newFilename);
                    $addedFiles[] = $newFilename;
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'ไม่สามารถสร้างไฟล์ ZIP ได้');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Export grades to PDF
     */
    public function exportGradesPdf()
    {
        $students = User::where('role', 'student')
            ->with([
                'typingSubmissions' => function ($q) {
                    $q->with('assignment')->whereNotNull('score');
                }
            ])
            ->orderBy('name')
            ->get();

        $assignments = TypingAssignment::select('id', 'title', 'max_score')->orderBy('created_at')->get();

        // Calculate summary
        $totalStudents = $students->count();
        $allScores = TypingSubmission::whereNotNull('score')->pluck('score');
        $averageScore = $allScores->avg() ?? 0;

        return view('typing.admin.grades-pdf', compact('students', 'assignments', 'totalStudents', 'averageScore'));
    }
    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UsersTemplateExport, 'students_template.xlsx');
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new \App\Imports\UsersImport;
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            // Check if new version with counters
            if (property_exists($import, 'imported')) {
                $message = "นำเข้าข้อมูลสำเร็จ: {$import->imported} รายการ";
                if ($import->skipped > 0) {
                    $message .= " (ข้าม {$import->skipped} รายการ)";
                }
            } else {
                $message = 'นำเข้าข้อมูลนักเรียนเรียบร้อยแล้ว';
            }

            return redirect()->route('typing.admin.students.index')->with('success', $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการนำเข้าข้อมูล: ' . $e->getMessage());
        }
    }

    /**
     * Auto-grade a single file submission using DocxGradingService.
     */
    public function autoGradeSubmission(Request $request, $id)
    {
        $submission = TypingSubmission::with('assignment')->findOrFail($id);

        // Check if it's a file submission
        if (!$submission->file_path) {
            return response()->json(['error' => 'ไม่ใช่งานประเภทอัปโหลดไฟล์'], 400);
        }

        // Check if assignment has master file
        if (!$submission->assignment->master_file_path) {
            return response()->json(['error' => 'ไม่พบไฟล์ต้นฉบับสำหรับงานนี้'], 400);
        }

        // Check file extension is .docx
        if (!str_ends_with(strtolower($submission->file_path), '.docx')) {
            return response()->json(['error' => 'รองรับเฉพาะไฟล์ .docx เท่านั้น'], 400);
        }

        $filePath = public_path($submission->file_path);
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'ไม่พบไฟล์งาน'], 404);
        }

        $masterFilePath = public_path($submission->assignment->master_file_path);
        if (!file_exists($masterFilePath)) {
            return response()->json(['error' => 'ไม่พบไฟล์ต้นฉบับ'], 404);
        }

        try {
            $gradingService = new \App\Services\DocxGradingService();
            $checkFormatting = (bool) $submission->assignment->check_formatting;

            // Extract master text from master file
            $masterText = $gradingService->extractText($masterFilePath);

            $result = $gradingService->gradeSubmission(
                $filePath,
                $masterText,
                $submission->assignment->max_score,
                $checkFormatting,
                $masterFilePath // Pass master file for formatting comparison
            );

            // Build STRICT MODE feedback message with character-level info
            $feedback = sprintf(
                "🔒 ตรวจโหมดเข้มงวด (ต้องตรงกับต้นฉบับเป๊ะๆ)\n\n📝 ความแม่นยำตัวอักษร: %.2f%%\nตัวอักษรถูก: %d/%d ตัว\nคำถูก: %d/%d คำ",
                $result['accuracy'],
                $result['correct_chars'] ?? 0,
                $result['total_chars'] ?? 0,
                $result['correct_words'],
                $result['total_words']
            );

            // Show text accuracy issues if any
            if ($result['accuracy'] < 100) {
                $feedback .= sprintf("\n⚠️ พบตัวอักษรผิด/ขาด: %d ตัว", $result['wrong_chars'] ?? ($result['total_chars'] - ($result['correct_chars'] ?? 0)));
            }

            if ($checkFormatting && isset($result['formatting'])) {
                $totalDeductions = 0;
                $feedback .= sprintf(
                    "\n\n📐 ตรวจรูปแบบเอกสาร: %.0f%%\n",
                    $result['formatting_score']
                );
                $feedback .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

                foreach ($result['formatting']['checks'] as $check) {
                    $icon = $check['passed'] ? '✅' : '❌';
                    $deduction = $check['deduction'] ?? 0;
                    $totalDeductions += $deduction;

                    if ($check['passed']) {
                        $feedback .= "{$icon} {$check['label']}: {$check['actual']}\n";
                    } else {
                        $feedback .= "{$icon} {$check['label']}\n";
                        $feedback .= "   ต้องการ: {$check['expected']}\n";
                        $feedback .= "   พบ: {$check['actual']}\n";
                        if ($deduction > 0) {
                            $feedback .= "   ❗ หักคะแนน: -{$deduction} รายการ\n";
                        }
                    }
                }

                $feedback .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                $feedback .= sprintf(
                    "📊 สรุป: ผ่าน %d/%d รายการ",
                    $result['formatting']['passed'],
                    $result['formatting']['total']
                );
                if ($totalDeductions > 0) {
                    $feedback .= sprintf(" (หักรวม %d รายการ)", $totalDeductions);
                }
                $feedback .= sprintf("\n\n🎯 คะแนนรวม: %.1f/%d", $result['score'], $submission->assignment->max_score);
                $feedback .= sprintf(" (ตัวอักษร 70%% + รูปแบบ 30%%)");
            }

            // Update submission with score
            $submission->update([
                'score' => $result['score'],
                'feedback' => $feedback,
            ]);

            // Notify student
            if ($submission->user) {
                $submission->user->notify(new \App\Notifications\AssignmentGraded($submission));
            }

            $response = [
                'success' => true,
                'score' => $result['score'],
                'accuracy' => $result['accuracy'],
                'correct_words' => $result['correct_words'],
                'total_words' => $result['total_words'],
                'wrong_words' => $result['wrong_words'],
                'missing_words' => $result['missing_words'],
            ];

            if ($checkFormatting && isset($result['formatting_score'])) {
                $response['formatting_score'] = $result['formatting_score'];
                $response['combined_accuracy'] = $result['combined_accuracy'];
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Auto-grade all pending file submissions for an assignment.
     */
    public function autoGradeAllSubmissions(Request $request, $assignmentId)
    {
        $assignment = TypingAssignment::findOrFail($assignmentId);

        if (!$assignment->master_file_path) {
            return response()->json(['error' => 'ไม่พบไฟล์ต้นฉบับสำหรับงานนี้'], 400);
        }

        $masterFilePath = public_path($assignment->master_file_path);
        if (!file_exists($masterFilePath)) {
            return response()->json(['error' => 'ไม่พบไฟล์ต้นฉบับ'], 404);
        }

        $submissions = TypingSubmission::where('assignment_id', $assignmentId)
            ->whereNull('score')
            ->whereNotNull('file_path')
            ->where('file_path', 'like', '%.docx')
            ->get();

        if ($submissions->isEmpty()) {
            return response()->json(['error' => 'ไม่พบงานที่รอตรวจ'], 404);
        }

        $gradingService = new \App\Services\DocxGradingService();
        $masterText = $gradingService->extractText($masterFilePath);
        $checkFormatting = (bool) $assignment->check_formatting;

        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($submissions as $submission) {
            $filePath = public_path($submission->file_path);

            if (!file_exists($filePath)) {
                $errorCount++;
                continue;
            }

            try {
                $result = $gradingService->gradeSubmission(
                    $filePath,
                    $masterText,
                    $assignment->max_score,
                    $checkFormatting,
                    $masterFilePath
                );

                // Build STRICT MODE feedback with character-level info
                $feedback = sprintf(
                    "🔒 ตรวจโหมดเข้มงวด (ต้องตรงกับต้นฉบับเป๊ะๆ)\n\n📝 ความแม่นยำตัวอักษร: %.2f%%\nตัวอักษรถูก: %d/%d ตัว\nคำถูก: %d/%d คำ",
                    $result['accuracy'],
                    $result['correct_chars'] ?? 0,
                    $result['total_chars'] ?? 0,
                    $result['correct_words'],
                    $result['total_words']
                );

                // Show text accuracy issues if any
                if ($result['accuracy'] < 100) {
                    $feedback .= sprintf("\n⚠️ พบตัวอักษรผิด/ขาด: %d ตัว", $result['wrong_chars'] ?? ($result['total_chars'] - ($result['correct_chars'] ?? 0)));
                }

                if ($checkFormatting && isset($result['formatting'])) {
                    $totalDeductions = 0;
                    $feedback .= sprintf("\n\n📐 ตรวจรูปแบบเอกสาร: %.0f%%", $result['formatting_score']);
                    $feedback .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━";

                    foreach ($result['formatting']['checks'] as $check) {
                        $icon = $check['passed'] ? '✅' : '❌';
                        $deduction = $check['deduction'] ?? 0;
                        $totalDeductions += $deduction;

                        if ($check['passed']) {
                            $feedback .= "\n{$icon} {$check['label']}: {$check['actual']}";
                        } else {
                            $feedback .= "\n{$icon} {$check['label']}";
                            $feedback .= "\n   ต้องการ: {$check['expected']}";
                            $feedback .= "\n   พบ: {$check['actual']}";
                            if ($deduction > 0) {
                                $feedback .= "\n   ❗ หักคะแนน: -{$deduction} รายการ";
                            }
                        }
                    }

                    $feedback .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━";
                    $feedback .= sprintf(
                        "\n📊 สรุป: ผ่าน %d/%d รายการ",
                        $result['formatting']['passed'],
                        $result['formatting']['total']
                    );
                    if ($totalDeductions > 0) {
                        $feedback .= sprintf(" (หักรวม %d รายการ)", $totalDeductions);
                    }
                    $feedback .= sprintf(
                        "\n\n🎯 คะแนนรวม: %.1f/%d (ตัวอักษร 70%% + รูปแบบ 30%%)",
                        $result['score'],
                        $assignment->max_score
                    );
                }

                $submission->update([
                    'score' => $result['score'],
                    'feedback' => $feedback,
                ]);

                if ($submission->user) {
                    $submission->user->notify(new \App\Notifications\AssignmentGraded($submission));
                }

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "ตรวจสำเร็จ {$successCount} งาน" . ($errorCount > 0 ? " (ผิดพลาด {$errorCount} งาน)" : ""),
            'graded_count' => $successCount,
            'error_count' => $errorCount,
        ]);
    }
}

