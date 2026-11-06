# PRD: payments.view.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk antarmuka tagihan dan konfirmasi pembayaran (*Checkout & Payment Receipt - Fase 2 Roadmap*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan Halaman:** 
  1. Halaman **"Tagihan / Checkout"** (`checkout.view.php`): Menampilkan rincian biaya reservasi slot berbayar, pilihan metode pembayaran (QRIS, Transfer Bank), dan nomor invoice unik.
  2. Halaman **"Bukti Pembayaran / Receipt"** (`receipt.view.php`): Bukti transaksi resmi setelah pembayaran berhasil diverifikasi.
- **Letak File Target:**
  - `app/view/payments/checkout.view.php`
  - `app/view/payments/receipt.view.php`
- **Controller Terkait:** `app/controller/payments.controller.php`

---

## 2. Struktur Elemen & Tampilan Per Halaman

### A. Halaman Checkout Pembayaran (`checkout.view.php`)

#### 1. Rincian Tagihan (Invoice Summary)
- Nomor Referensi Transaksi: `INV-<?= date('Ymd') ?>-XXXX`
- Judul Pengalaman & Nama Host.
- Jadwal Pengalaman (Hari, Tanggal, Jam).
- Jumlah Slot Dipesan: `<?= $booking['slot_count'] ?> Slot`.
- Total Tagihan: `Rp <?= number_format($payment['amount'], 0, ',', '.') ?>`.

#### 2. Formulir Pilihan Metode Pembayaran
- `<form method="POST" action="/index.php?page=confirm-payment">`
- `<input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">`
- Pilihan Metode Pembayaran (Radio Card):
  - **Opsi 1 (QRIS):** Gambar/placeholder kode QRIS statis.
  - **Opsi 2 (Transfer Bank BCA / Mandiri):** Nomor rekening virtual account dan tombol *"Salin Nomor Rekening"*.
- Instruksi Pembayaran:
  - *"Silakan selesaikan pembayaran dalam waktu 15 menit agar reservasi tidak kedaluwarsa."*
- Tombol Aksi:
  - `<button type="submit" class="btn btn-primary btn-block">Saya Sudah Bayar (Konfirmasi)</button>`

---

### B. Halaman Bukti Pembayaran (`receipt.view.php`)

#### 1. Kartu Bukti Lunas (Payment Receipt Card)
- Ikon centang hijau besar: *"Pembayaran Berhasil Dikonfirmasi!"*
- Nomor Transaksi: `<?= htmlspecialchars($payment['transaction_ref']) ?>`
- Waktu Pembayaran: `<?= htmlspecialchars($payment['paid_at']) ?>`
- Status Tagihan: Lencana hijau **LUNAS (PAID)**.
- Rincian Pengalaman & Akses:
  - Link/akses sesi pengalaman resmi kini sudah terbuka.
- Tombol Navigasi:
  - `<a href="/index.php?page=my-bookings" class="btn btn-primary">Lihat Jadwal di Pengalaman Saya</a>`

---

## 3. Checklist Implementasi
- [ ] Format mata uang selalu menggunakan standar Rupiah yang jelas.
- [ ] Status pembayaran ditampilkan secara tegas dengan badge warna (*Pending = Kuning*, *Paid = Hijau*).
- [ ] Validasi kepemilikan invoice: Pengguna hanya boleh melihat halaman checkout untuk booking miliknya sendiri.

---

## 4. Daftar Pengujian Fungsional UI (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Tampilan |
|---|---|---|---|
| TC-PAY-V01 | Tampilan Checkout | Peserta membuka checkout slot berbayar miliknya | Menampilkan rincian judul, jumlah nominal, dan opsi metode bayar |
| TC-PAY-V02 | Proteksi Akses | User lain mencoba membuka URL checkout pesanan orang lain | Muncul pesan error akses ditolak (403 Unauthorized) |
| TC-PAY-V03 | Bukti Lunas | Peserta membuka receipt setelah pembayaran sukses | Menampilkan status LUNAS dan tombol menuju jadwal pengalaman |

