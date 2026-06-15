<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Guest\BookingController;
use App\Http\Controllers\Guest\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [HomeController::class, 'services'])->name('services');

// Multi-step booking
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/langkah-1', [BookingController::class, 'step1'])->name('step1');
    Route::post('/langkah-1', [BookingController::class, 'storeStep1'])->name('step1.store');

    Route::get('/langkah-2', [BookingController::class, 'step2'])->name('step2');
    Route::get('/slots', [BookingController::class, 'getSlots'])->name('slots');
    Route::post('/langkah-2', [BookingController::class, 'storeStep2'])->name('step2.store');

    Route::get('/langkah-3', [BookingController::class, 'step3'])->name('step3');
    Route::post('/langkah-3', [BookingController::class, 'storeStep3'])->name('step3.store');

    Route::get('/konfirmasi', [BookingController::class, 'confirm'])->name('confirm');
    Route::post('/simpan', [BookingController::class, 'store'])->name('store');

    Route::get('/berhasil/{code}', [BookingController::class, 'success'])->name('success');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Bookings
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
        Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

        // Services
        Route::resource('services', ServiceController::class)->except(['show']);
    });
});
