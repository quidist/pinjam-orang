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
| Location | Lokasi atau media pelaksanaan |
| Approval Mode | Otomatis atau persetujuan Host |

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
| Database | PostgreSQL |
| Authentication | Spring Security |
| ORM / Data Access | Spring Data JPA |
| Containerization | Docker |
| Version Control | Git |

---

## 🔐 Important Business Rules

1. Host tidak dapat menawarkan waktu yang bertabrakan dengan penawaran aktif miliknya.
2. Participant tidak dapat memesan slot yang kapasitasnya sudah habis.
3. Setiap booking harus memiliki identitas Host, Participant, dan Listing yang valid.
4. Listing yang sudah dimulai tidak dapat menerima reservasi baru.
5. Booking berbayar tidak boleh dianggap berhasil sebelum pembayaran terkonfirmasi.
6. Host tetap memiliki kendali atas penawaran dan batas interaksinya.
7. Pengguna dapat membatalkan penawaran atau reservasi berdasarkan kebijakan yang berlaku.

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

### Phase 2 — Marketplace

- [ ] Paid listing.
- [ ] Payment gateway integration.
- [ ] Payment verification.
- [ ] Refund and cancellation policies.
- [ ] Host approval system.
- [ ] Search and filtering.

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