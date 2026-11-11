<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentPpeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecruitmentPpeController extends Controller
{
    public function index(): View
    {
        return view('recruitment.ppe.index', [
            'requests' => RecruitmentPpeRequest::latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('recruitment.ppe.form', ['ppeRequest' => new RecruitmentPpeRequest]);
    }

    public function store(Request $request): RedirectResponse
    {
        RecruitmentPpeRequest::create($request->validate($this->rules()));

        return redirect()->route('recruitment.ppe.index')->with('status', 'Input APD berhasil disimpan.');
    }

    public function edit(RecruitmentPpeRequest $ppeRequest): View
    {
        return view('recruitment.ppe.form', compact('ppeRequest'));
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
