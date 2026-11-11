@extends('layouts.app')

@section('title', $division['name'])
@section('breadcrumb', $division['short_name'])

@section('content')
    <section class="division-heading">
        <a class="back-link" href="{{ route('dashboard') }}"><x-icon name="arrow-right" /> Kembali ke dashboard</a>
        <div class="division-title-row">
            <span class="division-hero-icon"><x-icon :name="$division['icon']" /></span>
            <div>
                <span class="section-kicker">DIVISI HUMAN CAPITAL</span>
                <h1>{{ $division['name'] }}</h1>
                <p>{{ $division['description'] }}</p>
            </div>
        </div>
    </section>

    <section class="module-intro">
        <div>
            <span class="section-kicker">AREA KERJA</span>
            <h2>Ruang lingkup divisi</h2>
            <p>Informasi awal untuk area kerja {{ $division['short_name'] }}.</p>
        </div>
        <span class="module-status"><i></i> Portal internal</span>
    </section>

    <section class="scope-grid" aria-label="Ruang lingkup {{ $division['name'] }}">
        @foreach($division['scope'] as $index => $item)
            <article class="scope-card">
                <span class="scope-number">0{{ $index + 1 }}</span>
                <span class="scope-check"><x-icon name="check" /></span>
                <h3>{{ $item['title'] }}</h3>
                <p>{{ $item['description'] }}</p>
                <span class="scope-footer">AREA {{ $division['short_name'] }}</span>
            </article>
        @endforeach
    </section>

    <aside class="coming-soon">
        <span class="coming-soon-icon"><x-icon name="spark" /></span>
        <div>
            <strong>Ruang kerja {{ $division['short_name'] }}</strong>
            <p>Halaman ini merupakan kerangka awal. Fitur dan data operasional dapat ditambahkan pada tahap pengembangan berikutnya.</p>
        </div>
    </aside>
@endsection
