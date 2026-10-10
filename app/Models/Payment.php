<?php
namespace App\Models;

use Core\Model;
use PDO;

class Payment extends Model {
    protected $table = 'payments';

    public function findByBooking($bookingId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPayment($data) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (booking_id, method, amount, status) 
            VALUES (?, ?, ?, 'pending')
        ");
        $stmt->execute([
            $data['booking_id'],
            $data['method'],
            $data['amount']
        ]);
        return $this->db->lastInsertId();
    }

    public function markAsPaid($id, $transactionId) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET status = 'paid', transaction_id = ?, paid_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        return $stmt->execute([$transactionId, $id]);
    }
}
