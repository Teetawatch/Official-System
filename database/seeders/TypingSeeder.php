<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin User
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Student User
        \App\Models\User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'student_id' => '65001',
                'class_name' => 'ม.6/1',
                'password' => bcrypt('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );

        // 3. Create Sample Assignments
        \App\Models\TypingAssignment::updateOrCreate(
            ['title' => 'หนังสือราชการ ภายนอก'],
            [
                'content' => 'ขอเชิญประชุมข้าราชการประจำปี 2567...',
                'type' => 'external',
                'difficulty_level' => 3,
                'max_score' => 100,
                'is_active' => true,
                'due_date' => now()->addDays(7),
            ]
        );
        
        \App\Models\TypingAssignment::updateOrCreate(
            ['title' => 'บันทึกข้อความ ภายใน'],
            [
                'content' => 'บันทึกข้อความ ส่วนราชการ...',
                'type' => 'internal',
                'difficulty_level' => 2,
                'max_score' => 50,
                'is_active' => true,
                'due_date' => now()->addDays(3),
            ]
        );
    }
}
