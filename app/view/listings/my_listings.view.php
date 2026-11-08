<div class="catalog-section" style="padding-top: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: end; margin-bottom: 32px;">
        <div>
            <span class="section-kicker">Host Dashboard</span>
            <h1 style="font-family: var(--font-display); font-size: 34px; color: var(--color-slate-deep); margin-top: 6px;">
                Penawaran Waktu Saya
            </h1>
            <p style="font-size: 14px; color: var(--color-muted); margin-top: 4px;">
                Kelola jadwal waktu luang yang telah Anda publikasikan ke komunitas.
            </p>
        </div>
        <a href="/index.php?page=create-listing" class="button button-primary">
            + Tawarkan Waktu Baru
        </a>
    </div>

    <?php if (!empty($myListings)): ?>
        <div style="display: grid; gap: 16px;">
            <?php foreach ($myListings as $item): ?>
                <?php
                    $isFree = empty($item['price']) || (float)$item['price'] == 0;
                    $status = strtoupper($item['status'] ?? 'ACTIVE');
                    $statusColors = [
                        'ACTIVE'    => ['bg' => '#ecfdf5', 'text' => '#059669'],
                        'DRAFT'     => ['bg' => '#fffbeb', 'text' => '#d97706'],
                        'COMPLETED' => ['bg' => '#eff6ff', 'text' => '#2563eb'],
                        'CANCELLED' => ['bg' => '#f1f5f9', 'text' => '#64748b']
                    ];
                    $badge = $statusColors[$status] ?? ['bg' => '#f1f5f9', 'text' => '#64748b'];
                ?>
                <div style="background: white; border: 1px solid var(--color-border); border-radius: 14px; padding: 22px; display: flex; justify-content: space-between; align-items: center; gap: 20px;">
                    <div>
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                            <span class="session-badge <?= strtolower($item['session_type']) ?>">
                                <?= htmlspecialchars($item['session_type']) ?>
                            </span>
                            <span style="font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 99px; background: <?= $badge['bg'] ?>; color: <?= $badge['text'] ?>;">
                                <?= $status ?>
                            </span>
                            <span style="font-size: 12px; color: var(--color-muted);">
                                📅 <?= date('d M Y', strtotime($item['listing_date'])) ?> • <?= htmlspecialchars(substr($item['start_time'], 0, 5)) ?>-<?= htmlspecialchars(substr($item['end_time'], 0, 5)) ?> WIB
                            </span>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--color-slate-deep); margin-bottom: 6px;">
                            <a href="/index.php?page=listing-detail&id=<?= (int)$item['id'] ?>" style="color: inherit; text-decoration: none;">
                                <?= htmlspecialchars($item['title']) ?>
                            </a>
                        </h3>
                        <div style="font-size: 12px; color: var(--color-muted); display: flex; gap: 16px;">
                            <span>Kuota: <strong><?= (int)$item['capacity'] ?> Orang</strong></span>
                            <span>Biaya: <strong><?= $isFree ? 'GRATIS' : 'Rp ' . number_format((float)$item['price'], 0, ',', '.') ?></strong></span>
                            <span>Mode: <strong><?= htmlspecialchars($item['approval_mode']) ?></strong></span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; align-items: center;">
                        <a href="/index.php?page=listing-detail&id=<?= (int)$item['id'] ?>" class="button button-outline" style="min-height: 38px;">
                            Lihat Rincian
                        </a>
                        <?php if ($status === 'ACTIVE'): ?>
                            <form method="POST" action="/index.php?page=cancel-listing" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan sesi penawaran ini?');" style="margin: 0;">
                                <input type="hidden" name="listing_id" value="<?= (int)$item['id'] ?>">
                                <button type="submit" class="button" style="min-height: 38px; color: #dc2626; border: 1px solid #fecaca; background: #fff5f5;">
                                    Batalkan
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h3>Belum Ada Penawaran Waktu</h3>
            <p>Anda belum pernah membuat penawaran waktu. Mulailah berbagi momen berharga dengan orang lain!</p>
            <a href="/index.php?page=create-listing" class="button button-primary">
                Buat Penawaran Pertama Anda
            </a>
        </div>
    <?php endif; ?>
</div>
