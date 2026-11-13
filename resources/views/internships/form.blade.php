@extends('layouts.app')

@section('title', $internship->exists ? 'Edit Peserta Magang' : 'Pendaftaran Peserta Magang')
@section('breadcrumb', 'Pendaftaran Magang')

@section('content')
    <div class="page-heading-row">
        <div>
            <a class="back-link" href="{{ route('internships.index') }}"><x-icon name="arrow-right" /> Kembali ke Pipeline Magang</a>
            <span class="section-kicker">WEB MAGANG <span class="eyebrow-separator">/</span> TAHAP A: REGISTRASI &amp; SCREENING</span>
            <h1 class="page-title">{{ $internship->exists ? 'Edit Data Peserta Magang' : 'Pendaftaran Peserta Magang Baru' }}</h1>
            <p class="page-subtitle">Isi data identitas calon peserta magang untuk memulai pipeline seleksi Site BIB.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="form-alert" role="alert">
            Periksa kembali isian Anda: {{ $errors->first() }}
        </div>
    @endif

    <form class="entry-form" action="{{ $internship->exists ? route('internships.update', $internship) : route('internships.store') }}" method="POST">
        @csrf
        @if($internship->exists)
            @method('PUT')
        @endif

        <section class="form-section">
            <div class="form-section-heading">
                <span>01</span>
                <div>
                    <h2>Informasi Identitas Peserta (Tahap A)</h2>
                    <p>Lengkapi biodata pokok sesuai KTP dan asal perguruan tinggi/sekolah.</p>
                </div>
            </div>

            <div class="form-grid">
                <label class="form-field">
                    <span>Nama Lengkap <b>*</b></span>
                    <input type="text" name="name" value="{{ old('name', $internship->name) }}" required placeholder="Contoh: Muhammad Rizky">
                </label>

                <label class="form-field">
                    <span>Nomor Induk Kependudukan (NIK) <b>*</b></span>
                    <input type="text" name="nik" value="{{ old('nik', $internship->nik) }}" required placeholder="16 digit NIK sesuai KTP">
                </label>

                <label class="form-field">
                    <span>Tanggal Lahir <b>*</b></span>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $internship->birth_date?->format('Y-m-d')) }}" required>
                </label>

                <label class="form-field">
                    <span>Alamat Domisili <b>*</b></span>
                    <input type="text" name="domicile" value="{{ old('domicile', $internship->domicile) }}" required placeholder="Kota / Kabupaten tempat tinggal saat ini">
                </label>

                <label class="form-field">
                    <span>Asal Institusi / Universitas</span>
                    <input type="text" name="institution" value="{{ old('institution', $internship->institution) }}" placeholder="Contoh: Universitas Lambung Mangkurat / Politeknik Negeri">
                </label>

                <label class="form-field">
                    <span>Program Studi / Jurusan</span>
                    <input type="text" name="major" value="{{ old('major', $internship->major) }}" placeholder="Contoh: Teknik Pertambangan / Teknik Mesin">
                </label>

                <label class="form-field">
                    <span>No. WhatsApp / HP</span>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $internship->phone_number) }}" placeholder="Contoh: 081234567890">
                </label>

                <label class="form-field">
                    <span>Alamat Email</span>
                    <input type="email" name="email" value="{{ old('email', $internship->email) }}" placeholder="Contoh: nama@domain.com">
                </label>

                <label class="form-field form-field-wide">
                    <span>Status Screening Awal <b>*</b></span>
                    <select name="screening_status" required>
                        <option value="terkirim" @selected(old('screening_status', $internship->screening_status ?: 'terkirim') === 'terkirim')>
                            Terkirim (Berkas diterima, menunggu verifikasi)
                        </option>
                        <option value="on_proses" @selected(old('screening_status', $internship->screening_status) === 'on_proses')>
                            On Proses (Sedang ditinjau oleh HC)
                        </option>
                        <option value="done" @selected(old('screening_status', $internship->screening_status) === 'done')>
                            Done (Lolos screening, lanjut ke Tahap B: Briefing)
                        </option>
                    </select>
                </label>
            </div>
        </section>

        <div class="form-actions">
            <a class="button button-secondary" href="{{ route('internships.index') }}">Batal</a>
            <button class="button button-primary" type="submit">
                {{ $internship->exists ? 'Simpan Perubahan' : 'Daftarkan ke Pipeline' }}
            </button>
        </div>
    </form>
@endsection
