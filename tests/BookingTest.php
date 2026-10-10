<?php

/**
 * BookingTest.php
 * Manual test runner for the BadmintonBooking application.
 *
 * Run from project root:
 *   php tests/BookingTest.php
 *
 * Requires: .env configured with a valid MySQL connection.
 */

define('ROOT', dirname(__DIR__));

// ─── Bootstrap ───────────────────────────────────────────────────────────────
require_once ROOT . '/config/database.php';
require_once ROOT . '/core/Database.php';
require_once ROOT . '/core/Model.php';
require_once ROOT . '/app/Models/Booking.php';
require_once ROOT . '/app/Models/Branch.php';
require_once ROOT . '/app/Models/Court.php';
require_once ROOT . '/app/Models/PricingRule.php';
require_once ROOT . '/app/Models/User.php';

use Core\Database;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Court;
use App\Models\PricingRule;
use App\Models\User;

// ─── Test Helpers ─────────────────────────────────────────────────────────────
$passed = 0;
$failed = 0;

function test(string $name, bool $condition, string $failMsg = ''): void
{
    global $passed, $failed;
    if ($condition) {
        echo "\e[32m  ✓ PASS\e[0m {$name}\n";
        $passed++;
    } else {
        echo "\e[31m  ✗ FAIL\e[0m {$name}" . ($failMsg ? " — {$failMsg}" : '') . "\n";
        $failed++;
    }
}

function section(string $name): void
{
    echo "\n\e[36m═══ {$name} ═══\e[0m\n";
}

// ─── Tests ────────────────────────────────────────────────────────────────────

echo "\n\e[33m🏸 SmashZone — BadmintonBooking Test Suite\e[0m\n";
echo str_repeat('─', 50) . "\n";

// ── Database Connection
section('Database Connection');
try {
    $db = Database::getInstance();
    test('PDO connection established', $db !== null);
} catch (\Exception $e) {
    echo "\e[31mERROR: Cannot connect to database: " . $e->getMessage() . "\e[0m\n";
    echo "Make sure MySQL is running and .env is configured.\n\n";
    exit(1);
}

// ── Branch Model
section('Branch Model');
$branchModel = new Branch();
$branches = $branchModel->findActive();
test('findActive() returns branches',       count($branches) > 0);
test('findActive() returns 13 branches',    count($branches) === 13);

$cities = $branchModel->getCities();
test('getCities() returns array',           is_array($cities) && count($cities) > 0);

$branch = $branchModel->findById(1);
test('findById(1) returns branch',          $branch !== null && isset($branch['name']));

$hn = $branchModel->findByCity('Hà Nội');
test('findByCity() filters correctly',      count($hn) >= 5);

$searched = $branchModel->search('Sân');
test('search() works',                      is_array($searched));

// ── Court Model
section('Court Model');
$courtModel = new Court();
$courts = $courtModel->findByBranch(1);
test('findByBranch(1) returns 10 courts',   count($courts) === 10);
test('Court names are A1-A10',              $courts[0]['name'] === 'A1');

$court = $courtModel->findById(1);
test('findById(1) returns court',           $court !== null);
test('Court has branch_id',                 isset($court['branch_id']));

$grid = $courtModel->getAvailabilityGrid(1, date('Y-m-d'));
test('getAvailabilityGrid() returns data',  is_array($grid));

// ── Pricing Model
section('Pricing Rules');
$pricingModel = new PricingRule();

// Off-peak weekday (Monday, 6:00-7:00 = 1 hour)
$nextMonday = date('Y-m-d', strtotime('next monday'));
$offPeakPrice = $pricingModel->getPrice(1, $nextMonday, 6, 7);
test('Off-peak price is 80,000',            $offPeakPrice == 80000, "Got: {$offPeakPrice}");

// Peak weekday (10:00-12:00 = 2 hours)
$peakPrice = $pricingModel->getPrice(1, $nextMonday, 10, 12);
test('Peak 2h price is 300,000',            $peakPrice == 300000, "Got: {$peakPrice}");

// Weekend surcharge (+20%)
$nextSaturday = date('Y-m-d', strtotime('next saturday'));
$weekendPeak = $pricingModel->getPrice(1, $nextSaturday, 10, 11);
test('Weekend peak 1h price is 180,000',    $weekendPeak == 180000, "Got: {$weekendPeak}");

$rules = $pricingModel->getRules();
test('getRules() returns rules',            count($rules) >= 3);

// ── Booking Model
section('Booking Model');
$bookingModel = new Booking();

// Booking code generation
$code = $bookingModel->generateBookingCode();
test('generateBookingCode() returns code',  preg_match('/^BB-\d{8}-[A-Z0-9]{5}$/', $code) === 1, "Got: {$code}");
test('Each code is unique',                 $bookingModel->generateBookingCode() !== $bookingModel->generateBookingCode());

// Slot availability
$futureDate = date('Y-m-d', strtotime('+7 days'));
$isAvailable = $bookingModel->isSlotAvailable(1, $futureDate, 10, 11);
test('isSlotAvailable() on future date',    is_bool($isAvailable));

// Create a test booking
$testCode = 'BB-' . date('Ymd') . '-TEST1';
$bookingId = $bookingModel->create([
    'booking_code'   => $testCode,
    'user_id'        => null,
    'customer_name'  => 'Test User',
    'customer_phone' => '0901234567',
    'customer_email' => 'test@example.com',
    'court_id'       => 1,
    'branch_id'      => 1,
    'booking_date'   => $futureDate,
    'start_time'     => '10:00:00',
    'end_time'       => '11:00:00',
    'duration_hours' => 1,
    'total_price'    => 150000,
    'status'         => 'pending',
    'notes'          => 'Test booking',
]);
test('create() returns booking ID',         $bookingId > 0);

// Find by code
$found = $bookingModel->findByCode($testCode);
test('findByCode() finds booking',          $found !== null && $found['customer_name'] === 'Test User');

// Slot is now taken
$nowTaken = $bookingModel->isSlotAvailable(1, $futureDate, 10, 11);
test('Slot marked as unavailable after booking', $nowTaken === false);

// Phone search
$byPhone = $bookingModel->findByPhone('0901234567');
test('findByPhone() finds test booking',    count($byPhone) >= 1);

// Update status
$bookingModel->update($bookingId, ['status' => 'confirmed']);
$updated = $bookingModel->findById($bookingId);
test('update() changes status',             $updated['status'] === 'confirmed');

// Cleanup test booking
$db->query("DELETE FROM bookings WHERE booking_code = ?", [$testCode]);
test('Test booking cleaned up',             $bookingModel->findByCode($testCode) === null);

// ── User Model
section('User Model');
$userModel = new User();

$testEmail = 'unittest_' . time() . '@smashzone.vn';
$newUserId = $userModel->createUser([
    'name'     => 'Unit Test User',
    'email'    => $testEmail,
    'phone'    => '0988000000',
    'password' => 'password123',
]);
test('createUser() returns ID',             $newUserId > 0);

$foundUser = $userModel->findByEmail($testEmail);
test('findByEmail() finds user',            $foundUser !== null);

$auth = $userModel->authenticate($testEmail, 'password123');
test('authenticate() with correct password',   $auth !== false);

$authFail = $userModel->authenticate($testEmail, 'wrongpassword');
test('authenticate() with wrong password', $authFail === false);

// Cleanup
$db->query("DELETE FROM users WHERE email = ?", [$testEmail]);
test('Test user cleaned up',                $userModel->findByEmail($testEmail) === null);

// ─── Results ──────────────────────────────────────────────────────────────────
$total = $passed + $failed;
echo "\n" . str_repeat('─', 50) . "\n";
echo "\e[33mResults: {$passed}/{$total} tests passed\e[0m";
if ($failed > 0) {
    echo " | \e[31m{$failed} failed\e[0m";
}
echo "\n\n";
exit($failed > 0 ? 1 : 0);
