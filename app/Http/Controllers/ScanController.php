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
     * Hanya user yang sudah login yang bisa mengakses.
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
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan presensi.');
        }

        // 1. Validasi input dari form scanner
        $request->validate(['event_code' => 'required|string']);
        $eventCode = $request->input('event_code');
        $user = Auth::user();

        // 2. Cari event berdasarkan kode unik
        $event = Event::where('code', $eventCode)->first();

        // 3. Lakukan serangkaian pemeriksaan
        if (!$event) {
            return redirect()->route('dashboard')->with('error', 'Presensi Gagal: Event tidak ditemukan.');
        }

        // Pemeriksaan tambahan (opsional tapi sangat direkomendasikan)
        // if ($event->status !== 'Ongoing') {
        //     return redirect()->route('dashboard')->with('error', 'Presensi Gagal: Event ini belum dimulai atau sudah selesai.');
        // }

        // 4. Cek apakah user sudah pernah melakukan presensi untuk event ini
        $alreadyAttended = Attendance::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyAttended) {
            return redirect()->route('dashboard')->with('warning', 'Anda sudah tercatat hadir di event ini.');
        }

        // 5. Jika semua pemeriksaan lolos, catat kehadiran
        Attendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'check_in_time' => now(), // Catat waktu saat ini
        ]);

        // 6. Redirect ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', "Presensi untuk event '{$event->title}' berhasil!");
    }
}
