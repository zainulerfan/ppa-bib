<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentPpeRequest extends Model
{
    protected $fillable = [
        'created_date',
        'name',
        'position',
        'shoes',
        'helmet',
        'safety_glasses',
        'note',
        'ear_plug',
        'gloves',
        'mask',
        'preparation_date',
        'nrp',
        'process_status',
    ];

    protected function casts(): array
    {
        return [
            'created_date' => 'date',
            'preparation_date' => 'date',
        ];
    }
}
