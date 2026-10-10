<?php
namespace App\Models;

use Core\Model;
use PDO;

class Court extends Model {
    protected $table = 'courts';

    public function findByBranch($branchId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE branch_id = ? AND status = 'active' ORDER BY name");
        $stmt->execute([$branchId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAvailable($branchId, $date, $startTime, $endTime) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE branch_id = ? AND status = 'active' 
            AND id NOT IN (
                SELECT court_id FROM bookings 
                WHERE branch_id = ? AND booking_date = ? AND status IN ('pending', 'confirmed')
                AND ((start_time < ? AND end_time > ?) OR (start_time >= ? AND start_time < ?))
            )
        ");
        $stmt->execute([$branchId, $branchId, $date, $endTime, $startTime, $startTime, $endTime]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailabilityGrid($branchId, $date) {
        $courts = $this->findByBranch($branchId);
        
        $slots = [];
        for ($i = 5; $i < 22; $i++) {
            $slots[] = sprintf("%02d:00:00", $i);
        }
        
        $stmt = $this->db->prepare("
            SELECT court_id, start_time, end_time 
            FROM bookings 
            WHERE branch_id = ? AND booking_date = ? AND status IN ('pending', 'confirmed')
        ");
        $stmt->execute([$branchId, $date]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $grid = [];
        foreach ($courts as $court) {
            $grid[$court['id']] = [
                'court' => $court,
                'slots' => []
            ];
            foreach ($slots as $slot) {
                $isAvailable = true;
                $slotTime = strtotime($slot);
                $slotEndTime = strtotime("+1 hour", $slotTime);
                
                foreach ($bookings as $b) {
                    if ($b['court_id'] == $court['id']) {
                        $bStartTime = strtotime($b['start_time']);
                        $bEndTime = strtotime($b['end_time']);
                        if (($bStartTime <= $slotTime && $bEndTime > $slotTime) || ($bStartTime >= $slotTime && $bStartTime < $slotEndTime)) {
                            $isAvailable = false;
                            break;
                        }
                    }
                }
                $grid[$court['id']]['slots'][$slot] = $isAvailable;
            }
        }
        return $grid;
    }
}
