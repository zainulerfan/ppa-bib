<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentUniformRequest extends Model
{
    protected $fillable = [
        'created_date',
        'finger_number',
        'name',
        'department',
        'position',
        'uniform_color',
        'uniform_size',
        'note',
        'request_date',
        'pickup_quantity',
    ];

    protected function casts(): array
    {
        return [
            'created_date' => 'date',
            'request_date' => 'date',
            'pickup_quantity' => 'integer',
        ];
    }
}
