<?php
/**
 * PINJAMORANG - Main Application Front Router
 * File: index.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/app/config/database.php";

$page = $_GET['page'] ?? 'home';
$method = $_SERVER['REQUEST_METHOD'];

switch ($page) {
    // -------------------------------------------------------------------------
    // Modul Listings & Katalog Penawaran
    // -------------------------------------------------------------------------
    case 'home':
    case 'listings':
        require_once __DIR__ . "/app/controller/listings.controller.php";
        $controller = new ListingsController($pdo);
        $controller->index();
        break;

    case 'listing-detail':
        require_once __DIR__ . "/app/controller/listings.controller.php";
        $controller = new ListingsController($pdo);
        $controller->detail();
        break;

    case 'create-listing':
        require_once __DIR__ . "/app/controller/listings.controller.php";
        $controller = new ListingsController($pdo);
        if ($method === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;

    case 'my-listings':
        require_once __DIR__ . "/app/controller/listings.controller.php";
        $controller = new ListingsController($pdo);
        $controller->myListings();
        break;

    case 'cancel-listing':
        require_once __DIR__ . "/app/controller/listings.controller.php";
        $controller = new ListingsController($pdo);
        $controller->cancel();
        break;

    // -------------------------------------------------------------------------
    // Modul Pemesanan Slot Waktu (Bookings)
    // -------------------------------------------------------------------------
    case 'book':
        require_once __DIR__ . "/app/controller/bookings.controller.php";
        $controller = new BookingsController($pdo);
        $controller->book();
        break;

    case 'my-bookings':
        require_once __DIR__ . "/app/controller/bookings.controller.php";
        $controller = new BookingsController($pdo);
        $controller->myBookings();
        break;

    case 'host-requests':
        require_once __DIR__ . "/app/controller/bookings.controller.php";
        $controller = new BookingsController($pdo);
        $controller->hostRequests();
        break;

    case 'approve-booking':
        require_once __DIR__ . "/app/controller/bookings.controller.php";
        $controller = new BookingsController($pdo);
        $controller->approve();
        break;

    case 'reject-booking':
        require_once __DIR__ . "/app/controller/bookings.controller.php";
        $controller = new BookingsController($pdo);
        $controller->reject();
        break;

    case 'cancel-booking':
        require_once __DIR__ . "/app/controller/bookings.controller.php";
        $controller = new BookingsController($pdo);
        $controller->cancel();
        break;

    // -------------------------------------------------------------------------
    // Modul Pembayaran & Transaksi (Payments - Fase 2)
    // -------------------------------------------------------------------------
    case 'checkout':
        require_once __DIR__ . "/app/controller/payments.controller.php";
        $controller = new PaymentsController($pdo);
        $controller->showCheckout();
        break;

    case 'confirm-payment':
        require_once __DIR__ . "/app/controller/payments.controller.php";
        $controller = new PaymentsController($pdo);
        $controller->confirmPayment();
        break;

    case 'payment-receipt':
        require_once __DIR__ . "/app/controller/payments.controller.php";
        $controller = new PaymentsController($pdo);
        $controller->receipt();
        break;

    // -------------------------------------------------------------------------
    // Modul Autentikasi Pengguna
    // -------------------------------------------------------------------------
    case 'login':
        require_once __DIR__ . "/app/controller/auth.controller.php";
        $controller = new AuthController($pdo);
        if ($method === 'POST') {
            $controller->handleLogin();
        } else {
            $controller->showLogin();
        }
        break;

    case 'register':
        require_once __DIR__ . "/app/controller/auth.controller.php";
        $controller = new AuthController($pdo);
        if ($method === 'POST') {
            $controller->handleRegister();
        } else {
            $controller->showRegister();
        }
        break;

    case 'logout':
        require_once __DIR__ . "/app/controller/auth.controller.php";
        $controller = new AuthController($pdo);
        $controller->logout();
        break;

    // -------------------------------------------------------------------------
    // Modul Profil Pengguna & Reputasi (Users)
    // -------------------------------------------------------------------------
    case 'profile':
        require_once __DIR__ . "/app/controller/users.controller.php";
        $controller = new UsersController($pdo);
        $controller->showProfile();
        break;

    case 'my-profile':
        require_once __DIR__ . "/app/controller/users.controller.php";
        $controller = new UsersController($pdo);
        $controller->showMyProfile();
        break;

    case 'update-profile':
        require_once __DIR__ . "/app/controller/users.controller.php";
        $controller = new UsersController($pdo);
        $controller->updateProfile();
        break;

    case 'change-password':
        require_once __DIR__ . "/app/controller/users.controller.php";
        $controller = new UsersController($pdo);
        $controller->changePassword();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . "/app/view/layout/header.view.php";
        echo "<div class='catalog-section' style='text-align: center; padding: 100px 20px;'>";
        echo "<h1 style='font-family: var(--font-display); font-size: 38px;'>404 - Halaman Tidak Ditemukan</h1>";
        echo "<p style='color: var(--color-muted); margin: 12px 0 24px;'>Halaman yang Anda tuju tidak ada atau telah dipindahkan.</p>";
        echo "<a href='/index.php?page=home' class='button button-primary'>Kembali ke Beranda</a>";
        echo "</div>";
        require_once __DIR__ . "/app/view/layout/footer.view.php";
        break;
}
