<div class="catalog-section" style="padding-top: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: end; margin-bottom: 28px;">
        <div>
            <span class="section-kicker">Jadwal & Riwayat</span>
            <h1 style="font-family: var(--font-display); font-size: 34px; color: var(--color-slate-deep); margin-top: 6px;">
                Pengalaman Saya
            </h1>
            <p style="font-size: 14px; color: var(--color-muted); margin-top: 4px;">
                Sesi penawaran waktu yang Anda ikuti sebagai peserta (*Participant*).
            </p>
        </div>
        <a href="/index.php?page=listings" class="button button-outline">
            Jelajahi Katalog Baru
        </a>
    </div>

    <!-- Segmented Tab Navigasi Status -->
    <div style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px; overflow-x: auto;">
        <?php
            $currStatus = $_GET['status'] ?? '';
            $tabs = [
                ''          => 'Semua',
                'CONFIRMED' => 'Siap Hadir (Confirmed)',
                'PENDING'   => 'Menunggu Persetujuan',
                'COMPLETED' => 'Selesai',
                'CANCELLED' => 'Dibatalkan'
            ];
        ?>
        <?php foreach ($tabs as $key => $label): ?>
            <a 
                href="/index.php?page=my-bookings<?= !empty($key) ? '&status=' . $key : '' ?>" 
                style="padding: 6px 14px; border-radius: 99px; font-size: 13px; font-weight: 600; text-decoration: none; background: <?= ($currStatus === $key) ? 'var(--color-primary)' : 'white' ?>; color: <?= ($currStatus === $key) ? 'white' : 'var(--color-slate-deep)' ?>; border: 1px solid <?= ($currStatus === $key) ? 'var(--color-primary)' : 'var(--color-border)' ?>;"
            >
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Daftar Kartu Booking -->
    <?php if (!empty($myBookings)): ?>
        <div style="display: grid; gap: 20px;">
            <?php foreach ($myBookings as $b): ?>
                <?php
                    $status = strtoupper($b['status']);
                    $statusConfig = [
                        'CONFIRMED' => ['bg' => '#ecfdf5', 'text' => '#059669', 'label' => 'Terkonfirmasi - Siap Hadir'],
                        'PENDING'   => ['bg' => '#fffbeb', 'text' => '#d97706', 'label' => 'Menunggu Konfirmasi Host'],
                        'REJECTED'  => ['bg' => '#fef2f2', 'text' => '#dc2626', 'label' => 'Permintaan Ditolak'],
                        'CANCELLED' => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => 'Dibatalkan'],
                        'COMPLETED' => ['bg' => '#eff6ff', 'text' => '#2563eb', 'label' => 'Sesi Telah Selesai']
                    ];
                    $cfg = $statusConfig[$status] ?? ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => $status];
                ?>
                <div style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px; flex-wrap: wrap;">
                        <div>
                            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                                <span class="session-badge <?= strtolower($b['session_type']) ?>">
                                    <?= htmlspecialchars($b['session_type']) ?>
                                </span>
                                <span style="font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 99px; background: <?= $cfg['bg'] ?>; color: <?= $cfg['text'] ?>;">
                                    <?= $cfg['label'] ?>
                                </span>
                            </div>
                            <h3 style="font-size: 20px; font-weight: 700; color: var(--color-slate-deep); margin-bottom: 8px;">
                                <a href="/index.php?page=listing-detail&id=<?= (int)$b['listing_id'] ?>" style="color: inherit; text-decoration: none;">
                                    <?= htmlspecialchars($b['listing_title']) ?>
                                </a>
                            </h3>
                            <div style="font-size: 13px; color: #475569; display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 12px;">
                                <span>📅 <?= date('l, d M Y', strtotime($b['listing_date'])) ?></span>
                                <span>⏰ <?= htmlspecialchars(substr($b['start_time'], 0, 5)) ?>–<?= htmlspecialchars(substr($b['end_time'], 0, 5)) ?> WIB</span>
                                <span>⏱️ <?= (int)$b['duration_minutes'] ?> Menit</span>
                            </div>

                            <!-- Info Host -->
                            <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
                                <img 
                                    src="<?= !empty($b['host_avatar']) ? htmlspecialchars($b['host_avatar']) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?crop=faces&fit=crop&w=80&h=80&q=80' ?>" 
                                    alt="Foto Host" 
                                    style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;"
                                >
                                <span style="font-size: 13px; font-weight: 600; color: var(--color-slate-deep);">
                                    Host: <?= htmlspecialchars($b['host_name'] ?? 'Host') ?>
                                </span>
                                <span class="trust-badge" style="font-size: 10px;">
                                    ⭐ <?= (int)($b['host_trust_score'] ?? 100) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Area Aksi Peserta -->
                        <div style="display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
                            <?php if ($status === 'COMPLETED'): ?>
                                <a href="/index.php?page=review&booking_id=<?= (int)$b['id'] ?>" class="button button-primary">
                                    ⭐ Beri Ulasan & Bintang
                                </a>
                            <?php elseif (in_array($status, ['PENDING', 'CONFIRMED'], true)): ?>
                                <button 
                                    type="button" 
                                    class="button btn-open-cancel-modal" 
                                    data-id="<?= (int)$b['id'] ?>" 
                                    data-title="<?= htmlspecialchars($b['listing_title']) ?>"
                                    style="color: #dc2626; border: 1px solid #fecaca; background: #fff5f5; min-height: 38px;"
                                >
                                    Batalkan Reservasi
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- KOTAK PRIVAT AKSES SESI (HANYA MUNCUL JIKA STATUS CONFIRMED) -->
                    <?php if ($status === 'CONFIRMED'): ?>
                        <div style="margin-top: 20px; padding: 18px 20px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
                            <div>
                                <strong style="font-size: 13px; color: #065f46; display: block;">
                                    🎉 Akses Sesi Resmi Telah Terbuka!
                                </strong>
                                <span style="font-size: 12px; color: #047857;">
                                    <?php if ($b['session_type'] === 'ONLINE'): ?>
                                        Tautan video meeting dapat langsung Anda akses pada jadwal yang ditentukan:
                                    <?php else: ?>
                                        Lokasi pertemuan publik: <strong><?= htmlspecialchars($b['location']) ?></strong>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php if ($b['session_type'] === 'ONLINE' && !empty($b['location'])): ?>
                                <a href="<?= htmlspecialchars($b['location']) ?>" target="_blank" rel="noopener noreferrer" class="button button-primary" style="background: #059669; border-color: #059669;">
                                    Buka Link Google Meet / Zoom &rarr;
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">🎟️</div>
            <h3>Belum Ada Riwayat Pengalaman</h3>
            <p>Anda belum memiliki jadwal reservasi sesi waktu. Temukan penawaran menarik di katalog!</p>
            <a href="/index.php?page=listings" class="button button-primary">
                Mulai Jelajahi Katalog
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- MODAL KONFIRMASI PEMBATALAN (ANTI-GHOSTING WARNING) -->
<div id="modalCancelBooking" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 18px; max-width: 480px; width: 100%; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="color: #dc2626; font-size: 32px; margin-bottom: 12px;">⚠️</div>
        <h3 style="font-size: 20px; font-weight: 700; color: var(--color-slate-deep); margin-bottom: 10px;">
            Konfirmasi Pembatalan Reservasi
        </h3>
        <p style="font-size: 13px; line-height: 1.6; color: #475569; margin-bottom: 16px;">
            Apakah Anda yakin ingin membatalkan reservasi pada sesi <strong id="modalBookingTitle"></strong>?
        </p>
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 14px; margin-bottom: 24px;">
            <strong style="color: #dc2626; font-size: 12px; display: block; margin-bottom: 4px;">Peringatan Anti-Ghosting:</strong>
            <span style="font-size: 11px; color: #991b1b; line-height: 1.5; display: block;">
                Pembatalan mendadak yang dilakukan <strong>kurang dari 2 jam</strong> sebelum waktu mulai sesi akan otomatis memotong <strong>25 poin Trust Score</strong> Anda demi menjaga komitmen waktu sesama anggota.
            </span>
        </div>
        <form id="formCancelBooking" method="POST" action="/index.php?page=cancel-booking" style="display: flex; justify-content: flex-end; gap: 12px;">
            <input type="hidden" name="booking_id" id="modalBookingId" value="">
            <button type="button" id="btnDismissCancelModal" class="button button-outline">
                Kembali
            </button>
            <button type="submit" class="button" style="background: #dc2626; color: white;">
                Ya, Tetap Batalkan
            </button>
        </form>
    </div>
</div>
