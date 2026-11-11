# ppa-bib

## Modul Surat - Surat Rekrutmen

Modul Recruitment memerlukan pengguna yang sudah ada di tabel `users`; pendaftaran publik tidak diaktifkan. Jalankan migrasi untuk membuat tabel pengajuan Recruitment, kebutuhan APD, dan seragam:

```bash
php artisan migrate
```

Buat akun pengguna portal melalui Tinker (ganti nama, email, dan kata sandi dengan nilai milik Anda):

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'HC Admin',
    'email' => 'hc-admin@example.com',
    'password' => 'GantiDenganKataSandiYangKuat',
]);
```

Kata sandi akan di-hash oleh model `User`. Login tersedia di `/login`; setelah masuk, buka **Recruitment → Surat - Surat Rekrutmen**. Lampiran izin Visitor Kit BIB harus berupa PDF (maksimal 10 MB) dan disimpan pada storage privat. Formulir cetak dapat dicetak atau disimpan ke PDF melalui browser.