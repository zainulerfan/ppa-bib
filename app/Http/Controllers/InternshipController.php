<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InternshipController extends Controller
{
    public function index(Request $request): View
    {
        $query = InternshipApplication::query()->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('domicile', 'like', "%{$search}%");
            });
        }

        if ($stage = $request->input('stage')) {
            $query->where('current_stage', $stage);
        }

        $allApplications = $query->get();

        // Kelompokkan peserta untuk Kanban Board berdasarkan tahap (A - F)
        $kanban = [
            'A' => $allApplications->where('current_stage', 'A')->values(),
            'B' => $allApplications->where('current_stage', 'B')->values(),
            'C' => $allApplications->where('current_stage', 'C')->values(),
            'D' => $allApplications->where('current_stage', 'D')->values(),
            'E' => $allApplications->where('current_stage', 'E')->values(),
            'F' => $allApplications->where('current_stage', 'F')->values(),
        ];

        return view('internships.index', [
            'applications' => $allApplications,
            'kanban' => $kanban,
            'stages' => InternshipApplication::STAGES,
            'filters' => $request->only(['search', 'stage']),
            'totalCount' => $allApplications->count(),
        ]);
    }

    public function create(): View
    {
        return view('internships.form', [
            'internship' => new InternshipApplication,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'nik' => ['required', 'string', 'max:30'],
            'domicile' => ['required', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'major' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'screening_status' => ['required', 'in:terkirim,on_proses,done'],
        ]);

        $internship = InternshipApplication::create($validated);

        return redirect()->route('internships.show', $internship)
            ->with('status', 'Peserta magang berhasil didaftarkan ke Pipeline (Tahap A: Screening).');
    }

    public function show(InternshipApplication $internship): View
    {
        return view('internships.show', [
            'internship' => $internship,
            'stages' => InternshipApplication::STAGES,
        ]);
    }

    public function edit(InternshipApplication $internship): View
    {
        return view('internships.form', [
            'internship' => $internship,
        ]);
    }

    public function update(Request $request, InternshipApplication $internship): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'nik' => ['required', 'string', 'max:30'],
            'domicile' => ['required', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'major' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'screening_status' => ['required', 'in:terkirim,on_proses,done'],
        ]);

        $internship->update($validated);

        return redirect()->route('internships.show', $internship)
            ->with('status', 'Data dasar peserta magang berhasil diperbarui.');
    }

    /**
     * Update spesifik per tahapan alur magang (A - F).
     */
    public function updateStage(Request $request, InternshipApplication $internship, string $stage): RedirectResponse
    {
        match ($stage) {
            'A' => $this->handleStageA($request, $internship),
            'B' => $this->handleStageB($request, $internship),
            'C' => $this->handleStageC($request, $internship),
            'D' => $this->handleStageD($request, $internship),
            'E' => $this->handleStageE($request, $internship),
            'F' => $this->handleStageF($request, $internship),
            default => abort(400, 'Tahapan tidak dikenali.'),
        };

        return redirect()->route('internships.show', $internship)
            ->with('status', "Tahap {$stage} berhasil diperbarui.");
    }

    private function handleStageA(Request $request, InternshipApplication $internship): void
    {
        $validated = $request->validate([
            'screening_status' => ['required', 'in:terkirim,on_proses,done'],
        ]);

        $internship->update($validated);
    }

    private function handleStageB(Request $request, InternshipApplication $internship): void
    {
        $validated = $request->validate([
            'briefing_date' => ['nullable', 'date'],
            'briefing_status' => ['required', 'in:scheduled,done'],
            'briefing_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $internship->update($validated);
    }

    private function handleStageC(Request $request, InternshipApplication $internship): void
    {
        $validated = $request->validate([
            'mcu_date' => ['nullable', 'date'],
            'mcu_location' => ['nullable', 'string', 'max:255'],
            'mcu_terms' => ['nullable', 'string', 'max:2000'],
            'mcu_status' => ['required', 'in:scheduled,review,follow_up,fit_to_work'],
        ]);

        $internship->update($validated);
    }

    private function handleStageD(Request $request, InternshipApplication $internship): void
    {
        $validated = $request->validate([
            'registration_status' => ['nullable', 'in:pending,done_upload'],
            'ktp_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'kk_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'spdk_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $updates = [];
        $disk = Storage::disk('local');

        $docMapping = [
            'ktp_file' => 'ktp_path',
            'cv_file' => 'cv_path',
            'kk_file' => 'kk_path',
            'spdk_file' => 'spdk_path',
        ];

        foreach ($docMapping as $fileKey => $columnKey) {
            if ($request->hasFile($fileKey)) {
                if ($internship->$columnKey && $disk->exists($internship->$columnKey)) {
                    $disk->delete($internship->$columnKey);
                }
                $path = $request->file($fileKey)->store("internships/{$internship->id}/documents", 'local');
                $updates[$columnKey] = $path;
            }
        }

        if (isset($validated['registration_status'])) {
            $updates['registration_status'] = $validated['registration_status'];
        }

        // Jika semua dokumen utama sudah ada, otomatis set done_upload
        $hasKtp = isset($updates['ktp_path']) || $internship->ktp_path;
        $hasCv = isset($updates['cv_path']) || $internship->cv_path;
        $hasKk = isset($updates['kk_path']) || $internship->kk_path;
        $hasSpdk = isset($updates['spdk_path']) || $internship->spdk_path;

        if ($hasKtp && $hasCv && $hasKk && $hasSpdk) {
            $updates['registration_status'] = 'done_upload';
        }

        $internship->update($updates);
    }

    private function handleStageE(Request $request, InternshipApplication $internship): void
    {
        $validated = $request->validate([
            'mine_permit_status' => ['required', 'in:diajukan,released'],
            'mine_permit_number' => ['nullable', 'string', 'max:100'],
            'mine_permit_date' => ['nullable', 'date'],
        ]);

        $internship->update($validated);
    }

    private function handleStageF(Request $request, InternshipApplication $internship): void
    {
        $validated = $request->validate([
            'is_local' => ['nullable', 'boolean'],
            'departure_date' => ['nullable', 'date'],
            'arrival_information' => ['nullable', 'string', 'max:2000'],
            'onsite_status' => ['required', 'in:pending,arrived,active,completed'],
        ]);

        $validated['is_local'] = $request->boolean('is_local');
        $internship->update($validated);
    }

    /**
     * Unduh berkas dokumen registrasi privat (KTP, CV, KK, SPDK).
     */
    public function downloadDocument(InternshipApplication $internship, string $type): BinaryFileResponse
    {
        $column = match ($type) {
            'ktp' => 'ktp_path',
            'cv' => 'cv_path',
            'kk' => 'kk_path',
            'spdk' => 'spdk_path',
            default => abort(404, 'Jenis dokumen tidak valid.'),
        };

        $path = $internship->$column;
        $disk = Storage::disk('local');

        abort_unless($path && $disk->exists($path), 404, 'Dokumen belum diunggah atau tidak ditemukan.');

        $filename = "dokumen-{$type}-{$internship->name}-{$internship->id}." . pathinfo($path, PATHINFO_EXTENSION);

        return response()->download($disk->path($path), $filename);
    }

    /**
     * Unduh format resmi SPDK kosong (Surat Pernyataan Diri & Komitmen Magang Site BIB).
     */
    public function downloadSpdkTemplate(): Response
    {
        $logoPath = public_path('images/ppa-logo.png');
        $logoBase64 = (extension_loaded('gd') && file_exists($logoPath))
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $pdf = Pdf::loadView('internships.spdk-template', [
            'logoBase64' => $logoBase64,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('FORMAT_SPDK_MAGANG_PPA_SITE_BIB.pdf');
    }

    public function destroy(InternshipApplication $internship): RedirectResponse
    {
        $disk = Storage::disk('local');

        foreach (['ktp_path', 'cv_path', 'kk_path', 'spdk_path'] as $column) {
            if ($internship->$column && $disk->exists($internship->$column)) {
                $disk->delete($internship->$column);
            }
        }

        $internship->delete();

        return redirect()->route('internships.index')
            ->with('status', 'Data peserta magang berhasil dihapus.');
    }
}

