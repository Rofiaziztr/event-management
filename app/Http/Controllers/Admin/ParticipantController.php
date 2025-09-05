<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ParticipantController extends Controller
{
    /**
     * Menyimpan satu atau lebih peserta yang dipilih secara manual.
     */
    public function store(Request $request, Event $event)
    {
        try {
            // PERBAIKAN: Saring nilai-nilai null dari array sebelum validasi
            $userIds = collect($request->input('user_ids'))->filter()->all();

            // Validasi input yang sudah bersih
            $validated = validator(['user_ids' => $userIds], [
                'user_ids'   => 'required|array|min:1', // Pastikan array tidak kosong
                'user_ids.*' => 'exists:users,id',
            ])->validate();

            $event->participants()->syncWithoutDetaching($validated['user_ids']);

            return back()->with(
                'success',
                count($validated['user_ids']) . ' peserta berhasil diundang.'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi undang peserta gagal', [
                'errors' => $e->errors(),
                'request' => $request->all(),
            ]);
            // Tambahkan pesan error yang lebih spesifik jika tidak ada user yang dipilih
            if (empty($userIds)) {
                return back()->with('error', 'Anda harus memilih setidaknya satu peserta untuk diundang.')->withInput();
            }
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error undang peserta', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Gagal undang peserta: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan peserta secara massal berdasarkan kriteria (semua atau per divisi).
     */
    public function storeBulk(Request $request, Event $event)
    {
        try {
            // Validasi ini sudah benar. 'division' hanya wajib jika 'invite_method' adalah 'division'.
            $validated = $request->validate([
                'invite_method' => 'required|string|in:all,division',
                'division' => 'nullable|required_if:invite_method,division|string',
            ]);

            $query = User::query()->where('role', 'participant');

            // PERBAIKAN LOGIKA: Hanya filter berdasarkan divisi jika metodenya 'division'
            // dan 'division' memiliki nilai.
            if ($validated['invite_method'] == 'division' && !empty($validated['division'])) {
                $query->where('division', $validated['division']);
            }

            // ... sisa kode Anda sudah benar ...
            $potentialParticipants = $query->whereDoesntHave('participatedEvents', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            })->get();

            $userIds = $potentialParticipants->pluck('id')->toArray();
            $event->participants()->syncWithoutDetaching($userIds);

            return back()->with('success', count($userIds) . ' peserta massal berhasil diundang.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Logika ini ditambahkan agar pesan error lebih jelas
            Log::warning('Validasi undangan massal gagal', [
                'errors' => $e->errors(),
                'request' => $request->all(),
            ]);
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error saat undang massal', ['message' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeExternal(Request $request, Event $event)
    {
        try {
            $validated = $request->validate([
                'full_name'    => 'required|string|max:255',
                'email'        => 'required|email|max:255',
                'institution'  => 'required|string|max:255',
                'position'     => 'required|string|max:255',
                'phone_number' => 'nullable|string|max:20',
            ]);

            // Cek apakah email sudah terdaftar sebagai peserta di event ini
            if ($event->participants()->where('email', $validated['email'])->exists()) {
                return back()->with('error', 'Peserta dengan email ini sudah diundang ke event ini.');
            }

            // Cari atau buat user baru dengan role 'participant'
            // Ini akan membuat "akun tamu" jika email belum ada di database
            $participant = User::firstOrCreate(
                ['email' => $validated['email']], // Kunci untuk mencari
                [ // Data untuk dibuat jika tidak ditemukan
                    'full_name'    => $validated['full_name'],
                    'institution'  => $validated['institution'],
                    'position'     => $validated['position'],
                    'phone_number' => $validated['phone_number'],
                    'role'         => 'participant',
                    // Password sengaja dikosongkan agar tidak bisa login
                ]
            );

            // Lampirkan peserta ke event
            $event->participants()->syncWithoutDetaching($participant->id);

            return back()->with('success', 'Peserta eksternal berhasil diundang.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator, 'external')->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal undang peserta eksternal', ['message' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat mengundang peserta eksternal.');
        }
    }


    /**
     * Menghapus seorang peserta dari event.
     */
    public function destroy(Event $event, User $user)
    {
        $event->participants()->detach($user->id);

        return back()->with('success', 'Peserta berhasil dihapus.');
    }
}
