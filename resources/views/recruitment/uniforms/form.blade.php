@extends('layouts.app')

@section('title', $uniformRequest->exists ? 'Ubah Input Seragam' : 'Tambah Input Seragam')
@section('breadcrumb', 'Input Seragam')

@section('content')
    <div class="page-heading-row"><div>
        <a class="back-link" href="{{ route('recruitment.uniforms.index') }}"><x-icon name="arrow-right" /> Kembali ke data seragam</a>
        <span class="section-kicker">SURAT - SURAT REKRUTMEN</span>
        <h1 class="page-title">{{ $uniformRequest->exists ? 'Ubah data seragam' : 'Tambah data seragam' }}</h1>
        <p class="page-subtitle">Catat detail seragam kerja yang diajukan.</p>
    </div></div>
    @if($errors->any())<div class="form-alert" role="alert">Periksa kembali isian yang ditandai. {{ $errors->first() }}</div>@endif
    <form class="entry-form" action="{{ $uniformRequest->exists ? route('recruitment.uniforms.update', $uniformRequest) : route('recruitment.uniforms.store') }}" method="POST">
        @csrf @if($uniformRequest->exists) @method('PUT') @endif
        <section class="form-section">
            <div class="form-section-heading"><span>01</span><div><h2>Informasi kebutuhan seragam</h2><p>Lengkapi identitas karyawan dan spesifikasi seragam.</p></div></div>
            <div class="form-grid">
                <label class="form-field form-field-wide">
                    <span>Tautkan Pengajuan Kandidat (Opsional)</span>
                    <select name="recruitment_application_id">
                        <option value="">-- Tanpa Tautan Pengajuan (Standalone) --</option>
                        @foreach($applications as $app)
                            <option value="{{ $app->id }}" @selected(old('recruitment_application_id', $uniformRequest->recruitment_application_id) == $app->id)>
                                {{ $app->name }} (NIK: {{ $app->nik ?: '—' }}, Posisi: {{ $app->position }}, Dept: {{ $app->department }})
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="form-field"><span>Tanggal dibuat</span><input type="date" name="created_date" value="{{ old('created_date', $uniformRequest->created_date?->format('Y-m-d')) }}"></label>
                <label class="form-field"><span>No finger / NIK</span><input name="finger_number" value="{{ old('finger_number', $uniformRequest->finger_number) }}" maxlength="100"></label>
                <label class="form-field"><span>Nama <b>*</b></span><input name="name" value="{{ old('name', $uniformRequest->name) }}" maxlength="255" required></label>
                <label class="form-field"><span>Departemen</span><select name="department"><option value="">Pilih departemen</option>@foreach(['PLANT','PRODUKSI','MANAGEMENT'] as $option)<option value="{{ $option }}" @selected(old('department', $uniformRequest->department) === $option)>{{ $option }}</option>@endforeach</select></label>
                <label class="form-field"><span>Posisi</span><select name="position"><option value="">Pilih posisi</option>@foreach(['OPERATOR','PRODUCTION','MECHANIC'] as $option)<option value="{{ $option }}" @selected(old('position', $uniformRequest->position) === $option)>{{ $option }}</option>@endforeach</select></label>
                <label class="form-field"><span>Warna seragam</span><input name="uniform_color" value="{{ old('uniform_color', $uniformRequest->uniform_color) }}" maxlength="100"></label>
                <label class="form-field"><span>Ukuran seragam</span><input name="uniform_size" value="{{ old('uniform_size', $uniformRequest->uniform_size) }}" maxlength="50"></label>
                <label class="form-field"><span>Tanggal pembuatan (2)</span><input type="date" name="request_date" value="{{ old('request_date', $uniformRequest->request_date?->format('Y-m-d')) }}"></label>
                <label class="form-field"><span>Jumlah pengambilan <b>*</b></span><input type="number" name="pickup_quantity" min="1" max="10000" value="{{ old('pickup_quantity', $uniformRequest->pickup_quantity ?: 1) }}" required></label>
                <label class="form-field form-field-wide"><span>Keterangan</span><textarea name="note" rows="3" maxlength="2000">{{ old('note', $uniformRequest->note) }}</textarea></label>
            </div>
        </section>
        <div class="form-actions"><a class="button button-secondary" href="{{ route('recruitment.uniforms.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan data seragam</button></div>
    </form>
@endsection
