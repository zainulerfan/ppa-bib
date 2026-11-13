<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentUniformRequest extends Model
{
    protected $fillable = [
        'recruitment_application_id',
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

    public function application(): BelongsTo
    {
        return $this->belongsTo(RecruitmentApplication::class, 'recruitment_application_id');
    }
}
