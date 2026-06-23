<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\BookingService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(fn () => BookingService::markExpiredBookingsAsNoShow())
    ->name('mark-expired-bookings-as-no-show')
    ->everyMinute()
    ->withoutOverlapping();
