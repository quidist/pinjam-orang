<?php

class UserModel{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function createuser(PDO $pdo, array $data): int | false{
        $stmt = $this->pdo->prepare("
        INSERT INTO users (name, email, password_hash) 
        VALUES (:name, :email, :password_hash)");

        $create = $stmt->execute([
            ':nama' => $data['nama'],
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash']
        ]);

        if(!$create){
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    public function getUserByEmail(PDO $pdo, string $email): array | false{
        $stmt = $this->pdo->prepare("
        SELECT * WHERE
        email = :email");

        $getUserEmail = $stmt->execute([
            ':email' => $email
        ]);

        if(!$getUser){
            return false;
        }

        return (array) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById(PDO $pdo, int $id, bool $safe = true ): array | false{
        if ($safe === true){
            $query = "SELECT id, name, email, phone, bio, avatar_url, trust_score, is_verified, created_at FROM users WHERE id = :id";
        }else {
            $query = "SELECT * FROM users WHERE id = :id";
        }

        $stmt = $this->pdo->prepare($query);

        if (!$stmt){
            return false;
        }

        $getUserId = $stmt->execute([
            ':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile(PDO $pdo, int $id, array $data): bool{
        $stmt = $this->pdo->prepare("
        UPDATE users
        SET name = :data, phone = :data, bio = :bio, avatar_url = :avatar_url
        WHERE id = :id");

        $updateUser = $stmt->execute([
            ':data' => $data, 
            ':data' => $id]);

        if (!$updateUser){
            return false;
        }

        return true;
    }

    public function updatePassword(PDO $pdo, int $id, string $password_hash): bool{
        $stmt = $this->pdo->prepare("
        UPDATE FROM users
        SET password_hash = :password_hash
        WHERE id = :id");

        $updatePass = $this->execute([
            ':password_hash' => $password_hash, 
            ':id' => $id]);

        if (!$updatePass){
            return false;
        }

        return true;
    }

    public function updateTrustScore(PDO $PDO, int $id, int $pointDelta): bool{
        $stmt = $this->pdo->prepare("
        UPDATE FROM users
        SET trust_score = LEAST(100, GREATEST(0, :trust_score + :point_delta))
        WHERE id = :id");

        $updateScore = $stmt->execute([
            ':point_delta' => $point_delta,
            ':id' => $id
        ]);

        if (!$update_score){
            return false;
        }

        return true; 
    }

    public function isEmailExist(PDO $pdo, string $email): bool{
        $stmt = $this->pdo->prepare("
        SELECT * FROM users
        WHERE email = :email");

        $isExist = $stmt->execute([
            ':email' => $email
        ]);

        if(!$isExist){
            return false;
        }

        return true;
    }

}
