# ESA RUNNER - Sistem Layanan Jasa Suruh Berbasis Web

## UAS Pemrograman Web

**Nama** : Muhammad Naufal Febrian  
**NIM** : 20240801068  

**Judul Proyek** :  
**ESA RUNNER - Sistem Layanan Jasa Suruh Berbasis Web dengan Integrasi OpenStreetMap, OSRM, dan WhatsApp**

---

## 📖 Deskripsi

ESA RUNNER merupakan aplikasi layanan jasa suruh berbasis web yang memudahkan pengguna untuk membuat permintaan layanan seperti pembelian makanan, pengambilan paket, pengantaran dokumen, dan kebutuhan lainnya.

Sistem akan menghitung estimasi jarak serta biaya perjalanan menggunakan OpenStreetMap dan OSRM, kemudian secara otomatis membuat pesan WhatsApp kepada admin agar proses pemesanan dapat langsung diproses.

Admin mengelola seluruh permintaan melalui dashboard Filament, mulai dari memverifikasi request hingga memperbarui status pengerjaan.

---

# ✨ Fitur

## User

- Landing Page ESA RUNNER
- Melihat informasi layanan
- Membuat permintaan layanan
- Memilih kategori layanan
- Memilih tipe layanan (Normal / Express)
- Menentukan lokasi awal dan tujuan melalui peta
- Estimasi jarak otomatis
- Estimasi biaya perjalanan otomatis
- Input dana pembelian (opsional)
- Total biaya otomatis
- WhatsApp Admin otomatis
- Tracking status request
- Halaman detail request
- Konfirmasi pembayaran melalui WhatsApp
- Komplain melalui WhatsApp

---

## Admin

- Login Dashboard
- Kelola User
- Kelola Role
- Kelola Kategori Layanan
- Kelola Pengaturan Layanan
- Kelola Permintaan Layanan
- Melihat detail request
- Mengubah status request
- Monitoring histori status request

---

# 🛠 Tech Stack

### Backend

- Laravel 12
- PHP 8.3

### Admin Panel

- Filament v3

### Frontend

- Livewire
- Blade
- AlpineJS
- Tailwind CSS

### Database

- MariaDB

### Container

- Docker

### Maps

- Leaflet JS
- OpenStreetMap
- OSRM API

### Integrasi

- WhatsApp API (Link Generator)

---

# 📂 Struktur Sistem

```
User
 │
 ▼
Landing Page
 │
 ▼
Form Permintaan
 │
 ▼
Perhitungan Estimasi
(Map + OSRM)
 │
 ▼
Generate WhatsApp
 │
 ▼
Admin Dashboard
 │
 ▼
Update Status
 │
 ▼
User Tracking Status
```

---

# ⚙️ Instalasi

Clone repository

```bash
git clone https://github.com/PallFebrian/project_pemweb-2026.git
```

Masuk ke folder project

```bash
cd project_pemweb-2026
```

Copy environment

```bash
cp src/.env.example src/.env
```

Build docker

```bash
docker compose build
```

Jalankan container

```bash
docker compose up -d
```

Generate key

```bash
docker compose exec php php artisan key:generate
```

Migrasi database

```bash
docker compose exec php php artisan migrate --seed
```

---

# 📍 Akses

Frontend

```
http://localhost
```

Admin

```
http://localhost/admin
```

---

# 📸 Fitur Utama

- Estimasi jarak menggunakan OSRM
- Perhitungan biaya otomatis
- Tracking status request
- Dashboard Admin Filament
- Integrasi WhatsApp
- OpenStreetMap
- Responsive Interface

---

# 📊 Status Request

- Baru
- Diproses
- Selesai

---

# 📁 Database

Beberapa tabel utama

- users
- kategori_layanan
- pengaturan_layanans
- permintaan_layanan
- log_status_permintaan
- roles
- permissions

---

# 👨‍💻 Dibuat Oleh

**Muhammad Naufal Febrian**  
NIM : **20240801068**

Universitas Esa Unggul  
Program Studi Teknik Informatika  
Mata Kuliah Pemrograman Web

2026
