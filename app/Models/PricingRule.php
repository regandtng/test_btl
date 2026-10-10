<?php
namespace App\Models;

use Core\Model;
use PDO;

class PricingRule extends Model {
    protected $table = 'pricing_rules';

    public function getPrice($branchId, $date, $startHour, $endHour) {
        $dayOfWeek = date('w', strtotime($date));
        $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
        $dayType = $isWeekend ? 'weekend' : 'weekday';

        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE (branch_id = ? OR branch_id IS NULL) 
            AND day_type IN (?, 'all')
        ");
        $stmt->execute([$branchId, $dayType]);
        $rules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalPrice = 0;
        for ($h = (int) $startHour; $h < (int) $endHour; $h++) {
            $appliedPrice = 0;
            foreach ($rules as $rule) {
                $ruleStart = (int) substr($rule['start_hour'], 0, 2);
                $ruleEnd   = (int) substr($rule['end_hour'], 0, 2);
                if ($h >= $ruleStart && $h < $ruleEnd) {
                    $appliedPrice = (int) $rule['price_per_hour'];
                    if ($rule['branch_id'] !== null) {
                        break;
                    }
                }
            }

            if ($appliedPrice == 0) {
                $appliedPrice = 80000;
            }

            $totalPrice += $appliedPrice;
        }

        return $totalPrice;
    }

    public function getForBranch($branchId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE branch_id = ? OR branch_id IS NULL ORDER BY start_hour");
        $stmt->execute([$branchId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRules() {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
