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

        // Chặn các trạng thái không cho phép thanh toán
        if (in_array($booking['status'], ['cancelled', 'completed'])) {
            Session::flash('error', 'Đơn đặt sân đã bị hủy hoặc hoàn tất.');
            $this->redirect('/branches');
        }
        // Cho phép pending, pending_payment và các status khác (trừ confirmed/cancelled)

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

        // Chặn các trạng thái không cho phép thanh toán
        if (in_array($booking['status'], ['cancelled', 'completed'])) {
            Session::flash('error', 'Đơn đặt sân đã bị hủy hoặc hoàn tất.');
            $this->redirect('/branches');
        }
        // Cho phép pending, pending_payment và các status khác (trừ confirmed/cancelled)

        // Create payment record
        $transactionId = 'TXN-' . strtoupper(uniqid());
        
        // Xác định trạng thái booking theo phương thức thanh toán
        $isCash = $method === 'cash';
        $bookingStatus = $isCash ? 'pending_payment' : 'confirmed';
        $paymentStatus = $isCash ? 'pending' : 'paid';
        $paidAt = $isCash ? null : date('Y-m-d H:i:s');
        
        $paymentModel->create([
            'booking_id'     => $bookingId,
            'method'         => $method,
            'amount'         => $booking['total_price'],
            'status'         => $paymentStatus,
            'transaction_id' => $transactionId,
            'paid_at'        => $paidAt,
        ]);

        // Update booking status
        $bookingModel->update($bookingId, ['status' => $bookingStatus]);

        if ($isCash) {
            Session::flash('success', 'Đặt sân thành công! Trạng thái: Chờ thanh toán tiền mặt tại sân. Admin sẽ xác nhận khi bạn đến nhận sân.');
        } else {
            Session::flash('success', 'Thanh toán thành công! Mã đặt sân của bạn đã được xác nhận.');
        }
        $this->redirect("/booking/success/{$bookingId}");
    }
}
