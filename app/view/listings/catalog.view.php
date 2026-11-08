<!-- =============================================================================
     1. HERO SECTION
     ============================================================================= -->
<section class="hero">
    <div class="hero-grid">
        <div class="hero-copy">
            <div class="eyebrow">
                <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 3 1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3ZM5 15l.8 2.2L8 18l-2.2.8L5 21l-.8-2.2L2 18l2.2-.8L5 15Z"/>
                </svg>
                Waktu yang dibagi, cerita yang kembali
            </div>
            <h1>
                Temukan seseorang
                <br>
                untuk <em>berbagi waktu.</em>
            </h1>
            <p>
                Belajar hal baru, ngobrol tanpa dihakimi, atau sekadar punya teman jalan. Semua dimulai dari satu jam yang tulus.
            </p>
            <div class="hero-actions">
                <a href="#katalog" class="button button-primary">
                    Mulai menjelajah
                    <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14m-5-5 5 5-5 5"/>
                    </svg>
                </a>
                <div class="community-proof">
                    <div class="proof-avatars">
                        <img src="https://images.unsplash.com/photo-1526218769555-7a8cced31a78?crop=faces&fit=crop&w=80&h=80&q=80" alt="Avatar Komunitas">
                        <img src="https://images.unsplash.com/photo-1541537806773-cd2c2a215cc5?crop=faces&fit=crop&w=80&h=80&q=80" alt="Avatar Komunitas">
                        <img src="https://images.unsplash.com/photo-1516011362164-3095a82b0af9?crop=faces&fit=crop&w=80&h=80&q=80" alt="Avatar Komunitas">
                    </div>
                    <span>
                        <strong>1.200+ cerita</strong>
                        <br>
                        dimulai di sini
                    </span>
                </div>
            </div>
        </div>

        <div class="hero-visual" aria-label="Ilustrasi sesi berbagi waktu">
            <div class="photo-frame">
                <img src="https://images.unsplash.com/photo-1523151401668-fff9e38a48e4?crop=entropy&fit=crop&w=900&h=720&q=88" alt="Sekelompok teman berbincang santai di luar ruangan">
                <div class="photo-label">Cerita baru, Minggu sore</div>
            </div>
            <div class="floating-note trust-note">
                <span class="note-icon">
                    <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </span>
                <span>
                    <strong>Ruang aman</strong>
                    <small>Identitas & batasan terverifikasi</small>
                </span>
            </div>
            <div class="floating-note quote-note">
                <span class="quote-mark">“</span>
                <span>Rasanya seperti ngobrol dengan teman lama.</span>
            </div>
            <svg aria-hidden="true" class="hero-scribble" viewBox="0 0 120 70">
                <path d="M4 64c38-4 78-23 106-58M90 7l20-1-3 19"/>
            </svg>
        </div>
    </div>
</section>

<!-- =============================================================================
     2. TRUST STRIP
     ============================================================================= -->
<section class="trust-strip" id="keamanan">
    <div>
        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
            <path d="m9 12 2 2 4-4"/>
        </svg>
        <span>
            <strong>Identitas diverifikasi</strong>
            <small>Bukan akun anonim</small>
        </span>
    </div>
    <div>
        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 3 2.75 5.57 6.15.9-4.45 4.33 1.05 6.12L12 17.03l-5.5 2.89 1.05-6.12L3.1 9.47l6.15-.9L12 3Z"/>
        </svg>
        <span>
            <strong>Reputasi transparan</strong>
            <small>Dibangun dari pertemuan nyata</small>
        </span>
    </div>
    <div>
        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m5 12 4 4L19 6"/>
        </svg>
        <span>
            <strong>Batasan disepakati</strong>
            <small>Nyaman untuk semua pihak</small>
        </span>
    </div>
</section>

<!-- =============================================================================
     3. KATALOG & FILTER FORM
     ============================================================================= -->
<section class="catalog-section" id="katalog">
    <div class="section-heading">
        <div>
            <span class="section-kicker">Katalog penawaran waktu</span>
            <h2>Waktu yang siap dibagikan</h2>
        </div>
        <p>Orang-orang nyata, dengan keahlian dan cerita yang mungkin sedang kamu butuhkan.</p>
    </div>

    <!-- Filter Bar Form -->
    <form class="filter-bar" method="GET" action="/index.php">
        <input type="hidden" name="page" value="listings">

        <label class="search-field">
            <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"/>
                <path d="m20 20-4-4"/>
            </svg>
            <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="Cari topik, keahlian, atau kota...">
        </label>

        <label class="select-field">
            <span>Jenis sesi</span>
            <select name="session_type" onchange="this.form.submit()">
                <option value="" <?= empty($filters['session_type']) ? 'selected' : '' ?>>Semua sesi</option>
                <option value="ONLINE" <?= (($filters['session_type'] ?? '') === 'ONLINE') ? 'selected' : '' ?>>ONLINE</option>
                <option value="OFFLINE" <?= (($filters['session_type'] ?? '') === 'OFFLINE') ? 'selected' : '' ?>>OFFLINE</option>
            </select>
            <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </label>

        <label class="check-field">
            <input type="checkbox" name="is_free" value="1" <?= !empty($filters['is_free']) ? 'checked' : '' ?> onchange="this.form.submit()">
            <span class="custom-check">
                <svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m5 12 4 4L19 6"/>
                </svg>
            </span>
            Hanya yang gratis
        </label>

        <button type="submit" class="button button-primary">Terapkan filter</button>
    </form>

    <div class="result-meta">
        <span>
            <strong><?= count($listings ?? []) ?> pengalaman</strong> ditemukan
        </span>
        <span>Diperbarui hari ini</span>
    </div>

    <!-- Grid Kartu Penawaran -->
    <?php if (!empty($listings)): ?>
        <div class="listing-grid">
            <?php foreach ($listings as $item): ?>
                <?php
                    $isFree = empty($item['price']) || (float)$item['price'] == 0;
                    $sessionType = strtoupper($item['session_type'] ?? 'ONLINE');
                    $hostAvatar = !empty($item['avatar_url']) ? $item['avatar_url'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?crop=faces&fit=crop&w=160&h=160&q=80';
                    $trustScore = (int)($item['trust_score'] ?? 100);
                    $duration = (int)($item['duration_minutes'] ?? 60);
                ?>
                <article class="listing-card">
                    <div class="card-accent"></div>
                    <div class="card-topline">
                        <div class="badge-row">
                            <span class="session-badge <?= strtolower($sessionType) ?>">
                                <span class="status-dot"></span>
                                <?= htmlspecialchars($sessionType) ?>
                            </span>
                            <span class="city-label"><?= htmlspecialchars($item['location'] ?? 'Publik') ?></span>
                        </div>
                        <?php if ($isFree): ?>
                            <span class="price free">GRATIS</span>
                        <?php else: ?>
                            <span class="price">Rp <?= number_format((float)$item['price'], 0, ',', '.') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="host-row">
                        <img class="avatar" src="<?= htmlspecialchars($hostAvatar) ?>" alt="Foto <?= htmlspecialchars($item['name'] ?? 'Host') ?>">
                        <div>
                            <div class="host-name">
                                <?= htmlspecialchars($item['name'] ?? 'Host PinjamOrang') ?>
                                <span class="verified" title="Identitas terverifikasi">
                                    <svg aria-hidden="true" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m5 12 4 4L19 6"/>
                                    </svg>
                                </span>
                            </div>
                            <span class="trust-badge">
                                <svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="m12 3 2.75 5.57 6.15.9-4.45 4.33 1.05 6.12L12 17.03l-5.5 2.89 1.05-6.12L3.1 9.47l6.15-.9L12 3Z"/>
                                </svg>
                                <?= $trustScore ?> dipercaya
                            </span>
                        </div>
                    </div>

                    <div class="card-copy">
                        <h3><?= htmlspecialchars($item['title'] ?? 'Judul Sesi') ?></h3>
                        <p><?= htmlspecialchars($item['description'] ?? '') ?></p>
                    </div>

                    <div class="schedule">
                        <div>
                            <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="16" rx="3"/>
                                <path d="M16 3v4M8 3v4M3 10h18"/>
                            </svg>
                            <?= !empty($item['listing_date']) ? date('d M Y', strtotime($item['listing_date'])) : 'Jadwal fleksibel' ?>
                        </div>
                        <div>
                            <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2"/>
                            </svg>
                            <?= htmlspecialchars(substr($item['start_time'] ?? '00:00', 0, 5)) ?>–<?= htmlspecialchars(substr($item['end_time'] ?? '00:00', 0, 5)) ?> · <?= $duration ?> menit
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="slots">
                            <span>
                                <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                                Kuota <?= (int)($item['capacity'] ?? 1) ?> slot
                            </span>
                            <div class="slot-bar">
                                <span style="width: 70%;"></span>
                            </div>
                        </div>
                        <a href="/index.php?page=listing-detail&id=<?= (int)$item['id'] ?>" class="button button-outline detail-button">
                            Lihat detail
                            <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14m-5-5 5 5-5 5"/>
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Kondisi Kosong (Empty State) -->
        <div class="empty-state">
            <div class="empty-icon">
                <svg aria-hidden="true" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-4-4"/>
                </svg>
            </div>
            <h3>Belum ada waktu yang cocok</h3>
            <p>Coba gunakan kata kunci lain atau lihat semua penawaran waktu yang tersedia.</p>
            <a href="/index.php?page=listings" class="button button-outline">
                Reset pencarian
            </a>
        </div>
    <?php endif; ?>
</section>

<!-- =============================================================================
     4. CLOSING CTA BANNER
     ============================================================================= -->
<section class="closing" id="cara-kerja">
    <div class="closing-copy">
        <span class="section-kicker">Punya satu jam kosong?</span>
        <h2>
            Waktumu mungkin berarti
            <br>
            besar untuk orang lain.
        </h2>
    </div>
    <div class="closing-action">
        <p>
            Tawarkan keahlian, telinga untuk mendengar, atau sekadar teman untuk melakukan hal kecil bersama.
        </p>
        <a href="/index.php?page=create-listing" class="button button-dark">
            Tawarkan waktumu
            <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14m-5-5 5 5-5 5"/>
            </svg>
        </a>
    </div>
</section>
