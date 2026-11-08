<div class="catalog-section" style="padding-top: 40px;">
    <!-- Breadcrumb -->
    <div style="font-size: 12px; color: var(--color-muted); margin-bottom: 24px;">
        <a href="/index.php?page=listings" style="color: var(--color-primary); font-weight: 600;">Katalog</a>
        <span style="margin: 0 8px;">/</span>
        <span>Rincian Penawaran Waktu</span>
    </div>

    <div class="detail-grid">
        <!-- KOLOM KIRI: Detail Pengalaman, Profil Host, Boundaries -->
        <div>
            <!-- Top Badges -->
            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 16px;">
                <span class="session-badge <?= strtolower($listing['session_type']) ?>">
                    <span class="status-dot"></span>
                    <?= htmlspecialchars($listing['session_type']) ?>
                </span>
                <span style="font-size: 12px; color: var(--color-muted); font-weight: 600;">
                    Durasi: <?= (int)$listing['duration_minutes'] ?> Menit
                </span>
            </div>

            <!-- Title & Description -->
            <h1 style="font-family: var(--font-display); font-size: 36px; line-height: 1.2; color: var(--color-slate-deep); margin-bottom: 20px;">
                <?= htmlspecialchars($listing['title']) ?>
            </h1>

            <div style="background: white; border: 1px solid var(--color-border); border-radius: 14px; padding: 24px; margin-bottom: 30px;">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px; color: var(--color-slate-deep);">
                    Tentang Sesi Ini
                </h3>
                <p style="font-size: 14px; line-height: 1.7; color: #475569; white-space: pre-line;">
                    <?= htmlspecialchars($listing['description']) ?>
                </p>

                <!-- Jadwal & Lokasi -->
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px dashed var(--color-border); display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <div style="font-size: 11px; text-transform: uppercase; color: var(--color-muted); font-weight: 700;">Waktu Pelaksanaan</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--color-slate-deep); margin-top: 4px;">
                            📅 <?= date('l, d F Y', strtotime($listing['listing_date'])) ?>
                        </div>
                        <div style="font-size: 13px; color: #475569; margin-top: 2px;">
                            ⏰ <?= htmlspecialchars(substr($listing['start_time'], 0, 5)) ?> - <?= htmlspecialchars(substr($listing['end_time'], 0, 5)) ?> WIB
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 11px; text-transform: uppercase; color: var(--color-muted); font-weight: 700;">Lokasi Pertemuan</div>
                        <?php if ($listing['session_type'] === 'ONLINE'): ?>
                            <div style="font-size: 14px; font-weight: 600; color: #2563eb; margin-top: 4px;">
                                🌐 Sesi Virtual (Online)
                            </div>
                            <div style="font-size: 12px; color: var(--color-muted); margin-top: 2px;">
                                Link Google Meet / Zoom diberikan secara privat setelah booking terkonfirmasi.
                            </div>
                        <?php else: ?>
                            <div style="font-size: 14px; font-weight: 600; color: #7c3aed; margin-top: 4px;">
                                📍 <?= htmlspecialchars($listing['location']) ?>
                            </div>
                            <div style="font-size: 12px; color: var(--color-muted); margin-top: 2px;">
                                Area publik yang ramai demi kenyamanan bersama.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- PROFIL HOST -->
            <div style="background: white; border: 1px solid var(--color-border); border-radius: 14px; padding: 24px; margin-bottom: 30px;">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--color-slate-deep);">
                    Profil Host
                </h3>
                <div style="display: flex; gap: 16px; align-items: start;">
                    <img 
                        src="<?= !empty($listing['host_avatar']) ? htmlspecialchars($listing['host_avatar']) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?crop=faces&fit=crop&w=160&h=160&q=80' ?>" 
                        alt="Avatar Host" 
                        style="width: 58px; height: 58px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 0 0 1px #e2e8f0;"
                    >
                    <div>
                        <div style="font-size: 16px; font-weight: 700; color: var(--color-slate-deep); display: flex; align-items: center; gap: 6px;">
                            <?= htmlspecialchars($listing['host_name'] ?? 'Host PinjamOrang') ?>
                            <?php if (!empty($listing['host_is_verified'])): ?>
                                <span class="verified" title="Identitas terverifikasi">✓</span>
                            <?php endif; ?>
                        </div>
                        <div style="margin-top: 4px;">
                            <span class="trust-badge" style="font-size: 11px;">
                                ⭐ <?= (int)($listing['host_trust_score'] ?? 100) ?> Poin Reputasi (Disiplin & Terpercaya)
                            </span>
                        </div>
                        <p style="font-size: 13px; color: #475569; margin-top: 10px; line-height: 1.6;">
                            <?= !empty($listing['host_bio']) ? htmlspecialchars($listing['host_bio']) : 'Host ini belum menuliskan bio profil.' ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- INTERACTION BOUNDARIES (BATASAN INTERAKSI) -->
            <div style="background: #fffdf7; border: 1.5px solid #fde68a; border-radius: 14px; padding: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; color: #b45309;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <h3 style="font-size: 16px; font-weight: 700;">Aturan & Batasan Interaksi (Boundaries)</h3>
                </div>
                <p style="font-size: 13px; color: #78350f; line-height: 1.6; margin-bottom: 14px;">
                    Platform PINJAMORANG mengedepankan rasa aman, saling menghormati, dan batasan pribadi. Harap patuhi aturan interaksi berikut:
                </p>
                <ul style="font-size: 13px; color: #78350f; line-height: 1.7; padding-left: 20px;">
                    <li>Wajib hadir tepat waktu sesuai jadwal yang tertera.</li>
                    <li>Menghormati batasan privasi, tanpa paksaan membahas topik sensitif/pribadi.</li>
                    <li>Sesi tatap muka offline hanya dilangsungkan di ruang publik yang ramai dan terbuka.</li>
                    <li>Dilarang melakukan kontak fisik tanpa persetujuan langsung.</li>
                    <?php if (!empty($listing['boundaries'])): ?>
                        <li style="font-weight: 600; margin-top: 6px;">Catatan khusus Host: <?= htmlspecialchars($listing['boundaries']) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- KOLOM KANAN: Sticky Booking Box -->
        <div style="position: sticky; top: 100px;">
            <div style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);">
                <!-- Harga -->
                <div style="margin-bottom: 20px;">
                    <span style="font-size: 11px; text-transform: uppercase; color: var(--color-muted); font-weight: 700;">Biaya Sesi</span>
                    <div style="margin-top: 4px;">
                        <?php if (empty($listing['price']) || (float)$listing['price'] == 0): ?>
                            <span style="font-size: 28px; font-weight: 800; color: #059669;">GRATIS</span>
                        <?php else: ?>
                            <span style="font-size: 28px; font-weight: 800; color: var(--color-slate-deep);">
                                Rp <?= number_format((float)$listing['price'], 0, ',', '.') ?>
                            </span>
                            <span style="font-size: 12px; color: var(--color-muted);">/ sesi</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sisa Kuota -->
                <div style="margin-bottom: 24px; padding: 12px 16px; background: var(--color-canvas); border-radius: 10px;">
                    <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: var(--color-slate-deep);">
                        <span>Ketersediaan Slot</span>
                        <span style="color: <?= $remainingSlots > 0 ? 'var(--color-primary)' : '#dc2626' ?>;">
                            Sisa <?= $remainingSlots ?> dari <?= (int)$listing['capacity'] ?> slot
                        </span>
                    </div>
                    <div class="slot-bar" style="margin-top: 8px;">
                        <span style="width: <?= min(100, max(10, (int)(($remainingSlots / max(1, $listing['capacity'])) * 100))) ?>%;"></span>
                    </div>
                </div>

                <!-- Formulir Pemesanan -->
                <form method="POST" action="/index.php?page=book" id="formBooking">
                    <input type="hidden" name="listing_id" value="<?= (int)$listing['id'] ?>">

                    <div style="margin-bottom: 18px;">
                        <label style="font-size: 12px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                            Pesan Singkat untuk Host (Opsional)
                        </label>
                        <textarea 
                            name="notes" 
                            rows="3" 
                            placeholder="Ceritakan sedikit tujuan atau topik yang ingin kamu bicarakan..." 
                            style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 10px; font-size: 13px;"
                        ></textarea>
                    </div>

                    <!-- Persetujuan Wajib Boundaries -->
                    <div style="margin-bottom: 24px;">
                        <label style="display: flex; gap: 10px; align-items: start; font-size: 12px; color: #475569; cursor: pointer;">
                            <input type="checkbox" id="chkAgreedBoundaries" name="agreed_to_boundaries" value="1" required style="margin-top: 3px;">
                            <span>
                                Saya telah membaca, memahami, dan <strong>menyetujui seluruh batasan interaksi</strong> di atas.
                            </span>
                        </label>
                    </div>

                    <!-- Tombol Aksi -->
                    <?php if ($listing['status'] !== 'ACTIVE'): ?>
                        <button type="button" class="button button-outline" style="width: 100%;" disabled>
                            Penawaran Berakhir
                        </button>
                    <?php elseif ($remainingSlots <= 0): ?>
                        <button type="button" class="button button-outline" style="width: 100%;" disabled>
                            Kapasitas Penuh
                        </button>
                    <?php else: ?>
                        <button type="submit" id="btnBookNow" class="button button-primary" style="width: 100%;">
                            Pesan Slot Waktu Sekarang
                        </button>
                    <?php endif; ?>
                </form>

                <div style="margin-top: 18px; text-align: center; font-size: 11px; color: var(--color-muted);">
                    🛡️ Terlindungi sistem pencegahan ghosting & komitmen waktu.
                </div>
            </div>
        </div>
    </div>
</div>
