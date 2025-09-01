<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Participant\EventController as ParticipantEventController;

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk autentikasi umum
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk admin dengan prefix dan nama yang jelas
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', AdminEventController::class)
        ->parameters(['events' => 'event']);
    // Tambahkan rute admin lainnya di sini
    Route::get('/events/{event}/qrcode', [AdminEventController::class, 'showQrCode'])->name('events.qrcode');
});

// Rute untuk user biasa (peserta)
Route::middleware(['auth', 'role:peserta'])->prefix('participant')->name('participant.')->group(function () {
    // Route::resource('events', EventController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    // Tambahkan rute peserta lainnya di sini
    Route::get('/events', [ParticipantEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [ParticipantEventController::class, 'show'])->name('events.show');
});

// rute untuk qr dan scan qr
Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
Route::post('/scan/verify', [ScanController::class, 'verify'])->name('scan.verify');

require __DIR__ . '/auth.php';
