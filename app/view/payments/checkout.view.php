<div class="catalog-section" style="padding-top: 40px; max-width: 760px; margin: 0 auto;">
    <div style="font-size: 12px; color: var(--color-muted); margin-bottom: 20px;">
        <a href="/index.php?page=my-bookings" style="color: var(--color-primary); font-weight: 600;">Pengalaman Saya</a>
        <span style="margin: 0 8px;">/</span>
        <span>Checkout Pembayaran</span>
    </div>

    <div style="margin-bottom: 28px;">
        <span class="section-kicker">Tagihan Reservasi</span>
        <h1 style="font-family: var(--font-display); font-size: 34px; color: var(--color-slate-deep); margin-top: 6px;">
            Selesaikan Pembayaran Sesi
        </h1>
        <p style="font-size: 14px; color: var(--color-muted); margin-top: 4px;">
            Selesaikan transfer untuk mengamankan slot waktu Anda secara resmi.
        </p>
    </div>

    <!-- KARTU RINGKASAN TAGIHAN -->
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04); margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; padding-bottom: 18px; border-bottom: 1px dashed var(--color-border); margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: var(--color-muted); font-weight: 700;">Nomor Invoice</span>
                <div style="font-size: 16px; font-weight: 800; color: var(--color-slate-deep); font-family: monospace;">
                    <?= htmlspecialchars($payment['transaction_ref']) ?>
                </div>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 11px; text-transform: uppercase; color: var(--color-muted); font-weight: 700;">Total Tagihan</span>
                <div style="font-size: 26px; font-weight: 800; color: var(--color-primary);">
                    Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?>
                </div>
            </div>
        </div>

        <div style="font-size: 14px; color: var(--color-slate-deep); margin-bottom: 8px;">
            <strong><?= htmlspecialchars($booking['listing_title']) ?></strong>
        </div>
        <div style="font-size: 12px; color: var(--color-muted); display: flex; gap: 16px; flex-wrap: wrap;">
            <span>Host: <strong><?= htmlspecialchars($booking['host_name'] ?? 'Host') ?></strong></span>
            <span>Jadwal: <strong><?= date('d M Y', strtotime($booking['listing_date'])) ?> • <?= htmlspecialchars(substr($booking['start_time'], 0, 5)) ?> WIB</strong></span>
            <span>Jumlah: <strong><?= (int)$booking['slot_count'] ?> Slot Kursi</strong></span>
        </div>
    </div>

    <!-- METODE PEMBAYARAN & INSTRUKSI -->
    <form method="POST" action="/index.php?page=confirm-payment" style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
        <input type="hidden" name="booking_id" value="<?= (int)$booking['id'] ?>">

        <h3 style="font-size: 16px; font-weight: 700; color: var(--color-slate-deep); margin-bottom: 16px;">
            Pilih Metode Pembayaran
        </h3>

        <!-- Opsi 1: QRIS Instant -->
        <label style="border: 2px solid var(--color-primary); border-radius: 12px; padding: 18px; display: flex; gap: 14px; align-items: start; margin-bottom: 16px; cursor: pointer; background: #fffaf6;">
            <input type="radio" name="payment_method" value="QRIS" checked style="margin-top: 4px;">
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong style="font-size: 14px; color: var(--color-slate-deep);">QRIS (Semua E-Wallet & Mobile Banking)</strong>
                    <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 99px; background: #ecfdf5; color: #059669;">INSTAN</span>
                </div>
                <p style="font-size: 12px; color: var(--color-muted); margin: 6px 0 12px;">
                    Pindai kode QR menggunakan GoPay, OVO, Dana, BCA Mobile, atau aplikasi bank apa pun.
                </p>
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 100px; height: 100px; background: white; border: 1px solid #cbd5e1; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #64748b; font-weight: 700; text-align: center; padding: 8px;">
                        [QRIS Mock Code]
                    </div>
                    <small style="font-size: 11px; color: #64748b;">
                        Simulasi mode pengujian sandbox. Tekan tombol konfirmasi di bawah setelah meninjau nominal.
                    </small>
                </div>
            </div>
        </label>

        <!-- Opsi 2: Transfer Virtual Account -->
        <label style="border: 1px solid var(--color-border); border-radius: 12px; padding: 16px; display: flex; gap: 14px; align-items: start; margin-bottom: 24px; cursor: pointer;">
            <input type="radio" name="payment_method" value="BANK_TRANSFER" style="margin-top: 4px;">
            <div>
                <strong style="font-size: 14px; color: var(--color-slate-deep);">Transfer Bank / Virtual Account</strong>
                <p style="font-size: 12px; color: var(--color-muted); margin: 4px 0 0;">
                    Nomor VA: <span style="font-family: monospace; font-weight: 700; color: var(--color-slate-deep);">8801 9283 7481 9201</span> (BCA / Mandiri)
                </p>
            </div>
        </label>

        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 16px; margin-bottom: 24px; font-size: 12px; color: #b45309; display: flex; align-items: center; gap: 8px;">
            <span>⏱️</span>
            <span>Silakan selesaikan transaksi dalam waktu <strong>15 menit</strong> agar reservasi tidak kedaluwarsa.</span>
        </div>

        <button type="submit" class="button button-primary" style="width: 100%; min-height: 48px; font-size: 14px;">
            Saya Sudah Bayar (Konfirmasi Pelunasan)
        </button>
    </form>
</div>
