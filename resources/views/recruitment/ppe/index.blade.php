@extends('layouts.app')

@section('title', 'Input APD')
@section('breadcrumb', 'Input APD')

@section('content')
    <div class="page-heading-row">
        <div>
            <a class="back-link" href="{{ route('recruitment.letters') }}"><x-icon name="arrow-right" /> Surat - Surat Rekrutmen</a>
            <span class="section-kicker">RECRUITMENT <span class="eyebrow-separator">/</span> KEBUTUHAN APD</span>
            <h1 class="page-title">Input APD</h1>
            <p class="page-subtitle">Daftar kebutuhan alat pelindung diri karyawan.</p>
        </div>
        <a class="button button-primary" href="{{ route('recruitment.ppe.create') }}">+ Tambah data APD</a>
    </div>
    @if(session('status'))<div class="form-success" role="status">{{ session('status') }}</div>@endif
    <div class="table-card">
        <div class="table-card-heading"><div><strong>Data kebutuhan APD</strong><span>{{ $requests->total() }} data tersimpan</span></div></div>
        <div class="table-scroll"><table class="data-table">
            <thead><tr><th>No</th><th>Tanggal dibuat</th><th>Nama / Posisi</th><th>Sepatu</th><th>Helm</th><th>Kacamata</th><th>Ear plug</th><th>Sarung tangan</th><th>Masker</th><th>Tanggal pembuatan (2)</th><th>NRP</th><th>Keterangan</th><th>Status proses</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($requests as $index => $item)
                    <tr>
                        <td>{{ $requests->firstItem() + $index }}</td><td>{{ $item->created_date?->format('d/m/Y') ?: '—' }}</td>
                        <td><strong>{{ $item->name }}</strong><small>{{ $item->position ?: '—' }}</small></td>
                        <td>{{ $item->shoes ?: '—' }}</td><td>{{ $item->helmet ?: '—' }}</td><td>{{ $item->safety_glasses ?: '—' }}</td>
                        <td>{{ $item->ear_plug ?: '—' }}</td><td>{{ $item->gloves ?: '—' }}</td><td>{{ $item->mask ?: '—' }}</td>
                        <td>{{ $item->preparation_date?->format('d/m/Y') ?: '—' }}</td><td>{{ $item->nrp ?: '—' }}</td><td>{{ $item->note ?: '—' }}</td>
                        <td><span class="table-badge">{{ $item->process_status }}</span></td>
                        <td><div class="table-actions">
                            <a class="table-action-icon" href="{{ route('recruitment.print', ['type' => 'apd', 'record' => $item->id]) }}" aria-label="Cetak APD {{ $item->name }}" title="Cetak"><x-icon name="pdf" /></a>
                            <a class="table-action-icon" href="{{ route('recruitment.ppe.edit', $item) }}" aria-label="Ubah APD {{ $item->name }}" title="Ubah"><x-icon name="edit" /></a>
                            <form action="{{ route('recruitment.ppe.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data APD ini?')">@csrf @method('DELETE')<button class="table-action-icon is-danger" type="submit" aria-label="Hapus APD {{ $item->name }}" title="Hapus"><x-icon name="trash" /></button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td class="empty-state" colspan="14">Belum ada data kebutuhan APD.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        @if($requests->hasPages())<div class="table-pagination">{{ $requests->links() }}</div>@endif
    </div>
@endsection
