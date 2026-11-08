<?php
/**
 * PINJAMORANG - Authentication Controller
 * File: app/controller/auth.controller.php
 */

require_once __DIR__ . '/../model/users.model.php';

class AuthController {
    private PDO $pdo;
    private UserModel $userModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->userModel = new UserModel($pdo);
    }

    /**
     * Menampilkan formulir login (Masuk)
     */
    public function showLogin(): void {
        self::requireGuest();

        $pageTitle = "Masuk ke Akun - PINJAMORANG";
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        require_once __DIR__ . '/../view/layout/header.view.php';
        require_once __DIR__ . '/../view/auth/login.view.php';
        require_once __DIR__ . '/../view/layout/footer.view.php';
    }

    // Alias untuk kompatibilitas
    public function showLoginForm(): void {
        $this->showLogin();
    }

    /**
     * Memproses autentikasi login pengguna
     */
    public function handleLogin(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Silakan isi alamat email dan kata sandi Anda.'];
            header("Location: /index.php?page=login");
            exit;
        }

        $user = $this->userModel->getUserByEmail($this->pdo, $email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Email atau kata sandi yang Anda masukkan salah.'];
            header("Location: /index.php?page=login");
            exit;
        }

        // Regenerasi session ID untuk mencegah session fixation attack
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'          => (int)$user['id'],
            'name'        => $user['name'],
            'email'       => $user['email'],
            'trust_score' => (int)$user['trust_score']
        ];

        $_SESSION['flash_message'] = [
            'type' => 'success', 
            'text' => 'Selamat datang kembali, ' . htmlspecialchars($user['name']) . '!'
        ];

        header("Location: /index.php?page=home");
        exit;
    }

    /**
     * Menampilkan formulir registrasi akun baru
     */
    public function showRegister(): void {
        self::requireGuest();

        $pageTitle = "Daftar Akun Baru - PINJAMORANG";
        $extraScript = "/assets/js/auth.js";
        $errors = [];
        $oldInput = [];

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/auth/register.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    // Alias untuk kompatibilitas
    public function showRegisterForm(): void {
        $this->showRegister();
    }

    /**
     * Memproses pendaftaran pengguna baru
     */
    public function handleRegister(): void {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? $_POST['passwordConfirm'] ?? '';

        $errors = [];

        if (strlen($name) < 3 || strlen($name) > 100) {
            $errors[] = "Nama lengkap harus antara 3 hingga 100 karakter.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format alamat email tidak valid.";
        }

        if (strlen($password) < 6) {
            $errors[] = "Kata sandi minimal harus 6 karakter.";
        }

        if ($password !== $passwordConfirm) {
            $errors[] = "Konfirmasi kata sandi tidak cocok.";
        }

        if (empty($errors) && $this->userModel->isEmailExist($this->pdo, $email)) {
            $errors[] = "Email tersebut sudah terdaftar. Silakan masuk atau gunakan email lain.";
        }

        // Jika terdapat error validasi, tampilkan kembali formulir beserta feedback
        if (!empty($errors)) {
            $pageTitle = "Daftar Akun Baru - PINJAMORANG";
            $extraScript = "/assets/js/auth.js";
            $oldInput = [
                'name'  => $name,
                'email' => $email
            ];

            require_once __DIR__ . "/../view/layout/header.view.php";
            require_once __DIR__ . "/../view/auth/register.view.php";
            require_once __DIR__ . "/../view/layout/footer.view.php";
            return;
        }

        // Simpan pengguna baru
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $newUserId = $this->userModel->createUser($this->pdo, [
            'name'          => $name,
            'email'         => $email,
            'password_hash' => $passwordHash
        ]);

        if (!$newUserId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal mendaftarkan akun. Silakan coba kembali.'];
            header("Location: /index.php?page=register");
            exit;
        }

        $_SESSION['flash_message'] = [
            'type' => 'success', 
            'text' => 'Akun berhasil dibuat! Silakan masuk dengan kata sandi Anda.'
        ];
        header("Location: /index.php?page=login");
        exit;
    }

    /**
     * Menghapus sesi login (Logout)
     */
    public function logout(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        session_start();
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Anda telah berhasil keluar dari akun.'];
        header("Location: /index.php?page=home");
        exit;
    }

    // Alias untuk kompatibilitas
    public function handleLogout(): void {
        $this->logout();
    }

    /**
     * Middleware Guard: Mengharuskan pengguna sudah login
     */
    public static function requireAuth(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']['id'])) {
            $_SESSION['flash_message'] = [
                'type' => 'danger', 
                'text' => 'Silakan masuk ke akun terlebih dahulu untuk mengakses fitur ini.'
            ];
            header("Location: /index.php?page=login");
            exit;
        }
    }

    /**
     * Middleware Guard: Mengharuskan pengguna adalah tamu (belum login)
     */
    public static function requireGuest(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['user']['id'])) {
            header("Location: /index.php?page=home");
            exit;
        }
    }
}
