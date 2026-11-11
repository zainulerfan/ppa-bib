@extends('layouts.app')

@section('title', 'Surat - Surat Rekrutmen')
@section('breadcrumb', 'Surat - Surat Rekrutmen')

@section('content')
    <section class="division-heading">
        <a class="back-link" href="{{ route('recruitment.home') }}"><x-icon name="arrow-right" /> Kembali ke Recruitment</a>
        <div class="division-title-row">
            <span class="division-hero-icon"><x-icon name="layers" /></span>
            <div>
                <span class="section-kicker">RECRUITMENT <span class="eyebrow-separator">/</span> ADMINISTRASI</span>
                <h1>Surat - Surat Rekrutmen</h1>
                <p>Kelola data pengajuan dan kebutuhan karyawan baru, serta siapkan formulir administrasi untuk dicetak.</p>
            </div>
        </div>
    </section>

    @if(session('status'))
        <div class="form-success" role="status">{{ session('status') }}</div>
    @endif

    <section class="module-intro">
        <div>
            <span class="section-kicker">DATA &amp; FORMULIR</span>
            <h2>Pilih pekerjaan</h2>
            <p>Formulir cetak dapat dicetak atau disimpan sebagai PDF dari browser.</p>
        </div>
    </section>

    <section class="workspace-grid">
        <a class="workspace-card" href="{{ route('recruitment.applications.index') }}">
            <span class="workspace-index">01</span><span class="workspace-tag">INPUT &amp; DATA</span>
            <h3>Input Pengajuan</h3><p>Catat data kandidat, jadwal, tipe pengajuan, dan unggah izin Visitor Kit BIB.</p>
            <span class="workspace-action">Buka data <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.print', ['type' => 'visitor-im']) }}">
            <span class="workspace-index">02</span><span class="workspace-tag">FORM CETAK</span>
            <h3>Pengajuan Visitor (IM)</h3><p>Siapkan formulir pengajuan visitor untuk keperluan IM.</p>
            <span class="workspace-action">Buka formulir <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.print', ['type' => 'psikotest']) }}">
            <span class="workspace-index">03</span><span class="workspace-tag">FORM CETAK</span>
            <h3>Psikotest</h3><p>Pengantar pemeriksaan psikologi untuk kandidat.</p>
            <span class="workspace-action">Buka formulir <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.print', ['type' => 'tes-praktek']) }}">
            <span class="workspace-index">04</span><span class="workspace-tag">FORM CETAK</span>
            <h3>Tes Praktek</h3><p>Siapkan pengajuan tes praktek bagi kandidat.</p>
            <span class="workspace-action">Buka formulir <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.print', ['type' => 'refreshment']) }}">
            <span class="workspace-index">05</span><span class="workspace-tag">FORM CETAK</span>
            <h3>Refreshment Unit</h3><p>Siapkan pengajuan refreshment unit.</p>
            <span class="workspace-action">Buka formulir <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.print', ['type' => 'ro']) }}">
            <span class="workspace-index">06</span><span class="workspace-tag">FORM CETAK</span>
            <h3>Pengajuan RO</h3><p>Siapkan formulir pengajuan RO.</p>
            <span class="workspace-action">Buka formulir <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.ppe.index') }}">
            <span class="workspace-index">07</span><span class="workspace-tag">INPUT &amp; DATA</span>
            <h3>Input APD</h3><p>Catat kebutuhan perlengkapan pelindung dan status proses.</p>
            <span class="workspace-action">Buka data <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.print', ['type' => 'apd']) }}">
            <span class="workspace-index">08</span><span class="workspace-tag">FORM CETAK</span>
            <h3>Print APD</h3><p>Form rencana kebutuhan alat pelindung diri.</p>
            <span class="workspace-action">Buka formulir <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.uniforms.index') }}">
            <span class="workspace-index">09</span><span class="workspace-tag">INPUT &amp; DATA</span>
            <h3>Input Seragam</h3><p>Catat ukuran, warna, dan jumlah pengambilan seragam.</p>
            <span class="workspace-action">Buka data <x-icon name="arrow-right" /></span>
        </a>
        <a class="workspace-card" href="{{ route('recruitment.print', ['type' => 'seragam']) }}">
            <span class="workspace-index">10</span><span class="workspace-tag">FORM CETAK</span>
            <h3>Print Seragam</h3><p>Form pengajuan kebutuhan seragam kerja.</p>
            <span class="workspace-action">Buka formulir <x-icon name="arrow-right" /></span>
        </a>
    </section>
@endsection
