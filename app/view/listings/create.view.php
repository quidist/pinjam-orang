<div class="catalog-section" style="padding-top: 40px; max-width: 800px; margin: 0 auto;">
    <div style="font-size: 12px; color: var(--color-muted); margin-bottom: 20px;">
        <a href="/index.php?page=listings" style="color: var(--color-primary); font-weight: 600;">Katalog</a>
        <span style="margin: 0 8px;">/</span>
        <span>Buat Penawaran Baru</span>
    </div>

    <div style="margin-bottom: 28px;">
        <span class="section-kicker">Wadah Berbagi</span>
        <h1 style="font-family: var(--font-display); font-size: 34px; color: var(--color-slate-deep); margin-top: 6px;">
            Tawarkan Waktu Luang Anda
        </h1>
        <p style="font-size: 14px; color: var(--color-muted); margin-top: 6px;">
            Bagikan keahlian, pengalaman hidup, atau sekadar teman mengobrol yang tulus.
        </p>
    </div>

    <!-- Tampilan Error Validasi jika Ada -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <ul style="padding-left: 18px; margin: 0;">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="formCreateListing" method="POST" action="/index.php?page=create-listing" style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 32px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
        
        <!-- 1. Judul & Deskripsi -->
        <div style="margin-bottom: 22px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                Judul Pengalaman <span style="color: #dc2626;">*</span>
            </label>
            <input 
                type="text" 
                name="title" 
                value="<?= htmlspecialchars($oldInput['title'] ?? '') ?>" 
                placeholder="Contoh: Diskusi Santai tentang Kopi & Karir Tech" 
                required 
                style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 12px 14px; font-size: 14px;"
            >
        </div>

        <div style="margin-bottom: 24px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                Deskripsi Cerita & Aktivitas <span style="color: #dc2626;">*</span>
            </label>
            <textarea 
                name="description" 
                rows="4" 
                placeholder="Ceritakan apa yang akan kalian lakukan bersama, apa yang bisa dipelajari, dan suasana seperti apa yang diharapkan..." 
                required 
                style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 12px 14px; font-size: 13px; line-height: 1.6;"
            ><?= htmlspecialchars($oldInput['description'] ?? '') ?></textarea>
        </div>

        <!-- 2. Jenis Sesi (Online vs Offline) -->
        <div style="margin-bottom: 24px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 8px;">
                Format Pertemuan <span style="color: #dc2626;">*</span>
            </label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <label style="border: 1px solid var(--color-border); border-radius: 10px; padding: 14px; display: flex; gap: 12px; align-items: start; cursor: pointer;">
                    <input type="radio" name="session_type" value="ONLINE" id="radioOnline" <?= (($oldInput['session_type'] ?? 'ONLINE') === 'ONLINE') ? 'checked' : '' ?> style="margin-top: 3px;">
                    <div>
                        <strong style="font-size: 13px; color: var(--color-slate-deep); display: block;">🌐 Sesi Virtual (Online)</strong>
                        <span style="font-size: 11px; color: var(--color-muted);">Melalui Google Meet / Zoom. Link aman dibagikan privat setelah booking disetujui.</span>
                    </div>
                </label>
                <label style="border: 1px solid var(--color-border); border-radius: 10px; padding: 14px; display: flex; gap: 12px; align-items: start; cursor: pointer;">
                    <input type="radio" name="session_type" value="OFFLINE" id="radioOffline" <?= (($oldInput['session_type'] ?? '') === 'OFFLINE') ? 'checked' : '' ?> style="margin-top: 3px;">
                    <div>
                        <strong style="font-size: 13px; color: var(--color-slate-deep); display: block;">📍 Tatap Muka (Offline)</strong>
                        <span style="font-size: 11px; color: var(--color-muted);">Wajib di ruang publik yang ramai & terbuka (kafe, perpustakaan kota, taman).</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Lokasi Input (Khusus Sesi Offline) -->
        <div id="offlineLocationWrapper" style="margin-bottom: 24px; <?= (($oldInput['session_type'] ?? 'ONLINE') === 'OFFLINE') ? '' : 'display: none;' ?>">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                Nama Tempat Publik <span style="color: #dc2626;">*</span>
            </label>
            <input 
                type="text" 
                id="inputLocation" 
                name="location" 
                value="<?= htmlspecialchars($oldInput['location'] ?? '') ?>" 
                placeholder="Contoh: Kopi Tuku Blok M / Perpustakaan Nasional Lt. 4" 
                style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 12px 14px; font-size: 14px;"
            >
        </div>

        <!-- 3. Jadwal Waktu -->
        <div style="margin-bottom: 24px; padding: 20px; background: var(--color-canvas); border-radius: 12px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 12px;">
                Jadwal & Waktu Pelaksanaan <span style="color: #dc2626;">*</span>
            </label>
            <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 14px; align-items: end;">
                <div>
                    <span style="font-size: 11px; color: var(--color-muted); font-weight: 600; display: block; margin-bottom: 4px;">Tanggal</span>
                    <input 
                        type="date" 
                        id="inputListingDate" 
                        name="listing_date" 
                        value="<?= htmlspecialchars($oldInput['listing_date'] ?? date('Y-m-d')) ?>" 
                        min="<?= date('Y-m-d') ?>" 
                        required 
                        style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 10px; background: white; font-size: 13px;"
                    >
                </div>
                <div>
                    <span style="font-size: 11px; color: var(--color-muted); font-weight: 600; display: block; margin-bottom: 4px;">Jam Mulai</span>
                    <input 
                        type="time" 
                        id="inputStartTime" 
                        name="start_time" 
                        value="<?= htmlspecialchars($oldInput['start_time'] ?? '14:00') ?>" 
                        required 
                        style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 10px; background: white; font-size: 13px;"
                    >
                </div>
                <div>
                    <span style="font-size: 11px; color: var(--color-muted); font-weight: 600; display: block; margin-bottom: 4px;">Jam Selesai</span>
                    <input 
                        type="time" 
                        id="inputEndTime" 
                        name="end_time" 
                        value="<?= htmlspecialchars($oldInput['end_time'] ?? '15:30') ?>" 
                        required 
                        style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 10px; background: white; font-size: 13px;"
                    >
                </div>
            </div>
            <div id="durationBadge" style="margin-top: 10px; font-size: 11px; color: var(--color-primary-dark); font-weight: 700;">
                ⏱️ Perkiraan durasi: 90 menit
            </div>
        </div>

        <!-- 4. Kapasitas & Biaya -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div>
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Kuota Kapasitas Peserta <span style="color: #dc2626;">*</span>
                </label>
                <input 
                    type="number" 
                    name="capacity" 
                    min="1" 
                    max="10" 
                    value="<?= htmlspecialchars($oldInput['capacity'] ?? '1') ?>" 
                    required 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px;"
                >
                <small style="color: var(--color-muted); font-size: 11px; display: block; margin-top: 4px;">Satu sesi privat (1 orang) atau grup kecil (maksimal 10).</small>
            </div>

            <div>
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Biaya Sesi (Rupiah)
                </label>
                <input 
                    type="number" 
                    id="inputPrice" 
                    name="price" 
                    min="0" 
                    step="5000" 
                    value="<?= htmlspecialchars($oldInput['price'] ?? '0') ?>" 
                    placeholder="0 untuk gratis" 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px;"
                >
                <label style="display: flex; gap: 8px; align-items: center; margin-top: 8px; font-size: 12px; color: #475569; cursor: pointer;">
                    <input type="checkbox" id="chkIsFree" name="is_free" value="1" <?= (!empty($oldInput['is_free']) || empty($oldInput['price'])) ? 'checked' : '' ?>>
                    <span>Tawarkan waktu secara <strong>Gratis / Sukarela</strong></span>
                </label>
            </div>
        </div>

        <!-- 5. Mode Persetujuan & Batasan Interaksi -->
        <div style="margin-bottom: 24px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                Mode Konfirmasi Booking
            </label>
            <select name="approval_mode" style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 13px; background: white;">
                <option value="AUTOMATIC" <?= (($oldInput['approval_mode'] ?? 'AUTOMATIC') === 'AUTOMATIC') ? 'selected' : '' ?>>Otomatis (Langsung Dikonfirmasi)</option>
                <option value="MANUAL" <?= (($oldInput['approval_mode'] ?? '') === 'MANUAL') ? 'selected' : '' ?>>Manual (Saya tinjau dulu profil peserta)</option>
            </select>
        </div>

        <div style="margin-bottom: 28px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                Batasan Khusus Tambahan (Boundaries)
            </label>
            <textarea 
                name="boundaries" 
                rows="2" 
                placeholder="Contoh: Tolong tidak merokok selama sesi; Hanya untuk diskusi seputar karir..." 
                style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 13px;"
            ><?= htmlspecialchars($oldInput['boundaries'] ?? '') ?></textarea>
            <small style="color: var(--color-muted); font-size: 11px; display: block; margin-top: 4px;">Aturan standar platform (sopan, tanpa sentuhan fisik) otomatis berlaku.</small>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; gap: 14px;">
            <a href="/index.php?page=listings" class="button button-outline">Batal</a>
            <button type="submit" class="button button-primary" style="min-width: 200px;">
                Terbitkan Penawaran Waktu
            </button>
        </div>
    </form>
</div>
