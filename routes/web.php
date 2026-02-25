<?php

use App\Http\Controllers\DonationPublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/donate', [DonationPublicController::class, 'create'])->name('donations.create');
Route::post('/donate', [DonationPublicController::class, 'store'])->name('donations.store');

