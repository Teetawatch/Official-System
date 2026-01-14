<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Simple validation to ensure required fields exist
        if (!isset($row['email']) || !isset($row['student_id']) || !isset($row['name'])) {
            return null;
        }

        // Skip if email or student_id already exists to prevent duplicate errors
        if (User::where('email', $row['email'])->orWhere('student_id', $row['student_id'])->exists()) {
            return null;
        }

        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'student_id' => $row['student_id'],
            'class_name' => $row['class_name'] ?? null,
            'role' => 'student',
            'password' => Hash::make($row['student_id']), // Default password is student ID
        ]);
    }
}
