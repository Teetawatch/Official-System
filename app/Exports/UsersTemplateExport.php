<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'email',
            'student_id',
            'class_name',
        ];
    }
}
