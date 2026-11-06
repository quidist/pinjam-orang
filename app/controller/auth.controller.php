<?php

require_once __DIR__  . '/../model/users.model.php';

class AuthController{
    private PDO $pdo;
    private UserModel $userModel;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        $this->userModel = $userModel;
    }

    public function showLoginForm():void {
        if (isset($_SESSION['user']['id'])){
            header("Location: /index.php?page=home");
            exit;
        }

        $pageTitle = "Masuk - PinjamOrang";
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        require_once __DIR__ . '../view/layout/header.view.php';
        require_once __DIR__ . '../view/auth/login.view/php';
        require_once __DIR__ . '../view/layout/footer.view.php'; 
    }

    public function handleLogin(): void{
        $email = trim($_POST('email') ?? '');
        $password = $_POST('password') ?? '';
        
        $user = $this->userModel->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])){
            $_SESSION['flash_error'] = "Email atau kata sandi yang anda masukkan salah.";

            header("Location: /index/php?page=login");
            exit;
        }else{
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'trust_score' => $user['trust_score']
            ];

            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Selamat Datang Kembali, ' . $user['name'] . '!'];

            header("Location: /index.php?page=home");
            exit;
        }
    }

    public function showRegisterForm(): void{
        if (isset($_SESSION['user']['id'])){
            header("Location: /index.php?page=home");
            exit;
        }

        $pageTitle = "Daftar Akun Baru - PINJAMORANG";

        $extraScript = "/assets/js/auth.js";

        require_once __DIR__ . "../view/layout/header.view.php";
        require_once __DIR__ . "../view/auth/register.view.php";
        require_once __DIR__ . "../view/layout/footer.view.php";
    }

    public function handleRegister(): void{
        $name = $_POST['name'] ?? '';
        $email = $_POST('email') ?? '';
        $password = $__POST('password') ?? '';
        $passwordConfirm = $_POST('passwordConfirm') ?? '';

        if (strlen($name) < 3 || strlen($name) > 100){
            $error[] = "Nama minimal 3 karakter dan maksimal 100 karakter.";
        }

        if (strlen($password) < 6){
            $error[] = "Sandi minimal 6 karakter.";
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE){
            $error[] = "Email tidak valid.";
        }

        if (($password === $passwordConfirm) === FALSE){
            $error[] = "Password tidak sama."; 
        }

        if ($this->userModel->isEmailExist($email)){
            $error[] = "Email sudah terdaftar";
        }

        $oldInput = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirm' => $passwordConfirm
        ];

        if (!empty($error)){
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $newUserId = $this->userModel->createUser([
                'name' => $name,
                'email' => $email,
                'password_hash' => $passwordHash
            ]);

            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Akun berhasil dibuat! Silahkan masuk.'];
            header("Location: /index.php?page=login");
            exit;
        }
    }

    public function handleLogout(): void{
        $_SESSION = [];
        session_destroy();
        header("Location: /index.php?page=home"); 
    }

    public function requireAuth(): void {
        if (!isset($_SESSION['user']['id'])){
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Silahkan masuk terlebih dahulu untuk mengakses fitur ini.'];
            header("Location: /index.php?page=home");
            exit;
        }
    }
    public function requireGuest(): void{
        if(isset($_SESSION['user']['id'])){
            header("Location: /index.php?page=home");
            exit;
        }
    }


}