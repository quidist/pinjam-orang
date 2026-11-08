<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'PINJAMORANG - Borrow a Human. Return with a Story.') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="app-shell">
    <header class="navbar">
        <div class="nav-inner">
            <a aria-label="PINJAMORANG beranda" class="brand" href="/index.php?page=home" style="flex-shrink: 0;">
                <span class="brand-mark">
                    <span>P</span>
                </span>
                <span class="brand-copy">
                    <strong>PINJAMORANG</strong>
                    <small>Borrow a human. Return with a story.</small>
                </span>
            </a>

            <!-- Navigasi Tengah -->
            <nav class="desktop-nav" aria-label="Navigasi utama">
                <?php $currentPage = $_GET['page'] ?? 'home'; ?>
                <a class="<?= in_array($currentPage, ['home', 'listings', 'listing-detail'], true) ? 'active' : '' ?>" href="/index.php?page=listings">
                    Katalog
                </a>
                <?php if (isset($_SESSION['user']['id'])): ?>
                    <a class="<?= $currentPage === 'my-bookings' ? 'active' : '' ?>" href="/index.php?page=my-bookings" title="Sesi yang saya ikuti sebagai peserta">
                        Pengalaman Saya
                    </a>
                    <a class="<?= $currentPage === 'my-listings' ? 'active' : '' ?>" href="/index.php?page=my-listings" title="Kelola penawaran waktu milik saya">
                        Penawaran Saya
                    </a>
                    <a class="<?= $currentPage === 'host-requests' ? 'active' : '' ?>" href="/index.php?page=host-requests" title="Permintaan peserta yang mendaftar">
                        Permintaan Masuk
                    </a>
                <?php else: ?>
                    <a href="/index.php?page=listings#cara-kerja">Cara kerjanya</a>
                    <a href="/index.php?page=listings#keamanan">Keamanan</a>
                <?php endif; ?>
            </nav>

            <!-- Aksi Kanan & Profil -->
            <div class="desktop-actions">
                <?php if (isset($_SESSION['user']['id'])): ?>
                    <a href="/index.php?page=create-listing" class="button button-primary" style="flex-shrink: 0;">
                        + Tawarkan Waktu
                    </a>
                    <div class="user-pill">
                        <a href="/index.php?page=my-profile" class="user-pill-name" title="Buka profil saya">
                            👤 <?= htmlspecialchars(mb_strimwidth($_SESSION['user']['name'] ?? 'Profil', 0, 16, '...')) ?>
                        </a>
                        <span class="user-pill-divider"></span>
                        <a href="/index.php?page=logout" class="user-pill-logout" title="Keluar dari akun">
                            Keluar
                        </a>
                    </div>
                <?php else: ?>
                    <a class="login-link" href="/index.php?page=login">Masuk</a>
                    <a href="/index.php?page=register" class="button button-primary">
                        Daftar sekarang
                        <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14m-5-5 5 5-5 5"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>

            <button id="btnMobileMenu" class="menu-button button button-quiet" type="button" aria-label="Buka menu navigasi">
                <svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>

        <!-- Menu Mobile Drawer -->
        <div id="mobileNavDrawer" class="mobile-nav" style="display: none;">
            <div class="mobile-nav-group">
                <span class="mobile-nav-title">Jelajah</span>
                <a href="/index.php?page=listings">🔍 Jelajahi Katalog Waktu</a>
                <a href="/index.php?page=listings#cara-kerja">📖 Cara Kerjanya</a>
                <a href="/index.php?page=listings#keamanan">🛡️ Panduan Keamanan</a>
            </div>

            <?php if (isset($_SESSION['user']['id'])): ?>
                <div class="mobile-nav-group">
                    <span class="mobile-nav-title">Aktivitas Saya</span>
                    <a href="/index.php?page=my-bookings">🎒 Pengalaman Saya (Peserta)</a>
                    <a href="/index.php?page=my-listings">📅 Penawaran Saya (Host)</a>
                    <a href="/index.php?page=host-requests">👥 Permintaan Masuk Peserta</a>
                </div>

                <div class="mobile-nav-group" style="padding-top: 6px;">
                    <a href="/index.php?page=create-listing" class="button button-primary" style="width: 100%; justify-content: center; min-height: 44px;">
                        + Tawarkan Waktu Baru
                    </a>
                </div>

                <div class="mobile-nav-group" style="border-top: 1px solid var(--color-border); padding-top: 14px;">
                    <span class="mobile-nav-title">Akun Anda</span>
                    <a href="/index.php?page=my-profile">⚙️ Profil & Reputasi (<?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>)</a>
                    <a href="/index.php?page=logout" style="color: #dc2626;">🚪 Keluar dari Akun</a>
                </div>
            <?php else: ?>
                <div class="mobile-nav-group" style="border-top: 1px solid var(--color-border); padding-top: 14px;">
                    <a href="/index.php?page=login" class="button button-outline" style="width: 100%; justify-content: center; min-height: 42px; margin-bottom: 8px;">
                        Masuk ke Akun
                    </a>
                    <a href="/index.php?page=register" class="button button-primary" style="width: 100%; justify-content: center; min-height: 42px;">
                        Daftar Sekarang
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Penampung Flash Message Notifikasi -->
    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div id="flashAlertContainer" class="flash-alert-container">
            <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_message']['type'] ?? 'success') ?>">
                <span><?= htmlspecialchars($_SESSION['flash_message']['text'] ?? '') ?></span>
                <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
            </div>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <main class="main-container">
