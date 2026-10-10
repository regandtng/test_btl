<?php
namespace App\Models;

use Core\Model;
use PDO;

class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPhone($phone) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, phone, email, password) VALUES (?, ?, ?, ?)");
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $ok = $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['email'],
            $password_hash
        ]);
        return $ok ? (int) $this->db->getConnection()->lastInsertId() : false;
    }

    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
