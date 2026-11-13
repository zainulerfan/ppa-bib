@extends('layouts.app')

@section('title', $ppeRequest->exists ? 'Ubah Input APD' : 'Tambah Input APD')
@section('breadcrumb', 'Input APD')

@section('content')
    <div class="page-heading-row"><div>
        <a class="back-link" href="{{ route('recruitment.ppe.index') }}"><x-icon name="arrow-right" /> Kembali ke data APD</a>
        <span class="section-kicker">SURAT - SURAT REKRUTMEN</span>
        <h1 class="page-title">{{ $ppeRequest->exists ? 'Ubah data APD' : 'Tambah data APD' }}</h1>
        <p class="page-subtitle">Catat kebutuhan perlengkapan pelindung karyawan.</p>
    </div></div>
    @if($errors->any())<div class="form-alert" role="alert">Periksa kembali isian yang ditandai. {{ $errors->first() }}</div>@endif
    <form class="entry-form" action="{{ $ppeRequest->exists ? route('recruitment.ppe.update', $ppeRequest) : route('recruitment.ppe.store') }}" method="POST">
        @csrf @if($ppeRequest->exists) @method('PUT') @endif
        <section class="form-section">
            <div class="form-section-heading"><span>01</span><div><h2>Informasi kebutuhan APD</h2><p>Lengkapi identitas dan perlengkapan yang dibutuhkan.</p></div></div>
            <div class="form-grid">
                <label class="form-field form-field-wide">
                    <span>Tautkan Pengajuan Kandidat (Opsional)</span>
                    <select name="recruitment_application_id">
                        <option value="">-- Tanpa Tautan Pengajuan (Standalone) --</option>
                        @foreach($applications as $app)
                            <option value="{{ $app->id }}" @selected(old('recruitment_application_id', $ppeRequest->recruitment_application_id) == $app->id)>
                                {{ $app->name }} (NIK: {{ $app->nik ?: '—' }}, Posisi: {{ $app->position }}, Dept: {{ $app->department }})
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="form-field"><span>Tanggal dibuat</span><input type="date" name="created_date" value="{{ old('created_date', $ppeRequest->created_date?->format('Y-m-d')) }}"></label>
                <label class="form-field"><span>Nama <b>*</b></span><input name="name" value="{{ old('name', $ppeRequest->name) }}" maxlength="255" required></label>
                <label class="form-field"><span>Posisi</span><select name="position"><option value="">Pilih posisi</option>@foreach(['OPERATOR','PRODUCTION','MECHANIC'] as $option)<option value="{{ $option }}" @selected(old('position', $ppeRequest->position) === $option)>{{ $option }}</option>@endforeach</select></label>
                <label class="form-field"><span>Sepatu</span><input name="shoes" value="{{ old('shoes', $ppeRequest->shoes) }}" placeholder="Ukuran / jumlah" maxlength="100"></label>
                <label class="form-field"><span>Helm</span><input name="helmet" value="{{ old('helmet', $ppeRequest->helmet) }}" maxlength="100"></label>
                <label class="form-field"><span>Kacamata</span><input name="safety_glasses" value="{{ old('safety_glasses', $ppeRequest->safety_glasses) }}" maxlength="100"></label>
                <label class="form-field"><span>Ear plug</span><input name="ear_plug" value="{{ old('ear_plug', $ppeRequest->ear_plug) }}" maxlength="100"></label>
                <label class="form-field"><span>Sarung tangan</span><input name="gloves" value="{{ old('gloves', $ppeRequest->gloves) }}" maxlength="100"></label>
                <label class="form-field"><span>Masker</span><input name="mask" value="{{ old('mask', $ppeRequest->mask) }}" maxlength="100"></label>
                <label class="form-field"><span>Tanggal pembuatan (2)</span><input type="date" name="preparation_date" value="{{ old('preparation_date', $ppeRequest->preparation_date?->format('Y-m-d')) }}"></label>
                <label class="form-field"><span>NRP</span><input name="nrp" value="{{ old('nrp', $ppeRequest->nrp) }}" maxlength="100"></label>
                <label class="form-field"><span>Status proses <b>*</b></span><select name="process_status" required>@foreach(['Dalam proses','Selesai'] as $option)<option value="{{ $option }}" @selected(old('process_status', $ppeRequest->process_status ?: 'Dalam proses') === $option)>{{ $option }}</option>@endforeach</select></label>
                <label class="form-field form-field-wide"><span>Keterangan</span><textarea name="note" rows="3" maxlength="2000">{{ old('note', $ppeRequest->note) }}</textarea></label>
            </div>
        </section>
        <div class="form-actions"><a class="button button-secondary" href="{{ route('recruitment.ppe.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan data APD</button></div>
    </form>
@endsection
