<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TypingProfileController extends Controller
{
    /**
     * Display user profile page.
     */
    public function show()
    {
        $user = Auth::user();
        
        return view('typing.profile', compact('user'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        $user->update($validated);
        
        return back()->with('success', 'อัปเดตข้อมูลโปรไฟล์เรียบร้อยแล้ว');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        
        $user = Auth::user();
        
        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }
        
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }

    /**
     * Update user avatar.
     * Stores in public/avatars for shared hosting compatibility.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            unlink(public_path($user->avatar));
        }
        
        // Create avatars directory if not exists
        $avatarPath = public_path('avatars');
        if (!file_exists($avatarPath)) {
            mkdir($avatarPath, 0755, true);
        }
        
        // Generate unique filename
        $filename = 'avatar_' . $user->id . '_' . time() . '.' . $request->file('avatar')->getClientOriginalExtension();
        
        // Move file to public/avatars
        $request->file('avatar')->move($avatarPath, $filename);
        
        // Update user avatar path
        $user->update([
            'avatar' => 'avatars/' . $filename,
        ]);
        
        return back()->with('success', 'อัปเดตรูปโปรไฟล์เรียบร้อยแล้ว');
    }
}
