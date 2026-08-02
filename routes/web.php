<?php

use App\Http\Controllers\DonationPublicController;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $homepageSetting = HomepageSetting::query()->latest('id')->first();

    return view('welcome', compact('homepageSetting'));
});

Route::get('/donate', [DonationPublicController::class, 'create'])->name('donations.create');
Route::post('/donate', [DonationPublicController::class, 'store'])->name('donations.store');

