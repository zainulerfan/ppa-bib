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
        <div style="display: flex; gap: 8px; align-items: center;">
            <a class="button button-secondary" href="{{ route('recruitment.applications.export', request()->query()) }}" title="Ekspor rekap pengajuan sesuai filter saat ini">
                <x-icon name="download" /> Ekspor Rekap (CSV)
            </a>
            <a class="button button-primary" href="{{ route('recruitment.applications.create') }}">+ Tambah pengajuan</a>
        </div>
    </div>

    @if(session('status'))
        <div class="form-success" role="status">{{ session('status') }}</div>
    @endif

    <div class="table-card">
        <div class="table-card-heading">
            <div>
                <strong>Data pengajuan</strong>
                <span>{{ $applications->total() }} data ditemukan</span>
            </div>
        </div>

        <form method="GET" action="{{ route('recruitment.applications.index') }}" class="table-filter-bar">
            <div class="table-filter-item">
                <input type="text" name="search" class="table-filter-input" placeholder="Cari nama / NIK / no surat..." value="{{ request('search') }}">
            </div>
            <div class="table-filter-item">
                <select name="position" class="table-filter-select">
                    <option value="">Semua Posisi</option>
                    @foreach(['OPERATOR','PRODUCTION','MECHANIC'] as $pos)
                        <option value="{{ $pos }}" @selected(request('position') === $pos)>{{ $pos }}</option>
                    @endforeach
                </select>
            </div>
            <div class="table-filter-item">
                <select name="department" class="table-filter-select">
                    <option value="">Semua Departemen</option>
                    @foreach(['PLANT','PRODUKSI','MANAGEMENT'] as $dept)
                        <option value="{{ $dept }}" @selected(request('department') === $dept)>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="table-filter-item">
                <select name="application_type" class="table-filter-select">
                    <option value="">Semua Jenis Pengajuan</option>
                    @foreach(['INDUKSI','IM','TES PRAKTEK','REFRESHMENT','RO','PSIKOTEST'] as $type)
                        <option value="{{ $type }}" @selected(request('application_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="table-filter-actions">
                <button type="submit" class="button button-primary table-filter-btn">Filter</button>
                @if(request()->hasAny(['search', 'position', 'department', 'application_type']))
                    <a href="{{ route('recruitment.applications.index') }}" class="button button-secondary table-filter-btn">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No urut / surat</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Posisi</th>
                        <th>Departemen</th>
                        <th>Pengajuan</th>
                        <th>Tanggal</th>
                        <th>Lampiran</th>
                        <th>Kebutuhan Logistik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $index => $application)
                        <tr>
                            <td>{{ $applications->firstItem() + $index }}</td>
                            <td>{{ $application->sequence_number ?: '—' }}<small>{{ $application->letter_number ?: '—' }}</small></td>
                            <td><strong>{{ $application->name }}</strong></td>
                            <td>{{ $application->nik ?: '—' }}</td>
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
                            <td>
                                <div style="display: flex; gap: 6px; align-items: center;">
                                    @if($application->ppe_requests_count > 0)
                                        <a href="{{ route('recruitment.ppe.index', ['search' => $application->name]) }}" class="badge-link" title="{{ $application->ppe_requests_count }} request APD tercatat">
                                            APD <span class="badge-count">{{ $application->ppe_requests_count }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('recruitment.ppe.create', ['application_id' => $application->id]) }}" class="badge-link" title="Buat kebutuhan APD untuk kandidat ini">
                                            + APD
                                        </a>
                                    @endif

                                    @if($application->uniform_requests_count > 0)
                                        <a href="{{ route('recruitment.uniforms.index', ['search' => $application->name]) }}" class="badge-link" title="{{ $application->uniform_requests_count }} request Seragam tercatat">
                                            Seragam <span class="badge-count">{{ $application->uniform_requests_count }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('recruitment.uniforms.create', ['application_id' => $application->id]) }}" class="badge-link" title="Buat permohonan seragam untuk kandidat ini">
                                            + Seragam
                                        </a>
                                    @endif
                                </div>
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
                                    <a class="table-action-icon" href="{{ route('recruitment.print', ['type' => $printType, 'record' => $application->id]) }}" aria-label="Cetak pengajuan {{ $application->name }}" title="Cetak">
                                        <x-icon name="pdf" />
                                    </a>
                                @endif
                                <a class="table-action-icon" href="{{ route('recruitment.applications.edit', $application) }}" aria-label="Ubah pengajuan {{ $application->name }}" title="Ubah">
                                    <x-icon name="edit" />
                                </a>
                                <form action="{{ route('recruitment.applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Hapus data pengajuan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="table-action-icon is-danger" type="submit" aria-label="Hapus pengajuan {{ $application->name }}" title="Hapus">
                                        <x-icon name="trash" />
                                    </button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td class="empty-state" colspan="11">Belum ada data pengajuan yang sesuai dengan kriteria filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
            <div class="table-pagination">{{ $applications->links() }}</div>
        @endif
    </div>
@endsection
