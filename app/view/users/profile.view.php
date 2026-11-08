<div class="catalog-section" style="padding-top: 40px; max-width: 900px; margin: 0 auto;">
    <!-- Breadcrumb -->
    <div style="font-size: 12px; color: var(--color-muted); margin-bottom: 24px;">
        <a href="/index.php?page=listings" style="color: var(--color-primary); font-weight: 600;">Katalog</a>
        <span style="margin: 0 8px;">/</span>
        <span>Profil Pengguna</span>
    </div>

    <!-- KARTU IDENTITAS PROFIL -->
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 18px; padding: 36px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04); margin-bottom: 30px;">
        <div style="display: flex; gap: 24px; align-items: start; flex-wrap: wrap;">
            <img 
                src="<?= !empty($user['avatar_url']) ? htmlspecialchars($user['avatar_url']) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?crop=faces&fit=crop&w=160&h=160&q=80' ?>" 
                alt="Avatar <?= htmlspecialchars($user['name']) ?>" 
                style="width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 0 0 1px #cbd5e1;"
            >
            <div style="flex: 1; min-width: 260px;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <h1 style="font-family: var(--font-display); font-size: 28px; color: var(--color-slate-deep); margin: 0;">
                        <?= htmlspecialchars($user['name']) ?>
                    </h1>
                    <?php if (!empty($user['is_verified'])): ?>
                        <span class="verified" style="width: 18px; height: 18px; font-size: 11px;" title="Identitas Terverifikasi Resmi">✓</span>
                    <?php endif; ?>
                </div>

                <div style="font-size: 12px; color: var(--color-muted); margin-top: 4px;">
                    Anggota sejak <?= date('F Y', strtotime($user['created_at'])) ?>
                </div>

                <!-- Bio Diri -->
                <p style="font-size: 14px; line-height: 1.7; color: #475569; margin-top: 14px;">
                    <?= !empty($user['bio']) ? htmlspecialchars($user['bio']) : 'Pengguna ini belum menambahkan cerita perkenalan pada bio.' ?>
                </p>

                <!-- Privacy Notice -->
                <div style="margin-top: 14px; font-size: 11px; color: var(--color-muted); display: flex; align-items: center; gap: 6px;">
                    <span>🛡️</span>
                    <span>Informasi kontak pribadi terlindungi dan tidak ditampilkan kepada publik demi keamanan bersama.</span>
                </div>
            </div>

            <!-- Meteran Trust Score -->
            <?php
                $trust = (int)$user['trust_score'];
                if ($trust >= 90) {
                    $scoreBg = '#ecfdf5';
                    $scoreColor = '#059669';
                    $scoreLabel = 'Sangat Terpercaya & Disiplin';
                } elseif ($trust >= 70) {
                    $scoreBg = '#fffbeb';
                    $scoreColor = '#d97706';
                    $scoreLabel = 'Standar Komunitas';
                } else {
                    $scoreBg = '#fef2f2';
                    $scoreColor = '#dc2626';
                    $scoreLabel = 'Skor Rendah (Fitur Dibatasi)';
                }
            ?>
            <div style="background: <?= $scoreBg ?>; border-radius: 14px; padding: 18px 24px; text-align: center; min-width: 180px;">
                <span style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: <?= $scoreColor ?>; letter-spacing: 0.05em;">
                    Trust & Karma Score
                </span>
                <div style="font-size: 32px; font-weight: 800; color: <?= $scoreColor ?>; margin: 4px 0;">
                    ⭐ <?= $trust ?>
                </div>
                <div style="font-size: 11px; font-weight: 600; color: <?= $scoreColor ?>;">
                    <?= $scoreLabel ?>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR ULASAN DARI ORANG LAIN -->
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 18px; padding: 32px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--color-slate-deep); margin: 0;">
                    Ulasan & Pengalaman Bersama
                </h3>
                <span style="font-size: 12px; color: var(--color-muted);">
                    Cerita nyata dari sesi waktu yang telah diselesaikan.
                </span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px; font-size: 15px; font-weight: 700; color: var(--color-slate-deep);">
                <span style="color: #f59e0b;">★</span>
                <span><?= number_format((float)$avgRating, 1) ?> dari 5.0</span>
                <span style="color: var(--color-muted); font-size: 13px; font-weight: 400;">(<?= count($reviews) ?> ulasan)</span>
            </div>
        </div>

        <?php if (!empty($reviews)): ?>
            <div style="display: grid; gap: 18px;">
                <?php foreach ($reviews as $rev): ?>
                    <div style="border-bottom: 1px solid var(--color-border); padding-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img 
                                    src="<?= !empty($rev['reviewer_avatar']) ? htmlspecialchars($rev['reviewer_avatar']) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?crop=faces&fit=crop&w=80&h=80&q=80' ?>" 
                                    alt="Reviewer" 
                                    style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;"
                                >
                                <strong style="font-size: 13px; color: var(--color-slate-deep);">
                                    <?= htmlspecialchars($rev['reviewer_name'] ?? 'Pengguna') ?>
                                </strong>
                            </div>
                            <div style="color: #f59e0b; font-size: 13px;">
                                <?= str_repeat('★', (int)$rev['rating']) . str_repeat('☆', 5 - (int)$rev['rating']) ?>
                            </div>
                        </div>
                        <p style="font-size: 13px; line-height: 1.6; color: #475569; margin: 0;">
                            <?= !empty($rev['comment']) ? htmlspecialchars($rev['comment']) : '<em>Tidak ada komentar tertulis.</em>' ?>
                        </p>
                        <small style="color: #94a3b8; font-size: 11px; margin-top: 6px; display: block;">
                            <?= date('d M Y', strtotime($rev['created_at'])) ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px 20px; color: var(--color-muted);">
                <p style="font-size: 14px;">Belum ada ulasan untuk pengguna ini.</p>
                <small>Ulasan akan muncul setelah menyelesaikan sesi pertama bersama anggota lain.</small>
            </div>
        <?php endif; ?>
    </div>
</div>
