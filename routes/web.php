<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'show'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // Resource automatically create all the necessary routes (create, update, store ...)
    Route::resource('offers', OfferController::class)->except(['index']);

    // The scoped method verify that the product exists in the offer's scope
    Route::resource('offers.products', ProductController::class)->scoped([
        'product' => 'id',
    ]);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
