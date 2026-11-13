<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentApplication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecruitmentApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request)
            ->withCount(['ppeRequests', 'uniformRequests']);

        return view('recruitment.applications.index', [
            'applications' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['search', 'position', 'department', 'application_type']),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($request)
            ->withCount(['ppeRequests', 'uniformRequests']);

        $filename = 'rekap-pengajuan-rekrutmen-'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'No Urut',
                'No Surat',
                'No Surat Tambahan',
                'Nama Kandidat',
                'NIK',
                'Jenis Kelamin',
                'Posisi',
                'Departemen',
                'Jenis Pengajuan',
                'Tanggal Pengajuan',
                'Tanggal Tes',
                'No HP',
                'Keterangan Kandidat',
                'Catatan Penugasan',
                'Lampiran Izin Visitor',
                'Jumlah Request APD',
                'Jumlah Request Seragam',
                'Tanggal Dibuat',
            ]);

            $index = 1;
            $query->chunk(200, function ($applications) use ($handle, &$index) {
                foreach ($applications as $app) {
                    fputcsv($handle, [
                        $index++,
                        $app->sequence_number,
                        $app->letter_number,
                        $app->secondary_letter_number,
                        $app->name,
                        $app->nik ? "'".$app->nik : '',
                        $app->gender,
                        $app->position,
                        $app->department,
                        $app->application_type,
                        $app->application_date?->format('d/m/Y') ?? '',
                        $app->test_date?->format('d/m/Y') ?? '',
                        $app->phone_number ? "'".$app->phone_number : '',
                        $app->candidate_note,
                        $app->assignment_note,
                        $app->visitor_permit_path ? 'Ada' : 'Tidak Ada',
                        $app->ppe_requests_count,
                        $app->uniform_requests_count,
                        $app->created_at?->format('d/m/Y H:i') ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function filteredQuery(Request $request): Builder
    {
        $query = RecruitmentApplication::query()->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('letter_number', 'like', "%{$search}%")
                    ->orWhere('secondary_letter_number', 'like', "%{$search}%");
            });
        }

        if ($position = $request->input('position')) {
            $query->where('position', $position);
        }

        if ($department = $request->input('department')) {
            $query->where('department', $department);
        }

        if ($type = $request->input('application_type')) {
            $query->where('application_type', $type);
        }

        return $query;
    }

    public function create(): View
    {
        return view('recruitment.applications.form', [
            'application' => new RecruitmentApplication,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('visitor_permit')) {
            $path = $request->file('visitor_permit')->store('recruitment/visitor-permits', 'local');

            if ($path === false) {
                throw new RuntimeException('Gagal menyimpan PDF izin visitor secara privat.');
            }

            $data['visitor_permit_path'] = $path;
        }

        unset($data['visitor_permit']);
        RecruitmentApplication::create($data);

        return redirect()->route('recruitment.applications.index')->with('status', 'Pengajuan rekrutmen berhasil disimpan.');
    }

    public function edit(RecruitmentApplication $application): View
    {
        return view('recruitment.applications.form', compact('application'));
    }

    public function update(Request $request, RecruitmentApplication $application): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $previousPermit = $application->visitor_permit_path;

        if ($request->hasFile('visitor_permit')) {
            $path = $request->file('visitor_permit')->store('recruitment/visitor-permits', 'local');

            if ($path === false) {
                throw new RuntimeException('Gagal menyimpan PDF izin visitor secara privat.');
            }

            $data['visitor_permit_path'] = $path;
        }

        unset($data['visitor_permit']);
        $application->update($data);

        if (isset($data['visitor_permit_path']) && $previousPermit) {
            Storage::disk('local')->delete($previousPermit);
        }

        return redirect()->route('recruitment.applications.index')->with('status', 'Pengajuan rekrutmen berhasil diperbarui.');
    }

    public function destroy(RecruitmentApplication $application): RedirectResponse
    {
        $permitPath = $application->visitor_permit_path;
        $application->delete();

        if ($permitPath) {
            Storage::disk('local')->delete($permitPath);
        }

        return redirect()->route('recruitment.applications.index')->with('status', 'Pengajuan rekrutmen berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'sequence_number' => ['nullable', 'string', 'max:100'],
            'letter_number' => ['nullable', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'in:OPERATOR,PRODUCTION,MECHANIC'],
            'department' => ['required', 'in:PLANT,PRODUKSI,MANAGEMENT'],
            'candidate_note' => ['nullable', 'in:Kedatangan karyawan baru,Tes praktek karyawan baru'],
            'application_date' => ['nullable', 'date'],
            'test_date' => ['nullable', 'date'],
            'nik' => ['nullable', 'string', 'max:100'],
            'assignment_note' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'application_type' => ['required', 'in:INDUKSI,IM,TES PRAKTEK,REFRESHMENT,RO,PSIKOTEST'],
            'secondary_letter_number' => ['nullable', 'string', 'max:150'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'visitor_permit' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
