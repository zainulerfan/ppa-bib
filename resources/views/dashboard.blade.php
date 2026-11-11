@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <section class="welcome-banner">
        <div class="welcome-copy">
            <span class="eyebrow"><span class="eyebrow-dot"></span> HUMAN CAPITAL PORTAL <span class="eyebrow-separator">/</span> SITE BIB</span>
            <h1>Selamat datang di<br><span>HC PPA BIB.</span></h1>
            <p>Satu ruang kerja untuk mendukung perjalanan dan pertumbuhan setiap insan PPA.</p>
            <a class="banner-link" href="#divisions">Jelajahi divisi <x-icon name="arrow-right" /></a>
        </div>
        <div class="banner-art" aria-hidden="true">
            <div class="orbit orbit-one"></div>
            <div class="orbit orbit-two"></div>
            <div class="orbit orbit-three"></div>
            <span class="art-star art-star-one">✳</span>
            <span class="art-star art-star-two">✦</span>
            <span class="art-dot art-dot-one"></span>
            <span class="art-dot art-dot-two"></span>
            <div class="art-center"><x-icon name="layers" /></div>
            <div class="art-tag"><span></span> PEOPLE FIRST</div>
        </div>
        <div class="banner-index"><span>01</span> — 04</div>
    </section>

    <section class="section-heading" id="divisions">
        <div>
            <span class="section-kicker">RUANG KERJA</span>
            <h2>Jelajahi divisi Human Capital</h2>
            <p>Pilih area kerja untuk melihat informasi dan layanan yang tersedia.</p>
        </div>
        <span class="division-count"><strong>04</strong> DIVISI</span>
    </section>

    <section class="division-grid" aria-label="Divisi Human Capital">
        <a class="division-card card-blue" href="{{ route('recruitment.home') }}">
            <div class="card-topline"><span class="card-icon"><x-icon name="users" /></span><span class="card-number">01</span></div>
            <div class="card-body">
                <span class="card-category">TALENT ACQUISITION</span>
                <h3>Recruitment</h3>
                <p>Temukan dan kelola perjalanan kandidat terbaik untuk PPA.</p>
            </div>
            <div class="card-bottom"><span>Buka divisi</span><span class="card-arrow"><x-icon name="arrow-up-right" /></span></div>
        </a>
        <a class="division-card card-violet" href="{{ route('people-development') }}">
            <div class="card-topline"><span class="card-icon"><x-icon name="graduation" /></span><span class="card-number">02</span></div>
            <div class="card-body">
                <span class="card-category">LEARNING &amp; GROWTH</span>
                <h3>People Development</h3>
                <p>Tumbuhkan kompetensi, performa, dan potensi insan PPA.</p>
            </div>
            <div class="card-bottom"><span>Buka divisi</span><span class="card-arrow"><x-icon name="arrow-up-right" /></span></div>
        </a>
        <a class="division-card card-amber" href="{{ route('compensation-benefit') }}">
            <div class="card-topline"><span class="card-icon"><x-icon name="wallet" /></span><span class="card-number">03</span></div>
            <div class="card-body">
                <span class="card-category">REWARDS &amp; BENEFITS</span>
                <h3>Compensation &amp; Benefit</h3>
                <p>Dukung kesejahteraan melalui pengelolaan reward dan benefit.</p>
            </div>
            <div class="card-bottom"><span>Buka divisi</span><span class="card-arrow"><x-icon name="arrow-up-right" /></span></div>
        </a>
        <a class="division-card card-teal" href="{{ route('industrial-relation') }}">
            <div class="card-topline"><span class="card-icon"><x-icon name="handshake" /></span><span class="card-number">04</span></div>
            <div class="card-body">
                <span class="card-category">WORKPLACE RELATIONS</span>
                <h3>Industrial Relation</h3>
                <p>Rawat hubungan kerja yang harmonis dan saling menghargai.</p>
            </div>
            <div class="card-bottom"><span>Buka divisi</span><span class="card-arrow"><x-icon name="arrow-up-right" /></span></div>
        </a>
    </section>

    <aside class="dashboard-note">
        <span class="note-icon"><x-icon name="spark" /></span>
        <p><strong>People make the difference.</strong> Bersama, kita membangun lingkungan kerja yang aman, berkembang, dan penuh peluang.</p>
        <span class="note-accent">HC <i>×</i> PPA</span>
    </aside>
@endsection
