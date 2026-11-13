<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentPpeRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecruitmentPpeController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request)->with('application');

        return view('recruitment.ppe.index', [
            'requests' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['search', 'position', 'process_status']),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($request)->with('application');

        $filename = 'rekap-kebutuhan-apd-'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Tanggal Dibuat',
                'Nama Karyawan',
                'Terkait Pengajuan',
                'Posisi',
                'NRP',
                'Sepatu',
                'Helm',
                'Kacamata',
                'Ear Plug',
                'Sarung Tangan',
                'Masker',
                'Tanggal Pembuatan (2)',
                'Status Proses',
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
                        $item->position,
                        $item->nrp ? "'".$item->nrp : '',
                        $item->shoes,
                        $item->helmet,
                        $item->safety_glasses,
                        $item->ear_plug,
                        $item->gloves,
                        $item->mask,
                        $item->preparation_date?->format('d/m/Y') ?? '',
                        $item->process_status,
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
        $query = RecruitmentPpeRequest::query()->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nrp', 'like', "%{$search}%");
            });
        }

        if ($position = $request->input('position')) {
            $query->where('position', $position);
        }

        if ($status = $request->input('process_status')) {
            $query->where('process_status', $status);
        }

        return $query;
    }

    public function create(Request $request): View
    {
        $ppeRequest = new RecruitmentPpeRequest;

        if ($applicationId = $request->input('application_id')) {
            $application = RecruitmentApplication::find($applicationId);
            if ($application) {
                $ppeRequest->recruitment_application_id = $application->id;
                $ppeRequest->name = $application->name;
                $ppeRequest->position = $application->position;
            }
        }

        return view('recruitment.ppe.form', [
            'ppeRequest' => $ppeRequest,
            'applications' => RecruitmentApplication::orderBy('name')->get(['id', 'name', 'position', 'department', 'nik']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        RecruitmentPpeRequest::create($request->validate($this->rules()));

        return redirect()->route('recruitment.ppe.index')->with('status', 'Input APD berhasil disimpan.');
    }

    public function edit(RecruitmentPpeRequest $ppeRequest): View
    {
        return view('recruitment.ppe.form', [
            'ppeRequest' => $ppeRequest,
            'applications' => RecruitmentApplication::orderBy('name')->get(['id', 'name', 'position', 'department', 'nik']),
        ]);
    }

    public function update(Request $request, RecruitmentPpeRequest $ppeRequest): RedirectResponse
    {
        $ppeRequest->update($request->validate($this->rules()));

        return redirect()->route('recruitment.ppe.index')->with('status', 'Input APD berhasil diperbarui.');
    }

    public function destroy(RecruitmentPpeRequest $ppeRequest): RedirectResponse
    {
        $ppeRequest->delete();

        return redirect()->route('recruitment.ppe.index')->with('status', 'Input APD berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'recruitment_application_id' => ['nullable', 'exists:recruitment_applications,id'],
            'created_date' => ['nullable', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'in:OPERATOR,PRODUCTION,MECHANIC'],
            'shoes' => ['nullable', 'string', 'max:100'],
            'helmet' => ['nullable', 'string', 'max:100'],
            'safety_glasses' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:2000'],
            'ear_plug' => ['nullable', 'string', 'max:100'],
            'gloves' => ['nullable', 'string', 'max:100'],
            'mask' => ['nullable', 'string', 'max:100'],
            'preparation_date' => ['nullable', 'date'],
            'nrp' => ['nullable', 'string', 'max:100'],
            'process_status' => ['required', 'in:Dalam proses,Selesai'],
        ];
    }
}
