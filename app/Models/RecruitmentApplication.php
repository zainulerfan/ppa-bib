<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function ppeRequests(): HasMany
    {
        return $this->hasMany(RecruitmentPpeRequest::class);
    }

    public function uniformRequests(): HasMany
    {
        return $this->hasMany(RecruitmentUniformRequest::class);
    }

    public function latestPpeRequest(): HasOne
    {
        return $this->hasOne(RecruitmentPpeRequest::class)->latestOfMany();
    }

    public function latestUniformRequest(): HasOne
    {
        return $this->hasOne(RecruitmentUniformRequest::class)->latestOfMany();
    }
}
