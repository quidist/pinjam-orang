# PRD: listing-form.js.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk skrip interaksi formulir penawaran waktu (*Create Time Listing Form UX*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola logika interaktif pada formulir pembuatan penawaran waktu oleh Host, meliputi pengalihan jenis lokasi dinamis (Online vs Offline Public Venue), penonaktifan input harga jika opsi gratis dicentang, dan penghitungan durasi waktu otomatis.
- **Letak File Target:** `public/assets/js/listing-form.js`
- **Tampilan Terkait:** `app/view/listings/create.view.php`
- **Teknologi:** Modern Vanilla JavaScript (ES6+).

---

## 2. Spesifikasi Fungsi & Event Listeners

### 2.1. `initVenueSwitcher(): void`
- **Tujuan:** Menyesuaikan label dan placeholder input lokasi sesuai jenis sesi yang dipilih.
- **Target Elemen:**
  - Radio button Online: `#radioOnline`
  - Radio button Offline: `#radioOffline`
  - Label lokasi: `#labelLocation`
  - Input lokasi: `#inputLocation`
  - Boks peringatan keselamatan: `#offlineSafetyNotice`
- **Logika Interaksi:**
  - Dengarkan event `change` pada `#radioOnline` dan `#radioOffline`.
  - **Jika `ONLINE` dipilih:**
    - Ubah teks `#labelLocation` &rarr; *"Platform / Link Pertemuan Virtual"*
    - Ubah placeholder `#inputLocation` &rarr; *"Contoh: Google Meet / Discord / Zoom"*
    - Sembunyikan `#offlineSafetyNotice` (`display: none`).
  - **Jika `OFFLINE` dipilih:**
    - Ubah teks `#labelLocation` &rarr; *"Nama Ruang Publik Terbuka & Ramai"*
    - Ubah placeholder `#inputLocation` &rarr; *"Contoh: Starbucks Mall Grand Indonesia / Perpustakaan Cikini"*
    - Tampilkan `#offlineSafetyNotice` (Peringatan tegas bahwa sesi tatap muka dilarang di tempat privat/hotel/kendaraan).

### 2.2. `initFreePriceToggle(): void`
- **Tujuan:** Memudahkan Host menawarkan sesi gratis (*Free Participation*).
- **Target Elemen:** Checkbox `#chkFreeListing` dan input angka `#inputPrice`.
- **Logika Interaksi:**
  - Dengarkan event `change` pada `#chkFreeListing`.
  - Jika dicentang (`checked === true`):
    - Set `inputPrice.value = '0'`.
    - Nonaktifkan input: `inputPrice.readOnly = true` (atau tambahkan class `.is-disabled`).
  - Jika tidak dicentang:
    - Kosongkan atau kembalikan nilai harga normal.
    - Buka kunci: `inputPrice.readOnly = false`.

### 2.3. `initDurationCalculator(): void`
- **Tujuan:** Menghitung total durasi dalam menit secara otomatis saat Host memilih jam mulai dan jam selesai.
- **Target Elemen:** `#inputStartTime`, `#inputEndTime`, dan `#inputDuration`.
- **Logika Interaksi:**
  - Dengarkan event `change` pada kedua input waktu.
  - Jika kedua jam sudah terisi:
    - Hitung selisih waktu:
      ```javascript
      const start = new Date(`1970-01-01T${inputStartTime.value}:00`);
      const end = new Date(`1970-01-01T${inputEndTime.value}:00`);
      const diffMinutes = (end - start) / (1000 * 60);
      ```
    - Jika `diffMinutes <= 0`: Beri peringatan visual (Jam selesai tidak boleh sebelum jam mulai) dan set durasi = 0.
    - Jika `diffMinutes > 0`: Isi nilai otomatis ke `inputDuration.value = diffMinutes`.

---

## 3. Checklist Implementasi
- [ ] Script hanya diaktifkan jika elemen `#formCreateListing` ada di halaman.
- [ ] Penanganan kasus jam selesai melewati tengah malam (*cross-midnight handling* jika relevan).
- [ ] Memastikan nilai `price` yang terkirim ke backend adalah `0` jika opsi gratis aktif.

---

## 4. Daftar Pengujian Fungsional JS (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-JS-LF01 | Venue Switcher | Klik radio button `OFFLINE` | Label berubah jadi nama ruang publik, kotak peringatan keselamatan muncul |
| TC-JS-LF02 | Venue Switcher | Klik kembali radio button `ONLINE` | Label kembali ke platform virtual, peringatan keselamatan tersembunyi |
| TC-JS-LF03 | Free Toggle | Centang checkbox "Jadikan Penawaran Gratis" | Nilai input harga jadi 0 dan terkunci (readonly) |
| TC-JS-LF04 | Free Toggle | Hapus centang checkbox gratis | Input harga bisa diketik kembali |
| TC-JS-LF05 | Durasi Auto | Pilih jam mulai 14:00 dan jam selesai 15:30 | Input durasi otomatis terisi angka 90 (menit) |
| TC-JS-LF06 | Validasi Jam | Pilih jam mulai 15:00 dan jam selesai 14:00 | Muncul pesan error visual jam selesai tidak valid |

