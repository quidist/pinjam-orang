<div class="catalog-section" style="padding-top: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: end; margin-bottom: 28px;">
        <div>
            <span class="section-kicker">Host Approval System</span>
            <h1 style="font-family: var(--font-display); font-size: 34px; color: var(--color-slate-deep); margin-top: 6px;">
                Permintaan Masuk Peserta
            </h1>
            <p style="font-size: 14px; color: var(--color-muted); margin-top: 4px;">
                Tinjau calon peserta yang mendaftar pada penawaran waktu milik Anda.
            </p>
        </div>
        <a href="/index.php?page=my-listings" class="button button-outline">
            Kelola Penawaran Saya
        </a>
    </div>

    <?php if (!empty($requests)): ?>
        <div style="display: grid; gap: 16px;">
            <?php foreach ($requests as $req): ?>
                <?php
                    $status = strtoupper($req['status']);
                    $statusConfig = [
                        'PENDING'   => ['bg' => '#fffbeb', 'text' => '#d97706', 'label' => 'Menunggu Persetujuan'],
                        'CONFIRMED' => ['bg' => '#ecfdf5', 'text' => '#059669', 'label' => 'Telah Disetujui'],
                        'REJECTED'  => ['bg' => '#fef2f2', 'text' => '#dc2626', 'label' => 'Telah Ditolak'],
                        'CANCELLED' => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => 'Dibatalkan Peserta']
                    ];
                    $cfg = $statusConfig[$status] ?? ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => $status];
                ?>
                <div style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 22px; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 280px;">
                        <!-- Top Line -->
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                            <span style="font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 99px; background: <?= $cfg['bg'] ?>; color: <?= $cfg['text'] ?>;">
                                <?= $cfg['label'] ?>
                            </span>
                            <span style="font-size: 12px; color: var(--color-muted);">
                                Sesi: <strong><?= htmlspecialchars($req['listing_title']) ?></strong>
                            </span>
                        </div>

                        <!-- Data Peserta -->
                        <div style="display: flex; gap: 14px; align-items: center; margin: 12px 0;">
                            <a href="/index.php?page=profile&id=<?= (int)$req['participant_id'] ?>" target="_blank" title="Klik untuk meninjau profil lengkap peserta" style="text-decoration: none; display: block;">
                                <img 
                                    src="<?= !empty($req['participant_avatar']) ? htmlspecialchars($req['participant_avatar']) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?crop=faces&fit=crop&w=80&h=80&q=80' ?>" 
                                    alt="Foto Peserta" 
                                    style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 0 0 1px #cbd5e1;"
                                >
                            </a>
                            <div>
                                <div style="font-size: 15px; font-weight: 700; color: var(--color-slate-deep); display: flex; align-items: center; gap: 6px;">
                                    <a href="/index.php?page=profile&id=<?= (int)$req['participant_id'] ?>" target="_blank" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='inherit'">
                                        <?= htmlspecialchars($req['participant_name']) ?>
                                    </a>
                                    <?php if (!empty($req['participant_is_verified'])): ?>
                                        <span class="verified" title="Identitas Terverifikasi">✓</span>
                                    <?php endif; ?>
                                </div>
                                <div style="display: flex; gap: 8px; align-items: center; margin-top: 3px;">
                                    <span class="trust-badge" style="font-size: 11px;">
                                        ⭐ <?= (int)($req['participant_trust_score'] ?? 100) ?> Poin Reputasi
                                    </span>
                                    <a href="/index.php?page=profile&id=<?= (int)$req['participant_id'] ?>" target="_blank" style="font-size: 11px; color: var(--color-primary); font-weight: 600; text-decoration: none;">
                                        Lihat Profil &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Peserta -->
                        <?php if (!empty($req['notes'])): ?>
                            <div style="font-size: 13px; color: #475569; background: var(--color-canvas); border-radius: 8px; padding: 10px 14px; margin-top: 10px; line-height: 1.5;">
                                💬 <em>"<?= htmlspecialchars($req['notes']) ?>"</em>
                            </div>
                        <?php endif; ?>

                        <!-- Jadwal & Slot -->
                        <div style="font-size: 12px; color: var(--color-muted); margin-top: 10px; display: flex; gap: 16px;">
                            <span>📅 <?= date('d M Y', strtotime($req['listing_date'])) ?></span>
                            <span>⏰ <?= htmlspecialchars(substr($req['start_time'], 0, 5)) ?>–<?= htmlspecialchars(substr($req['end_time'], 0, 5)) ?> WIB</span>
                            <span>Slot: <strong><?= (int)$req['slot_count'] ?> Kursi</strong></span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Persetujuan Host -->
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <a href="/index.php?page=profile&id=<?= (int)$req['participant_id'] ?>" target="_blank" class="button button-outline" style="min-height: 40px; padding: 0 14px; font-size: 13px;" title="Tinjau detail profil & reputasi pendaftar">
                            🔍 Tinjau Profil
                        </a>
                        <?php if ($status === 'PENDING'): ?>
                            <form method="POST" action="/index.php?page=approve-booking" style="margin: 0;">
                                <input type="hidden" name="booking_id" value="<?= (int)$req['id'] ?>">
                                <button type="submit" class="button" style="background: #059669; color: white; min-height: 40px; padding: 0 18px; font-size: 13px;">
                                    ✓ Setujui (Approve)
                                </button>
                            </form>
                            <form method="POST" action="/index.php?page=reject-booking" onsubmit="return confirm('Tolak pendaftaran peserta ini?');" style="margin: 0;">
                                <input type="hidden" name="booking_id" value="<?= (int)$req['id'] ?>">
                                <button type="submit" class="button button-outline" style="color: #dc2626; border-color: #fecaca; min-height: 40px; padding: 0 16px; font-size: 13px;">
                                    ✕ Tolak
                                </button>
                            </form>
                        <?php else: ?>
                            <span style="font-size: 12px; color: var(--color-muted); font-weight: 600;">
                                Status saat ini: <?= $status ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">📩</div>
            <h3>Belum Ada Permintaan Masuk</h3>
            <p>Belum ada calon peserta yang mendaftar pada penawaran waktu aktif Anda saat ini.</p>
        </div>
    <?php endif; ?>
</div>
