@extends('layouts.app')

@section('title', 'Input Seragam')
@section('breadcrumb', 'Input Seragam')

@section('content')
    <div class="page-heading-row">
        <div>
            <a class="back-link" href="{{ route('recruitment.letters') }}"><x-icon name="arrow-right" /> Surat - Surat Rekrutmen</a>
            <span class="section-kicker">RECRUITMENT <span class="eyebrow-separator">/</span> KEBUTUHAN SERAGAM</span>
            <h1 class="page-title">Input Seragam</h1>
            <p class="page-subtitle">Daftar permintaan seragam kerja karyawan.</p>
        </div>
        <a class="button button-primary" href="{{ route('recruitment.uniforms.create') }}">+ Tambah data seragam</a>
    </div>
    @if(session('status'))<div class="form-success" role="status">{{ session('status') }}</div>@endif
    <div class="table-card">
        <div class="table-card-heading"><div><strong>Data kebutuhan seragam</strong><span>{{ $requests->total() }} data tersimpan</span></div></div>
        <div class="table-scroll"><table class="data-table">
            <thead><tr><th>No</th><th>Tanggal dibuat</th><th>No finger / NIK</th><th>Nama</th><th>Departemen</th><th>Posisi</th><th>Warna</th><th>Ukuran</th><th>Keterangan</th><th>Tanggal pembuatan (2)</th><th>Jumlah pengambilan</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($requests as $index => $item)
                    <tr><td>{{ $requests->firstItem() + $index }}</td><td>{{ $item->created_date?->format('d/m/Y') ?: '—' }}</td><td>{{ $item->finger_number ?: '—' }}</td>
                        <td><strong>{{ $item->name }}</strong></td><td>{{ $item->department ?: '—' }}</td><td>{{ $item->position ?: '—' }}</td><td>{{ $item->uniform_color ?: '—' }}</td><td>{{ $item->uniform_size ?: '—' }}</td><td>{{ $item->note ?: '—' }}</td><td>{{ $item->request_date?->format('d/m/Y') ?: '—' }}</td><td>{{ $item->pickup_quantity }}</td>
                        <td><div class="table-actions">
                            <a class="table-action-icon" href="{{ route('recruitment.print', ['type' => 'seragam', 'record' => $item->id]) }}" aria-label="Cetak seragam {{ $item->name }}" title="Cetak"><x-icon name="pdf" /></a>
                            <a class="table-action-icon" href="{{ route('recruitment.uniforms.edit', $item) }}" aria-label="Ubah seragam {{ $item->name }}" title="Ubah"><x-icon name="edit" /></a>
                            <form action="{{ route('recruitment.uniforms.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data seragam ini?')">@csrf @method('DELETE')<button class="table-action-icon is-danger" type="submit" aria-label="Hapus seragam {{ $item->name }}" title="Hapus"><x-icon name="trash" /></button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td class="empty-state" colspan="12">Belum ada data kebutuhan seragam.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        @if($requests->hasPages())<div class="table-pagination">{{ $requests->links() }}</div>@endif
    </div>
@endsection
