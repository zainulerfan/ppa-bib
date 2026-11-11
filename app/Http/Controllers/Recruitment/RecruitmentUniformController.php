<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentUniformRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecruitmentUniformController extends Controller
{
    public function index(): View
    {
        return view('recruitment.uniforms.index', [
            'requests' => RecruitmentUniformRequest::latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('recruitment.uniforms.form', ['uniformRequest' => new RecruitmentUniformRequest]);
    }

    public function store(Request $request): RedirectResponse
    {
        RecruitmentUniformRequest::create($request->validate($this->rules()));

        return redirect()->route('recruitment.uniforms.index')->with('status', 'Input seragam berhasil disimpan.');
    }

    public function edit(RecruitmentUniformRequest $uniformRequest): View
    {
        return view('recruitment.uniforms.form', compact('uniformRequest'));
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
