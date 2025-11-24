<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
    /**
     * Menampilkan halaman scanner.
     */
    public function index()
    {
        return view('participant.scan');
    }

    /**
     * Memverifikasi kode QR dan mencatat kehadiran.
     */
    public function verify(Request $request)
    {
        // Validasi input
        $request->validate([
            'event_code' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
        $eventCode = $request->input('event_code');
        $user = Auth::user();

        // Cari event berdasarkan kode unik
        $event = Event::where('code', $eventCode)->first();

        if (!$event) {
            Log::warning('Event tidak ditemukan dengan kode: ' . $eventCode . ' oleh user: ' . $user->id);
            return redirect()->route('scan.index')
                ->with('error', 'Presensi Gagal: Event tidak ditemukan.');
        }

        // Cek apakah user terdaftar sebagai peserta event
        $isParticipant = $event->participants()
            ->where('user_id', $user->id)
            ->exists();

        if (!$isParticipant) {
            Log::warning('User ' . $user->id . ' mencoba presensi untuk event ' . $event->id . ' yang tidak diikutinya.');
            return redirect()->route('scan.index')
                ->with('error', '❌ Akses Ditolak: Anda tidak diundang ke event "' . $event->title . '". Silakan hubungi panitia jika ada kesalahan.');
        }

        // Cek apakah event aktif untuk presensi
        if (!$event->isActiveForAttendance()) {
            Log::warning('User ' . $user->id . ' mencoba presensi untuk event ' . $event->id . ' di luar waktu yang ditentukan.');
            return redirect()->route('scan.index')
                ->with('error', 'Event "' . $event->title . '" tidak aktif untuk presensi saat ini.');
        }

        // Cek apakah user sudah presensi
        $alreadyAttended = Attendance::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyAttended) {
            Log::info('User ' . $user->id . ' sudah presensi untuk event ' . $event->id);
            return redirect()->route('scan.index')
                ->with('warning', '✅ Sudah Tercatat: Anda sudah presensi untuk event "' . $event->title . '" sebelumnya. Terima kasih atas kehadiran Anda!');
        }

        // Catat kehadiran
        Attendance::create([
            'event_id'      => $event->id,
            'user_id'       => $user->id,
            'check_in_time' => now(),
            'latitude'      => $request->input('latitude'),
            'longitude'     => $request->input('longitude'),
        ]);

        Log::info('Presensi berhasil: User ' . $user->id . ' untuk event ' . $event->id);

        return redirect()->route('scan.index')
            ->with('success', "Presensi untuk event '{$event->title}' berhasil!");
    }
}
