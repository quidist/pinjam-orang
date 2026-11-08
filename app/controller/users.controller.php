<?php

require_once __DIR__ . "/../model/users.model.php";
require_once __DIR__ . "/../model/reviews.model.php";
require_once __DIR__ . "/auth.controller.php";

class UsersController {
    private PDO $pdo;
    private UserModel $userModel;
    private ReviewsModel $reviewModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->userModel = new UserModel($pdo);
        $this->reviewModel = new ReviewsModel($pdo);
    }

    /**
     * 3.1. showProfile: Menampilkan profil publik & reputasi pengguna (Privacy-First)
     */
    public function showProfile(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'ID pengguna tidak valid.'];
            header("Location: /index.php?page=home");
            exit;
        }

        $user = $this->userModel->getUserById($this->pdo, $id, safe: true);
        if (!$user) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Profil pengguna tidak ditemukan.'];
            header("Location: /index.php?page=home");
            exit;
        }

        // Privacy Guard: Pastikan nomor telepon tidak pernah dibagikan ke publik
        unset($user['phone']);

        $reviews = $this->reviewModel->getReviewsByUserId($this->pdo, $id);
        $avgRating = $this->reviewModel->getUserAverageRating($this->pdo, $id);
        $pageTitle = "Profil " . htmlspecialchars($user['name']) . " - PINJAMORANG";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/users/profile.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.2. showMyProfile: Menampilkan dashboard pengaturan profil akun sendiri
     */
    public function showMyProfile(): void {
        AuthController::requireAuth();

        $currentUserId = (int)$_SESSION['user']['id'];
        $user = $this->userModel->getUserById($this->pdo, $currentUserId, safe: true);
        $pageTitle = "Pengaturan Profil Saya - PINJAMORANG";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/users/my_profile.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.3. updateProfile: Menyimpan pembaruan informasi profil akun
     */
    public function updateProfile(): void {
        AuthController::requireAuth();

        $currentUserId = (int)$_SESSION['user']['id'];
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $avatarUrl = trim($_POST['avatar_url'] ?? '');

        if (strlen($name) < 3 || strlen($name) > 100) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Nama harus antara 3 hingga 100 karakter.'];
            header("Location: /index.php?page=my-profile");
            exit;
        }

        if (strlen($bio) > 500) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Bio profil maksimal 500 karakter.'];
            header("Location: /index.php?page=my-profile");
            exit;
        }

        $this->userModel->updateProfile($this->pdo, $currentUserId, [
            'name'       => $name,
            'phone'      => !empty($phone) ? $phone : null,
            'bio'        => !empty($bio) ? $bio : null,
            'avatar_url' => !empty($avatarUrl) ? $avatarUrl : null
        ]);

        $_SESSION['user']['name'] = $name;
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Profil Anda berhasil diperbarui!'];
        header("Location: /index.php?page=my-profile");
        exit;
    }

    /**
     * 3.4. changePassword: Mengubah kata sandi mandiri
     */
    public function changePassword(): void {
        AuthController::requireAuth();

        $currentUserId = (int)$_SESSION['user']['id'];
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_new_password'] ?? '';

        $user = $this->userModel->getUserById($this->pdo, $currentUserId, safe: false);
        if (!$user || !password_verify($oldPassword, $user['password_hash'])) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Kata sandi lama yang Anda masukkan salah.'];
            header("Location: /index.php?page=my-profile");
            exit;
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Kata sandi baru minimal harus 6 karakter.'];
            header("Location: /index.php?page=my-profile");
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Konfirmasi kata sandi baru tidak cocok.'];
            header("Location: /index.php?page=my-profile");
            exit;
        }

        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->userModel->updatePassword($this->pdo, $currentUserId, $newHash);

        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Kata sandi Anda berhasil diperbarui!'];
        header("Location: /index.php?page=my-profile");
        exit;
    }
}
