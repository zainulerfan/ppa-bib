@extends('layouts.app')

@section('title', 'Recruitment')
@section('breadcrumb', 'Recruitment')

@section('content')
    <section class="division-heading">
        <div class="division-title-row">
            <span class="division-hero-icon"><x-icon name="users" /></span>
            <div>
                <span class="section-kicker">DIVISI HUMAN CAPITAL</span>
                <h1>Recruitment</h1>
                <p>Kelola proses pemenuhan tenaga kerja dan administrasi kandidat dalam satu ruang kerja.</p>
            </div>
        </div>
    </section>

    <section class="module-intro">
        <div>
            <span class="section-kicker">RUANG KERJA</span>
            <h2>Modul Recruitment</h2>
            <p>Pilih modul untuk melanjutkan pekerjaan.</p>
        </div>
        <span class="module-status"><i></i> Akses internal</span>
    </section>

    <a class="module-card" href="{{ route('recruitment.letters') }}">
        <span class="module-card-icon"><x-icon name="layers" /></span>
        <span class="module-card-copy">
            <span class="card-category">ADMINISTRASI KANDIDAT</span>
            <strong>Surat - Surat Rekrutmen</strong>
            <span>Kelola pengajuan, kebutuhan APD dan seragam, serta siapkan formulir cetak.</span>
        </span>
        <span class="module-card-arrow"><x-icon name="arrow-up-right" /></span>
    </a>
@endsection
