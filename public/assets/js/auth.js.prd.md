# PRD: auth.js.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk skrip interaksi formulir autentikasi pengguna (*Login & Registration Client-Side UX*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Meningkatkan pengalaman pengguna pada formulir masuk dan pendaftaran, seperti toggle intip kata sandi (show/hide), pengecekan kecocokan konfirmasi kata sandi secara instan (*real-time*), indikator kekuatan kata sandi, dan pencegahan klik ganda (*anti-double submit*).
- **Letak File Target:** `public/assets/js/auth.js`
- **Tampilan Terkait:** `app/view/auth/login.view.php` dan `app/view/auth/register.view.php`
- **Teknologi:** Modern Vanilla JavaScript (ES6+).

---

## 2. Spesifikasi Fungsi & Event Listeners

### 2.1. `initPasswordToggle(): void`
- **Tujuan:** Mengubah visibilitas karakter kata sandi antara bintang-bintang dan teks biasa saat tombol ikon mata diklik.
- **Target Elemen:** Semua tombol dengan class `.btn-toggle-password`.
- **Logika Interaksi:**
  - Baca atribut `data-target` pada tombol (berisi ID elemen input password, misal `loginPassword` atau `regPassword`).
  - Ambil elemen input target: `const input = document.getElementById(targetId)`.
  - Jika tipe input saat ini `"password"`, ubah menjadi `"text"` dan ganti ikon menjadi `🙈`.
  - Jika tipe input saat ini `"text"`, kembalikan menjadi `"password"` dan ganti ikon menjadi `👁️`.

### 2.2. `initLivePasswordMatch(): void`
- **Tujuan:** Memberikan feedback visual instan saat user mengetik di kolom konfirmasi password, tanpa harus menunggu submit form.
- **Target Elemen:** 
  - Input password utama: `#regPassword`
  - Input konfirmasi password: `#regPasswordConfirm`
  - Wadah pesan status: `#passwordMatchMsg`
- **Logika Interaksi:**
  - Dengarkan event `input` pada `#regPasswordConfirm` dan `#regPassword`.
  - Jika input konfirmasi masih kosong: kosongkan `#passwordMatchMsg`.
  - Jika `regPasswordConfirm.value !== regPassword.value`:
    - Tampilkan teks merah: *"Kata sandi tidak cocok"*.
    - Tambahkan class `.is-invalid` pada input konfirmasi.
  - Jika `regPasswordConfirm.value === regPassword.value`:
    - Tampilkan teks hijau: *"Kata sandi cocok ✓"*.
    - Ganti class menjadi `.is-valid`.

### 2.3. `initPasswordStrengthMeter(): void`
- **Tujuan:** Menampilkan bar meteran kekuatan kata sandi saat user mengetik password baru.
- **Target Elemen:** `#regPassword` dan kontainer meteran `#passwordStrengthMeter`.
- **Kriteria Evaluasi:**
  - Lemah: < 6 karakter (bar merah, label: *"Terlalu Pendek"*).
  - Sedang: &ge; 6 karakter berupa huruf saja (bar kuning, label: *"Sedang"*).
  - Kuat: &ge; 8 karakter kombinasi huruf dan angka/simbol (bar hijau, label: *"Kuat"*).

### 2.4. `initAntiDoubleSubmit(): void`
- **Tujuan:** Mencegah pengiriman form berulang kali akibat klik cepat yang tidak sengaja.
- **Target Elemen:** `#formLogin` dan `#formRegister`.
- **Logika Interaksi:**
  - Dengarkan event `submit` pada formulir.
  - Ambil tombol submit di dalam form tersebut (`button[type="submit"]`).
  - Nonaktifkan tombol: `btn.disabled = true`.
  - Ubah teks tombol menjadi: `btn.innerText = 'Memproses...'`.

---

## 3. Checklist Implementasi
- [ ] Pemeriksaan ketersediaan form sebelum mengeksekusi listener (agar file `auth.js` bisa dipakai di login maupun register tanpa melempar error).
- [ ] Validasi HTML5 tetap dihormati jika form valid.
- [ ] Menjaga keamanan: Script tidak menyimpan atau me-log nilai password ke console browser.

---

## 4. Daftar Pengujian Fungsional JS (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-JS-AU01 | Toggle Password | Klik ikon mata pada input password | Karakter password terlihat (tipe berubah jadi `text`), ikon berubah |
| TC-JS-AU02 | Toggle Password | Klik ikon mata kedua kali | Karakter kembali tersembunyi (tipe kembali `password`) |
| TC-JS-AU03 | Match Password | Ketik "rahasia1" di password dan "rahasia2" di konfirmasi | Muncul pesan merah "Kata sandi tidak cocok" |
| TC-JS-AU04 | Match Password | Perbaiki konfirmasi menjadi "rahasia1" | Pesan berubah hijau "Kata sandi cocok ✓" |
| TC-JS-AU05 | Anti Double Click | Klik tombol submit form login | Tombol langsung disabled dan teks berubah jadi "Memproses..." |

