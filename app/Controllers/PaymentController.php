<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Core\Session;
use DateTime;

class PaymentController extends Controller
{
    // GET /payment/{bookingId}
    public function show($bookingId)
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking) {
            Session::flash('error', 'Không tìm thấy thông tin đặt sân.');
            $this->redirect('/');
        }

        if ($booking['status'] === 'confirmed') {
            $this->redirect("/booking/success/{$bookingId}");
        }

        if ($booking['status'] === 'cancelled') {
            Session::flash('error', 'Đơn đặt sân đã bị hủy.');
            $this->redirect('/branches');
        }

        $paymentMethods = [
            ['id' => 'qr',   'label' => 'Chuyển khoản QR',  'icon' => '🏦', 'desc' => 'Quét QR qua app ngân hàng'],
            ['id' => 'momo', 'label' => 'Ví MoMo',           'icon' => '💜', 'desc' => 'Thanh toán qua ví MoMo'],
            ['id' => 'cash', 'label' => 'Tiền mặt tại sân',  'icon' => '💵', 'desc' => 'Thanh toán khi đến sân'],
        ];

        $this->render('bookings/payment', [
            'title'          => 'Thanh Toán - SmashZone',
            'booking'        => $booking,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    // POST /payment/process
    public function process()
    {
        $bookingId = $_POST['booking_id'] ?? null;
        $method    = $_POST['payment_method'] ?? null;

        $allowedMethods = ['qr', 'momo', 'cash'];
        if (!$bookingId || !in_array($method, $allowedMethods)) {
            Session::flash('error', 'Thông tin thanh toán không hợp lệ.');
            $this->redirect('/');
        }

        $bookingModel = new Booking();
        $paymentModel = new Payment();

        $booking = $bookingModel->findById($bookingId);
        if (!$booking) {
            Session::flash('error', 'Không tìm thấy đặt sân.');
            $this->redirect('/');
        }

        if ($booking['status'] === 'cancelled') {
            Session::flash('error', 'Đơn đặt sân đã bị hủy.');
            $this->redirect('/branches');
        }

        // Create payment record - booking vẫn ở pending, chờ admin duyệt
        $transactionId = 'TXN-' . strtoupper(uniqid());
        $paymentStatus = $method === 'cash' ? 'pending' : 'paid';
        $paidAt = $method === 'cash' ? null : date('Y-m-d H:i:s');
        
        $paymentModel->create([
            'booking_id'     => $bookingId,
            'method'         => $method,
            'amount'         => $booking['total_price'],
            'status'         => $paymentStatus,
            'transaction_id' => $transactionId,
            'paid_at'        => $paidAt,
        ]);

        // Booking KHÔNG đổi status, vẫn pending chờ admin duyệt
        $msg = $method === 'cash' 
            ? 'Đã ghi nhận chọn tiền mặt. Đơn đang chờ admin xác nhận.' 
            : 'Đã ghi nhận thanh toán online. Đơn đang chờ admin xác nhận.';
        Session::flash('success', $msg);
        $this->redirect("/booking/success/{$bookingId}");
    }
}
