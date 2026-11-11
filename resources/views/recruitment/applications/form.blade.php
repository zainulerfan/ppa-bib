@extends('layouts.app')

@section('title', $application->exists ? 'Ubah Pengajuan' : 'Tambah Pengajuan')
@section('breadcrumb', 'Input Pengajuan')

@section('content')
    <div class="page-heading-row">
        <div>
            <a class="back-link" href="{{ route('recruitment.applications.index') }}"><x-icon name="arrow-right" /> Kembali ke data pengajuan</a>
            <span class="section-kicker">SURAT - SURAT REKRUTMEN</span>
            <h1 class="page-title">{{ $application->exists ? 'Ubah pengajuan' : 'Tambah pengajuan' }}</h1>
            <p class="page-subtitle">Lengkapi data kandidat dan detail pengajuan.</p>
        </div>
    </div>
    @if($errors->any())
        <div class="form-alert" role="alert">Periksa kembali isian yang ditandai. {{ $errors->first() }}</div>
    @endif

    <form class="entry-form" action="{{ $application->exists ? route('recruitment.applications.update', $application) : route('recruitment.applications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($application->exists) @method('PUT') @endif
        <section class="form-section">
            <div class="form-section-heading"><span>01</span><div><h2>Identitas pengajuan</h2><p>Nomor pengajuan dan informasi kandidat.</p></div></div>
            <div class="form-grid">
                <label class="form-field"><span>No urut</span><input name="sequence_number" value="{{ old('sequence_number', $application->sequence_number) }}" maxlength="100"></label>
                <label class="form-field"><span>No surat</span><input name="letter_number" value="{{ old('letter_number', $application->letter_number) }}" maxlength="150"></label>
                <label class="form-field"><span>Nama <b>*</b></span><input name="name" value="{{ old('name', $application->name) }}" maxlength="255" required></label>
                <label class="form-field"><span>NIK (new hired)</span><input name="nik" value="{{ old('nik', $application->nik) }}" maxlength="100"></label>
                <label class="form-field"><span>Posisi <b>*</b></span>
                    <select name="position" required><option value="">Pilih posisi</option>@foreach(['OPERATOR', 'PRODUCTION', 'MECHANIC'] as $option)<option value="{{ $option }}" @selected(old('position', $application->position) === $option)>{{ $option }}</option>@endforeach</select>
                </label>
                <label class="form-field"><span>Departemen <b>*</b></span>
                    <select name="department" required><option value="">Pilih departemen</option>@foreach(['PLANT', 'PRODUKSI', 'MANAGEMENT'] as $option)<option value="{{ $option }}" @selected(old('department', $application->department) === $option)>{{ $option }}</option>@endforeach</select>
                </label>
                <label class="form-field"><span>Jenis kelamin <b>*</b></span>
                    <select name="gender" required><option value="">Pilih jenis kelamin</option>@foreach(['Laki-laki', 'Perempuan'] as $option)<option value="{{ $option }}" @selected(old('gender', $application->gender) === $option)>{{ $option }}</option>@endforeach</select>
                </label>
                <label class="form-field"><span>Jenis pengajuan <b>*</b></span>
                    <select name="application_type" required><option value="">Pilih pengajuan</option>@foreach(['INDUKSI', 'IM', 'TES PRAKTEK', 'REFRESHMENT', 'RO', 'PSIKOTEST'] as $option)<option value="{{ $option }}" @selected(old('application_type', $application->application_type) === $option)>{{ $option }}</option>@endforeach</select>
                </label>
                <label class="form-field"><span>Tanggal pengajuan</span><input type="date" name="application_date" value="{{ old('application_date', $application->application_date?->format('Y-m-d')) }}"></label>
                <label class="form-field"><span>Tanggal tes</span><input type="date" name="test_date" value="{{ old('test_date', $application->test_date?->format('Y-m-d')) }}"></label>
                <label class="form-field"><span>Keterangan pengajuan</span>
                    <select name="candidate_note"><option value="">Pilih keterangan</option>@foreach(['Kedatangan karyawan baru', 'Tes praktek karyawan baru'] as $option)<option value="{{ $option }}" @selected(old('candidate_note', $application->candidate_note) === $option)>{{ $option }}</option>@endforeach</select>
                </label>
                <label class="form-field"><span>Keterangan posisi gabungan</span><input name="assignment_note" value="{{ old('assignment_note', $application->assignment_note) }}" placeholder="Contoh: Pos gabungan Trimartani" maxlength="255"></label>
                <label class="form-field"><span>No surat (tambahan)</span><input name="secondary_letter_number" value="{{ old('secondary_letter_number', $application->secondary_letter_number) }}" maxlength="150"></label>
                <label class="form-field"><span>No. HP</span><input type="tel" name="phone_number" value="{{ old('phone_number', $application->phone_number) }}" maxlength="30"></label>
                <label class="form-field form-field-wide"><span>Izin Visitor Kit BIB (PDF, maks. 10 MB)</span><input type="file" name="visitor_permit" accept="application/pdf,.pdf"><small>Unggah dokumen PDF. File tersimpan privat dan hanya dapat diunduh setelah login.</small>
                    @if($application->visitor_permit_path)<span class="existing-file">PDF saat ini tersedia. Unggah file baru jika ingin menggantinya.</span>@endif
                </label>
            </div>
        </section>
        <div class="form-actions"><a class="button button-secondary" href="{{ route('recruitment.applications.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan pengajuan</button></div>
    </form>
@endsection
