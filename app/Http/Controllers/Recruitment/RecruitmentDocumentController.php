<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentPpeRequest;
use App\Models\RecruitmentUniformRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
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
            'rows' => $this->getDocumentRows($type, $recordData),
        ]);
    }

    public function downloadPdf(string $type, ?string $record = null): Response
    {
        abort_unless(isset(self::DOCUMENTS[$type]), 404);

        $recordData = match ($type) {
            'apd' => $record ? RecruitmentPpeRequest::findOrFail($record) : null,
            'seragam' => $record ? RecruitmentUniformRequest::findOrFail($record) : null,
            default => $record ? RecruitmentApplication::findOrFail($record) : null,
        };

        $logoPath = public_path('images/ppa-logo.png');
        $logoBase64 = (extension_loaded('gd') && file_exists($logoPath))
            ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath))
            : null;

        $pdf = Pdf::loadView('recruitment.pdf', [
            'document' => self::DOCUMENTS[$type],
            'type' => $type,
            'record' => $recordData,
            'rows' => $this->getDocumentRows($type, $recordData),
            'logoBase64' => $logoBase64,
        ])->setPaper('a4', 'portrait');

        $filename = 'dokumen-'.$type.'-'.($record ?? 'blank').'.pdf';

        return $pdf->download($filename);
    }

    public function downloadPermit(RecruitmentApplication $application): BinaryFileResponse
    {
        $path = $application->visitor_permit_path;

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->download(Storage::disk('local')->path($path), 'izin-visitor-'.$application->id.'.pdf');
    }

    private function getDocumentRows(string $type, $record): array
    {
        $formatDate = fn ($date) => $date?->format('d/m/Y') ?? '';

        return match ($type) {
            'apd' => [
                ['Nama', $record?->name],
                ['Posisi', $record?->position],
                ['Sepatu', $record?->shoes],
                ['Helm', $record?->helmet],
                ['Kacamata', $record?->safety_glasses],
                ['Ear plug', $record?->ear_plug],
                ['Sarung tangan', $record?->gloves],
                ['Masker', $record?->mask],
                ['NRP', $record?->nrp],
                ['Keterangan', $record?->note],
                ['Tanggal dibuat', $formatDate($record?->created_date)],
                ['Tanggal pembuatan (2)', $formatDate($record?->preparation_date)],
            ],
            'seragam' => [
                ['Nama', $record?->name],
                ['No finger / NIK', $record?->finger_number],
                ['Departemen', $record?->department],
                ['Posisi', $record?->position],
                ['Warna seragam', $record?->uniform_color],
                ['Ukuran seragam', $record?->uniform_size],
                ['Keterangan', $record?->note],
                ['Jumlah pengambilan', $record?->pickup_quantity],
                ['Tanggal dibuat', $formatDate($record?->created_date)],
                ['Tanggal pembuatan (2)', $formatDate($record?->request_date)],
            ],
            default => [
                ['Nama kandidat', $record?->name],
                ['NIK', $record?->nik],
                ['Posisi', $record?->position],
                ['Departemen', $record?->department],
                ['Jenis pengajuan', $record?->application_type],
                ['Keterangan', $record?->candidate_note],
                ['Keterangan posisi gabungan', $record?->assignment_note],
                ['No surat', $record?->letter_number],
                ['No surat (tambahan)', $record?->secondary_letter_number],
                ['Tanggal pengajuan', $formatDate($record?->application_date)],
                ['Tanggal tes', $formatDate($record?->test_date)],
                ['No. HP', $record?->phone_number],
            ],
        };
    }
}
