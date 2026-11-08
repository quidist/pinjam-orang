<?php

class UserModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * 3.1. createUser: Membuat user baru
     */
    public function createUser(PDO $pdo, array $data): int|false {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password_hash) 
            VALUES (:name, :email, :password_hash)
        ");

        $success = $stmt->execute([
            ':name'          => $data['name'],
            ':email'         => $data['email'],
            ':password_hash' => $data['password_hash']
        ]);

        if (!$success) {
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * 3.2. getUserByEmail: Mengambil data user berdasarkan email
     */
    public function getUserByEmail(PDO $pdo, string $email): array|false {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users 
            WHERE email = :email
        ");

        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3.3. getUserById: Mengambil profil user dengan opsi sembunyikan password
     */
    public function getUserById(PDO $pdo, int $id, bool $safe = true): array|false {
        if ($safe) {
            $query = "SELECT id, name, email, phone, bio, avatar_url, trust_score, is_verified, created_at FROM users WHERE id = :id";
        } else {
            $query = "SELECT * FROM users WHERE id = :id";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3.4. updateProfile: Memperbarui nama, telepon, bio, avatar
     */
    public function updateProfile(PDO $pdo, int $id, array $data): bool {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET name = :name, phone = :phone, bio = :bio, avatar_url = :avatar_url
            WHERE id = :id
        ");

        return $stmt->execute([
            ':name'       => $data['name'],
            ':phone'      => $data['phone'] ?? null,
            ':bio'        => $data['bio'] ?? null,
            ':avatar_url' => $data['avatar_url'] ?? null,
            ':id'         => $id
        ]);
    }

    /**
     * 3.5. updatePassword: Mengganti kata sandi
     */
    public function updatePassword(PDO $pdo, int $id, string $passwordHash): bool {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET password_hash = :password_hash
            WHERE id = :id
        ");

        return $stmt->execute([
            ':password_hash' => $passwordHash,
            ':id'            => $id
        ]);
    }

    /**
     * 3.6. updateTrustScore: Menambah atau mengurangi reputasi Trust Score (0-100)
     */
    public function updateTrustScore(PDO $pdo, int $id, int $pointDelta): bool {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET trust_score = LEAST(100, GREATEST(0, trust_score + :delta))
            WHERE id = :id
        ");

        return $stmt->execute([
            ':delta' => $pointDelta,
            ':id'    => $id
        ]);
    }

    /**
     * 3.7. isEmailExist: Cek apakah email sudah terdaftar
     */
    public function isEmailExist(PDO $pdo, string $email): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM users
            WHERE email = :email
        ");

        $stmt->execute([':email' => $email]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
