<?php
namespace App\Models;

use Core\Model;
use PDO;

class Branch extends Model {
    protected $table = 'branches';

    public function findActive() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY city, name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCity($city) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE city = ? AND is_active = 1");
        $stmt->execute([$city]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWithCourtsCount() {
        $stmt = $this->db->query("
            SELECT b.*, COUNT(c.id) as actual_courts_count 
            FROM {$this->table} b 
            LEFT JOIN courts c ON b.id = c.branch_id AND c.status = 'active'
            WHERE b.is_active = 1 
            GROUP BY b.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeatured($limit = 6) {
        $limit = (int) $limit;
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY id LIMIT {$limit}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCities() {
        $stmt = $this->db->query("SELECT DISTINCT city FROM {$this->table} WHERE is_active = 1 ORDER BY city");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function search($query) {
        $search = "%{$query}%";
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE is_active = 1 AND (name LIKE ? OR address LIKE ? OR city LIKE ?)");
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
