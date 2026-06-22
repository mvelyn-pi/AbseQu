<div align="center">

<h1>📋 AbsenQu</h1>

<p><strong>Sistem Manajemen Absensi Sekolah Berbasis QR Code</strong></p>

<p>
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Livewire-4.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

<p>
  AbsenQu adalah aplikasi web manajemen absensi sekolah yang modern dan efisien.<br>
  Guru cukup scan QR Code siswa — absensi langsung tercatat secara otomatis!
</p>

</div>

---

## 🎯 Tentang AbsenQu

**AbsenQu** adalah sistem absensi digital berbasis web yang dirancang khusus untuk sekolah. Sistem ini menggantikan metode absensi konvensional (kertas/manual) dengan teknologi **QR Code** yang cepat, akurat, dan bebas kertas.

### Masalah yang Diselesaikan

| Masalah Konvensional | Solusi AbsenQu |
|---|---|
| Absensi manual rentan manipulasi | QR Code unik tiap siswa, tidak bisa dipalsukan |
| Rekap data lambat & tidak akurat | Data terekam real-time, bisa diekspor ke CSV |
| Orang tua tidak tahu kehadiran anak | Portal khusus orang tua dengan notifikasi |
| Guru tidak punya dashboard ringkas | Dashboard dengan statistik kehadiran hari ini |
| Izin & sakit susah dikomunikasikan | Fitur pengajuan izin digital dengan approval |

---

## ✨ Fitur Utama

### 👨‍💼 Panel Admin
- **Dashboard** — ringkasan kehadiran seluruh sekolah hari ini
- **Manajemen Siswa** — CRUD data siswa lengkap dengan foto & NIS unik
- **Manajemen Kelas** — atur kelas dan wali kelas
- **Manajemen Guru** — tambah/ubah/hapus akun guru
- **Manajemen Orang Tua** — kelola akun portal orang tua
- **Generate QR Code** — buat & unduh QR Code siswa (format SVG)
- **Cetak ID Card** — print kartu identitas siswa dengan QR Code
- **Rekap Absensi** — lihat & filter absensi per tanggal/kelas, ekspor CSV
- **Manajemen Izin** — approve/tolak pengajuan izin dari orang tua
- **Log Notifikasi** — riwayat notifikasi yang terkirim
- **Pengaturan** — konfigurasi jam masuk, toleransi keterlambatan, dll.
- **Leaderboard** — peringkat kehadiran antar kelas/siswa

### 👨‍🏫 Panel Guru
- **Dashboard Guru** — statistik kehadiran hari ini + scan terbaru
- **Scanner QR Code** — scan QR Code siswa langsung dari browser (kamera)
- **Rekap Absensi** — filter absensi per kelas & tanggal, ekspor CSV
- **Manajemen Izin** — approve/tolak permohonan izin siswa
- **Leaderboard** — peringkat kehadiran per kelas
- **Wali Kelas** — guru wali kelas melihat ringkasan kelas sendiri secara otomatis

### 👨‍👩‍👦 Portal Orang Tua
- **Dashboard** — rekap kehadiran anak bulan ini (hadir, terlambat, izin, sakit, alpha)
- **Riwayat Absensi** — histori kehadiran harian dengan filter per bulan
- **Tren Kehadiran** — grafik kehadiran 30 hari terakhir
- **Peringkat Kelas** — posisi kehadiran anak di antara teman sekelasnya
- **Pengajuan Izin** — ajukan izin/sakit dengan upload bukti foto
- **Status Pengajuan** — pantau status izin (pending / disetujui / ditolak)

---

## 🏗️ Arsitektur Sistem

```
AbsenQu/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/          # Controller untuk panel admin
│   │       ├── Guru/           # Controller untuk panel guru
│   │       ├── Orangtua/       # Controller untuk portal orang tua
│   │       └── AttendanceController.php  # Inti proses absensi & QR scan
│   ├── Models/
│   │   ├── Student.php         # Data siswa + auto-generate QR code
│   │   ├── Attendance.php      # Rekaman absensi harian
│   │   ├── LeaveRequest.php    # Pengajuan izin/sakit
│   │   ├── Kelas.php           # Data kelas
│   │   ├── User.php            # Akun (admin/guru/orang tua)
│   │   ├── NotificationLog.php # Log notifikasi terkirim
│   │   └── Setting.php         # Konfigurasi sistem
│   ├── Services/
│   │   ├── AttendanceService   # Logika absensi, keterlambatan, alpha
│   │   ├── QrCodeService       # Generate QR Code SVG
│   │   └── LeaderboardService  # Hitung & tampilkan peringkat kehadiran
│   └── Livewire/               # Komponen Livewire interaktif
├── database/
│   └── migrations/             # 9 tabel: users, students, attendances, dst.
├── resources/views/
│   ├── admin/                  # Tampilan panel admin
│   ├── guru/                   # Tampilan panel guru
│   └── orangtua/               # Tampilan portal orang tua
└── routes/
    └── web.php                 # Routing dengan middleware role-based
```

### Alur Absensi

```
Siswa datang ke sekolah
       │
       ▼
Guru buka halaman Scanner (/guru/scanner)
       │
       ▼
Kamera HP/laptop scan QR Code di kartu siswa
       │
       ▼
AttendanceService memvalidasi QR Code
       │
       ├── Belum absen hari ini → Catat sebagai "Hadir" atau "Terlambat"
       ├── Sudah absen           → Tampilkan info, tidak dicatat ulang
       └── QR tidak valid        → Pesan error
       │
       ▼
Data tersimpan di tabel attendances
       │
       ▼
Orang tua dapat melihat status di portal mereka
```

---

## ⚙️ Persyaratan Sistem

Sebelum instalasi, pastikan laptop/komputer Anda memiliki:

| Kebutuhan | Versi Minimum | Cek |
|---|---|---|
| **PHP** | 8.3+ | `php -v` |
| **Composer** | 2.x | `composer -V` |
| **Node.js** | 18.x+ | `node -v` |
| **NPM** | 9.x+ | `npm -v` |
| **Git** | 2.x | `git -v` |

> **Tidak perlu MySQL!** AbsenQu menggunakan **SQLite** secara default — tidak perlu instal database server apapun.

---

## 🚀 Cara Instalasi (Step by Step)

### Langkah 1 — Clone atau Download Proyek

```bash
# Jika menggunakan Git
git clone https://github.com/mvelyn-pi/AbsenQu.git
cd AbsenQu

# Atau ekstrak file ZIP yang sudah didownload, lalu masuk ke foldernya
cd AbsenQu
```

### Langkah 2 — Install Dependensi PHP

```bash
composer install
```

> Perintah ini akan mengunduh semua library PHP yang dibutuhkan ke folder `vendor/`. Tunggu hingga selesai.

### Langkah 3 — Salin File Konfigurasi Environment

```bash
# Windows (Command Prompt / PowerShell)
copy .env.example .env

# Linux / macOS
cp .env.example .env
```

### Langkah 4 — Generate Application Key

```bash
php artisan key:generate
```

> Perintah ini membuat kunci enkripsi unik untuk aplikasi Anda. **Wajib dijalankan!**

### Langkah 5 — Setup Database

AbsenQu menggunakan SQLite, jadi tidak perlu konfigurasi database server.

```bash
# Jalankan migrasi untuk membuat semua tabel
php artisan migrate

# (Opsional) Isi data dummy untuk testing
php artisan db:seed
```

### Langkah 6 — Install Dependensi JavaScript & Build Aset

```bash
npm install
npm run build
```

### Langkah 7 — Buat Symbolic Link Storage

```bash
php artisan storage:link
```

> Diperlukan agar foto siswa dan file bukti izin bisa diakses melalui browser.

### Langkah 8 — Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi akan berjalan di: **http://localhost:8000**

---

## 🔑 Login Pertama Kali

Setelah instalasi, buka **http://localhost:8000** di browser.

Jika Anda menjalankan `db:seed`, akun default sudah tersedia:

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@absenqu.com` | `password` |
| **Guru** | `guru@absenqu.com` | `password` |
| **Orang Tua** | `orangtua@absenqu.com` | `password` |

> ⚠️ **Ganti password segera** setelah login pertama kali melalui menu profil!

---

## 🛠️ Konfigurasi Tambahan (Opsional)

### Mengubah Nama Aplikasi

Edit file `.env`:
```env
APP_NAME="AbsenQu - SMAN 1 Contoh"
APP_URL=http://localhost:8000
```

### Mengubah Timezone

Secara default, AbsenQu menggunakan timezone **Asia/Makassar (WITA, UTC+8)**. Untuk mengubahnya:

```env
# Di file .env
APP_TIMEZONE=Asia/Jakarta    # WIB (UTC+7)
APP_TIMEZONE=Asia/Makassar   # WITA (UTC+8)
APP_TIMEZONE=Asia/Jayapura   # WIT (UTC+9)
```

### Menggunakan MySQL (Opsional)

Jika ingin menggunakan MySQL daripada SQLite, edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absenqu
DB_USERNAME=root
DB_PASSWORD=password_anda
```

Kemudian buat database `absenqu` di MySQL, lalu jalankan ulang:

```bash
php artisan migrate
```

---

## 🔧 Pengembangan (Development Mode)

Untuk mode pengembangan dengan hot-reload:

```bash
# Terminal 1 — Jalankan semua service sekaligus
composer run dev
```

Atau secara manual:

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev

# Terminal 3 (opsional, untuk queue)
php artisan queue:listen
```

---

## 🧪 Menjalankan Test

```bash
composer run test
# atau
php artisan test
```

---

## 📦 Tech Stack

| Teknologi | Kegunaan |
|---|---|
| **Laravel 13** | Framework PHP backend |
| **Livewire 4** | Komponen interaktif tanpa banyak JavaScript |
| **Tailwind CSS 3** | Styling UI modern dan responsif |
| **Vite** | Build tool & asset bundler |
| **SQLite** | Database default (tanpa setup) |
| **simple-qrcode** | Generate QR Code SVG untuk tiap siswa |
| **Laravel Breeze** | Autentikasi (login, register, dll.) |

---

## 🗂️ Struktur Role & Hak Akses

```
┌─────────────────────────────────────────────────────┐
│                      ADMIN                          │
│  Akses penuh: siswa, guru, kelas, ortu,             │
│  absensi, izin, notifikasi, pengaturan, leaderboard │
└──────────────────────┬──────────────────────────────┘
                       │
         ┌─────────────┴─────────────┐
         ▼                           ▼
┌────────────────┐         ┌─────────────────────┐
│     GURU       │         │     ORANG TUA        │
│ Scanner QR     │         │ Lihat absensi anak   │
│ Rekap absensi  │         │ Ajukan izin/sakit    │
│ Approve izin   │         │ Pantau status izin   │
│ Leaderboard    │         │ Tren kehadiran       │
└────────────────┘         └─────────────────────┘
```

---

## ❓ Troubleshooting (Solusi Masalah Umum)

### Error: `php artisan key:generate` gagal
Pastikan file `.env` sudah ada. Jalankan `copy .env.example .env` terlebih dahulu.

### Halaman CSS/JS tidak muncul
Jalankan ulang `npm run build` dan pastikan `php artisan storage:link` sudah dijalankan.

### Error "Class not found"
```bash
composer dump-autoload
```

### Database error / tabel tidak ditemukan
```bash
php artisan migrate:fresh
```
> ⚠️ Perintah ini akan menghapus seluruh data. Gunakan hanya di lingkungan pengembangan.

### Storage foto tidak muncul
```bash
php artisan storage:link
```

---
<div align="center">
  <p>Dibuat dengan menggunakan Laravel & Livewire</p>
  <p><em>AbsenQu — Absensi cerdas untuk sekolah modern</em></p>
</div>
