@extends('layouts.app')

@section('title', 'Input Pengajuan')
@section('breadcrumb', 'Input Pengajuan')

@section('content')
    <div class="page-heading-row">
        <div>
            <a class="back-link" href="{{ route('recruitment.letters') }}"><x-icon name="arrow-right" /> Surat - Surat Rekrutmen</a>
            <span class="section-kicker">RECRUITMENT <span class="eyebrow-separator">/</span> PENGAJUAN</span>
            <h1 class="page-title">Input Pengajuan</h1>
            <p class="page-subtitle">Daftar pengajuan rekrutmen dan administrasi kandidat.</p>
        </div>
        <a class="button button-primary" href="{{ route('recruitment.applications.create') }}">+ Tambah pengajuan</a>
    </div>

    @if(session('status'))
        <div class="form-success" role="status">{{ session('status') }}</div>
    @endif

    <div class="table-card">
        <div class="table-card-heading"><div><strong>Data pengajuan</strong><span>{{ $applications->total() }} data tersimpan</span></div></div>
        <div class="table-scroll">
            <table class="data-table">
                <thead><tr><th>No</th><th>No urut / surat</th><th>Nama &amp; NIK</th><th>Posisi</th><th>Departemen</th><th>Pengajuan</th><th>Tanggal</th><th>Lampiran</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($applications as $index => $application)
                        <tr>
                            <td>{{ $applications->firstItem() + $index }}</td>
                            <td>{{ $application->sequence_number ?: '—' }}<small>{{ $application->letter_number ?: '—' }}</small></td>
                            <td><strong>{{ $application->name }}</strong><small>{{ $application->nik ?: 'NIK belum diisi' }}</small></td>
                            <td>{{ $application->position }}</td>
                            <td>{{ $application->department }}</td>
                            <td><span class="table-badge">{{ $application->application_type }}</span></td>
                            <td>{{ $application->application_date?->format('d/m/Y') ?: '—' }}<small>Tes: {{ $application->test_date?->format('d/m/Y') ?: '—' }}</small></td>
                            <td>
                                @if($application->visitor_permit_path)
                                    <a class="table-link" href="{{ route('recruitment.applications.permit', $application) }}">PDF izin</a>
                                @else
                                    <span class="muted-text">—</span>
                                @endif
                            </td>
                            <td><div class="table-actions">
                                @php($printType = match ($application->application_type) {
                                    'IM' => 'visitor-im',
                                    'TES PRAKTEK' => 'tes-praktek',
                                    'REFRESHMENT' => 'refreshment',
                                    'RO' => 'ro',
                                    'PSIKOTEST' => 'psikotest',
                                    default => null,
                                })
                                @if($printType)
                                    <a class="table-link" href="{{ route('recruitment.print', ['type' => $printType, 'record' => $application->id]) }}">Cetak</a>
                                @endif
                                <a class="table-link" href="{{ route('recruitment.applications.edit', $application) }}">Ubah</a>
                                <form action="{{ route('recruitment.applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Hapus data pengajuan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="table-delete" type="submit">Hapus</button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td class="empty-state" colspan="9">Belum ada data pengajuan. Pilih “Tambah pengajuan” untuk memulai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
            <div class="table-pagination">{{ $applications->links() }}</div>
        @endif
    </div>
@endsection
