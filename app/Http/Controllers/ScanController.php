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
        // Validate input using Validator to provide a specific flash error for missing GPS
            // First validate event_code only, so we can fetch the event and then validate coordinates conditionally.
            $initialValidator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'event_code' => 'required|string',
            ]);

            if ($initialValidator->fails()) {
                return redirect()->route('scan.index')
                    ->withErrors($initialValidator)
                    ->withInput();
            }

            $eventCode = $request->input('event_code');
            $user = Auth::user();

            // Cari event berdasarkan kode unik
            $event = Event::where('code', $eventCode)->first();

            if (!$event) {
                Log::warning('Event tidak ditemukan dengan kode: ' . $eventCode . ' oleh user: ' . $user->id);
                return redirect()->route('scan.index')
                    ->with('error', 'Presensi Gagal: Event tidak ditemukan.');
            }

            // Now validate coordinates depending on event's require_gps flag
            $rules = [];
            if ($event->require_gps) {
                $rules['latitude'] = 'required|numeric|between:-90,90';
                $rules['longitude'] = 'required|numeric|between:-180,180';
            } else {
                $rules['latitude'] = 'nullable|numeric|between:-90,90';
                $rules['longitude'] = 'nullable|numeric|between:-180,180';
            }

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, [
                'latitude.required' => 'Lokasi (GPS) diperlukan untuk melakukan presensi. Silakan aktifkan layanan lokasi pada perangkat Anda dan coba lagi.',
                'longitude.required' => 'Lokasi (GPS) diperlukan untuk melakukan presensi. Silakan aktifkan layanan lokasi pada perangkat Anda dan coba lagi.',
            ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $failed = $validator->failed();

            // If the failure is due to missing GPS (Required rule), show user-friendly message
            $missingLatitude = isset($failed['latitude']) && isset($failed['latitude']['Required']);
            $missingLongitude = isset($failed['longitude']) && isset($failed['longitude']['Required']);

            if ($missingLatitude || $missingLongitude) {
                return redirect()->route('scan.index')
                    ->with('error', 'Lokasi (GPS) diperlukan untuk melakukan presensi. Silakan aktifkan layanan lokasi pada perangkat Anda dan coba lagi.');
            }

            // For other validation errors, return with standard validation errors
            return redirect()->route('scan.index')
                ->withErrors($errors)
                ->withInput();
        }
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
            // Catat kehadiran, mark location_allowed when coordinates present
            $locationAllowed = !is_null($request->input('latitude')) && !is_null($request->input('longitude'));

            Attendance::create([
                'event_id'      => $event->id,
                'user_id'       => $user->id,
                'check_in_time' => now(),
                'latitude'      => $request->input('latitude'),
                'longitude'     => $request->input('longitude'),
                'location_allowed' => $locationAllowed,
            ]);

        Log::info('Presensi berhasil: User ' . $user->id . ' untuk event ' . $event->id);

        return redirect()->route('scan.index')
            ->with('success', "Presensi untuk event '{$event->title}' berhasil!");
    }
}
