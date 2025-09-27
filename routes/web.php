<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Participant\EventController as ParticipantEventController;
use App\Http\Controllers\Participant\DashboardController as ParticipantDashboardController;

// Rute dasar
Route::get('/', [WelcomeController::class, 'index']);

// Middleware untuk otentikasi dan verifikasi
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute dashboard utama dengan logika role
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('participant.dashboard');
    })->name('dashboard');

    // Rute profil
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Rute admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export', [AdminDashboardController::class, 'export'])->name('dashboard.export');

        // Manajemen event
        Route::resource('events', EventController::class);
        Route::get('events/{event}/qrcode', [EventController::class, 'showQrCode'])->name('events.qrcode');

        // Manajemen peserta
        Route::controller(ParticipantController::class)->prefix('events/{event}/participants')->name('events.participants.')->group(function () {
            Route::get('/', 'list')->name('list');
            Route::post('/', 'store')->name('store');
            Route::post('/invite-all', 'inviteAllAvailable')->name('invite-all-available');
            Route::post('/invite-by-division', 'inviteByDivision')->name('invite-by-division');
            Route::delete('/{user}', 'destroy')->name('destroy');
            Route::post('/external', 'storeExternal')->name('store.external');
            Route::post('/{user}/manual', 'manualAttendance')->name('manual');
            Route::post('/bulk-attendance', 'bulkAttendance')->name('bulk-attendance');

            // Export routes
            Route::get('/export', 'export')->name('export');
            Route::get('/export-filtered', 'exportFiltered')->name('export-filtered');
        });

        // General participant routes
        Route::controller(ParticipantController::class)->prefix('participants')->name('participants.')->group(function () {
            Route::get('/download-template', 'downloadTemplate')->name('download-template');
        });

        // Multi-event comparison
        Route::post('/events/export-comparison', [ParticipantController::class, 'exportComparison'])
            ->name('events.export-comparison');

        // Manajemen dokumen
        Route::controller(DocumentController::class)->group(function () {
            Route::post('events/{event}/documents', 'store')->name('events.documents.store');
            Route::delete('documents/{document}', 'destroy')->name('documents.destroy');
            Route::post('events/{event}/notulensi', 'storeOrUpdateNotulensi')->name('events.notulensi.store');
        });

        // Manajemen pengguna
        Route::resource('users', UserController::class);
        Route::get('admin/users/export', [UserController::class, 'export'])->name('users.export');

        // Presensi manual (sudah ada di dalam group participants, bisa dihapus jika duplikat)
        Route::post('/events/{event}/participants/{user}/manual', [ParticipantController::class, 'manualAttendance'])->name('events.participants.manual');
    });

    // Rute peserta
    Route::prefix('participant')->name('participant.')->middleware('role:participant')->group(function () {
        Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');
        Route::controller(ParticipantEventController::class)->prefix('events')->name('events.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{event}', 'show')->name('show');
        });
    });

    // Rute scan untuk peserta
    Route::controller(ScanController::class)->middleware('role:participant')->group(function () {
        Route::get('/scan', 'index')->name('scan.index');
        Route::post('/scan', 'verify')->name('scan.verify');
    });
});

require __DIR__ . '/auth.php';