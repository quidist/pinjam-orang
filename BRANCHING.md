# Panduan Strategi Git Branching: PINJAMORANG

Dokumen ini adalah panduan resmi alur kerja manajemen cabang (*Git Branching Strategy*) untuk repositori proyek **PINJAMORANG**.

---

## 1. Filosofi & Model Alur Kerja

Repositori ini menerapkan model **Feature Branch Workflow**:
- **Cabang Utama (`main`) adalah versi stabil:** Kode di `main` selalu siap dijalankan, bebas dari *error*, dan telah lulus pengujian.
- **Dilarang langsung mengoding di `main`:** Setiap fitur baru atau perbaikan harus dikerjakan di cabang fitur terisolasi (`feature/...`).
- **1 Branch = 1 Fokus Fitur:** Menjaga riwayat commit tetap bersih dan mencegah terjadinya konflik kode (*merge conflict*).

---

## 2. Peta Keseluruhan Cabang & Tanggung Jawabnya

```text
main (Versi Stabil)
  │
  ├── feature/users ───────────────────────────> Selesai & Merge ke main
  │
  ├── feature/listings ────────────────────────> Selesai & Merge ke main
  │
  ├── feature/bookings ────────────────────────> Selesai & Merge ke main
  │
  ├── feature/user-profile ────────────────────> Selesai & Merge ke main
  │
  ├── feature/payments (Fase 2) ───────────────> Selesai & Merge ke main
  │
  └── feature/reviews-reputation (Fase 3) ─────> Selesai & Merge ke main
```

---

## 3. Rincian Setiap Cabang Fitur

### A. Cabang Utama
- **Nama Cabang:** `main`
- **Fungsi:** Menyimpan kode produksi yang stabil. 
- **Aturan:** Kode hanya masuk ke `main` melalui proses *merge* dari cabang fitur yang sudah tuntas diuji.

---

### B. Cabang Fitur Fase 1 (MVP)

#### 1. `feature/users` (Sedang Aktif)
- **Tujuan:** Membangun fondasi sistem pengguna dan autentikasi.
- **Komponen yang Dikerjakan:**
  - Model: `app/model/users.model.php`
  - Controller: `app/controller/auth.controller.php`
  - View Layout: `app/view/layout/header.view.php` & `footer.view.php`
  - View Auth: `app/view/auth/login.view.php` & `register.view.php`
  - Script Klien: `public/assets/js/auth.js`
- **Kriteria Selesai (*Definition of Done*):**
  - Pengguna baru dapat mendaftar (kata sandi terenkripsi `BCRYPT`).
  - Pengguna dapat masuk dan sesi login (`$_SESSION['user']`) aktif.
  - Navbar menampilkan nama pengguna saat login dan tombol login saat tamu.
  - Pengguna dapat logout dengan aman.

---

#### 2. `feature/listings`
- **Tujuan:** Membangun modul penawaran waktu (*Human Catalog*).
- **Komponen yang Dikerjakan:**
  - Model: `app/model/listings.model.php`
  - Controller: `app/controller/listings.controller.php`
  - View: `app/view/listings/catalog.view.php`, `detail.view.php`, `create.view.php`
  - Script Klien: `public/assets/js/listing-form.js`
- **Kriteria Selesai (*Definition of Done*):**
  - Pengunjung publik dapat menjelajahi katalog waktu dengan filter kata kunci, tipe sesi (Online/Offline), dan harga gratis.
  - Host (dengan Trust Score &ge; 70) dapat membuat penawaran baru.
  - Terjadi validasi pencegahan jadwal bentrok (*anti-overlapping*).
  - Tipe sesi Offline mewajibkan penentuan lokasi ruang publik yang ramai.

---

#### 3. `feature/bookings`
- **Tujuan:** Membangun sistem reservasi slot waktu dan dashboard persetujuan Host.
- **Komponen yang Dikerjakan:**
  - Model: `app/model/bookings.model.php`
  - Controller: `app/controller/bookings.controller.php`
  - View: `app/view/bookings/my_bookings.view.php`, `host_requests.view.php`
  - Script Klien: `public/assets/js/booking.js`
- **Kriteria Selesai (*Definition of Done*):**
  - Peserta hanya bisa memesan jika mencentang persetujuan batasan (*Boundaries Agreement*).
  - Kapasitas slot berkurang secara akurat dan tidak dapat memesan penawaran sendiri.
  - Host dapat menyetujui (*Approve*) atau menolak (*Reject*) peserta yang mendaftar.
  - Link sesi privat (Google Meet / lokasi) hanya dapat diakses setelah status `CONFIRMED`.
  - Pembatalan mendadak (< 2 jam) otomatis memotong Trust Score peserta sebesar 25 poin.

---

#### 4. `feature/user-profile`
- **Tujuan:** Membangun profil reputasi publik dan pengaturan akun pribadi.
- **Komponen yang Dikerjakan:**
  - Controller: `app/controller/users.controller.php`
  - View: `app/view/users/profile.view.php`, `my_profile.view.php`
- **Kriteria Selesai (*Definition of Done*):**
  - Profil publik menampilkan nama, bio, ulasan, dan meteran Trust Score.
  - **Prinsip Privasi Terjaga:** Nomor telepon tidak pernah ditampilkan pada profil publik.
  - Pemilik akun dapat memperbarui nama, bio, dan mengganti kata sandi.

---

### C. Cabang Fitur Fase 2 & 3 (Lanjutan)

#### 5. `feature/payments` (Fase 2)
- **Tujuan:** Menangani tagihan dan konfirmasi pembayaran untuk slot berbayar.
- **Komponen yang Dikerjakan:**
  - Model: `app/model/payments.model.php`
  - Controller: `app/controller/payments.controller.php`
  - View: `app/view/payments/checkout.view.php`, `receipt.view.php`
- **Kriteria Selesai:**
  - Terbit invoice transaksi unik untuk pemesanan berbayar.
  - Konfirmasi pembayaran otomatis mengesahkan status booking menjadi `CONFIRMED`.

---

#### 6. `feature/reviews-reputation` (Fase 3)
- **Tujuan:** Menangani ulasan pengalaman pasca-sesi dan reward disiplin waktu.
- **Komponen yang Dikerjakan:**
  - Model: `app/model/reviews.model.php`
  - Controller: `app/controller/reviews.controller.php`
  - View: `app/view/reviews/review_form.view.php`
  - Script Klien: `public/assets/js/review.js`
- **Kriteria Selesai:**
  - Peserta pada sesi `COMPLETED` dapat memberikan rating 1-5 bintang dan ulasan.
  - Pengiriman ulasan otomatis menambahkan +5 poin Trust Score ke akun Host dan Participant.

---

## 4. Panduan Perintah Git (Cheat Sheet)

Berikut adalah panduan langkah-demi-langkah mengetik perintah Git di terminal saat mengerjakan fitur:

### Langkah 1: Memulai Fitur Baru dari `main`
Sebelum membuat branch baru, pastikan Anda berada di branch `main` yang paling baru:
```bash
git checkout main
git pull origin main
git checkout -b feature/listings
```

### Langkah 2: Menyimpan Progres Pekerjaan (Commit)
Lakukan commit secara bertahap saat ada progres fungsional:
```bash
git status
git add app/model/listings.model.php
git commit -m "feat: implement active listings and overlap validation query"
```

### Langkah 3: Mengunggah Branch ke GitHub / Remote
```bash
git push -u origin feature/listings
```

### Langkah 4: Menggabungkan (Merge) ke `main` Setelah Fitur Selesai & Lulus Tes
Setelah fitur selesai dan diuji coba di browser:
```bash
# 1. Pindah ke branch main
git checkout main

# 2. Tarik update terbaru (jika ada)
git pull origin main

# 3. Gabungkan branch fitur
git merge feature/listings

# 4. Unggah versi terbaru main ke remote
git push origin main

# 5. (Opsional) Hapus branch fitur lokal yang sudah selesai
git branch -d feature/listings
```

---

## 5. Standar Penamaan Pesan Commit (*Conventional Commits*)

Gunakan format standar berikut saat membuat pesan commit agar riwayat git rapi dan mudah dibaca:

| Awalan | Kapan Digunakan | Contoh Pesan Commit |
|---|---|---|
| `feat:` | Menambahkan fitur atau fungsi baru | `feat: add password toggle interaction in auth.js` |
| `fix:` | Memperbaiki bug atau kesalahan logika | `fix: resolve incorrect table name in getUserByEmail query` |
| `refactor:` | Merapikan struktur kode tanpa mengubah fungsi | `refactor: extract session check to requireAuth guard` |
| `docs:` | Membuat atau memperbarui dokumentasi/PRD | `docs: add PRD for bookings view and client scripts` |
| `style:` | Penyesuaian tampilan CSS atau format indentasi | `style: improve listing card padding and badge colors` |

