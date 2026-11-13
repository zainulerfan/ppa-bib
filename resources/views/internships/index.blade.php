@extends('layouts.app')

@section('title', 'Web Magang (Internship)')
@section('breadcrumb', 'Web Magang')

@section('content')
    <div class="page-heading-row">
        <div>
            <span class="section-kicker">HUMAN CAPITAL <span class="eyebrow-separator">/</span> PIPELINE MAGANG</span>
            <h1 class="page-title">Web Magang (Internship)</h1>
            <p class="page-subtitle">Monitoring 6 tahapan linear seleksi, induksi, hingga kedatangan onsite peserta magang PT Putra Perkasa Abadi Site BIB.</p>
        </div>
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <a class="button button-secondary" href="{{ route('internships.spdk.template') }}" target="_blank" title="Unduh blanko PDF Surat Pernyataan Diri & Komitmen">
                <x-icon name="download" /> Format SPDK Kosong
            </a>
            <a class="button button-primary" href="{{ route('internships.create') }}">
                + Daftarkan Peserta Baru
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="form-success" role="status">{{ session('status') }}</div>
    @endif

    {{-- Filter Bar --}}
    <div class="table-card" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('internships.index') }}" class="table-filter-bar">
            <div class="table-filter-item">
                <input type="text" name="search" class="table-filter-input" placeholder="Cari nama / NIK / kampus / domisili..." value="{{ request('search') }}">
            </div>
            <div class="table-filter-item">
                <select name="stage" class="table-filter-select">
                    <option value="">Semua Tahapan Pipeline</option>
                    @foreach($stages as $key => $stageInfo)
                        <option value="{{ $key }}" @selected(request('stage') === $key)>
                            Tahap {{ $key }}: {{ $stageInfo['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="table-filter-actions">
                <button type="submit" class="button button-primary table-filter-btn">Filter</button>
                @if(request()->hasAny(['search', 'stage']))
                    <a href="{{ route('internships.index') }}" class="button button-secondary table-filter-btn">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- 1. KANBAN BOARD 6 TAHAPAN (A - F) --}}
    <div style="margin-bottom: 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <h2 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0;">
                Pipeline Visual (Kanban Board)
            </h2>
            <span style="font-size: 10px; color: #64748b;">Total {{ $totalCount }} peserta aktif</span>
        </div>

        <div class="kanban-grid">
            @php
                $colColors = [
                    'A' => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#bfdbfe'],
                    'B' => ['bg' => '#f5f3ff', 'text' => '#6d28d9', 'border' => '#ddd6fe'],
                    'C' => ['bg' => '#fffbeb', 'text' => '#b45309', 'border' => '#fde68a'],
                    'D' => ['bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#fed7aa'],
                    'E' => ['bg' => '#f0fdfa', 'text' => '#0f766e', 'border' => '#99f6e4'],
                    'F' => ['bg' => '#ecfdf5', 'text' => '#047857', 'border' => '#a7f3d0'],
                ];
            @endphp

            @foreach($stages as $stageKey => $stageInfo)
                @php
                    $color = $colColors[$stageKey];
                @endphp
                <div class="kanban-col" style="border-top: 3px solid {{ $color['text'] }};">
                    <div class="kanban-col-header" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                        <span>Tahap {{ $stageKey }}: {{ $stageInfo['name'] }}</span>
                        <span class="badge-count" style="background: {{ $color['text'] }}; color: #fff;">
                            {{ $kanban[$stageKey]->count() }}
                        </span>
                    </div>
                    <div class="kanban-col-body">
                        @forelse($kanban[$stageKey] as $intern)
                            <a href="{{ route('internships.show', $intern) }}" class="kanban-card">
                                <div class="kanban-card-title">{{ $intern->name }}</div>
                                <div class="kanban-card-sub">{{ $intern->institution ?: ($intern->domicile ?: 'Site BIB') }}</div>
                                
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 6px;">
                                    @php
                                        $subStatus = match($stageKey) {
                                            'A' => ucfirst($intern->screening_status),
                                            'B' => $intern->briefing_status ? ucfirst($intern->briefing_status) : 'Menunggu',
                                            'C' => $intern->mcu_status ? str_replace('_', ' ', ucfirst($intern->mcu_status)) : 'Belum MCU',
                                            'D' => $intern->registration_status === 'done_upload' ? 'Dokumen Lengkap' : 'Pending Upload',
                                            'E' => $intern->mine_permit_status ? ucfirst($intern->mine_permit_status) : 'Proses Permit',
                                            'F' => ucfirst($intern->onsite_status),
                                            default => 'Aktif',
                                        };
                                    @endphp
                                    <span class="table-badge" style="font-size: 7.5px; padding: 2px 6px;">{{ $subStatus }}</span>
                                    <span style="font-size: 8px; font-weight: 700; color: #475569;">{{ $intern->progress_percent }}%</span>
                                </div>
                                <div class="progress-bar-bg" style="margin-top: 5px; height: 3px;">
                                    <div class="progress-bar-fill" style="width: {{ $intern->progress_percent }}%;"></div>
                                </div>
                            </a>
                        @empty
                            <div style="font-size: 9px; color: #94a3b8; text-align: center; padding: 24px 8px;">
                                Tidak ada peserta
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 2. TABEL DAFTAR PESERTA MAGANG --}}
    <div class="table-card">
        <div class="table-card-heading">
            <div>
                <strong>Daftar Semua Peserta Magang</strong>
                <span>{{ $applications->count() }} peserta ditemukan</span>
            </div>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peserta</th>
                        <th>NIK / Tanggal Lahir</th>
                        <th>Institusi / Jurusan</th>
                        <th>Domisili</th>
                        <th>Tahapan Saat Ini</th>
                        <th>Progress Pipeline</th>
                        <th>Dokumen Berkas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('internships.show', $item) }}" style="color: #2563eb; font-weight: 700;">
                                    {{ $item->name }}
                                </a>
                                @if($item->phone_number)
                                    <small>{{ $item->phone_number }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $item->nik }}
                                <small>{{ $item->birth_date?->format('d/m/Y') ?: '—' }}</small>
                            </td>
                            <td>
                                <strong>{{ $item->institution ?: '—' }}</strong>
                                <small>{{ $item->major ?: '—' }}</small>
                            </td>
                            <td>{{ $item->domicile }}</td>
                            <td>
                                <span class="table-badge">
                                    Tahap {{ $item->current_stage }}: {{ $stages[$item->current_stage]['name'] ?? 'Proses' }}
                                </span>
                            </td>
                            <td style="min-width: 120px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px;">
                                    <span style="font-size: 8px; color: #64748b;">Tahap {{ $item->current_stage }}/F</span>
                                    <strong style="font-size: 8px; color: #0f172a;">{{ $item->progress_percent }}%</strong>
                                </div>
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $item->progress_percent }}%;"></div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 4px; font-size: 8px;">
                                    <span title="KTP" style="padding: 1px 4px; border-radius: 3px; background: {{ $item->ktp_path ? '#dcfce7; color: #166534;' : '#f1f5f9; color: #94a3b8;' }}">KTP</span>
                                    <span title="CV" style="padding: 1px 4px; border-radius: 3px; background: {{ $item->cv_path ? '#dcfce7; color: #166534;' : '#f1f5f9; color: #94a3b8;' }}">CV</span>
                                    <span title="KK" style="padding: 1px 4px; border-radius: 3px; background: {{ $item->kk_path ? '#dcfce7; color: #166534;' : '#f1f5f9; color: #94a3b8;' }}">KK</span>
                                    <span title="SPDK" style="padding: 1px 4px; border-radius: 3px; background: {{ $item->spdk_path ? '#dcfce7; color: #166534;' : '#f1f5f9; color: #94a3b8;' }}">SPDK</span>
                                </div>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a class="table-action-icon" href="{{ route('internships.show', $item) }}" title="Lihat &amp; Update Pipeline">
                                        <x-icon name="arrow-right" />
                                    </a>
                                    <a class="table-action-icon" href="{{ route('internships.edit', $item) }}" title="Edit Data Dasar">
                                        <x-icon name="edit" />
                                    </a>
                                    <form action="{{ route('internships.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data peserta magang {{ $item->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="table-action-icon is-danger" type="submit" title="Hapus">
                                            <x-icon name="trash" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty-state" colspan="9">
                                Belum ada data peserta magang. Silakan klik "+ Daftarkan Peserta Baru".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
