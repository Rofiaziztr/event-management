<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;

// Hapus atau beri komentar pada rute /events yang lama
// Route::get('/events', [App\Http\Controllers\EventController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// GRUP RUTE UNTUK ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    // Nantinya halaman manajemen event, user, dll akan ada di sini

    // Contoh: Rute untuk menampilkan semua event yang hanya bisa diakses admin
    // URL-nya akan menjadi /admin/events
    Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
