# 🎨 DESIGN SYSTEM & FIGMA AI PROMPT SPECIFICATION
## Project: PINJAMORANG ("Borrow a human. Return with a story.")
**Tech Stack Target:** HTML5, CSS3 (Modern Vanilla / Utility CSS), Vanilla JavaScript, PHP 8+ MVC  
**Purpose:** Ready-to-copy Master Design Document & Structured Prompts for AI Figma / Galileo AI / v0 / Claude UI Designers.

---

## 1. BRAND IDENTITY & DESIGN PHILOSOPHY

- **Product Essence:** A warm, trustworthy, and privacy-first time-sharing social marketplace where people offer their spare time for meaningful conversations, peer mentoring, study buddies, or public activities.
- **Design Mood & Vibe:** 
  - **Warm & Empathetic:** Inviting, human-centric, avoiding sterile or overly transactional corporate feelings.
  - **High Trust & Safety:** Clear verification badges, prominent Trust/Karma gauges, transparent boundary agreements.
  - **Clean & Content-First:** Scannable cards, high contrast, readable typography, zero clutter.
- **Key Metaphor:** "Lending a book from a library, but the book is a human with stories."

---

## 2. DESIGN TOKENS & SYSTEM FOUNDATIONS

### A. Color Palette

| Token Name | Hex Code | Usage & Context |
|---|---|---|
| **Primary (Warm Terracotta/Coral)** | `#E05D44` | Main CTA buttons, active highlights, key brand accents |
| **Primary Hover / Dark** | `#C74730` | Hover & active states for primary buttons |
| **Primary Light / Soft** | `#FDF2E9` | Subtle button backgrounds, tag backgrounds, active card highlights |
| **Secondary (Deep Slate / Navy)** | `#1E293B` | High-contrast headers, dark surfaces, dominant text color |
| **Accent / Trust Teal** | `#0D9488` | Trust badges, verification marks, high karma indicators |
| **Background Light (Canvas)** | `#F8FAFC` | Page body background (clean off-white slate) |
| **Surface White (Cards)** | `#FFFFFF` | Card containers, modal windows, form wrappers |
| **Border / Divider** | `#E2E8F0` | Subtle lines, card borders, form input borders |
| **Text Primary** | `#0F172A` | Main headings, titles, active labels |
| **Text Secondary / Muted** | `#64748B` | Subtitles, helper text, timestamps, captions |

#### Functional / Status Colors:
| State | Background | Text / Border | Purpose |
|---|---|---|---|
| **Success / Free / Confirmed** | `#ECFDF5` | `#059669` | `CONFIRMED` status, "GRATIS" badge, success alerts |
| **Warning / Pending** | `#FFFBEB` | `#D97706` | `PENDING` status, review prompts, medium trust warnings |
| **Danger / Rejected / Low Score** | `#FEF2F2` | `#DC2626` | `REJECTED` status, cancellation penalty warnings, error alerts |
| **Info / Online Session** | `#EFF6FF` | `#2563EB` | `ONLINE` session badge, virtual links |
| **Offline Sesi (Meetup)** | `#F5F3FF` | `#7C3AED` | `OFFLINE` public meetup badge |

---

### B. Typography Scale

- **Font Family:** `Inter`, `Plus Jakarta Sans`, or `system-ui, -apple-system, sans-serif` (clean, rounded humanist grotesk).
- **Scale:**
  - **Display (Hero Headline):** `36px` / `Line Height 1.2` / Bold (`700`)
  - **H1 (Page Title):** `28px` / `Line Height 1.3` / SemiBold (`600`)
  - **H2 (Section Header):** `22px` / `Line Height 1.35` / SemiBold (`600`)
  - **H3 (Card Title / Subheader):** `18px` / `Line Height 1.4` / Medium (`500`)
  - **Body Text:** `15px` / `Line Height 1.5` / Regular (`400`)
  - **Body Small / Hints:** `13px` / `Line Height 1.45` / Regular (`400`)
  - **Overline / Badge / Micro:** `11px` - `12px` / Bold (`700`) / Uppercase (`tracking: 0.05em`)

---

### C. Spacing, Elevation & Shapes

- **8pt Grid Spacing:** `4px` (xs), `8px` (sm), `16px` (md), `24px` (lg), `32px` (xl), `48px` (2xl), `64px` (3xl).
- **Border Radius:**
  - Badges / Pills: `9999px` (Full pill)
  - Inputs / Buttons: `8px`
  - Cards / Containers: `12px` - `16px`
  - Modals / Popups: `16px` - `20px`
- **Box Shadows (Elevation):**
  - **Elevation 1 (Cards):** `0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03)`
  - **Elevation 2 (Card Hover):** `0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.03)`
  - **Elevation 3 (Modals / Sticky Nav):** `0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)`

---

## 3. CORE UI COMPONENTS SPECIFICATION

### 1. Global Navigation Bar (`navbar`)
- **Desktop (Auto Layout Row, Space Between, Height 72px):**
  - Left: Logo text **"PINJAMORANG"** in `#E05D44` (bold) + Tagline *"Borrow a human. Return with a story."* in `#64748B` (`12px`).
  - Center: Nav link *"Jelajahi Katalog"* with active underline indicator.
  - Right:
    - **Guest State:** Button Outline *"Masuk"* + Button Primary *"Daftar"*.
    - **Logged In State:** Button Accent *"+ Tawarkan Waktu"* + Link *"Pengalaman Saya"* + Avatar Dropdown (User Name, Trust Badge ⭐, *"Profil Saya"*, *"Keluar"*).
- **Mobile Drawer:** Hamburger icon button expanding a sleek slide-in sheet from right.

### 2. Trust Score Gauge / Karma Badge (`.trust-badge`)
- Visual pill badge containing a star icon + numerical score:
  - **Score ≥ 90 (High Trust):** Green background `#ECFDF5`, text `#059669`, label: *"⭐ 100 • Sangat Terpercaya"*.
  - **Score 70 - 89 (Standard):** Amber background `#FFFBEB`, text `#D97706`, label: *"⭐ 85 • Standar"*.
  - **Score < 70 (Low / Frozen):** Red background `#FEF2F2`, text `#DC2626`, label: *"⚠️ 65 • Akun Dibatasi"*.

### 3. Listing Card (`.listing-card`)
- Layout: Vertical card with padding `20px`, border `1px solid #E2E8F0`, rounded `16px`.
- Header:
  - Badge Session Type (`ONLINE` in blue or `OFFLINE` in purple).
  - Price Badge (Green pill `"GRATIS"` if price is 0, or bold `"Rp 50.000"` if paid).
- Host Info Section:
  - Circular Avatar (`40px` diameter) with fall-back initial.
  - Host Name + Verification Checkmark Badge + Trust Score small pill.
- Body:
  - Title (`18px`, 2-line max ellipsis, semi-bold).
  - Date & Time row with subtle calendar/clock icons (`"Sabtu, 14 Nov • 14:00 - 15:30 (90 min)"`).
  - Capacity indicator: *"Sisa 2 dari 5 slot"*.
- Footer:
  - Full-width button: `"Lihat Detail"` (Secondary outline).

### 4. Interaction Boundaries Box (`.boundaries-box`)
- Essential trust component on the Listing Detail screen:
  - Container with amber/shield accent border (`#FDE68A`), light background (`#FFFDF7`), padding `20px`.
  - Icon: Shield with checkmark.
  - Title: *"Aturan & Batasan Interaksi (Boundaries Agreement)"*.
  - Checklist bullets:
    - [x] Ruang publik netral & ramai (untuk sesi offline).
    - [x] Menghormati privasi, dilarang menanyakan hal pribadi di luar persetujuan.
    - [x] Tanpa kontak fisik yang tidak diinginkan.
  - Mandatory confirmation checkbox: `[ ] "Saya telah membaca dan menyetujui seluruh batasan interaksi di atas."`

### 5. Interactive Star Rating Widget (`.star-rating`)
- 5 large star icons (`32px` size), default gold color `#F59E0B` when active, `#CBD5E1` when inactive.
- Dynamic subtitle underneath: *"5 Bintang - Pengalaman Luar Biasa!"*.
- Anti-ghosting reward alert card: *"🎉 Terima kasih telah hadir tepat waktu! Ulasan ini menambahkan +5 Trust Score untuk Anda dan Host."*

---

## 4. SCREEN-BY-SCREEN DESIGN SPECIFICATIONS

### Screen 1: Human Catalog (`catalog.view.php`)
- **Hero / Header:**
  - Headline: *"Jelajahi Waktu Manusia"*.
  - Subtitle: *"Temukan teman ngobrol, diskusi santai, atau rekan belajar baru di sekitarmu."*
- **Search & Filter Bar (Sticky Card on Top):**
  - Search input with magnifying glass icon: *"Cari pengalaman (misal: curhat, temani ngopi)..."*.
  - Filter Session Type dropdown: `Semua`, `ONLINE (Virtual)`, `OFFLINE (Public Meetup)`.
  - Filter Checkbox: `[ ] Hanya yang Gratis`.
  - Primary button: `"Terapkan Filter"`.
- **Card Grid:** 3 columns on desktop, 2 on tablet, 1 on mobile.
- **Empty State:** Friendly illustration of an empty bench + *"Belum ada penawaran waktu yang cocok dengan pencarian Anda."* + Reset button.

### Screen 2: Listing Detail (`detail.view.php`)
- **2-Column Desktop Layout (65% Content / 35% Sticky Booking Card):**
  - **Left Column (Experience Details):**
    - Badges: Session type + Date & Duration.
    - Title (`28px` bold) + Full descriptive narrative.
    - Host Profile Card: Large avatar (`64px`), Host Name, Trust Score gauge, Joined date, Bio paragraph.
    - Interaction Boundaries Box with clear bulleted safety rules.
  - **Right Column (Sticky Booking Widget):**
    - Price callout (Large bold text).
    - Remaining slots indicator bar (e.g. 3 of 5 filled).
    - Mandatory Boundaries agreement checkbox.
    - Optional notes textarea: *"Tulis catatan untuk Host..."*.
    - CTA Button: Primary Coral `"Pesan Slot Waktu"` (Disabled if capacity full with text *"Kapasitas Penuh"*).

### Screen 3: Create Listing Form (`create.view.php`)
- **Single Centered Form Card (Max width 720px):**
  - Header: *"Buat Penawaran Waktu Baru"*.
  - Input: Title & Detailed Description.
  - Radio Card Group: Session Type (`ONLINE` vs `OFFLINE`).
    - If OFFLINE selected: dynamic location input displays *"Nama Tempat Publik Ramai (Kafe / Perpus / Coworking Space)"*.
    - If ONLINE selected: displays helper *"Link Google Meet / Zoom akan dibagikan privat setelah booking disetujui"*.
  - Schedule Grid: Date Picker (`listing_date`), Start Time (`start_time`), End Time (`end_time`).
  - Auto-calculated Duration badge (e.g. *"90 Menit"*).
  - Capacity & Pricing (Checkbox *"Tawarkan Gratis / Free"* auto-sets price to 0).
  - Boundaries Checklist Editor.
  - CTA Button: `"Terbitkan Penawaran Waktu"`.

### Screen 4: My Bookings - Participant Dashboard (`my_bookings.view.php`)
- **Tab Navigation:** `Semua` | `Mendatang / Aktif` | `Selesai` | `Dibatalkan`.
- **Booking List Cards:**
  - Status Badge with color coding (`PENDING` Amber, `CONFIRMED` Green, `COMPLETED` Blue).
  - Title & Host info.
  - **Privacy Box (Only visible if CONFIRMED):**
    - Green button: `"Buka Link Pertemuan (Google Meet)"` or public venue directions.
  - Action buttons:
    - If PENDING/CONFIRMED: Danger outline `"Batalkan Reservasi"`.
    - If COMPLETED: Primary `"Beri Ulasan & Bintang"`.
- **Modal Dialog (`#modalCancelBooking`):**
  - Caution shield icon.
  - Warning copy: *"Perhatian: Pembatalan kurang dari 2 jam sebelum sesi akan mengurangi Trust Score Anda sebesar 25 poin demi mencegah ghosting."*
  - Buttons: `"Kembali"` (Neutral) & `"Ya, Tetap Batalkan"` (Red).

### Screen 5: Host Requests Dashboard (`host_requests.view.php`)
- **Listing Summary Bar:** Title + Capacity counter (`Terisi: 2 / 5 Peserta`).
- **Participant Rows / Cards:**
  - Participant Avatar + Full Name.
  - Participant Trust Score Badge (e.g. `⭐ 98 • Peserta Sangat Disiplin`).
  - Notes snippet written by the participant.
  - Actions:
    - Button Green `"Setujui (Approve)"`.
    - Button Red Outline `"Tolak"`.

### Screen 6: Public Profile & Reputation (`profile.view.php`)
- **Hero Profile Card:**
  - Large Avatar (`80px`), Full Name, Verified blue checkmark, Member since timestamp.
  - Trust & Karma Score Gauge: Large circular or pill meter (`⭐ 100 Poin`).
  - Privacy Notice: *"Nomor kontak disembunyikan demi keamanan & privasi."*
- **Bio Section:** Long text about hobbies, interests, and conversational topics.
- **Reviews & Ratings Section:**
  - Summary: `⭐ 4.9 dari 5 (18 Ulasan)`.
  - Cards of testimonials from previous participants/hosts with star ratings and comments.

### Screen 7: Auth Screens (`login.view.php` & `register.view.php`)
- **Clean Centered Auth Card (Max width 440px):**
  - Brand Logo & welcome title.
  - Email input + Password input with eye toggle button (`👁️`).
  - Register additions: Real-time password strength meter bar + match hint.
  - Error alert banners (Soft red box with clear bullet list).
  - Submit button (Full width) + Link switcher between Login & Register.

---

## 5. READY-TO-PASTE FIGMA AI / UI PROMPTS

Gunakan teks prompt di bawah ini langsung pada fitur AI Prompt Figma (seperti Figma AI, Relume, Galileo AI, v0, atau Claude Artifacts):

### Prompt 1: Main Design System & UI Kit
```text
Design a modern, clean, and warm Web UI Kit for a social time-sharing platform named "PINJAMORANG" (tagline: "Borrow a human. Return with a story"). 
Style: Humanist, warm, trustworthy, minimalist.
Color tokens:
- Primary: #E05D44 (Warm Terracotta / Coral)
- Secondary: #1E293B (Deep Slate Navy)
- Background: #F8FAFC (Soft Slate White)
- Surface: #FFFFFF
- Success/Trust: #0D9488 & #059669
- Warning: #D97706
- Danger: #DC2626
Typography: Inter or Plus Jakarta Sans.
Create components for:
1. Navbar with logo, search link, "+ Tawarkan Waktu" button, and user avatar dropdown with Trust Score badge.
2. Listing Card featuring session badge (ONLINE blue / OFFLINE purple), price pill (GRATIS or IDR format), host avatar with trust score (e.g. ⭐ 100), schedule time, and "Lihat Detail" button.
3. Interaction Boundaries Agreement Card with security shield icon and checklist.
4. Trust Score Meter Gauge showing Green (≥90), Amber (70-89), and Red (<70).
5. 5-Star Interactive Rating Component with karma reward notification banner.
```

### Prompt 2: Human Catalog Screen (`catalog.view.php`)
```text
Create a desktop and responsive mobile web page for "Human Catalog" on PINJAMORANG.
Layout:
- Top Sticky Header: Logo, navigation, and user status.
- Hero Banner: Friendly title "Jelajahi Waktu Manusia" with subtle warm illustration and subtitle.
- Sticky Filter Bar: Text search input ("Cari pengalaman..."), Dropdown ("Semua", "ONLINE", "OFFLINE"), Checkbox ("Hanya yang Gratis"), and Coral "Terapkan Filter" button.
- 3-Column Responsive Grid: 6 listing cards showcasing diverse social experiences (e.g., "Diskusi UI/UX Santai", "Teman Lari Pagi GBK", "Curhat Karir Online"). Each card displays session badge, host avatar, trust score badge, date/time, remaining capacity ("Sisa 2 dari 5 slot"), and price tag.
- Footer: Clean slate footer with copyright and tagline "Because not every moment needs a reason."
```

### Prompt 3: Listing Detail & Booking Screen (`detail.view.php`)
```text
Design a high-fidelity web page for a Listing Detail on PINJAMORANG.
Desktop 2-column layout (65% content left, 35% sticky booking card right):
Left Column:
- Breadcrumb navigation: Home > Katalog > Detail Pengalaman.
- Title: "Ngobrol Santai Seputar Transisi Karir ke Tech & Product".
- Badges: "ONLINE (Virtual)" and "90 Menit".
- Detailed description of the session with bullet points.
- Host Profile Card: Host photo, Name, Verified badge, Trust Score ⭐ 98, Bio paragraph, and member since date.
- "Aturan & Batasan Interaksi (Boundaries Agreement)" card: Highlighted box with shield icon and safety guidelines (No personal stalking, neutral public or virtual space, mutual respect).
Right Column (Sticky Card):
- Price: "Rp 50.000 / slot" (or "GRATIS").
- Capacity progress: "3 dari 5 slot terisi".
- Mandatory Checkbox: "Saya telah membaca dan menyetujui seluruh batasan interaksi di atas."
- Short note textarea for the host.
- Primary Coral Button: "Pesan Slot Waktu".
```

### Prompt 4: Participant Dashboard & Privacy Access (`my_bookings.view.php`)
```text
Design a user dashboard page titled "Pengalaman Saya" for PINJAMORANG participants.
Features:
- Segmented Tab Filters: "Semua", "Mendatang / Aktif (2)", "Selesai", "Dibatalkan".
- Card 1 (Status CONFIRMED): Green status badge "Terkonfirmasi - Siap Hadir". Displays date, time, and Host info. Features an exclusive highlighted Privacy Box containing a green button: "Buka Link Pertemuan (Google Meet)" and helper text "Link privat ini hanya terlihat oleh Anda dan Host". Includes an outline button "Batalkan Reservasi".
- Card 2 (Status PENDING): Yellow badge "Menunggu Persetujuan Host". Location shows "Link pertemuan akan aktif setelah disetujui".
- Card 3 (Status COMPLETED): Blue badge "Selesai" with button "Beri Ulasan & Bintang".
- Modal Popup: "Konfirmasi Pembatalan" with alert icon warning that cancelling less than 2 hours before the session deducts 25 Trust Score points.
```

