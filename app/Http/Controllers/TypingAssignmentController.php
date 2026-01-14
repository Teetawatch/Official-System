<?php

namespace App\Http\Controllers;

use App\Models\TypingAssignment;
use Illuminate\Http\Request;

class TypingAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = TypingAssignment::latest()->paginate(10);
        
        // Mock stats for now or calculate from DB
        $totalAssignments = TypingAssignment::count();
        $activeAssignments = TypingAssignment::where('is_active', true)->count();
        $totalSubmissions = \App\Models\TypingSubmission::count();
        
        return view('typing.admin.assignments', compact('assignments', 'totalAssignments', 'activeAssignments', 'totalSubmissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('typing.admin.assignments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'master_file' => 'nullable|file|mimes:docx|max:10240', // 10MB max
            'check_formatting' => 'boolean',
            'type' => 'required|in:internal,external,command,memo',
            'submission_type' => 'required|in:typing,file',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'max_score' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        // Content is required for typing mode
        if ($validated['submission_type'] === 'typing' && empty($validated['content'])) {
            return back()->withErrors(['content' => 'กรุณากรอกเนื้อหาสำหรับโหมดพิมพ์'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['check_formatting'] = $request->has('check_formatting');

        // Handle master file upload
        if ($request->hasFile('master_file')) {
            $file = $request->file('master_file');
            $filename = 'master_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('master_files', $filename, 'public');
            $validated['master_file_path'] = 'storage/' . $path;
            $validated['master_file_name'] = $file->getClientOriginalName();
        }

        unset($validated['master_file']);
        TypingAssignment::create($validated);

        return redirect()->route('typing.admin.assignments.index')
            ->with('success', 'สร้างบทเรียนใหม่เรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(TypingAssignment $typingAssignment)
    {
        // Maybe show details or preview
        return view('typing.practice', ['assignment' => $typingAssignment]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $assignment = TypingAssignment::findOrFail($id);
        return view('typing.admin.assignments.edit', compact('assignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $assignment = TypingAssignment::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'master_file' => 'nullable|file|mimes:docx|max:10240', // 10MB max
            'check_formatting' => 'boolean',
            'type' => 'required|in:internal,external,command,memo',
            'submission_type' => 'required|in:typing,file',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'max_score' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        // Content is required for typing mode
        if ($validated['submission_type'] === 'typing' && empty($validated['content'])) {
            return back()->withErrors(['content' => 'กรุณากรอกเนื้อหาสำหรับโหมดพิมพ์'])->withInput();
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['check_formatting'] = $request->has('check_formatting');

        // Handle master file upload
        if ($request->hasFile('master_file')) {
            // Delete old file if exists
            if ($assignment->master_file_path && file_exists(public_path($assignment->master_file_path))) {
                unlink(public_path($assignment->master_file_path));
            }
            
            $file = $request->file('master_file');
            $filename = 'master_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('master_files', $filename, 'public');
            $validated['master_file_path'] = 'storage/' . $path;
            $validated['master_file_name'] = $file->getClientOriginalName();
        }

        unset($validated['master_file']);
        $assignment->update($validated);

        return redirect()->route('typing.admin.assignments.index')
            ->with('success', 'อัปเดตบทเรียนเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $assignment = TypingAssignment::findOrFail($id);
        $assignment->delete();

        return redirect()->route('typing.admin.assignments.index')
            ->with('success', 'ลบบทเรียนเรียบร้อยแล้ว');
    }
}
