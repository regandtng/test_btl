<?php

// Public Routes
$router->get('/', 'HomeController@index');
$router->get('/branches', 'BranchController@index');
$router->get('/branch/{id}', 'BranchController@show');

// Booking Routes — static routes MUST come before dynamic {courtId}
$router->post('/booking/store', 'BookingController@store');
$router->get('/booking/confirm', 'BookingController@confirm');
$router->post('/booking/confirm', 'BookingController@processConfirm');
$router->get('/booking/history', 'BookingController@history');
$router->get('/history', 'BookingController@history');
$router->get('/booking/detail/{id}', 'BookingController@detail');
$router->get('/booking/success/{bookingId}', 'BookingController@success');
$router->get('/booking/{courtId}', 'BookingController@create');
$router->get('/api/available-slots', 'BookingController@availableSlots'); // JSON API

// Payment Routes
$router->get('/payment/{bookingId}', 'PaymentController@show');
$router->post('/payment/process', 'PaymentController@process');

// Auth Routes
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Profile Routes (customer)
$router->get('/profile', 'AuthController@profile');
$router->post('/profile/update', 'AuthController@updateProfile');
$router->post('/profile/change-password', 'AuthController@changePassword');

// Owner Routes
$router->get('/owner/login', 'OwnerController@loginForm');
$router->post('/owner/login', 'OwnerController@login');
$router->get('/owner/logout', 'OwnerController@logout');

$router->get('/owner/dashboard', 'OwnerController@dashboard');
$router->get('/owner/branches', 'OwnerController@branches');
$router->post('/owner/branch/store', 'OwnerController@storeBranch');
$router->post('/owner/branch/update', 'OwnerController@updateBranch');
$router->post('/owner/branch/delete', 'OwnerController@deleteBranch');

$router->get('/owner/bookings', 'OwnerController@bookings');
$router->post('/owner/booking/store', 'OwnerController@storeBooking');
$router->post('/owner/booking/update', 'OwnerController@updateBooking');

$router->get('/owner/pricing', 'OwnerController@pricing');
$router->post('/owner/pricing/store', 'OwnerController@storePricing');
$router->post('/owner/pricing/update', 'OwnerController@updatePricing');
$router->post('/owner/pricing/delete', 'OwnerController@deletePricing');
$router->get('/owner/revenue', 'OwnerController@revenue');
