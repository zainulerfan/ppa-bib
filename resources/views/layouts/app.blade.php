<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101a2d">
    <title>@yield('title', 'Dashboard') | HC PPA BIB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell">
        <button class="sidebar-backdrop" type="button" aria-label="Tutup menu" data-sidebar-close></button>
        <aside class="sidebar" id="main-sidebar" aria-label="Navigasi utama">
            <a class="brand" href="{{ route('dashboard') }}" aria-label="Putra Perkasa Abadi, beranda">
                <span class="brand-mark"><img src="{{ asset('images/ppa-logo.png') }}" alt="Logo PPA"></span>
                <span class="brand-copy">
                    <span class="brand-name">PUTRA PERKASA ABADI</span>
                    <span class="brand-caption">HC PPA BIB <span aria-hidden="true">·</span> Site BIB</span>
                </span>
            </a>

            <div class="sidebar-divider"></div>
            <p class="nav-label">WORKSPACE</p>
            <nav class="primary-nav">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                    <x-icon name="grid" />
                    <span>Dashboard</span>
                </a>
                <p class="nav-label nav-label-spaced">DIVISI HUMAN CAPITAL</p>
                <a class="nav-link {{ request()->routeIs('recruitment.*') ? 'is-active' : '' }}" href="{{ route('recruitment.home') }}" @if(request()->routeIs('recruitment.*')) aria-current="page" @endif>
                    <x-icon name="users" />
                    <span>Recruitment</span>
                    <x-icon name="chevron" class="nav-chevron {{ request()->routeIs('recruitment.*') ? 'is-expanded' : '' }}" />
                </a>
                @if(request()->routeIs('recruitment.*'))
                    <div class="nav-submenu">
                        <a class="nav-submenu-parent {{ request()->routeIs('recruitment.letters') || (request()->routeIs('recruitment.*') && !request()->routeIs('recruitment.home')) ? 'is-current' : '' }}" href="{{ route('recruitment.letters') }}">Surat - Surat Rekrutmen</a>
                        <div class="nav-submenu-children">
                            <a class="{{ request()->routeIs('recruitment.applications.*') || request()->routeIs('recruitment.applications.permit') ? 'is-current' : '' }}" href="{{ route('recruitment.applications.index') }}">Input Pengajuan</a>
                            <a class="{{ request()->routeIs('recruitment.print') && request()->route('type') === 'visitor-im' ? 'is-current' : '' }}" href="{{ route('recruitment.print', ['type' => 'visitor-im']) }}">Pengajuan Visitor (IM)</a>
                            <a class="{{ request()->routeIs('recruitment.print') && request()->route('type') === 'psikotest' ? 'is-current' : '' }}" href="{{ route('recruitment.print', ['type' => 'psikotest']) }}">Psikotest</a>
                            <a class="{{ request()->routeIs('recruitment.print') && request()->route('type') === 'tes-praktek' ? 'is-current' : '' }}" href="{{ route('recruitment.print', ['type' => 'tes-praktek']) }}">Tes Praktek</a>
                            <a class="{{ request()->routeIs('recruitment.print') && request()->route('type') === 'refreshment' ? 'is-current' : '' }}" href="{{ route('recruitment.print', ['type' => 'refreshment']) }}">Refreshment</a>
                            <a class="{{ request()->routeIs('recruitment.print') && request()->route('type') === 'ro' ? 'is-current' : '' }}" href="{{ route('recruitment.print', ['type' => 'ro']) }}">Pengajuan RO</a>
                            <a class="{{ request()->routeIs('recruitment.ppe.*') ? 'is-current' : '' }}" href="{{ route('recruitment.ppe.index') }}">Input APD</a>
                            <a class="{{ request()->routeIs('recruitment.print') && request()->route('type') === 'apd' ? 'is-current' : '' }}" href="{{ route('recruitment.print', ['type' => 'apd']) }}">Print APD</a>
                            <a class="{{ request()->routeIs('recruitment.uniforms.*') ? 'is-current' : '' }}" href="{{ route('recruitment.uniforms.index') }}">Input Seragam</a>
                            <a class="{{ request()->routeIs('recruitment.print') && request()->route('type') === 'seragam' ? 'is-current' : '' }}" href="{{ route('recruitment.print', ['type' => 'seragam']) }}">Print Seragam</a>
                        </div>
                    </div>
                @endif
                <a class="nav-link {{ request()->routeIs('people-development') ? 'is-active' : '' }}" href="{{ route('people-development') }}" @if(request()->routeIs('people-development')) aria-current="page" @endif>
                    <x-icon name="graduation" />
                    <span>People Development</span>
                    <x-icon name="chevron" class="nav-chevron" />
                </a>
                <a class="nav-link {{ request()->routeIs('compensation-benefit') ? 'is-active' : '' }}" href="{{ route('compensation-benefit') }}" @if(request()->routeIs('compensation-benefit')) aria-current="page" @endif>
                    <x-icon name="wallet" />
                    <span>Compensation &amp; Benefit</span>
                    <x-icon name="chevron" class="nav-chevron" />
                </a>
                <a class="nav-link {{ request()->routeIs('industrial-relation') ? 'is-active' : '' }}" href="{{ route('industrial-relation') }}" @if(request()->routeIs('industrial-relation')) aria-current="page" @endif>
                    <x-icon name="handshake" />
                    <span>Industrial Relation</span>
                    <x-icon name="chevron" class="nav-chevron" />
                </a>
            </nav>

            <div class="sidebar-bottom">
                <div class="sidebar-divider"></div>
                <div class="profile">
                    <span class="avatar">HC</span>
                    <span class="profile-copy">
                        @auth
                            <strong>{{ auth()->user()->name }}</strong>
                            <span>{{ auth()->user()->email }}</span>
                        @else
                            <strong>Human Capital</strong>
                            <span>PT Putra Perkasa Abadi</span>
                        @endauth
                    </span>
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="profile-logout" type="submit" aria-label="Keluar"><x-icon name="arrow-up-right" /></button>
                        </form>
                    @else
                        <a class="profile-logout" href="{{ route('login') }}" aria-label="Masuk"><x-icon name="arrow-right" /></a>
                    @endauth
                </div>
                <p class="sidebar-footnote">SITE BIB <span>•</span> INTERNAL PORTAL</p>
            </div>
        </aside>

        <div class="main-panel">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="mobile-menu-button" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="main-sidebar" data-sidebar-toggle>
                        <x-icon name="menu" />
                    </button>
                    <div class="breadcrumb">
                        <span>HC PPA BIB</span>
                        <x-icon name="chevron" />
                        <strong>@yield('breadcrumb', 'Dashboard')</strong>
                    </div>
                </div>
                <div class="topbar-right">
                    <span class="topbar-date">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                    <span class="topbar-divider"></span>
                    <span class="status-indicator"><i></i> Internal portal</span>
                    @guest
                        <a class="topbar-login" href="{{ route('login') }}">Masuk</a>
                    @endguest
                    <span class="topbar-avatar">HC</span>
                </div>
            </header>

            <main class="page-content">
                @yield('content')
            </main>
            <footer class="page-footer">
                <span>HC PPA BIB</span>
                <span>Human Capital <i>•</i> Site BIB</span>
            </footer>
        </div>
    </div>
</body>
</html>
