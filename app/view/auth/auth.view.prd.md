# PRD: auth.view.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk antarmuka formulir autentikasi pengguna (*Login & Registration Views*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan Halaman:** Menyediakan tampilan formulir bagi pengguna untuk mendaftar akun baru (*Register*) dan masuk ke sistem (*Login*), lengkap dengan validasi visual pesan kesalahan dan elemen interaktif intip kata sandi.
- **Letak File Target:**
  - `app/view/auth/login.view.php`
  - `app/view/auth/register.view.php`
- **Controller Terkait:** `app/controller/auth.controller.php`
- **Script JS Terkait:** `public/assets/js/auth.js`

---

## 2. Struktur Elemen & Form Spesifikasi

### A. Tampilan Masuk (`login.view.php`)

#### 1. Form Attributes
- `<form id="formLogin" method="POST" action="/index.php?page=login" novalidate>`

#### 2. Input Fields
- **Email:**
  - Label: *"Alamat Email"*
  - Tag: `<input type="email" id="loginEmail" name="email" value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>" placeholder="nama@email.com" required>`
- **Kata Sandi:**
  - Label: *"Kata Sandi"*
  - Pembungkus input kata sandi: `<div class="password-wrapper">`
  - Tag: `<input type="password" id="loginPassword" name="password" placeholder="Masukkan kata sandi" required>`
  - Tombol intip sandi: `<button type="button" class="btn-toggle-password" data-target="loginPassword">👁️</button>`

#### 3. Error Banner (Jika Login Gagal)
- Ditampilkan jika Controller mengirimkan variabel `$error`:
  ```php
  <?php if (!empty($error)): ?>
      <div class="alert alert-danger" role="alert">
          <?= htmlspecialchars($error) ?>
      </div>
  <?php endif; ?>
  ```

#### 4. Tombol Aksi & Navigasi
- Tombol Submit: `<button type="submit" id="btnSubmitLogin" class="btn btn-primary">Masuk ke Akun</button>`
- Link bantuan: *"Belum punya akun? [Daftar Sekarang](/index.php?page=register)"*

---

### B. Tampilan Pendaftaran (`register.view.php`)

#### 1. Form Attributes
- `<form id="formRegister" method="POST" action="/index.php?page=register" novalidate>`

#### 2. Input Fields
- **Nama Lengkap:**
  - Tag: `<input type="text" id="regName" name="name" value="<?= htmlspecialchars($oldInput['name'] ?? '') ?>" placeholder="Nama lengkap Anda" minlength="3" maxlength="100" required>`
- **Alamat Email:**
  - Tag: `<input type="email" id="regEmail" name="email" value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>" placeholder="contoh@domain.com" required>`
- **Kata Sandi Baru:**
  - Tag: `<input type="password" id="regPassword" name="password" placeholder="Minimal 6 karakter" minlength="6" required>`
  - Tombol intip: `<button type="button" class="btn-toggle-password" data-target="regPassword">👁️</button>`
  - Indikator kekuatan sandi: `<div id="passwordStrengthMeter" class="strength-meter"></div>`
- **Konfirmasi Kata Sandi:**
  - Tag: `<input type="password" id="regPasswordConfirm" name="password_confirm" placeholder="Ulangi kata sandi" required>`
  - Wadah pesan kecocokan real-time: `<small id="passwordMatchMsg" class="form-hint"></small>`

#### 3. Tampilan Ringkasan Error Validasi
- Menampilkan daftar kesalahan jika Controller mengembalikan array `$errors`:
  ```php
  <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
          <ul>
              <?php foreach ($errors as $err): ?>
                  <li><?= htmlspecialchars($err) ?></li>
              <?php endforeach; ?>
          </ul>
      </div>
  <?php endif; ?>
  ```

#### 4. Tombol Aksi & Navigasi
- Tombol Submit: `<button type="submit" id="btnSubmitRegister" class="btn btn-primary">Daftar Akun Baru</button>`
- Link navigasi: *"Sudah punya akun? [Masuk di sini](/index.php?page=login)"*

---

## 3. Checklist Implementasi
- [ ] Atribut form `method="POST"` dan penanganan `oldInput` agar data tidak hilang saat validasi gagal.
- [ ] Atribut form `novalidate` disertakan agar validasi browser native tidak menabrak validasi kustom JS.
- [ ] Tombol toggle password memiliki atribut `data-target` yang mengarah ke ID input password terkait.
- [ ] Wadah pesan error di-escape menggunakan `htmlspecialchars()`.

---

## 4. Daftar Pengujian Fungsional UI (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Tampilan |
|---|---|---|---|
| TC-ATH-V01 | Form Login | Buka `/index.php?page=login` | Input email dan password kosong, tombol submit aktif |
| TC-ATH-V02 | Error State | Controller mengembalikan pesan error login | Kotak merah error muncul di atas form login |
| TC-ATH-V03 | Retensi Input | Pendaftaran gagal validasi | Input `name` dan `email` tetap terisi data lama (`oldInput`), kolom password kosong |
| TC-ATH-V04 | Error List | Controller mengembalikan 2 error (email duplikat & password kurang panjang) | Daftar peluru (`<ul><li>`) menampilkan kedua pesan error |
| TC-ATH-V05 | Navigasi | Mengklik link "Daftar Sekarang" pada form login | Browser berpindah ke halaman register |

