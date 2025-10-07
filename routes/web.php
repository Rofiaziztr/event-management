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
use App\Http\Controllers\GoogleCalendarAuthController;

// Rute dasar
Route::get('/', [WelcomeController::class, 'index']);

// Middleware untuk otentikasi dan verifikasi
Route::middleware(['auth'])->group(function () {
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

    // Contoh Alert System
    Route::controller(\App\Http\Controllers\AlertExampleController::class)->group(function () {
        Route::get('/alert-examples', 'showExamples')->name('alert.examples');
        Route::get('/alert-examples/success', 'successAlert')->name('alert.success');
        Route::get('/alert-examples/error', 'errorAlert')->name('alert.error');
        Route::get('/alert-examples/warning', 'warningAlert')->name('alert.warning');
        Route::get('/alert-examples/info', 'infoAlert')->name('alert.info');
        Route::post('/alert-examples/js', 'jsAlert')->name('alert.js');
    });

    // Rute admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export', [AdminDashboardController::class, 'export'])->name('dashboard.export');

        // Manajemen event
        Route::resource('events', EventController::class);
        Route::get('events/{event}/qrcode', [EventController::class, 'showQrCode'])->name('events.qrcode');
        // Rute Ekspor Baru yang Terpusat
        Route::get('events/{event}/export', [EventController::class, 'export'])->name('events.export');
        // Manual calendar sync
        Route::post('events/{event}/sync-calendar', [EventController::class, 'syncCalendar'])->name('events.sync-calendar');


        // Manajemen peserta
        Route::controller(ParticipantController::class)->prefix('events/{event}/participants')->name('events.participants.')->group(function () {
            Route::get('/', 'list')->name('list');
            Route::post('/', 'store')->name('store');
            Route::post('/invite-all', 'inviteAllAvailable')->name('invite-all-available');
            Route::post('/invite-by-division', 'inviteByDivision')->name('invite-by-division');
            Route::delete('/{user}', 'destroy')->name('destroy');
            Route::post('/external', 'storeExternal')->name('store.external');
            // HAPUS RUTE LAMA DARI SINI
            // Route::get('/export', 'export')->name('export');
            // Route::get('/export-filtered', 'exportFiltered')->name('export-filtered');
            Route::post('/{user}/manual', 'manualAttendance')->name('manual');
            Route::post('/bulk-attendance', 'bulkAttendance')->name('bulk-attendance');
            // Individual calendar sync
            Route::post('/{user}/sync-calendar', 'syncIndividualCalendar')->name('sync-calendar');
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
    });

    // Rute peserta
    Route::prefix('participant')->name('participant.')->middleware('role:participant')->group(function () {
        Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');
        Route::controller(ParticipantEventController::class)->prefix('events')->name('events.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{event}', 'show')->name('show');
            Route::post('/sync-calendar', 'syncCalendar')->name('sync-calendar');
        });
    });

    // Rute scan untuk peserta
    Route::controller(ScanController::class)->middleware('role:participant')->group(function () {
        Route::get('/scan', 'index')->name('scan.index');
        Route::post('/scan', 'verify')->name('scan.verify');
    });

    // Google Calendar routes for participants
    Route::middleware(['auth', 'role:participant'])->group(function () {
        Route::get('/google-calendar/auth', [GoogleCalendarAuthController::class, 'redirectToGoogle'])->name('google-calendar.auth');
        Route::get('/google-calendar/callback', [GoogleCalendarAuthController::class, 'handleGoogleCallback'])->name('google-calendar.callback');
        Route::post('/google-calendar/revoke', [GoogleCalendarAuthController::class, 'revokeAccess'])->name('google-calendar.revoke');
        Route::get('/google-calendar/status', [GoogleCalendarAuthController::class, 'status'])->name('google-calendar.status');
    });
});

require __DIR__ . '/auth.php';
