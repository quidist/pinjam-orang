<?php
/**
 * PINJAMORANG - User & Profile Model
 * File: app/model/users.model.php
 */

class UserModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * 3.1. createUser: Membuat user baru
     */
    public function createUser(PDO|array $pdoOrData, ?array $data = null): int|false {
        $userData = is_array($pdoOrData) ? $pdoOrData : ($data ?? []);

        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password_hash) 
            VALUES (:name, :email, :password_hash)
        ");

        $success = $stmt->execute([
            ':name'          => $userData['name'],
            ':email'         => $userData['email'],
            ':password_hash' => $userData['password_hash']
        ]);

        if (!$success) {
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * 3.2. getUserByEmail: Mengambil data user berdasarkan email
     */
    public function getUserByEmail(PDO|string $pdoOrEmail, ?string $email = null): array|false {
        $targetEmail = is_string($pdoOrEmail) ? $pdoOrEmail : ($email ?? '');

        $stmt = $this->pdo->prepare("
            SELECT * FROM users 
            WHERE email = :email
        ");

        $stmt->execute([':email' => $targetEmail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3.3. getUserById: Mengambil profil user dengan opsi sembunyikan password
     */
    public function getUserById(PDO|int $pdoOrId, int|bool $idOrSafe = true, bool $safe = true): array|false {
        if ($pdoOrId instanceof PDO) {
            $id = (int)$idOrSafe;
            $safeMode = $safe;
        } else {
            $id = (int)$pdoOrId;
            $safeMode = is_bool($idOrSafe) ? $idOrSafe : true;
        }

        if ($safeMode) {
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
    public function updateProfile(PDO|int $pdoOrId, int|array $idOrData = [], ?array $data = null): bool {
        if ($pdoOrId instanceof PDO) {
            $id = (int)$idOrData;
            $updateData = $data ?? [];
        } else {
            $id = (int)$pdoOrId;
            $updateData = is_array($idOrData) ? $idOrData : [];
        }

        $stmt = $this->pdo->prepare("
            UPDATE users
            SET name = :name, phone = :phone, bio = :bio, avatar_url = :avatar_url
            WHERE id = :id
        ");

        return $stmt->execute([
            ':name'       => $updateData['name'],
            ':phone'      => $updateData['phone'] ?? null,
            ':bio'        => $updateData['bio'] ?? null,
            ':avatar_url' => $updateData['avatar_url'] ?? null,
            ':id'         => $id
        ]);
    }

    /**
     * 3.5. updatePassword: Mengganti kata sandi
     */
    public function updatePassword(PDO|int $pdoOrId, int|string $idOrHash = '', ?string $passwordHash = null): bool {
        if ($pdoOrId instanceof PDO) {
            $id = (int)$idOrHash;
            $hash = $passwordHash ?? '';
        } else {
            $id = (int)$pdoOrId;
            $hash = is_string($idOrHash) ? $idOrHash : '';
        }

        $stmt = $this->pdo->prepare("
            UPDATE users
            SET password_hash = :password_hash
            WHERE id = :id
        ");

        return $stmt->execute([
            ':password_hash' => $hash,
            ':id'            => $id
        ]);
    }

    /**
     * 3.6. updateTrustScore: Menambah atau mengurangi reputasi Trust Score (0-100)
     */
    public function updateTrustScore(PDO|int $pdoOrId, int $idOrDelta = 0, ?int $pointDelta = null): bool {
        if ($pdoOrId instanceof PDO) {
            $id = (int)$idOrDelta;
            $delta = $pointDelta ?? 0;
        } else {
            $id = (int)$pdoOrId;
            $delta = $idOrDelta;
        }

        $stmt = $this->pdo->prepare("
            UPDATE users
            SET trust_score = LEAST(100, GREATEST(0, trust_score + :delta))
            WHERE id = :id
        ");

        return $stmt->execute([
            ':delta' => $delta,
            ':id'    => $id
        ]);
    }

    /**
     * 3.7. isEmailExist: Cek apakah email sudah terdaftar
     */
    public function isEmailExist(PDO|string $pdoOrEmail, ?string $email = null): bool {
        $targetEmail = is_string($pdoOrEmail) ? $pdoOrEmail : ($email ?? '');

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM users
            WHERE email = :email
        ");

        $stmt->execute([':email' => $targetEmail]);
        return (int)$stmt->fetchColumn() > 0;
    }
}

