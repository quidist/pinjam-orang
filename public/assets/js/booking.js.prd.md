# PRD: booking.js.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk skrip interaksi pemesanan slot waktu (*Booking & Reservation Client-Side UX*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola interaksi saat peserta melakukan reservasi waktu, meliputi kalkulasi total biaya slot secara *real-time*, penguncian tombol pesan sampai batasan interaksi disetujui, dan dialog modal konfirmasi pembatalan.
- **Letak File Target:** `public/assets/js/booking.js`
- **Tampilan Terkait:** `app/view/listings/detail.view.php` dan `app/view/bookings/my_bookings.view.php`
- **Teknologi:** Modern Vanilla JavaScript (ES6+).

---

## 2. Spesifikasi Fungsi & Event Listeners

### 2.1. `initBoundariesAgreementGuard(): void`
- **Tujuan:** Menjamin kepatuhan bisnis: Peserta tidak boleh mengirim formulir pemesanan jika belum menyetujui batasan interaksi (*Boundaries Agreement*).
- **Target Elemen:** 
  - Checkbox persetujuan: `#chkAgreedBoundaries`
  - Tombol submit pesanan: `#btnBookNow`
- **Logika Interaksi:**
  - Pada saat halaman pertama kali dimuat, set tombol pesanan: `btnBookNow.disabled = true`.
  - Dengarkan event `change` pada `#chkAgreedBoundaries`.
  - Jika dicentang (`checked === true`) &rarr; Aktifkan tombol: `btnBookNow.disabled = false`.
  - Jika centang dicabut &rarr; Kunci kembali: `btnBookNow.disabled = true`.

### 2.2. `initLivePriceCalculator(): void`
- **Tujuan:** Mengalikan harga satuan dengan jumlah slot yang dipilih secara *real-time*.
- **Target Elemen:**
  - Input kuantitas slot: `#inputSlotCount`
  - Atribut harga satuan pada elemen: `data-unit-price="15000"`
  - Teks tampilan total harga: `#displayTotalPrice`
- **Logika Interaksi:**
  - Dengarkan event `input` dan `change` pada `#inputSlotCount`.
  - Hitung: `const total = parseInt(inputSlotCount.value || 1) * unitPrice`.
  - Format angka ke format Rupiah menggunakan `Intl.NumberFormat('id-ID')`.
  - Update teks `#displayTotalPrice` dengan nominal baru.

### 2.3. `initCancelBookingModal(): void`
- **Tujuan:** Menampilkan modal dialog peringatan penalti Trust Score sebelum peserta membatalkan reservasi aktif.
- **Target Elemen:** 
  - Tombol-tombol batal: `.btn-cancel-booking`
  - Modal: `#modalCancelBooking`
  - Input tersembunyi ID booking di modal: `#modalCancelBookingId`
- **Logika Interaksi:**
  - Dengarkan event `click` pada semua tombol `.btn-cancel-booking`.
  - Ambil atribut `data-id` dari tombol yang diklik.
  - Masukkan ID ke dalam `#modalCancelBookingId`.
  - Tampilkan modal ke layar (tambahkan class `.is-visible` atau `display: block`).
  - Dengarkan tombol batal modal (`.btn-close-modal`) untuk menutup kembali.

---

## 3. Checklist Implementasi
- [ ] Tombol submit booking `#btnBookNow` dalam kondisi terkunci secara default sampai user mencentang persetujuan boundaries.
- [ ] Format mata uang menggunakan lokal Indonesia `id-ID`.
- [ ] Modal dialog memiliki penutup saat user menekan tombol `Esc` pada keyboard atau mengklik di luar area modal (*backdrop click*).

---

## 4. Daftar Pengujian Fungsional JS (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-JS-BK01 | Boundaries Guard | Buka halaman detail penawaran | Tombol "Pesan Slot Waktu" dalam kondisi terkunci (disabled) |
| TC-JS-BK02 | Boundaries Guard | Centang checkbox persetujuan boundaries | Tombol "Pesan Slot Waktu" langsung terbuka (enabled) |
| TC-JS-BK03 | Live Calculator | Listing harga Rp 15.000, ubah jumlah slot dari 1 ke 3 | Tampilan total harga langsung berubah menjadi "Rp 45.000" |
| TC-JS-BK04 | Cancel Modal | Klik tombol "Batalkan Reservasi" pada daftar booking | Modal pop-up muncul dan menampilkan peringatan penalti Trust Score |
| TC-JS-BK05 | Modal Dismiss | Tekan tombol Escape saat modal terbuka | Modal tertutup kembali |

