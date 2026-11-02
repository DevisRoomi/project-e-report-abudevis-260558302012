# E-Report — Sistem Pengaduan & Dokumentasi Infrastruktur Sipil
**Tugas Proyek Semester 1 — TRPL (Teknologi Rekayasa Perangkat Lunak)**  
**Nama Mahasiswa:** Abu Devis Jalaluddin Rumi  
**NIM:** 260558302012  
**Repository:** `project-e-report-abudevis-260558302012`  

---

Aplikasi pengaduan masyarakat berbasis web untuk memfasilitasi warga dalam melaporkan aspirasi, aduan lingkungan, dan kerusakan infrastruktur sipil kepada dinas/admin secara transparan dan akuntabel.

Dibangun mengacu pada modul kurikulum [Tutorial E-Report (10 Part)](https://modul-e-report.netlify.app/) dengan standar rekayasa perangkat lunak modern.

## Fitur Utama

### Warga
- **Registrasi & Autentikasi**: Pendaftaran akun warga dan otentikasi aman dengan proteksi brute force rate limiting.
- **Pengaduan & Bukti Foto**: Formulir pengaduan dengan validasi tanggal, deskripsi, dan upload bukti foto aman.
- **Manajemen Laporan**: Melihat daftar riwayat status laporan (pending, proses, selesai), detail, serta edit & hapus untuk aduan berstatus pending.
- **Profil & Password**: Kelola identitas, nomor WhatsApp, alamat, foto profil, dan ubah password dengan konfirmasi password lama.
- **Dashboard Ringkas**: Statistik total laporan, status aduan aktif, dan akses cepat buat aduan baru.

### Admin
- **Dashboard Rekapitulasi**: Ringkasan data real-time jumlah total aduan, pengguna warga terdaftar, dan rincian per status.
- **Pencarian & Filter Terpadu**: Cari laporan berdasarkan judul atau nama pelapor, filter per status, dan urutkan berdasarkan waktu (terbaru/terlama).
- **Alur Status Berjenjang**: Pengubahan status laporan secara tertib dan terkontrol (`pending` → `proses` → `selesai`).
- **Data Kontak Pelapor**: Tinjau detail aduan lengkap beserta foto lampiran dan nomor kontak/alamat pelapor untuk tindak lanjut cepat.

## Stack Teknologi

- **Backend**: Laravel 12.x / PHP 8.2+ (PHP 8.4 verified)
- **Frontend / Reaktivitas**: Livewire, Blade Components, Alpine.js, Tailwind CSS
- **Desain & Aset**: Editorial Wine & Stone (`#4A151C`, `#F7F4EE`, `#C59B27`), Custom SVG Civic Logo `<x-civic-logo />`, Zero Emoji
- **Database**: MySQL (Production / Local), SQLite in-memory (Automated Testing)
- **Testing**: Pest / PHPUnit (38 tests, 181 assertions — 100% passed)

## Panduan Instalasi Lokal

1. **Clone repository**:
   ```bash
   git clone https://github.com/DevisRoomi/project-e-report-abudevis-260558302012.git
   cd project-e-report-abudevis-260558302012
   ```

2. **Install dependensi**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Atur koneksi database dan kredensial admin pada file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=e-report
   DB_USERNAME=root
   DB_PASSWORD=root

   ADMIN_EMAIL=admin@ereport.test
   ADMIN_PASSWORD=Admin#EReport2026!
   ```

4. **Migrasi, Storage Link & Seeder**:
   ```bash
   php artisan storage:link
   php artisan migrate --seed
   ```

5. **Jalankan aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka di browser: `http://localhost:8000`

6. **Menjalankan Pengujian Otomatis**:
   ```bash
   php artisan test
   ```

## Akun Bawaan (Default Seeder)

- **Admin**:
  - Email: `admin@ereport.test`
  - Password: Nilai yang diatur pada `ADMIN_PASSWORD` di `.env` (contoh: `Admin#EReport2026!`)

---
Lisensi: [MIT](LICENSE)
