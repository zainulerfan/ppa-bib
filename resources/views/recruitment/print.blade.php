@extends('layouts.app')

@section('title', $document['title'])
@section('breadcrumb', 'Formulir Cetak')

@section('content')
    @php
        $formatDate = fn ($date) => $date?->format('d/m/Y') ?? '';
        $rows = match ($type) {
            'apd' => [
                ['Nama', $record?->name],
                ['Posisi', $record?->position],
                ['Sepatu', $record?->shoes],
                ['Helm', $record?->helmet],
                ['Kacamata', $record?->safety_glasses],
                ['Ear plug', $record?->ear_plug],
                ['Sarung tangan', $record?->gloves],
                ['Masker', $record?->mask],
                ['NRP', $record?->nrp],
                ['Keterangan', $record?->note],
                ['Tanggal dibuat', $formatDate($record?->created_date)],
                ['Tanggal pembuatan (2)', $formatDate($record?->preparation_date)],
            ],
            'seragam' => [
                ['Nama', $record?->name],
                ['No finger / NIK', $record?->finger_number],
                ['Departemen', $record?->department],
                ['Posisi', $record?->position],
                ['Warna seragam', $record?->uniform_color],
                ['Ukuran seragam', $record?->uniform_size],
                ['Keterangan', $record?->note],
                ['Jumlah pengambilan', $record?->pickup_quantity],
                ['Tanggal dibuat', $formatDate($record?->created_date)],
                ['Tanggal pembuatan (2)', $formatDate($record?->request_date)],
            ],
            default => [
                ['Nama kandidat', $record?->name],
                ['NIK', $record?->nik],
                ['Posisi', $record?->position],
                ['Departemen', $record?->department],
                ['Jenis pengajuan', $record?->application_type],
                ['Keterangan', $record?->candidate_note],
                ['Keterangan posisi gabungan', $record?->assignment_note],
                ['No surat', $record?->letter_number],
                ['No surat (tambahan)', $record?->secondary_letter_number],
                ['Tanggal pengajuan', $formatDate($record?->application_date)],
                ['Tanggal tes', $formatDate($record?->test_date)],
                ['No. HP', $record?->phone_number],
            ],
        };
    @endphp

    <div class="print-toolbar">
        <a class="button button-secondary" href="{{ route('recruitment.letters') }}">Kembali</a>
        <button class="button button-primary" type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>
    <article class="print-sheet">
        <header class="print-header">
            <img src="{{ asset('images/ppa-logo.png') }}" alt="Logo PPA">
            <div><strong>PT PUTRA PERKASA ABADI</strong><span>SITE BIB · HUMAN CAPITAL</span></div>
            <span class="print-form-code">HC / REC / {{ strtoupper($type) }}</span>
        </header>
        <div class="print-rule"></div>
        <div class="print-title"><span>{{ $document['subtitle'] }}</span><h1>{{ $document['title'] }}</h1></div>
        <div class="print-meta"><span>No. Dokumen: <b>{{ $record?->letter_number ?: '................................' }}</b></span><span>Tanggal: <b>{{ now()->format('d/m/Y') }}</b></span></div>

        <table class="print-table">
            <tbody>
                @foreach($rows as [$label, $value])
                    <tr><th>{{ $label }}</th><td>{{ filled($value) ? $value : '' }}</td></tr>
                @endforeach
            </tbody>
        </table>
        <div class="print-notes"><strong>Catatan / tujuan pengajuan</strong><div></div><div></div></div>
        <div class="print-signatures">
            <div><span>Diajukan oleh,</span><strong></strong><span>Nama &amp; tanggal</span></div>
            <div><span>Diperiksa oleh,</span><strong></strong><span>Atasan / HC</span></div>
            <div><span>Disetujui oleh,</span><strong></strong><span>Pejabat berwenang</span></div>
        </div>
        <footer class="print-footer">Dokumen internal PT Putra Perkasa Abadi · Site BIB</footer>
    </article>
@endsection
