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
            <a aria-label="PINJAMORANG beranda" class="brand" href="/index.php?page=home">
                <span class="brand-mark">
                    <span>P</span>
                </span>
                <span class="brand-copy">
                    <strong>PINJAMORANG</strong>
                    <small>Borrow a human. Return with a story.</small>
                </span>
            </a>

            <nav class="desktop-nav" aria-label="Navigasi utama">
                <a class="<?= (($_GET['page'] ?? 'home') === 'listings' || ($_GET['page'] ?? '') === 'home') ? 'active' : '' ?>" href="/index.php?page=listings">
                    Jelajahi katalog
                </a>
                <a href="/index.php?page=listings#cara-kerja">Cara kerjanya</a>
                <a href="/index.php?page=listings#keamanan">Keamanan</a>
            </nav>

            <div class="desktop-actions">
                <?php if (isset($_SESSION['user']['id'])): ?>
                    <a href="/index.php?page=my-bookings" class="nav-link-subtle">Pengalaman Saya</a>
                    <a href="/index.php?page=create-listing" class="button button-primary">
                        + Tawarkan Waktu
                    </a>
                    <a href="/index.php?page=my-profile" class="user-greeting" title="Buka profil saya">
                        👤 <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Profil') ?>
                    </a>
                    <a href="/index.php?page=logout" class="nav-link-subtle" title="Keluar dari akun">Keluar</a>
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
            <a href="/index.php?page=listings">Jelajahi katalog</a>
            <a href="/index.php?page=listings#cara-kerja">Cara kerjanya</a>
            <a href="/index.php?page=listings#keamanan">Keamanan</a>
            <?php if (isset($_SESSION['user']['id'])): ?>
                <a href="/index.php?page=my-bookings">Pengalaman Saya</a>
                <a href="/index.php?page=create-listing">+ Tawarkan Waktu</a>
                <a href="/index.php?page=my-profile">Profil (<?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>)</a>
                <a href="/index.php?page=logout">Keluar</a>
            <?php else: ?>
                <a href="/index.php?page=login">Masuk ke Akun</a>
                <a href="/index.php?page=register" class="button button-primary">Daftar sekarang</a>
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
