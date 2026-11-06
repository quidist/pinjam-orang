# PRD: bookings.view.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk antarmuka pengelolaan reservasi waktu (*My Bookings & Host Request Dashboard*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan Halaman:** 
  1. Halaman **"Pengalaman Saya"** (`my_bookings.view.php`): Menampilkan daftar sesi waktu yang dipesan oleh pengguna (Participant), lengkap dengan status reservasi, link akses sesi, dan opsi pembatalan.
  2. Halaman **"Permintaan Peserta"** (`host_requests.view.php`): Dashboard bagi Host untuk meninjau, menyetujui (*Approve*), atau menolak (*Reject*) peserta yang mendaftar pada penawarannya.
- **Letak File Target:**
  - `app/view/bookings/my_bookings.view.php`
  - `app/view/bookings/host_requests.view.php`
- **Controller Terkait:** `app/controller/bookings.controller.php`
- **Script JS Terkait:** `public/assets/js/booking.js`

---

## 2. Struktur Elemen & Tampilan Per Halaman

### A. Pengalaman Saya - Sisi Peserta (`my_bookings.view.php`)

#### 1. Tab Navigasi Status
- Filter cepat: `Semua`, `Mendatang / Aktif`, `Selesai`, `Dibatalkan`.

#### 2. Kartu Reservasi Peserta (`.booking-card`)
- **Judul Pengalaman:** Link ke detail penawaran.
- **Nama Host:** Nama dan foto profil Host.
- **Waktu Pelaksanaan:** Hari, Tanggal, Jam mulai - selesai.
- **Badge Status Booking:**
  - `PENDING` (Kuning): *"Menunggu Persetujuan Host / Menunggu Pembayaran"*.
  - `CONFIRMED` (Hijau): *"Terkonfirmasi - Siap Hadir"*.
  - `REJECTED` (Merah): *"Permintaan Ditolak oleh Host"*.
  - `CANCELLED` (Abu-abu): *"Dibatalkan"*.
  - `COMPLETED` (Biru): *"Selesai"*.
- **Informasi Akses Sesi (Hanya Tampil Jika Status `CONFIRMED`):**
  - Kotak Informasi Privat:
    - Jika Online: Tombol `<a href="<?= htmlspecialchars($b['location']) ?>" target="_blank" class="btn btn-success">Buka Link Pertemuan (Meet/Zoom)</a>`.
    - Jika Offline: Alamat ruang publik & petunjuk pertemuan.
- **Tombol Aksi:**
  - Jika Status `PENDING` atau `CONFIRMED`:
    - Tombol Batal: `<button type="button" class="btn btn-danger btn-cancel-booking" data-id="<?= $b['id'] ?>">Batalkan Reservasi</button>`.
  - Jika Status `COMPLETED`:
    - Tombol Beri Review: `<a href="/index.php?page=review&booking_id=<?= $b['id'] ?>" class="btn btn-primary">Beri Ulasan & Bintang</a>`.

#### 3. Modal Dialog Konfirmasi Batal (`#modalCancelBooking`)
- Dialog pop-up konfirmasi pembatalan.
- Peringatan anti-ghosting: *"Pembatalan mendadak kurang dari 2 jam sebelum sesi akan mengurangi Trust Score Anda sebesar 25 poin."*
- Tombol: *"Batal"* dan *"Ya, Tetap Batalkan"*.

---

### B. Dashboard Permintaan Masuk - Sisi Host (`host_requests.view.php`)

#### 1. Ringkasan Listing
- Menampilkan judul penawaran yang sedang ditinjau, jadwal, dan indikator kapasitas:
  `Kapasitas Terisi: X / Y Peserta`.

#### 2. Daftar Calon Peserta Masuk (`.participant-row`)
Setiap peserta yang mendaftar menampilkan:
- **Foto & Nama Calon Peserta:** Terhubung ke profil publik peserta.
- **Reputasi Peserta:** Badge Trust Score (misal: `⭐ 95 Poin (Aman / Disiplin)`).
- **Catatan Peserta:** Teks pesan yang ditulis peserta saat booking.
- **Tanggal Pengajuan:** Tanggal dan jam peserta mengirim booking.
- **Tombol Aksi Persetujuan (Jika Status `PENDING`):**
  - Form Approve:
    ```html
    <form method="POST" action="/index.php?page=approve-booking" style="display:inline;">
        <input type="hidden" name="booking_id" value="<?= $req['id'] ?>">
        <button type="submit" class="btn btn-success btn-sm">Setujui (Approve)</button>
    </form>
    ```
  - Form Reject:
    ```html
    <form method="POST" action="/index.php?page=reject-booking" style="display:inline;">
        <input type="hidden" name="booking_id" value="<?= $req['id'] ?>">
        <button type="submit" class="btn btn-outline-danger btn-sm">Tolak</button>
    </form>
    ```

---

## 3. Checklist Implementasi
- [ ] Informasi lokasi privat / link meeting **tidak pernah ditampilkan** kecuali status booking sudah bernilai `CONFIRMED`.
- [ ] Tombol pembatalan memicu modal konfirmasi JS `#modalCancelBooking` dengan pesan peringatan penalti Trust Score.
- [ ] Badge status menggunakan warna yang jelas dan membedakan tahapan reservasi secara tegas.
- [ ] Tombol submit form approve/reject memiliki pencegahan double click.

---

## 4. Daftar Pengujian Fungsional UI (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Tampilan |
|---|---|---|---|
| TC-BK-V01 | Tampilan Reservasi | Peserta membuka riwayat booking status CONFIRMED | Link sesi meeting / lokasi publik muncul dan bisa diklik |
| TC-BK-V02 | Proteksi Privasi | Peserta membuka riwayat booking status PENDING | Link sesi meeting tersembunyi, hanya muncul teks "Menunggu konfirmasi" |
| TC-BK-V03 | Modal Pembatalan | Klik tombol "Batalkan Reservasi" | Modal peringatan penalti skor 25 poin muncul ke layar |
| TC-BK-V04 | Host Approval | Host melihat permintaan masuk status PENDING | Muncul tombol hijau "Setujui" dan tombol merah "Tolak" |
| TC-BK-V05 | Empty State | Pengguna belum pernah memesan penawaran apa pun | Tampil pesan "Anda belum memiliki jadwal pengalaman. [Jelajahi Katalog]" |

