<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Participant\EventController as ParticipantEventController;
use App\Http\Controllers\Participant\DashboardController as ParticipantDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Main Dashboard Route - Redirects based on role
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('participant.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Events Management
        Route::resource('events', EventController::class);
        Route::get('events/{event}/qrcode', [EventController::class, 'showQrCode'])->name('events.qrcode');

        // Participants Management
        Route::post('events/{event}/participants', [ParticipantController::class, 'store'])->name('events.participants.store');
        Route::post('events/{event}/participants/bulk', [ParticipantController::class, 'storeBulk'])->name('events.participants.store.bulk');
        Route::delete('events/{event}/participants/{user}', [ParticipantController::class, 'destroy'])->name('events.participants.destroy');
        Route::post('/events/{event}/participants/external', [ParticipantController::class, 'storeExternal'])->name('events.participants.store.external');

        // Documents Management
        Route::post('events/{event}/documents', [DocumentController::class, 'store'])->name('events.documents.store');
        Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::post('events/{event}/notulensi', [DocumentController::class, 'storeOrUpdateNotulensi'])->name('events.notulensi.store');
    });

    // Participant Routes
    Route::prefix('participant')->name('participant.')->middleware('role:participant')->group(function () { 
        // Participant Dashboard
        Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');
        
        // Events for Participants
        Route::get('events', [ParticipantEventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [ParticipantEventController::class, 'show'])->name('events.show');
    });

    // Scan Routes (Available for participants)
    Route::middleware(['role:participant'])->group(function () {
        Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan', [ScanController::class, 'verify'])->name('scan.verify');
    });
});




require __DIR__ . '/auth.php';
