<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Branch;
use App\Models\Booking;

class HomeController extends Controller
{
    public function index()
    {
        $branchModel = new Branch();
        $bookingModel = new Booking();

        $featuredBranches = $branchModel->getFeatured(6);
        $stats = [
            'total_branches' => $branchModel->count(),
            'total_courts'   => 130, // 13 branches x 10 courts
            'total_bookings' => $bookingModel->countConfirmed(),
        ];

        $this->render('home/index', [
            'title'           => 'SmashZone - Đặt Sân Cầu Lông Online',
            'featuredBranches' => $featuredBranches,
            'stats'           => $stats,
        ]);
    }
}
