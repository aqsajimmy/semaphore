<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');
    Route::get('/daftar-penjualan/download/{id}', [InvoiceController::class, 'downloadInvoice'])->name('download_invoice');
    Route::get('/daftar-penjualan', [PenjualanController::class, 'daftar_penjualan'])->name('daftar_penjualan');
    Route::get('/daftar-penjualan/detail/{id}', [PenjualanController::class, 'detail_penjualan'])->name('detail_penjualan');


});

require __DIR__.'/auth.php';
