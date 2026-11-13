@extends('layouts.app')

@section('title', 'Detail Peserta Magang: ' . $internship->name)
@section('breadcrumb', 'Detail Magang')

@section('content')
    <div class="page-heading-row">
        <div>
            <a class="back-link" href="{{ route('internships.index') }}"><x-icon name="arrow-right" /> Kembali ke Pipeline Magang</a>
            <span class="section-kicker">WEB MAGANG <span class="eyebrow-separator">/</span> PIPELINE DETAIL</span>
            <h1 class="page-title">{{ $internship->name }}</h1>
            <p class="page-subtitle">NIK: {{ $internship->nik }} &middot; {{ $internship->institution ?: 'Site BIB' }} ({{ $internship->domicile }})</p>
        </div>
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <a class="button button-secondary" href="{{ route('internships.spdk.template') }}" target="_blank">
                <x-icon name="download" /> Format SPDK Kosong
            </a>
            <a class="button button-secondary" href="{{ route('internships.edit', $internship) }}">
                <x-icon name="edit" /> Edit Data Dasar
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="form-success" role="status">{{ session('status') }}</div>
    @endif

    {{-- 1. VISUAL STEPPER PIPELINE (A - F) --}}
    <div class="pipeline-stepper">
        @php
            $stageOrder = ['A', 'B', 'C', 'D', 'E', 'F'];
            $currentIndex = array_search($internship->current_stage, $stageOrder);
            if ($currentIndex === false) $currentIndex = 0;
        @endphp

        @foreach($stageOrder as $idx => $stg)
            @php
                $isDone = $idx < $currentIndex || ($internship->current_stage === 'F' && in_array($internship->onsite_status, ['arrived', 'active', 'completed']));
                $isActive = $idx === $currentIndex && !$isDone;
            @endphp
            <div class="step-item {{ $isDone ? 'is-done' : '' }} {{ $isActive ? 'is-active' : '' }}">
                <div class="step-circle">
                    @if($isDone)
                        ✓
                    @else
                        {{ $stg }}
                    @endif
                </div>
                <div class="step-title">Tahap {{ $stg }}: {{ $stages[$stg]['name'] }}</div>
                <div class="step-subtitle">
                    @if($isDone)
                        Selesai
                    @elseif($isActive)
                        Tahap Aktif
                    @else
                        Menunggu
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- KONTEN DETAIL STEP-BY-STEP (A - F) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 18px;">

        {{-- TAHAP A: SCREENING --}}
        <div class="stage-card">
            <div class="stage-card-header">
                <div class="stage-card-title">
                    <span class="badge-stage">A</span>
                    <span>Tahap A: Data Dasar &amp; Screening</span>
                </div>
                <span class="table-badge">
                    {{ ucfirst(str_replace('_', ' ', $internship->screening_status)) }}
                </span>
            </div>

            <div style="font-size: 10px; color: #475569; margin-bottom: 14px; line-height: 1.6;">
                <div><strong>Nama:</strong> {{ $internship->name }}</div>
                <div><strong>NIK:</strong> {{ $internship->nik }}</div>
                <div><strong>Tgl Lahir:</strong> {{ $internship->birth_date?->format('d/m/Y') }}</div>
                <div><strong>Domisili:</strong> {{ $internship->domicile }}</div>
                <div><strong>Kampus/Prodi:</strong> {{ $internship->institution ?: '—' }} ({{ $internship->major ?: '—' }})</div>
                <div><strong>Kontak:</strong> {{ $internship->phone_number ?: '—' }} | {{ $internship->email ?: '—' }}</div>
            </div>

            <form action="{{ route('internships.stage.update', ['internship' => $internship, 'stage' => 'A']) }}" method="POST">
                @csrf @method('PUT')
                <div style="display: flex; gap: 8px; align-items: flex-end;">
                    <label class="form-field" style="flex: 1;">
                        <span>Perbarui Status Screening</span>
                        <select name="screening_status" required>
                            <option value="terkirim" @selected($internship->screening_status === 'terkirim')>Terkirim (Baru)</option>
                            <option value="on_proses" @selected($internship->screening_status === 'on_proses')>On Proses (Review HC)</option>
                            <option value="done" @selected($internship->screening_status === 'done')>Done (Lolos Screening)</option>
                        </select>
                    </label>
                    <button type="submit" class="button button-primary" style="min-height: 37px;">Update Tahap A</button>
                </div>
            </form>
        </div>

        {{-- TAHAP B: BRIEFING --}}
        <div class="stage-card">
            <div class="stage-card-header">
                <div class="stage-card-title">
                    <span class="badge-stage" style="background: #f5f3ff; color: #7c3aed;">B</span>
                    <span>Tahap B: Briefing Awal</span>
                </div>
                <span class="table-badge" style="background: #f5f3ff; color: #7c3aed;">
                    {{ $internship->briefing_status ? ucfirst($internship->briefing_status) : 'Belum Dijadwalkan' }}
                </span>
            </div>

            <form action="{{ route('internships.stage.update', ['internship' => $internship, 'stage' => 'B']) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-grid" style="margin-bottom: 10px;">
                    <label class="form-field">
                        <span>Tanggal Briefing</span>
                        <input type="date" name="briefing_date" value="{{ old('briefing_date', $internship->briefing_date?->format('Y-m-d')) }}">
                    </label>

                    <label class="form-field">
                        <span>Status Briefing <b>*</b></span>
                        <select name="briefing_status" required>
                            <option value="scheduled" @selected($internship->briefing_status === 'scheduled')>Scheduled (Terjadwal)</option>
                            <option value="done" @selected($internship->briefing_status === 'done')>Done (Briefing Selesai)</option>
                        </select>
                    </label>

                    <label class="form-field form-field-wide">
                        <span>Catatan Briefing</span>
                        <textarea name="briefing_notes" rows="2" placeholder="Catatan instruktur, platform Zoom/Meeting, dll.">{{ old('briefing_notes', $internship->briefing_notes) }}</textarea>
                    </label>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="button button-primary" style="min-height: 37px;">Simpan Briefing</button>
                </div>
            </form>
        </div>

        {{-- TAHAP C: MEDICAL CHECK UP (MCU) --}}
        <div class="stage-card">
            <div class="stage-card-header">
                <div class="stage-card-title">
                    <span class="badge-stage" style="background: #fffbeb; color: #b45309;">C</span>
                    <span>Tahap C: Medical Check-Up (MCU)</span>
                </div>
                <span class="table-badge" style="background: #fffbeb; color: #b45309;">
                    {{ $internship->mcu_status ? str_replace('_', ' ', strtoupper($internship->mcu_status)) : 'Belum Ada Hasil' }}
                </span>
            </div>

            <form action="{{ route('internships.stage.update', ['internship' => $internship, 'stage' => 'C']) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-grid" style="margin-bottom: 10px;">
                    <label class="form-field">
                        <span>Tanggal MCU</span>
                        <input type="date" name="mcu_date" value="{{ old('mcu_date', $internship->mcu_date?->format('Y-m-d')) }}">
                    </label>

                    <label class="form-field">
                        <span>Lokasi / Klinik Faskes</span>
                        <input type="text" name="mcu_location" value="{{ old('mcu_location', $internship->mcu_location) }}" placeholder="Contoh: Lab Kimia Farma / RSUD">
                    </label>

                    <label class="form-field">
                        <span>Status Kelayakan MCU <b>*</b></span>
                        <select name="mcu_status" required>
                            <option value="scheduled" @selected($internship->mcu_status === 'scheduled')>Scheduled (Terjadwal)</option>
                            <option value="review" @selected($internship->mcu_status === 'review')>Review (Hasil Ditinjau Dokter)</option>
                            <option value="follow_up" @selected($internship->mcu_status === 'follow_up')>Follow Up (Butuh Pemeriksaan Lanjutan)</option>
                            <option value="fit_to_work" @selected($internship->mcu_status === 'fit_to_work')>Fit to Work (Lolos / Layak Kerja)</option>
                        </select>
                    </label>

                    <label class="form-field">
                        <span>Ketentuan &amp; Syarat MCU</span>
                        <input type="text" name="mcu_terms" value="{{ old('mcu_terms', $internship->mcu_terms) }}" placeholder="Contoh: Puasa 10 jam, bawa KTP asli">
                    </label>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="button button-primary" style="min-height: 37px;">Update Status MCU</button>
                </div>
            </form>
        </div>

        {{-- TAHAP D: REGISTRASI & DOKUMEN --}}
        <div class="stage-card">
            <div class="stage-card-header">
                <div class="stage-card-title">
                    <span class="badge-stage" style="background: #fff7ed; color: #c2410c;">D</span>
                    <span>Tahap D: Registrasi &amp; Dokumen</span>
                </div>
                <span class="table-badge" style="background: {{ $internship->registration_status === 'done_upload' ? '#dcfce7; color: #15803d;' : '#fff7ed; color: #c2410c;' }}">
                    {{ $internship->registration_status === 'done_upload' ? 'Semua Dokumen Lengkap' : 'Pending Upload' }}
                </span>
            </div>

            <form action="{{ route('internships.stage.update', ['internship' => $internship, 'stage' => 'D']) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-grid" style="margin-bottom: 12px;">
                    {{-- 1. KTP --}}
                    <label class="form-field">
                        <span>KTP Peserta (PDF/JPG/PNG, Max 5MB)</span>
                        <input type="file" name="ktp_file" accept=".pdf,.jpg,.jpeg,.png">
                        @if($internship->ktp_path)
                            <a href="{{ route('internships.document.download', ['internship' => $internship, 'type' => 'ktp']) }}" class="existing-file">
                                ✓ KTP Tersimpan (Klik untuk unduh)
                            </a>
                        @endif
                    </label>

                    {{-- 2. CV --}}
                    <label class="form-field">
                        <span>Curriculum Vitae / CV (PDF, Max 10MB)</span>
                        <input type="file" name="cv_file" accept=".pdf">
                        @if($internship->cv_path)
                            <a href="{{ route('internships.document.download', ['internship' => $internship, 'type' => 'cv']) }}" class="existing-file">
                                ✓ CV Tersimpan (Klik untuk unduh)
                            </a>
                        @endif
                    </label>

                    {{-- 3. KK --}}
                    <label class="form-field">
                        <span>Kartu Keluarga / KK (PDF/JPG/PNG, Max 5MB)</span>
                        <input type="file" name="kk_file" accept=".pdf,.jpg,.jpeg,.png">
                        @if($internship->kk_path)
                            <a href="{{ route('internships.document.download', ['internship' => $internship, 'type' => 'kk']) }}" class="existing-file">
                                ✓ KK Tersimpan (Klik untuk unduh)
                            </a>
                        @endif
                    </label>

                    {{-- 4. SPDK --}}
                    <label class="form-field">
                        <span>Surat Pernyataan Diri &amp; Komitmen (SPDK)</span>
                        <input type="file" name="spdk_file" accept=".pdf,.jpg,.jpeg,.png">
                        @if($internship->spdk_path)
                            <a href="{{ route('internships.document.download', ['internship' => $internship, 'type' => 'spdk']) }}" class="existing-file">
                                ✓ SPDK Bertanda Tangan (Klik untuk unduh)
                            </a>
                        @else
                            <small>
                                Belum ada? <a href="{{ route('internships.spdk.template') }}" target="_blank" style="color: #2563eb; font-weight: 600;">Unduh Blanko SPDK</a>
                            </small>
                        @endif
                    </label>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <button type="submit" class="button button-primary" style="min-height: 37px;">Simpan &amp; Unggah Berkas</button>
                </div>
            </form>
        </div>

        {{-- TAHAP E: MINE PERMIT BIB --}}
        <div class="stage-card">
            <div class="stage-card-header">
                <div class="stage-card-title">
                    <span class="badge-stage" style="background: #f0fdfa; color: #0f766e;">E</span>
                    <span>Tahap E: Mine Permit (Site BIB)</span>
                </div>
                <span class="table-badge" style="background: #f0fdfa; color: #0f766e;">
                    {{ $internship->mine_permit_status ? strtoupper($internship->mine_permit_status) : 'Belum Diajukan' }}
                </span>
            </div>

            <form action="{{ route('internships.stage.update', ['internship' => $internship, 'stage' => 'E']) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-grid" style="margin-bottom: 10px;">
                    <label class="form-field">
                        <span>Status Mine Permit <b>*</b></span>
                        <select name="mine_permit_status" required>
                            <option value="diajukan" @selected($internship->mine_permit_status === 'diajukan')>Diajukan (Pengajuan ke KTT/Safety)</option>
                            <option value="released" @selected($internship->mine_permit_status === 'released')>Released (Permit Terbit / Siap Lapangan)</option>
                        </select>
                    </label>

                    <label class="form-field">
                        <span>Nomor Mine Permit</span>
                        <input type="text" name="mine_permit_number" value="{{ old('mine_permit_number', $internship->mine_permit_number) }}" placeholder="Contoh: MP-BIB-2026-XXXX">
                    </label>

                    <label class="form-field form-field-wide">
                        <span>Tanggal Terbit Mine Permit</span>
                        <input type="date" name="mine_permit_date" value="{{ old('mine_permit_date', $internship->mine_permit_date?->format('Y-m-d')) }}">
                    </label>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="button button-primary" style="min-height: 37px;">Update Permit</button>
                </div>
            </form>
        </div>

        {{-- TAHAP F: KEDATANGAN ONSITE --}}
        <div class="stage-card">
            <div class="stage-card-header">
                <div class="stage-card-title">
                    <span class="badge-stage" style="background: #ecfdf5; color: #047857;">F</span>
                    <span>Tahap F: Kedatangan Onsite (Site BIB)</span>
                </div>
                <span class="table-badge" style="background: #ecfdf5; color: #047857;">
                    {{ ucfirst($internship->onsite_status) }}
                </span>
            </div>

            <form action="{{ route('internships.stage.update', ['internship' => $internship, 'stage' => 'F']) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-grid" style="margin-bottom: 10px;">
                    <label class="form-field">
                        <span>Kategori Asal Peserta</span>
                        <select name="is_local">
                            <option value="0" @selected(!$internship->is_local)>Non-Lokal (Memerlukan Tiket / Transport)</option>
                            <option value="1" @selected($internship->is_local)>Lokal (Domisili Sekitar Site BIB)</option>
                        </select>
                    </label>

                    <label class="form-field">
                        <span>Tanggal Keberangkatan</span>
                        <input type="date" name="departure_date" value="{{ old('departure_date', $internship->departure_date?->format('Y-m-d')) }}">
                    </label>

                    <label class="form-field">
                        <span>Status Kedatangan Onsite <b>*</b></span>
                        <select name="onsite_status" required>
                            <option value="pending" @selected($internship->onsite_status === 'pending')>Pending (Menunggu Jadwal)</option>
                            <option value="arrived" @selected($internship->onsite_status === 'arrived')>Arrived (Tiba di Mess/Site BIB)</option>
                            <option value="active" @selected($internship->onsite_status === 'active')>Active (Sedang Aktif Magang)</option>
                            <option value="completed" @selected($internship->onsite_status === 'completed')>Completed (Program Magang Selesai)</option>
                        </select>
                    </label>

                    <label class="form-field form-field-wide">
                        <span>Informasi Kedatangan &amp; Akomodasi</span>
                        <textarea name="arrival_information" rows="2" placeholder="Nama mess, jadwal jemput bandara/pelabuhan, PIC penjemput...">{{ old('arrival_information', $internship->arrival_information) }}</textarea>
                    </label>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="button button-primary" style="min-height: 37px;">Update Kedatangan Onsite</button>
                </div>
            </form>
        </div>

    </div>
@endsection

