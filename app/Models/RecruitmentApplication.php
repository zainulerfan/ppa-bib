<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentApplication extends Model
{
    protected $fillable = [
        'sequence_number',
        'letter_number',
        'name',
        'position',
        'department',
        'candidate_note',
        'application_date',
        'test_date',
        'nik',
        'assignment_note',
        'gender',
        'application_type',
        'secondary_letter_number',
        'phone_number',
        'visitor_permit_path',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'test_date' => 'date',
        ];
    }
}
