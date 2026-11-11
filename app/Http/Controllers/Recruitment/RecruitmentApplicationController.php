<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class RecruitmentApplicationController extends Controller
{
    public function index(): View
    {
        return view('recruitment.applications.index', [
            'applications' => RecruitmentApplication::latest()->paginate(15),
        ]);
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
