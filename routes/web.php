<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::resource('users', UserController::class)->middleware('role:admin');
    Route::resource('pembelian', PembelianController::class)->except(['show']);
    Route::post('/pembelian/data-pembelian', [PembelianController::class, 'dataPembelian'])->name('pembelian.dataPembelian');
    Route::get('/pembelian/struk/{id}', [PembelianController::class, 'struk'])->name('pembelian.struk');
    Route::get('/pembelian/pdf/{id}', [PembelianController::class, 'downloadPdf'])->name('pembelian.struk_pdf');
    Route::get('/pembelian/export', [PembelianController::class, 'export'])->name('pembelian.export');
});

require __DIR__.'/auth.php';
