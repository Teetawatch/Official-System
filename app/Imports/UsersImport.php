<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersImport implements ToModel, WithHeadingRow
{
    public $imported = 0;
    public $skipped = 0;
    public $errors = [];

    public function model(array $row)
    {
        // Log the row for debugging
        Log::info('Import row:', $row);

        // Try to get values from different possible column names
        $name = $this->getValue($row, ['name', 'ชื่อ', 'ชื่อ-นามสกุล', 'ชื่อ-สกุล', 'ชื่อนักเรียน']);
        $email = $this->getValue($row, ['email', 'อีเมล', 'e-mail', 'อีเมลล์']);
        $studentId = $this->getValue($row, ['student_id', 'รหัสนักเรียน', 'เลขประจำตัว', 'รหัส']);
        $className = $this->getValue($row, ['class_name', 'ห้อง', 'ห้องเรียน', 'ชั้น', 'ระดับชั้น']);

        // Validation
        if (empty($name)) {
            $this->skipped++;
            $this->errors[] = "Row skipped: missing name";
            return null;
        }

        // Generate email if not provided
        if (empty($email) && !empty($studentId)) {
            $email = $studentId . '@student.local';
        }

        if (empty($email)) {
            $this->skipped++;
            $this->errors[] = "Row skipped: missing email for {$name}";
            return null;
        }

        if (empty($studentId)) {
            $this->skipped++;
            $this->errors[] = "Row skipped: missing student_id for {$name}";
            return null;
        }

        // Skip if email or student_id already exists
        if (User::where('email', $email)->exists()) {
            $this->skipped++;
            $this->errors[] = "Row skipped: email {$email} already exists";
            return null;
        }

        if (User::where('student_id', $studentId)->exists()) {
            $this->skipped++;
            $this->errors[] = "Row skipped: student_id {$studentId} already exists";
            return null;
        }

        $this->imported++;

        return new User([
            'name'     => trim($name),
            'email'    => trim($email),
            'student_id' => trim($studentId),
            'class_name' => $className ? trim($className) : null,
            'role' => 'student',
            'password' => null,
            'is_registered' => false,
        ]);
    }

    /**
     * Get value from row using multiple possible column names
     */
    private function getValue(array $row, array $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            // Try exact match
            if (isset($row[$key]) && !empty($row[$key])) {
                return $row[$key];
            }
            // Try lowercase match
            $lowerKey = strtolower($key);
            if (isset($row[$lowerKey]) && !empty($row[$lowerKey])) {
                return $row[$lowerKey];
            }
        }

        // Try to find by checking all keys
        foreach ($row as $rowKey => $value) {
            foreach ($possibleKeys as $key) {
                if (stripos($rowKey, $key) !== false && !empty($value)) {
                    return $value;
                }
            }
        }

        return null;
    }
}
