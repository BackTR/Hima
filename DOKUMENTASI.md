# 📘 Dokumentasi Project Sistem Anggota HIMA

> **Versi:** 1.0.0  
> **Framework:** Laravel 11 + Breeze + Sanctum  
> **Tanggal:** 2026  
> **Developer:** Asing Developer

---

## 📋 Daftar Isi

1. [Gambaran Umum](#1-gambaran-umum)
2. [Arsitektur Sistem](#2-arsitektur-sistem)
3. [Struktur Database](#3-struktur-database)
4. [Fitur-Fitur](#4-fitur-fitur)
5. [Role & Permission](#5-role--permission)
6. [API Documentation](#6-api-documentation)
7. [Teknologi yang Digunakan](#7-teknologi-yang-digunakan)
8. [Cara Install & Running](#8-cara-install--running)

---

## 1. Gambaran Umum

**Sistem Anggota HIMA** adalah aplikasi manajemen anggota himpunan mahasiswa yang dibangun menggunakan Laravel. Aplikasi ini menyediakan dua antarmuka:

- **Web Application** - Untuk admin dan pengurus dalam mengelola anggota, event, dan kehadiran
- **Mobile API** - Untuk anggota melakukan absensi QR dan melihat profil

### Tujuan Aplikasi

1. Memudahkan pengelolaan data anggota himpunan
2. Digitalisasi proses absensi event dengan QR Code
3. Monitoring kaderisasi anggota
4. Logging aktivitas untuk audit trail

---

## 2. Arsitektur Sistem

### 2.1 Arsitektur MVC (Model-View-Controller)

```
┌─────────────────────────────────────────────────────────────┐
│                        ROUTES                                │
│  (web.php, api.php, auth.php)                               │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    CONTROLLERS                               │
│  - DashboardController      - AttendanceController          │
│  - MemberController         - EventSessionController        │
│  - EventController          - ActivityLogController         │
│  - KaderisasiController    - Api\AuthController             │
│  - AnggotaController        - Api\AnggotaController          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      SERVICES                                │
│  - MemberService (Business Logic untuk member)             │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       MODELS                                 │
│  - User              - EventSession    - ActivityLog        │
│  - Member            - Attendance       - Kaderisasi        │
│  - Event                                                   │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    DATABASE (MySQL/SQLite)                  │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Route Groups & Middleware

```
Web Routes (dengan session auth)
├── / (welcome page - public)
├── /dashboard (semua role)
├── /profil/* (semua role)
│   ├── profil - lihat profil
│   ├── profil/edit - edit profil
│   ├── riwayat - riwayat kehadiran
│   ├── scan-qr - scan QR absensi
│   └── ganti-password - ubah password
├── /members (admin+)
│   ├── index, show (semua authenticated)
│   └── create, edit, update, destroy (superadmin, admin)
├── /events (pengurus+)
│   ├── CRUD lengkap
│   └── sessions (start/stop QR)
├── /kaderisasi (superadmin, admin)
│   └── CRUD lengkap
├── /activity-logs (superadmin, admin)
│   └── lihat log aktivitas
└── /attendances (pengurus+)
    └── manage kehadiran

API Routes (dengan Sanctum token)
├── POST /api/login
├── POST /api/logout (protected)
├── GET /api/profil (protected)
├── PATCH /api/profil (protected)
├── PATCH /api/ganti-password (protected)
├── GET /api/riwayat (protected)
├── GET /api/events (protected)
└── GET /api/absen/{token} (protected)
```

---

## 3. Struktur Database

### 3.1 Diagram Relasi

```
┌─────────────────┐       ┌─────────────────┐
│     users      │       │    members      │
├─────────────────┤       ├─────────────────┤
│ id              │◄──────│ user_id (FK)    │
│ name            │       │ id              │
│ email           │       │ nim             │
│ password        │       │ angkatan        │
│ role            │       │ divisi          │
│ is_active       │       │ no_hp           │
│ remember_token  │       │ alamat          │
│ created_at      │       │ status          │
│ updated_at      │       │ created_at      │
└─────────────────┘       │ updated_at      │
         │                └────────┬────────┘
         │                         │
         │                ┌────────┴────────┐
         │                │  kaderisasi     │
         │                ├─────────────────┤
         │                │ id              │
         │                │ member_id (FK)  │◄──┐
         │                │ level           │   │
         │                │ status          │   │
         │                │ catatan         │   │
         │                │ created_at      │   │
         │                └─────────────────┘   │
         │                                      │
         │                ┌─────────────────────┘
         │                │
         │                ▼
         │        ┌─────────────────┐       ┌─────────────────┐
         │        │     events      │       │ activity_logs   │
         │        ├─────────────────┤       ├─────────────────┤
         │        │ id              │       │ id              │
         ├────────│ created_by (FK) │       │ user_id (FK)   │
         │        │ nama_event      │       │ action          │
         │        │ tanggal         │       │ model           │
         │        │ lokasi          │       │ model_id        │
         │        │ deskripsi       │       │ description     │
         │        │ created_at      │       │ ip_address      │
         │        │ updated_at      │       │ created_at      │
         │        └────────┬────────┘       └─────────────────┘
         │                 │
         │                 │
         │        ┌────────┴────────┐
         │        │  event_sessions  │
         │        ├─────────────────┤
         │        │ id               │
         │        │ event_id (FK)   │◄──────┐
         │        │ created_by (FK) │       │
         │        │ token (UUID)    │       │
         │        │ is_active       │       │
         │        │ expired_at      │       │
         │        │ created_at      │       │
         │        └─────────────────┘       │
         │                                  │
         │                ┌──────────────────┘
         │                │
         │                ▼
         │        ┌─────────────────┐
         │        │  attendances    │
         │        ├─────────────────┤
         │        │ id              │
         └────────│ event_id (FK)   │
                  │ member_id (FK)  │
                  │ waktu_scan      │
                  │ status          │
                  │ created_at      │
                  └─────────────────┘
```

### 3.2 Detail Tabel

#### 📌 Tabel: `users`

| Kolom | Tipe | Keterangan |
|-------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | Nama lengkap |
| email | varchar(255) | Email unik |
| password | varchar(255) | Hashed password |
| role | enum | superadmin, admin, pengurus, anggota |
| is_active | boolean | Status aktif/nonaktif |
| remember_token | varchar(100) | Remember me token |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

#### 📌 Tabel: `members`

| Kolom | Tipe | Keterangan |
|-------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint (FK) | Relasi ke users |
| nim | varchar(20) | NIM mahasiswa (nullable, unique) |
| angkatan | varchar(2) | Tahun masuk (2 digit) |
| divisi | varchar(100) | Divisi anggota |
| no_hp | varchar(15) | Nomor HP |
| alamat | text | Alamat lengkap |
| status | enum | aktif, alumni, nonaktif |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

#### 📌 Tabel: `events`

| Kolom | Tipe | Keterangan |
|-------|------|-------------|
| id | bigint | Primary key |
| nama_event | varchar(255) | Nama event |
| tanggal | date | Tanggal event |
| lokasi | varchar(255) | Lokasi event |
| deskripsi | text | Deskripsi event |
| created_by | bigint (FK) | User yang membuat |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

#### 📌 Tabel: `event_sessions`

| Kolom | Tipe | Keterangan |
|-------|------|-------------|
| id | bigint | Primary key |
| event_id | bigint (FK) | Relasi ke events |
| created_by | bigint (FK) | User yang memulai |
| token | uuid | Token unik untuk QR |
| is_active | boolean | Status aktif |
| expired_at | timestamp | Waktu kedaluwarsa |
| created_at | timestamp | Waktu dibuat |

#### 📌 Tabel: `attendances`

| Kolom | Tipe | Keterangan |
|-------|------|-------------|
| id | bigint | Primary key |
| event_id | bigint (FK) | Relasi ke events |
| member_id | bigint (FK) | Relasi ke members |
| waktu_scan | timestamp | Waktu scan absensi |
| status | enum | hadir, izin |
| created_at | timestamp | Waktu dibuat |

#### 📌 Tabel: `kaderisasi`

| Kolom | Tipe | Keterangan |
|-------|------|-------------|
| id | bigint | Primary key |
| member_id | bigint (FK) | Relasi ke members |
| level | varchar(100) | Tingkat kaderisasi |
| status | enum | proses, lulus, gagal |
| catatan | text | Catatan evaluasi |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

#### 📌 Tabel: `activity_logs`

| Kolom | Tipe | Keterangan |
|-------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint (FK) | User yang melakukan |
| action | varchar(50) | Jenis aksi |
| model | varchar(50) | Nama model |
| model_id | bigint | ID model |
| description | text | Deskripsi aktivitas |
| ip_address | varchar(45) | IP user |
| created_at | timestamp | Waktu dibuat |

---

## 4. Fitur-Fitur

### 4.1 🔐 Authentication & Authorization

| Fitur | Deskripsi |
|-------|-----------|
| Login | Session-based untuk web |
| Register | Pendaftaran anggota baru (via admin) |
| Logout | Hapus session |
| Password Reset | Reset password oleh admin |
| Ganti Password | User ganti password sendiri |
| Role Middleware | Proteksi route berdasarkan role |

### 4.2 👥 Manajemen Anggota (Member Management)

| Fitur | Deskripsi | Akses |
|-------|-----------|-------|
| List Anggota | Lihat semua anggota | Admin+ |
| Tambah Anggota | Buat user + member baru | Superadmin, Admin |
| Edit Anggota | Update data anggota | Superadmin, Admin |
| Hapus Anggota | Soft delete | Superadmin, Admin |
| Reset Password | Reset password anggota | Superadmin, Admin |
| Lihat Profil | Detail profil anggota | Semua role |
| Edit Profil | Update profil sendiri | Semua role |
| Status Anggota | Aktif, Alumni, Nonaktif | - |

### 4.3 📅 Manajemen Event

| Fitur | Deskripsi | Akses |
|-------|-----------|-------|
| List Event | Lihat semua event | Pengurus+ |
| Buat Event | Buat event baru | Pengurus+ |
| Edit Event | Update event | Pengurus+ |
| Hapus Event | Hapus event | Pengurus+ |
| Lihat Detail | Detail event + peserta | Pengurus+ |

### 4.4 ✅ Sistem Absensi

| Fitur | Deskripsi | Akses |
|-------|-----------|-------|
| Mulai Sesi | Generate QR Code (5 menit) | Pengurus+ |
| Tutup Sesi | Nonaktifkan QR | Pengurus+ |
| Tampilkan QR | Tampilkan QR di layar | Pengurus+ |
| Scan QR | Scan QR untuk absensi | Anggota |
| Manual Absen | Input kehadiran manual | Pengurus+ |
| List Kehadiran | Lihat kehadiran per event | Pengurus+ |
| Hapus Kehadiran | Hapus data kehadiran | Pengurus+ |

#### Alur Absensi QR:

```
1. Pengurus memulai sesi absensi
   ↓
2. Sistem generate token UUID + expired 5 menit
   ↓
3. Tampilkan QR Code di layar
   ↓
4. Anggota scan QR via web/mobile
   ↓
5. Sistem validasi token (aktif & belum expired)
   ↓
6. Cek duplikasi kehadiran
   ↓
7. Simpan attendance ke database
   ↓
8. Tampilkan hasil scan
```

### 4.5 🎓 Sistem Kaderisasi

| Fitur | Deskripsi | Akses |
|-------|-----------|-------|
| List Kaderisasi | Lihat semua kaderisasi | Superadmin, Admin |
| Tambah Kaderisasi | Input kaderisasi | Superadmin, Admin |
| Edit Kaderisasi | Update kaderisasi | Superadmin, Admin |
| Hapus Kaderisasi | Hapus kaderisasi | Superadmin, Admin |

#### Level Kaderisasi:
- **Calon Anggota Baru (CAB)**
- **Anggota Muda**
- **Anggota Aktif**
- **Calon Pengurus**
- **Pengurus**

#### Status:
- **Proses** - Sedang dalam evaluasi
- **Lulus** - Lulus kaderisasi
- **Gagal** - Tidak lulus

### 4.6 📊 Dashboard

| Role | Widget |
|------|--------|
| Superadmin | Total user, total anggota, total pengurus, total admin, anggota aktif/alumni/nonaktif, recent members, recent activity logs |
| Admin | Total user, total anggota, total pengurus, total admin, anggota aktif/alumni/nonaktif, recent members |
| Pengurus | List event terbaru |
| Anggota | Stats kehadiran |

### 4.7 📝 Activity Log

| Fitur | Deskripsi | Akses |
|-------|-----------|-------|
| List Log | Lihat semua aktivitas | Superadmin |
| Auto Log | Logging otomatis semua aksi | Sistem |

#### Aktivitas yang Di-log:
- Login/Logout
- CRUD Anggota
- CRUD Event
- CRUD Kaderisasi
- Update Profil
- Ganti Password
- Absensi

### 4.8 📱 API Mobile

| Endpoint | Method | Deskripsi |
|----------|--------|-----------|
| /api/login | POST | Login mobile |
| /api/logout | POST | Logout mobile |
| /api/profil | GET | Get profil user |
| /api/profil | PATCH | Update profil |
| /api/ganti-password | PATCH | Ganti password |
| /api/riwayat | GET | Riwayat kehadiran |
| /api/events | GET | List event hari ini |
| /api/absen/{token} | GET | Absen via token |

---

## 5. Role & Permission

### 5.1 Daftar Role

| Role | Deskripsi |
|------|-----------|
| **superadmin** | Akses penuh ke semua fitur, tidak bisa dihapus |
| **admin** | Manajemen anggota & kaderisasi, tidak bisa reset superadmin |
| **pengurus** | Kelola event & absensi, lihat anggota |
| **anggota** | Profil, riwayat absensi, scan QR |

### 5.2 Matrix Permission

| Fitur | Superadmin | Admin | Pengurus | Anggota |
|-------|------------|-------|----------|---------|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| List Anggota | ✅ | ✅ | ✅ | ❌ |
| Tambah Anggota | ✅ | ✅ | ❌ | ❌ |
| Edit Anggota | ✅ | ✅* | ❌ | ❌ |
| Hapus Anggota | ✅ | ✅* | ❌ | ❌ |
| Reset Password | ✅ | ✅* | ❌ | ❌ |
| CRUD Event | ✅ | ✅ | ✅ | ❌ |
| Absensi QR | ✅ | ✅ | ✅ | ❌ |
| Manual Absensi | ✅ | ✅ | ✅ | ❌ |
| Scan QR | ✅ | ✅ | ✅ | ✅ |
| CRUD Kaderisasi | ✅ | ✅ | ❌ | ❌ |
| Activity Log | ✅ | ❌ | ❌ | ❌ |
| Edit Profil Sendiri | ✅ | ✅ | ✅ | ✅ |
| Ganti Password | ✅ | ✅ | ✅ | ✅ |

*Catatan: Admin tidak bisa edit/reset/hapus superadmin atau admin lain

### 5.3 Proteksi Khusus

```php
// Admin tidak bisa edit superadmin
if ($user->role === 'superadmin' && $currentUser->role !== 'superadmin') {
    return redirect()->route('members.index')->with('error', 'Tidak bisa edit superadmin!');
}

// Admin tidak bisa hapus dirinya sendiri
if ($user->id === $currentUser->id) {
    return redirect()->route('members.index')->with('error', 'Tidak bisa hapus diri sendiri!');
}

// Admin tidak bisa hapus admin lain
if ($currentUser->role === 'admin' && $user->role === 'admin') {
    return redirect()->route('members.index')->with('error', 'Admin tidak bisa hapus admin lain!');
}
```

---

## 6. API Documentation

### Base URL

```
Production: https://api.hima.example.com
Development: http://localhost:8000/api
```

### Authentication

#### 📌 POST /api/login

**Request:**

```json
{
    "email": "anggota@gmail.com",
    "password": "password123"
}
```

**Response Success (200):**

```json
{
    "succes": true,
    "message": "Login Berhasil",
    "token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "anggota@gmail.com",
        "role": "anggota"
    }
}
```

**Response Error (401):**

```json
{
    "success": false,
    "message": "email atau password salah"
}
```

**Response Error (403):**

```json
{
    "success": false,
    "message": "akun kamu sudah tidak aktif"
}
```

#### 📌 POST /api/logout

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "success": false,
    "message": "Logout Berhasil"
}
```

### Profile

#### 📌 GET /api/profil

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "anggota@gmail.com",
        "role": "anggota",
        "is_active": true,
        "member": {
            "nim": "12345678",
            "angkatan": "24",
            "divisi": "Media",
            "no_hp": "081234567890",
            "alamat": "Jl. Merdeka No. 1",
            "status": "aktif"
        }
    }
}
```

#### 📌 PATCH /api/profil

**Headers:**

```
Authorization: Bearer {token}
```

**Request:**

```json
{
    "name": "John Doe Updated",
    "no_hp": "081234567890",
    "alamat": "Jl. Baru No. 5"
}
```

**Response (200):**

```json
{
    "success": true,
    "message": "profil berhasil di update"
}
```

#### 📌 PATCH /api/ganti-password

**Headers:**

```
Authorization: Bearer {token}
```

**Request:**

```json
{
    "password_lama": "password123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Response Success (200):**

```json
{
    "success": true,
    "message": "password berhasil di ubah"
}
```

**Response Error (422):**

```json
{
    "success": false,
    "message": "Password lama tidak sesuai!"
}
```

### Attendance

#### 📌 GET /api/riwayat

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "success": true,
    "data": [
        {
            "event": "Seminar Teknologi",
            "tanggal": "2026-03-01",
            "waktu_scan": "2026-03-01 10:00:00",
            "status": "hadir"
        },
        {
            "event": "Workshop Flutter",
            "tanggal": "2026-02-28",
            "waktu_scan": "2026-02-28 13:30:00",
            "status": "hadir"
        }
    ]
}
```

### Events

#### 📌 GET /api/events

**Headers:**

```
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama_event": "Seminar Teknologi",
            "lokasi": "Gedung A",
            "tanggal": "2026-03-01",
            "has_active_session": true
        },
        {
            "id": 2,
            "nama_event": "Workshop Flutter",
            "lokasi": "Lab Komputer",
            "tanggal": "2026-03-02",
            "has_active_session": false
        }
    ]
}
```

#### 📌 GET /api/absen/{token}

**Headers:**

```
Authorization: Bearer {token}
```

**Response Success (200):**

```json
{
    "success": true,
    "message": "Berhasil! Kehadiran kamu sudah tercatat.",
    "event": "Seminar Teknologi"
}
```

**Response Error - Token Invalid (404):**

```json
{
    "success": false,
    "message": "Token tidak valid!"
}
```

**Response Error - Session Closed (400):**

```json
{
    "success": false,
    "message": "Sesi absensi sudah ditutup!"
}
```

**Response Error - QR Expired (400):**

```json
{
    "success": false,
    "message": "QR Code sudah expired!"
}
```

**Response Error - Already Absent (400):**

```json
{
    "success": false,
    "message": "Kamu sudah tercatat hadir di event ini!"
}
```

---

## 7. Teknologi yang Digunakan

### 7.1 Backend

| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| Laravel | 11.x | Framework utama |
| PHP | 8.2+ | Bahasa pemrograman |
| MySQL/SQLite | - | Database |
| Sanctum | - | API Authentication |

### 7.2 Frontend

| Teknologi | Kegunaan |
|-----------|----------|
| Blade | Template engine |
| Tailwind CSS | Styling |
| Breeze | UI starter kit |

### 7.3 Tools

| Tool | Kegunaan |
|------|----------|
| Composer | Dependency manager |
| NPM | Package manager |
| Vite | Build tool |
| Artisan | CLI commands |

---

## 8. Cara Install & Running

### 8.1 Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/SQLite
- Git

### 8.2 Installation Steps

```bash
# 1. Clone project
git clone https://github.com/username/hima-sistem-anggota.git
cd hima-sistem-anggota

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env

# 4. Generate key
php artisan key:generate

# 5. Setup database
# Edit .env dengan konfigurasi database

# 6. Run migration
php artisan migrate

# 7. Seed database
php artisan db:seed

# 8. Build assets
npm run build

# 9. Run server
php artisan serve
```

### 8.3 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Superadmin | superadmin@gmail.com | password123 |

### 8.4 Available Artisan Commands

```bash
# Clear all cache
php artisan optimize:clear

# Seed database
php artisan db:seed

# Create seeder
php artisan make:seeder UserSeeder

# Create migration
php artisan make:migration create_members_table

# Create controller
php artisan make:controller MemberController

# Create model
php artisan make:model Member

# List routes
php artisan route:list

# Clear log activity (custom command)
php artisan activitylogs:clean
```

---

## 📌 Catatan Penting

### Keamanan

1. **Password** selalu di-hash menggunakan bcrypt
2. **API Token** menggunakan Laravel Sanctum (plain text token)
3. **QR Token** menggunakan UUID yang expired dalam 5 menit
4. **Middleware** proteksi berdasarkan role

### Best Practices

1. Semua input divalidasi menggunakan Request Validation
2. Menggunakan database transaction untuk operasi multi-table
3. Activity log untuk audit trail
4. Soft delete tidak digunakan (hard delete)

### Pengembangan Masa Depan

1. ~~QR Code Absensi~~ ✅
2. ~~API Mobile~~ ✅
3. ~~Activity Log~~ ✅
4. ~~Kaderisasi~~ ✅
5. Notifikasi email
6. Export laporan (Excel/PDF)
7. Dashboard analytics
8. Upload foto profil

---

## 📞 Dukungan

Jika ada pertanyaan atau issues, silakan hubungi tim developer atau buat issue di repository.

---

*Dokumentasi ini dibuat dengan standar profesional. Setiap perubahan fitur akan diupdate pada versi berikutnya.*

