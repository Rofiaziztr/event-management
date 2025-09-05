<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Participant\EventController as ParticipantEventController;
use App\Http\Controllers\ScanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.events.index');
    }
    return redirect()->route('participant.events.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Rute untuk Admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('events', AdminEventController::class);
        Route::get('events/{event}/qrcode', [AdminEventController::class, 'showQrCode'])->name('events.qrcode');

        // --- Perbaikan dan Penambahan Route Peserta ---
        Route::post('events/{event}/participants', [ParticipantController::class, 'store'])->name('events.participants.store');
        Route::post('events/{event}/participants/bulk', [ParticipantController::class, 'storeBulk'])->name('events.participants.store.bulk'); // DITAMBAHKAN: Route untuk undangan massal
        Route::delete('events/{event}/participants/{user}', [ParticipantController::class, 'destroy'])->name('events.participants.destroy');
        // --- Akhir Perbaikan ---

        Route::post('events/{event}/documents', [DocumentController::class, 'store'])->name('events.documents.store');
        Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        Route::post('events/{event}/notulensi', [DocumentController::class, 'storeOrUpdateNotulensi'])->name('events.notulensi.store');

        Route::post('/events/{event}/participants/external', [App\Http\Controllers\Admin\ParticipantController::class, 'storeExternal'])
            ->name('events.participants.store.external');
    });

    // Rute untuk Peserta
    Route::prefix('participant')->name('participant.')->middleware('role:participant')->group(function () { // DIPERBAIKI: Mengubah 'peserta' menjadi 'participant'
        Route::get('events', [ParticipantEventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [ParticipantEventController::class, 'show'])->name('events.show');
    });

    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('/scan', [ScanController::class, 'verify'])->name('scan.verify');
});


require __DIR__ . '/auth.php';
