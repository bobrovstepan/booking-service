<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;


Route::controller(ServiceController::class)->group(function () {
    Route::get('/', 'index');
    Route::prefix('/service')->group(function () {
        Route::get('/available-days', 'availableDays');
        Route::get('/available-time-slots', 'availableTimeSlots');
    });
});

Route::controller(BookingController::class)->group(function () {
    Route::prefix('/booking')->group(function () {
        Route::post('/', 'store');
    });
});
