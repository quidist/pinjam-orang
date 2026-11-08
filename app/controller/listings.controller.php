<?php

require_once __DIR__ . "/../model/listings.model.php";
require_once __DIR__ . "/../model/users.model.php";
require_once __DIR__ . "/auth.controller.php";

class ListingsController {
    private PDO $pdo;
    private ListingsModel $listingModel;
    private UserModel $userModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->listingModel = new ListingsModel($pdo);
        $this->userModel = new UserModel($pdo);
    }

    /**
     * 3.1. index: Menampilkan katalog penawaran aktif publik dengan filter
     */
    public function index(): void {
        $filters = [
            'keyword'      => trim($_GET['keyword'] ?? ''),
            'session_type' => trim($_GET['session_type'] ?? ''),
            'is_free'      => !empty($_GET['is_free']),
            'date'         => trim($_GET['date'] ?? '')
        ];

        $listings = $this->listingModel->getActiveListings($this->pdo, $filters);
        $pageTitle = "Jelajahi Waktu Manusia - PINJAMORANG";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/listings/catalog.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.2. detail: Menampilkan rincian penawaran, profil host, boundaries, dan booking form
     */
    public function detail(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'ID penawaran tidak valid.'];
            header("Location: /index.php?page=listings");
            exit;
        }

        $listing = $this->listingModel->getListingById($this->pdo, $id);
        if (!$listing) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Penawaran waktu tidak ditemukan atau telah dihapus.'];
            header("Location: /index.php?page=listings");
            exit;
        }

        $remainingSlots = $this->listingModel->getRemainingCapacity($this->pdo, $id);
        $pageTitle = htmlspecialchars($listing['title']) . " - PINJAMORANG";
        $extraScript = "/assets/js/booking.js";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/listings/detail.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.3. create: Menampilkan formulir pembuatan penawaran baru
     */
    public function create(): void {
        AuthController::requireAuth();

        $userId = (int)$_SESSION['user']['id'];
        $host = $this->userModel->getUserById($this->pdo, $userId);

        if ($host && (int)$host['trust_score'] < 70) {
            $_SESSION['flash_message'] = [
                'type' => 'danger', 
                'text' => 'Akun Anda dalam masa pembatasan karena Trust Score di bawah 70. Anda belum dapat menawarkan waktu.'
            ];
            header("Location: /index.php?page=my-profile");
            exit;
        }

        $pageTitle = "Buat Penawaran Waktu Baru - PINJAMORANG";
        $extraScript = "/assets/js/listing-form.js";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/listings/create.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.4. store: Memvalidasi dan menyimpan penawaran waktu baru
     */
    public function store(): void {
        AuthController::requireAuth();

        $hostId = (int)$_SESSION['user']['id'];
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $sessionType = strtoupper(trim($_POST['session_type'] ?? 'ONLINE'));
        $listingDate = trim($_POST['listing_date'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');
        $capacity = (int)($_POST['capacity'] ?? 1);
        $price = !empty($_POST['is_free']) ? 0.00 : (float)($_POST['price'] ?? 0);
        $location = trim($_POST['location'] ?? '');
        $approvalMode = strtoupper(trim($_POST['approval_mode'] ?? 'AUTOMATIC'));
        $boundaries = trim($_POST['boundaries'] ?? '');

        $errors = [];

        if (empty($title)) {
            $errors[] = "Judul penawaran wajib diisi.";
        }
        if (empty($description)) {
            $errors[] = "Deskripsi pengalaman wajib diisi.";
        }
        if (!in_array($sessionType, ['ONLINE', 'OFFLINE'], true)) {
            $errors[] = "Tipe sesi tidak valid.";
        }
        if (empty($listingDate) || $listingDate < date('Y-m-d')) {
            $errors[] = "Tanggal sesi tidak boleh di masa lalu.";
        }
        if (empty($startTime) || empty($endTime) || $endTime <= $startTime) {
            $errors[] = "Jam selesai harus lebih besar dari jam mulai.";
        }
        if ($capacity < 1) {
            $errors[] = "Kapasitas minimal 1 orang.";
        }
        if ($sessionType === 'OFFLINE' && empty($location)) {
            $errors[] = "Untuk sesi OFFLINE, sebutkan nama lokasi ruang publik yang ramai.";
        }

        // Cek Jadwal Bentrok (Anti-Overlapping Guard)
        if (empty($errors)) {
            $isOverlap = $this->listingModel->hasOverlappingListing(
                $this->pdo, 
                $hostId, 
                $listingDate, 
                $startTime, 
                $endTime
            );

            if ($isOverlap) {
                $errors[] = "Jadwal yang Anda pilih bertabrakan dengan penawaran aktif Anda yang lain.";
            }
        }

        if (!empty($errors)) {
            $oldInput = $_POST;
            $pageTitle = "Buat Penawaran Waktu Baru - PINJAMORANG";
            $extraScript = "/assets/js/listing-form.js";

            require_once __DIR__ . "/../view/layout/header.view.php";
            require_once __DIR__ . "/../view/listings/create.view.php";
            require_once __DIR__ . "/../view/layout/footer.view.php";
            return;
        }

        // Hitung durasi dalam menit
        $startSec = strtotime("$listingDate $startTime");
        $endSec = strtotime("$listingDate $endTime");
        $durationMinutes = max(15, (int)(($endSec - $startSec) / 60));

        $listingData = [
            'host_id'          => $hostId,
            'title'            => $title,
            'description'      => $description,
            'session_type'     => $sessionType,
            'listing_date'     => $listingDate,
            'start_time'       => $startTime,
            'end_time'         => $endTime,
            'duration_minutes' => $durationMinutes,
            'capacity'         => $capacity,
            'price'            => $price,
            'location'         => $location,
            'approval_mode'    => in_array($approvalMode, ['AUTOMATIC', 'MANUAL'], true) ? $approvalMode : 'AUTOMATIC',
            'boundaries'       => $boundaries,
            'status'           => 'ACTIVE'
        ];

        $newId = $this->listingModel->createListing($this->pdo, $listingData);

        if (!$newId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal menerbitkan penawaran. Silakan coba lagi.'];
            header("Location: /index.php?page=create-listing");
            exit;
        }

        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Penawaran waktu Anda berhasil diterbitkan!'];
        header("Location: /index.php?page=listing-detail&id=" . $newId);
        exit;
    }

    /**
     * 3.5. myListings: Menampilkan seluruh penawaran yang dibuat oleh host yang sedang login
     */
    public function myListings(): void {
        AuthController::requireAuth();

        $hostId = (int)$_SESSION['user']['id'];
        $myListings = $this->listingModel->getListingByHost($this->pdo, $hostId);
        $pageTitle = "Penawaran Waktu Saya - PINJAMORANG";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/listings/my_listings.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.6. cancel: Membatalkan penawaran waktu oleh host pemiliknya
     */
    public function cancel(): void {
        AuthController::requireAuth();

        $hostId = (int)$_SESSION['user']['id'];
        $listingId = (int)($_POST['listing_id'] ?? 0);

        if ($listingId <= 0) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'ID penawaran tidak valid.'];
            header("Location: /index.php?page=my-listings");
            exit;
        }

        $listing = $this->listingModel->getListingById($this->pdo, $listingId);
        if (!$listing || (int)$listing['host_id'] !== $hostId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Anda tidak memiliki hak untuk membatalkan penawaran ini.'];
            header("Location: /index.php?page=my-listings");
            exit;
        }

        if (in_array($listing['status'], ['COMPLETED', 'CANCELLED'], true)) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Penawaran ini sudah berakhir atau telah dibatalkan sebelumnya.'];
            header("Location: /index.php?page=my-listings");
            exit;
        }

        $this->listingModel->updateListingStatus($this->pdo, $listingId, 'CANCELLED');
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Penawaran waktu berhasil dibatalkan.'];
        header("Location: /index.php?page=my-listings");
        exit;
    }
}
