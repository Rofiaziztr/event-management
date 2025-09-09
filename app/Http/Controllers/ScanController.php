<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    /**
     * Menampilkan halaman scanner.
     */
    public function index()
    {
        return view('scan');
    }

    /**
     * Memverifikasi kode QR dan mencatat kehadiran.
     */
    public function verify(Request $request)
    {
        // Validasi input
        $request->validate(['event_code' => 'required|string']);
        $eventCode = $request->input('event_code');
        $user = Auth::user();

        // Cari event berdasarkan kode unik
        $event = Event::where('code', $eventCode)->first();
        if (!$event) {
            return redirect()->route('participant.events.index')
                ->with('error', 'Presensi Gagal: Event tidak ditemukan.');
        }

        // Cek apakah user terdaftar sebagai peserta event
        $isParticipant = $event->participants()
            ->where('user_id', $user->id)
            ->exists();

        if (!$isParticipant) {
            return redirect()->route('participant.events.index')
                ->with('error', 'Anda bukan peserta terdaftar untuk event ini.');
        }

        // Cek apakah user sudah presensi
        $alreadyAttended = Attendance::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyAttended) {
            return redirect()->route('participant.events.index')
                ->with('warning', 'Anda sudah tercatat hadir di event ini.');
        }

        // Catat kehadiran
        Attendance::create([
            'event_id'      => $event->id,
            'user_id'       => $user->id,
            'check_in_time' => now(),
        ]);

        return redirect()->route('participant.events.index')
            ->with('success', "Presensi untuk event '{$event->title}' berhasil!");
    }
}
