<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentPpeRequest extends Model
{
    protected $fillable = [
        'recruitment_application_id',
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

    public function application(): BelongsTo
    {
        return $this->belongsTo(RecruitmentApplication::class, 'recruitment_application_id');
    }
}
