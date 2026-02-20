<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class StudentRegistrationController extends Controller
{
    /**
     * Show the registration form with list of unregistered students
     */
    public function showForm(Request $request)
    {
        // Get class names for filter
        $classes = User::where('role', 'student')
            ->where('is_registered', false)
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        // Get unregistered students
        $query = User::where('role', 'student')
            ->where('is_registered', false);

        if ($request->filled('class')) {
            $query->where('class_name', $request->class);
        }

        $students = $query->orderBy('class_name')
            ->orderBy('name')
            ->get(['id', 'name', 'student_id', 'class_name']);

        return view('typing.auth.student-register', compact('students', 'classes'));
    }

    /**
     * Handle the student registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'username' => [
                'required', 
                'string', 
                'min:4', 
                'max:50', 
                'unique:users,username', 
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $value)) {
                        $fail('Username ต้องเป็นตัวอักษรภาษาอังกฤษ ตัวเลข, จุด (.) หรือ _ เท่านั้น');
                    }
                }
            ],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'student_id.required' => 'กรุณาเลือกชื่อของคุณ',
            'student_id.exists' => 'ไม่พบข้อมูลนักเรียน',
            'username.required' => 'กรุณากรอก Username',
            'username.min' => 'Username ต้องมีอย่างน้อย 4 ตัวอักษร',
            'username.max' => 'Username ต้องไม่เกิน 50 ตัวอักษร',
            'username.unique' => 'Username นี้ถูกใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
        ]);

        // Find the student and verify they haven't registered yet
        $student = User::where('id', $validated['student_id'])
            ->where('role', 'student')
            ->where('is_registered', false)
            ->first();

        if (!$student) {
            return back()->withErrors(['student_id' => 'นักเรียนคนนี้ได้ลงทะเบียนแล้ว หรือไม่พบข้อมูล'])->withInput();
        }

        // Update the student record
        $student->update([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'is_registered' => true,
        ]);

        // Auto login
        Auth::login($student);

        return redirect()->route('typing.student.dashboard')
            ->with('success', 'ลงทะเบียนสำเร็จ! ยินดีต้อนรับเข้าสู่ระบบ');
    }

    /**
     * API endpoint to get students by class (for AJAX filtering)
     */
    public function getStudentsByClass(Request $request)
    {
        $query = User::where('role', 'student')
            ->where('is_registered', false);

        if ($request->filled('class')) {
            $query->where('class_name', $request->class);
        }

        $students = $query->orderBy('name')
            ->get(['id', 'name', 'student_id', 'class_name']);

        return response()->json($students);
    }
}
