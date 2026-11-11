<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101a2d">
    <title>Masuk | HC PPA BIB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">
    <main class="login-card">
        <a class="brand login-brand" href="{{ route('dashboard') }}">
            <span class="brand-mark"><img src="{{ asset('images/ppa-logo.png') }}" alt="Logo PPA"></span>
            <span class="brand-copy">
                <span class="brand-name">PUTRA PERKASA ABADI</span>
                <span class="brand-caption">HC PPA BIB · Site BIB</span>
            </span>
        </a>
        <span class="section-kicker"></span>
        <h1>Selamat datang kembali</h1>
        <p class="login-description">Masuk untuk membuka ruang kerja Human Capital.</p>

        @if($errors->any())
            <div class="form-alert" role="alert">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="login-form">
            @csrf
            <label>
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            </label>
            <label>
                <span>Kata sandi</span>
                <span class="password-field">
                    <input id="login-password" type="password" name="password" autocomplete="current-password" required>
                    <button class="password-toggle" type="button" aria-label="Tampilkan kata sandi" aria-pressed="false" aria-controls="login-password" data-password-toggle>
                        <svg class="password-eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="password-eye-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m3 3 18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 5.2A10.7 10.7 0 0 1 12 5c6.4 0 10 7 10 7a15.8 15.8 0 0 1-3.1 3.9"/><path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7a10.7 10.7 0 0 0 4-.8"/>
                        </svg>
                    </button>
                </span>
            </label>
            <label class="remember-label">
                <input type="checkbox" name="remember" value="1">
                <span>Ingat saya</span>
            </label>
            <button type="submit" class="button button-primary button-wide">Masuk ke portal</button>
        </form>
        <a class="login-back" href="{{ route('dashboard') }}">Kembali ke beranda</a>
    </main>
</body>
</html>
