<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipApplication extends Model
{
    public const STAGES = [
        'A' => ['name' => 'Screening', 'desc' => 'Pemeriksaan data & kualifikasi dasar'],
        'B' => ['name' => 'Briefing', 'desc' => 'Pengarahan awal & komitmen magang'],
        'C' => ['name' => 'MCU', 'desc' => 'Pemeriksaan kesehatan & uji kelayakan kerja'],
        'D' => ['name' => 'Registrasi & Dokumen', 'desc' => 'Unggah KTP, CV, KK, & SPDK'],
        'E' => ['name' => 'Mine Permit', 'desc' => 'Penerbitan izin masuk area tambang BIB'],
        'F' => ['name' => 'Kedatangan Onsite', 'desc' => 'Mobilisasi & orientasi lapangan'],
    ];

    protected $fillable = [
        'name',
        'birth_date',
        'nik',
        'domicile',
        'institution',
        'major',
        'phone_number',
        'email',
        'screening_status',
        'briefing_date',
        'briefing_status',
        'briefing_notes',
        'mcu_date',
        'mcu_location',
        'mcu_terms',
        'mcu_status',
        'ktp_path',
        'cv_path',
        'kk_path',
        'spdk_path',
        'registration_status',
        'mine_permit_status',
        'mine_permit_number',
        'mine_permit_date',
        'is_local',
        'departure_date',
        'arrival_information',
        'onsite_status',
        'current_stage',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'briefing_date' => 'date',
            'mcu_date' => 'date',
            'mine_permit_date' => 'date',
            'departure_date' => 'date',
            'is_local' => 'boolean',
        ];
    }

    /**
     * Hitung secara dinamis tahapan pipeline saat ini.
     */
    public function determineStage(): string
    {
        if ($this->onsite_status === 'arrived' || $this->onsite_status === 'active' || $this->onsite_status === 'completed') {
            return 'F';
        }

        if ($this->mine_permit_status === 'released') {
            return 'F';
        }

        if ($this->registration_status === 'done_upload') {
            return 'E';
        }

        if ($this->mcu_status === 'fit_to_work') {
            return 'D';
        }

        if ($this->briefing_status === 'done') {
            return 'C';
        }

        if ($this->screening_status === 'done') {
            return 'B';
        }

        return 'A';
    }

    /**
     * Persentase penyelesaian pipeline (0% - 100%).
     */
    public function getProgressPercentAttribute(): int
    {
        $stageWeights = [
            'A' => 15,
            'B' => 30,
            'C' => 50,
            'D' => 70,
            'E' => 85,
            'F' => ($this->onsite_status === 'active' || $this->onsite_status === 'completed') ? 100 : 92,
        ];

        return $stageWeights[$this->current_stage] ?? 10;
    }

    /**
     * Perbarui field current_stage sebelum disimpan.
     */
    protected static function booted(): void
    {
        static::saving(function (InternshipApplication $application) {
            $application->current_stage = $application->determineStage();
        });
    }
}
