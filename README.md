# PINJAMORANG.

> **Borrow a human. Return with a story.**

Marketplace untuk meminjam waktu manusia lain. Karena ternyata, waktu luang seseorang bisa menjadi pengalaman yang tidak terduga bagi orang lain.

---

## 🧠 What is PINJAMORANG?

**PINJAMORANG** adalah platform eksperimental berbasis *time-based social marketplace* yang memungkinkan seseorang menawarkan waktu luangnya kepada orang lain.

Bayangkan kamu memiliki waktu kosong selama 30 menit pada hari Sabtu pukul 19.00.

Daripada menghabiskannya sendirian, kamu bisa membuat sebuah penawaran di PINJAMORANG.

Orang lain dapat menemukan penawaran tersebut melalui katalog, kemudian bergabung secara gratis atau membeli slot yang tersedia.

Tidak ada barang yang dikirimkan. Tidak ada produk yang harus dibungkus.

**Yang ditawarkan adalah waktu. Yang didapatkan adalah pengalaman.**

> We don't sell people. We share moments.

---

## 🎯 Core Concept

PINJAMORANG dibangun berdasarkan tiga konsep utama:

1. **Time Listing** — Pengguna dapat menawarkan waktu kosong mereka.
2. **Human Catalog** — Pengguna lain dapat menjelajahi berbagai penawaran waktu yang tersedia.
3. **Time Booking** — Pengguna dapat bergabung atau memesan slot waktu berdasarkan kapasitas dan ketentuan yang ditetapkan Host.

---

## ⚙️ How It Works

```text
          HOST
           |
           v
    Create Time Listing
           |
           v
      Set Schedule
      Set Duration
      Set Capacity
      Set Price
           |
           v
      HUMAN CATALOG
           |
      +----+----+
      |         |
      v         v
    FREE      PAID
      |         |
      v         v
    JOIN      BOOK
      |         |
      +----+----+
           |
           v
      TIME SESSION
           |
           v
      EXPERIENCE
```

---

## 👥 User Roles

### 1. Host

Host adalah pengguna yang menawarkan waktu luangnya.

Host dapat:
- Membuat penawaran waktu.
- Menentukan tanggal dan jam.
- Menentukan durasi aktivitas.
- Menentukan kapasitas peserta.
- Menentukan harga atau menggratiskan penawaran.
- Menyetujui atau menolak peserta.
- Mengelola dan membatalkan penawaran.

### 2. Participant

Participant adalah pengguna yang ingin mengikuti penawaran dari Host.

Participant dapat:
- Menjelajahi katalog.
- Melihat detail penawaran.
- Bergabung dengan penawaran gratis.
- Memesan slot berbayar.
- Membatalkan reservasi sesuai ketentuan.
- Melihat jadwal pengalaman yang telah dipesan.

Satu pengguna dapat menjadi Host sekaligus Participant.

---

## 🛍️ Marketplace Features

### Time Listing

Setiap penawaran memiliki informasi:

| Property | Description |
|---|---|
| Title | Nama pengalaman |
| Description | Deskripsi aktivitas |
| Host | Pemilik penawaran |
| Date | Tanggal pelaksanaan |
| Start Time | Waktu mulai |
| End Time | Waktu selesai |
| Capacity | Jumlah peserta maksimal |
| Price | Harga per peserta |
| Session Type | `ONLINE` (Virtual) atau `OFFLINE` (Public Meetup) |
| Location | Link meeting (jika Online) atau nama ruang publik (jika Offline) |
| Approval Mode | Otomatis atau persetujuan Host |
| Boundaries | Checklist batasan interaksi + catatan opsional |

### Session Mode Classification

Untuk menjamin kenyamanan dan keselamatan, setiap penawaran diklasifikasikan secara tegas:

1. **`ONLINE` (Virtual Experience):**
   - Menggunakan platform virtual (Google Meet, Discord, Zoom, dsb.).
   - Link sesi dibagikan secara privat hanya setelah booking terkonfirmasi.
2. **`OFFLINE` (Public Meetup Only):**
   - Pertemuan tatap muka **wajib** bertempat di ruang publik terbuka yang ramai (misal: kafe, perpustakaan umum, taman kota).
   - Dilarang keras memilih lokasi privat (rumah tinggal, kamar hotel, kendaraan pribadi, area sepi).

### Interaction Boundaries (Do & Don't Checklist)

Sistem mengadopsi model **Hybrid (Preset Checklist + Custom Notes)** saat Host membuat listing:
* **Preset Checklist (Standar Terverifikasi):**
  - `[ ] Bebas bahasan asmara, perjodohan, atau flirtatious talk`
  - `[ ] Bebas bahasan politik, SARA, dan topik polarisasi`
  - `[ ] Dilarang merekam audio/video atau mengambil foto tanpa izin tertulis`
  - `[ ] Interaksi bersifat non-fisik (no physical touch)`
  - `[ ] Kamera wajib on / boleh off (khusus sesi Online)`
* **Custom Boundaries (Opsional):** Kolom catatan bebas bagi Host untuk menambahkan batasan spesifik (misal: *"Saya memiliki fobia anjing, mohon tidak membawa hewan peliharaan"*).
* **Participant Agreement:** Participant wajib membaca dan mencentang persetujuan atas batasan tersebut sebelum mengajukan booking.

### Trust & Karma Score System (Anti-Ghosting)

Untuk mencegah pembatalan sepihak dan meremehkan jadwal (terutama pada penawaran *FREE*):
* Setiap akun baru memiliki modal awal skor (misal: **100 Poin**).
* **Kehadiran Tepat Waktu:** Menambah poin (+5).
* **No-Show / Ghosting:** Mengurangi poin secara drastis (-25).
* **Pembatalan Wajar:** Pembatalan minimal 2 jam sebelum sesi tidak dikenakan penalti poin.
* **Penalti Batas Bawah:** Akun dengan skor di bawah ambang batas (misal: < 70) akan dibekukan sementara dari hak booking maupun listing (*cooldown period*).

### Privacy-First Communication

* Identitas kontak pribadi (nomor WhatsApp, telepon, medsos) tidak pernah ditampilkan ke publik.
* Komunikasi dan koordinasi dilakukan melalui pesan dalam aplikasi (*in-app messaging*) atau link sesi resmi.
* Melindungi privasi Host dan Participant dari tindak *spamming*, *stalking*, atau pelecehan pasca-sesi.

### Participation Models

**FREE PARTICIPATION**

Pengguna dapat bergabung tanpa membayar. Kapasitas tetap dibatasi oleh Host.

**PAID PARTICIPATION**

Pengguna harus membayar untuk mendapatkan slot. Host menentukan harga yang ditawarkan.

### Booking System

Sistem booking memastikan:
- Kapasitas tidak pernah terlampaui.
- Tidak terjadi double booking.
- Reservasi memiliki status yang jelas.
- Pembatalan ditangani secara konsisten.
- Pembayaran harus dikonfirmasi sebelum booking berbayar menjadi confirmed.

---

## 💡 Example Listings

Berikut beberapa contoh penawaran yang mungkin ditemukan di PINJAMORANG.

| Experience | Duration | Price |
|---|---:|---:|
| Let's talk about absolutely nothing | 30 min | FREE |
| Walk with me, no destination | 45 min | Rp15.000 |
| Teach me something weird | 20 min | FREE |
| Listen to my random thoughts | 15 min | Rp5.000 |
| Let's pretend we're old friends | 60 min | FREE |
| Help me name my future cat | 10 min | FREE |

*Because not every moment needs a reason.*

---

## 🏗️ Technical Architecture

PINJAMORANG dirancang menggunakan arsitektur client-server dengan pemisahan tanggung jawab yang jelas.

```text
             CLIENT
                |
                v
          NEXT.JS APP
                |
                v
          REST API
                |
                v
        SPRING BOOT
                |
       +--------+--------+
       |        |        |
       v        v        v
     AUTH    LISTING   BOOKING
       |        |        |
       +--------+--------+
                |
                v
            POSTGRESQL
```

### Technology Stack

| Layer | Technology |
|---|---|
| Frontend | Next.js |
| Backend | Java + Spring Boot |
| API | REST API |
| Database | MySQL / MariaDB (Laragon) |
| Authentication | Spring Security |
| ORM / Data Access | Spring Data JPA |
| Containerization | Docker |
| Version Control | Git |

### Struktur Folder MVC

Folder `app/` menjadi kerangka awal pemisahan tanggung jawab:

```text
app/
├── controller/
├── model/
└── view/
```

Letakkan pengendali alur aplikasi di `controller/`, struktur dan logika data di
`model/`, serta tampilan di `view/`.

---

## 🚀 Panduan Instalasi & Menjalankan Proyek (Setup Guide)

Bagi siapa saja yang meng-clone repositori ini, ikuti langkah mudah berikut untuk menjalankan PINJAMORANG di komputer lokal:

### 1. Prasyarat Sistem
- **PHP** versi 8.1 atau lebih baru (dengan ekstensi `pdo_mysql` aktif).
- **MySQL / MariaDB** (dapat menggunakan Laragon, XAMPP, Docker, atau MySQL Server standalone).
- **Git**

### 2. Clone Repositori
```bash
git clone https://github.com/quidist/pinjam-orang.git
cd pinjam-orang
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env` di root direktori proyek:
```bash
cp .env.example .env
```
Isi dan sesuaikan kredensial database di dalam `.env`:
```ini
DB_HOST="127.0.0.1"
DB_NAME="pinjam_orang"
DB_USERNAME="root"
DB_PASSWORD=""
```

### 4. Setup Database
1. Buat database baru di MySQL dengan nama `pinjam_orang`:
   ```sql
   CREATE DATABASE pinjam_orang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Import skema tabel dari file `database.sql`:
   - **Melalui Terminal:**
     ```bash
     mysql -u root -p pinjam_orang < database.sql
     ```
   - **Atau melalui GUI (phpMyAdmin / HeidiSQL / DBeaver):** Buka database `pinjam_orang`, lalu impor file `database.sql`.

### 5. Jalankan Aplikasi
Proyek ini dibangun menggunakan arsitektur murni (*Pure PHP MVC*) tanpa dependensi runtime pihak ketiga yang rumit. Anda dapat langsung menjalankan server lokal bawaan PHP melalui terminal:

```bash
php -S localhost:8000 index.php
```

Buka peramban (browser) dan akses alamat:
👉 **http://localhost:8000**

> *Catatan: Jika menggunakan Laragon atau Apache XAMPP, arahkan Document Root virtual host ke direktori proyek ini.*


---

## 🔐 Important Business Rules

1. Host tidak dapat menawarkan waktu yang bertabrakan dengan penawaran aktif miliknya.
2. Participant tidak dapat memesan slot yang kapasitasnya sudah habis.
3. Setiap booking harus memiliki identitas Host, Participant, dan Listing yang valid.
4. Listing yang sudah dimulai tidak dapat menerima reservasi baru.
5. Booking berbayar tidak boleh dianggap berhasil sebelum pembayaran terkonfirmasi.
6. Host tetap memiliki kendali atas penawaran dan batas interaksinya.
7. Pengguna dapat membatalkan penawaran atau reservasi berdasarkan kebijakan yang berlaku.
8. Pertemuan tatap muka (offline) hanya diizinkan di ruang publik terbuka yang ramai.
9. Pengguna dengan Trust Score di bawah batas minimum tidak dapat membuat reservasi baru.
10. Dilarang bertukar kontak pribadi secara langsung di luar platform sebelum sesi terkonfirmasi.
11. Participant wajib menyetujui batasan interaksi (boundaries) yang ditetapkan Host sebelum booking terkonfirmasi.

---

## 🚧 Development Roadmap

### Phase 1 — MVP

- [ ] User registration and authentication.
- [ ] User profile.
- [ ] Create time listing.
- [ ] Browse human catalog.
- [ ] Listing detail.
- [ ] Free participation.
- [ ] Booking management.
- [ ] Capacity management.
- [ ] Host dashboard.
- [ ] Session type filter (Online vs Offline Public).
- [ ] Boundaries checklist & participant agreement.

### Phase 2 — Marketplace

- [ ] Paid listing.
- [ ] Payment gateway integration.
- [ ] Payment verification.
- [ ] Refund and cancellation policies.
- [ ] Host approval system.
- [ ] Search and filtering.
- [ ] Trust & Karma scoring system (anti-ghosting).
- [ ] Privacy-first communication mask.

### Phase 3 — Social Experience

- [ ] Private messaging.
- [ ] Reviews and ratings.
- [ ] User verification.
- [ ] Notifications.
- [ ] Experience history.
- [ ] Reputation system.

---

## 🛡️ Safety & Boundaries

PINJAMORANG memperdagangkan kesempatan untuk berpartisipasi dalam waktu seseorang, bukan kepemilikan atas seseorang.

Platform harus memastikan:
- Persetujuan kedua pihak.
- Batas interaksi yang jelas.
- Perlindungan data pribadi.
- Mekanisme pelaporan dan pemblokiran.
- Tidak ada pemaksaan atau pelecehan.
- Pembatalan dan refund yang transparan.
- Perlindungan privasi (tanpa membagikan nomor telepon pribadi).
- Pengamanan pertemuan tatap muka hanya di ruang publik.
- Penegakan integritas kehadiran melalui Trust & Karma Score.

---

## 📌 Project Philosophy

> Time is the only thing you cannot get back.
>
> But apparently, you can share it with a stranger.

PINJAMORANG lahir dari sebuah gagasan sederhana:

Bagaimana jika waktu kosong manusia dapat menjadi sesuatu yang bisa ditemukan, ditawarkan, dan dialami bersama orang lain?

Kami tidak sedang mencoba membuat dunia lebih produktif.

Kami hanya ingin membuat waktu luang menjadi sedikit lebih menarik.

---

**PINJAMORANG.**

*Borrow a human. Return with a story.*