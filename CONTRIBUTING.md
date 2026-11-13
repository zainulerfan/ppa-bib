# Panduan Kontribusi Tim Developer PPA-BIB

Selamat datang di repositori portal internal Human Capital (HC) **PT Putra Perkasa Abadi (PPA) Site BIB**. Panduan ini ditujukan untuk seluruh developer (tim inti 3 orang) agar alur kolaborasi, standar kode, dan manajemen database tetap konsisten dan terhindar dari konflik (*merge conflict*).

---

## 1. Setup Lingkungan Lokal

Lakukan langkah instalasi berikut saat pertama kali mengklon repositori atau menyiapkan mesin lokal baru:

```bash
# 1. Install dependensi PHP (Laravel 12)
composer install

# 2. Siapkan file konfigurasi environment
cp .env.example .env

# 3. Generate application encryption key
php artisan key:generate

# 4. Buat file database SQLite (jika belum ada)
touch database/database.sqlite

# 5. Jalankan migrasi dan seeder awal (membuat akun HC Admin default)
php artisan migrate --seed

# 6. Buat symbolic link untuk storage
php artisan storage:link

# 7. Install dependensi frontend dan compile aset (Vite 7 + Tailwind CSS v4)
npm install
npm run build
```

### Akun Bawaan (Seeded User):
- **Admin HC:** `admin@ppa-bib.com` / `password`
- **Recruitment Team:** `hc@ppa-bib.com` / `password`

### Menjalankan Server Lokal:
```bash
# Terminal 1 (Laravel Server)
php artisan serve

# Terminal 2 (Vite Hot-Reload saat pengembangan frontend)
npm run dev
```

---

## 2. Aturan Git Branching

1. **Branch `main`:**
   - Merupakan branch produksi yang stabil (*stable release branch*).
   - Jangan pernah melakukan *direct commit / push* langsung ke `main` untuk fitur yang belum diuji secara menyeluruh.
2. **Branch Feature:**
   - Setiap pekerjaan modul atau fitur baru wajib menggunakan branch terpisah dengan format nama:
     - `feature/people-development`
     - `feature/compensation-benefit`
     - `feature/industrial-relation`
     - `bugfix/nama-perbaikan`
3. **Standar Pull Request (PR) & Penggabungan:**
   - Sebelum membuka PR atau menggabungkan ke `main`, pastikan seluruh test suite lolos 100%:
     ```bash
     php artisan test
     ```

---

## 3. Aturan Database & Migration

1. **Dilarang Mengedit Migration Lama:**
   - File migrasi yang sudah pernah di-commit ke `main` **TIDAK BOLEH** diubah atau dihapus isinya.
   - Mengubah file migration lama akan merusak database developer lain dan lingkungan deployment.
2. **Selalu Buat File Migration Baru:**
   - Jika membutuhkan penambahan kolom, tabel baru, atau perubahan constraint, selalu buat file migrasi baru:
     ```bash
     php artisan make:migration add_kolom_to_nama_tabel_table
     ```
   - Selalu sertakan perintah `down()` yang simetris untuk mendukung proses *rollback*.

---

## 4. Standar Kode & Testing

- **Backend:** Ikuti standar PSR-12. Gunakan `laravel/pint` untuk merapikan format kode:
  ```bash
  ./vendor/bin/pint
  ```
- **Testing:** Setiap penambahan rute, controller, atau fitur bisnis baru (CRUD, filter, ekspor, PDF) wajib disertai penulisan Feature Test di `tests/Feature/`.
- **Aset & Dokumen Privat:** Dokumen sensitif (seperti PDF Izin Visitor Kit) harus selalu disimpan di disk lokal privat (`storage/app/private`), bukan di `storage/app/public`.

---

Dokumen ini berlaku untuk seluruh tim pengembang PPA-BIB. Jika ada kendala terkait environment atau otorisasi, koordinasikan dengan Lead Developer.
