<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentPpeRequest;
use App\Models\RecruitmentUniformRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RecruitmentDocumentController extends Controller
{
    private const DOCUMENTS = [
        'visitor-im' => ['title' => 'Pengajuan Visitor (IM)', 'subtitle' => 'FORM PENGAJUAN VISITOR'],
        'psikotest' => ['title' => 'Pengantar Pemeriksaan Psikologi', 'subtitle' => 'FORM PENGANTAR PEMERIKSAAN PSIKOLOGI'],
        'tes-praktek' => ['title' => 'Pengajuan Tes Praktek', 'subtitle' => 'FORM PENGAJUAN TES PRAKTEK'],
        'refreshment' => ['title' => 'Pengajuan Refreshment Unit', 'subtitle' => 'FORM PENGAJUAN REFRESHMENT UNIT'],
        'ro' => ['title' => 'Pengajuan RO', 'subtitle' => 'FORM PENGAJUAN RO'],
        'apd' => ['title' => 'Form Rencana Kebutuhan APD', 'subtitle' => 'FORM RENCANA KEBUTUHAN APD'],
        'seragam' => ['title' => 'Form Pengajuan Kebutuhan Seragam', 'subtitle' => 'FORM PENGAJUAN KEBUTUHAN SERAGAM'],
    ];

    public function show(string $type, ?string $record = null): View
    {
        abort_unless(isset(self::DOCUMENTS[$type]), 404);

        $recordData = match ($type) {
            'apd' => $record ? RecruitmentPpeRequest::findOrFail($record) : null,
            'seragam' => $record ? RecruitmentUniformRequest::findOrFail($record) : null,
            default => $record ? RecruitmentApplication::findOrFail($record) : null,
        };

        return view('recruitment.print', [
            'document' => self::DOCUMENTS[$type],
            'type' => $type,
            'record' => $recordData,
        ]);
    }

    public function downloadPermit(RecruitmentApplication $application): BinaryFileResponse
    {
        $path = $application->visitor_permit_path;

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->download(Storage::disk('local')->path($path), 'izin-visitor-'.$application->id.'.pdf');
    }
}
