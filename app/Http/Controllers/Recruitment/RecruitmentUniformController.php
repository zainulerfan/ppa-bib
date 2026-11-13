<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentUniformRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecruitmentUniformController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request)->with('application');

        return view('recruitment.uniforms.index', [
            'requests' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['search', 'department', 'position']),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($request)->with('application');

        $filename = 'rekap-kebutuhan-seragam-'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Tanggal Dibuat',
                'Nama Karyawan',
                'Terkait Pengajuan',
                'No Finger / NIK',
                'Departemen',
                'Posisi',
                'Warna Seragam',
                'Ukuran Seragam',
                'Jumlah Pengambilan',
                'Tanggal Pembuatan (2)',
                'Keterangan',
                'Dibuat Pada',
            ]);

            $index = 1;
            $query->chunk(200, function ($items) use ($handle, &$index) {
                foreach ($items as $item) {
                    fputcsv($handle, [
                        $index++,
                        $item->created_date?->format('d/m/Y') ?? '',
                        $item->name,
                        $item->application ? $item->application->name.' ('.$item->application->application_type.')' : '',
                        $item->finger_number ? "'".$item->finger_number : '',
                        $item->department,
                        $item->position,
                        $item->uniform_color,
                        $item->uniform_size,
                        $item->pickup_quantity,
                        $item->request_date?->format('d/m/Y') ?? '',
                        $item->note,
                        $item->created_at?->format('d/m/Y H:i') ?? '',
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
        $query = RecruitmentUniformRequest::query()->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('finger_number', 'like', "%{$search}%");
            });
        }

        if ($department = $request->input('department')) {
            $query->where('department', $department);
        }

        if ($position = $request->input('position')) {
            $query->where('position', $position);
        }

        return $query;
    }

    public function create(Request $request): View
    {
        $uniformRequest = new RecruitmentUniformRequest;

        if ($applicationId = $request->input('application_id')) {
            $application = RecruitmentApplication::find($applicationId);
            if ($application) {
                $uniformRequest->recruitment_application_id = $application->id;
                $uniformRequest->name = $application->name;
                $uniformRequest->department = $application->department;
                $uniformRequest->position = $application->position;
                $uniformRequest->finger_number = $application->nik;
            }
        }

        return view('recruitment.uniforms.form', [
            'uniformRequest' => $uniformRequest,
            'applications' => RecruitmentApplication::orderBy('name')->get(['id', 'name', 'position', 'department', 'nik']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        RecruitmentUniformRequest::create($request->validate($this->rules()));

        return redirect()->route('recruitment.uniforms.index')->with('status', 'Input seragam berhasil disimpan.');
    }

    public function edit(RecruitmentUniformRequest $uniformRequest): View
    {
        return view('recruitment.uniforms.form', [
            'uniformRequest' => $uniformRequest,
            'applications' => RecruitmentApplication::orderBy('name')->get(['id', 'name', 'position', 'department', 'nik']),
        ]);
    }

    public function update(Request $request, RecruitmentUniformRequest $uniformRequest): RedirectResponse
    {
        $uniformRequest->update($request->validate($this->rules()));

        return redirect()->route('recruitment.uniforms.index')->with('status', 'Input seragam berhasil diperbarui.');
    }

    public function destroy(RecruitmentUniformRequest $uniformRequest): RedirectResponse
    {
        $uniformRequest->delete();

        return redirect()->route('recruitment.uniforms.index')->with('status', 'Input seragam berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'recruitment_application_id' => ['nullable', 'exists:recruitment_applications,id'],
            'created_date' => ['nullable', 'date'],
            'finger_number' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'in:PLANT,PRODUKSI,MANAGEMENT'],
            'position' => ['nullable', 'in:OPERATOR,PRODUCTION,MECHANIC'],
            'uniform_color' => ['nullable', 'string', 'max:100'],
            'uniform_size' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:2000'],
            'request_date' => ['nullable', 'date'],
            'pickup_quantity' => ['required', 'integer', 'min:1', 'max:10000'],
        ];
    }
}
