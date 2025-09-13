<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use App\Mail\EventInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ParticipantController extends Controller
{
    /**
     * Menyimpan satu atau lebih peserta yang dipilih secara manual.
     */
   public function store(Request $request, Event $event)
{
    set_time_limit(0); // Nonaktifkan batas waktu eksekusi
    try {
        $userIds = collect($request->input('user_ids'))->filter()->all();
        
        Log::debug('Store method called', [
            'user_ids' => $userIds,
            'event_id' => $event->id,
            'event_title' => $event->title
        ]);
        
        $validated = validator(['user_ids' => $userIds], [
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ])->validate();

        // Dapatkan peserta yang sudah ada sebelumnya
        $existingParticipants = $event->participants()->pluck('users.id')->toArray();
        
        // Filter hanya peserta yang baru
        $newParticipantIds = array_diff($validated['user_ids'], $existingParticipants);
        
        if (empty($newParticipantIds)) {
            Log::debug('No new participants to add');
            return back()->with('info', 'Semua peserta yang dipilih sudah terdaftar dalam event ini.');
        }

        $event->participants()->attach($newParticipantIds);

        // --- LOGIKA PENGIRIMAN EMAIL ---
        $newlyInvitedUsers = User::find($newParticipantIds);
        
        Log::debug('Attempting to send emails to new participants', [
            'count' => count($newlyInvitedUsers),
            'emails' => $newlyInvitedUsers->pluck('email')->toArray()
        ]);
        
        foreach ($newlyInvitedUsers as $user) {
            try {
                Log::debug('Sending email to: ' . $user->email);
                
                // Gunakan send() bukan queue() untuk testing
                Mail::to($user->email)->queue(new EventInvitationMail($event, $user));
                
                Log::info('Email invitation sent for user: ' . $user->email . ' to event: ' . $event->title);
            } catch (\Exception $e) {
                Log::error('Failed to send email for user: ' . $user->email, [
                    'error' => $e->getMessage(),
                    'event_id' => $event->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        // --- SELESAI ---

        return back()->with('success', count($newParticipantIds) . ' peserta berhasil diundang. Notifikasi email telah dikirim.');
    } catch (ValidationException $e) {
        Log::warning('Validasi undang peserta gagal', [
            'errors' => $e->errors(),
            'request' => $request->all(),
        ]);
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
        set_time_limit(0); // Nonaktifkan batas waktu eksekusi
        try {
            $validated = $request->validate([
                'invite_method' => 'required|string|in:all,division',
                'division' => 'nullable|required_if:invite_method,division|string',
            ]);

            $query = User::query()->where('role', 'participant');

            if ($validated['invite_method'] == 'division' && !empty($validated['division'])) {
                $query->where('division', $validated['division']);
            }

            // Dapatkan semua peserta event
            $existingParticipantIds = $event->participants()->pluck('users.id')->toArray();
            
            // Dapatkan peserta yang memenuhi kriteria dan belum diundang
            $potentialParticipants = $query->whereNotIn('id', $existingParticipantIds)->get();

            if ($potentialParticipants->isEmpty()) {
                return back()->with('error', 'Tidak ada peserta baru untuk diundang.');
            }

            $userIds = $potentialParticipants->pluck('id')->toArray();
            $event->participants()->attach($userIds);

            // Batasi pengiriman email per batch
        $chunks = $potentialParticipants->chunk(10); // 10 email per batch
        
        foreach ($chunks as $chunk) {
            foreach ($chunk as $participant) {
                try {
                    Mail::to($participant->email)->queue(new EventInvitationMail($event, $participant));
                    Log::info('Email invitation queued for bulk user: ' . $participant->email);
                } catch (\Exception $e) {
                    Log::error('Failed to queue bulk email for user: ' . $participant->email);
                }
            }
            // Tambahkan delay antar batch
            sleep(2);
        }

            return back()->with('success', count($userIds) . ' peserta massal berhasil diundang. Notifikasi email telah dikirim.');
        } catch (\Illuminate\Validation\ValidationException $e) {
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
        set_time_limit(0); // Nonaktifkan batas waktu eksekusi
        try {
            $validated = $request->validate([
                'full_name'    => 'required|string|max:255',
                'email'        => 'required|email|max:255',
                'institution'  => 'required|string|max:255',
                'position'     => 'required|string|max:255',
                'phone_number' => 'nullable|string|max:20',
            ]);

            // Cek apakah peserta sudah diundang ke event ini
            if ($event->participants()->where('email', $validated['email'])->exists()) {
                return back()->with('error', 'Peserta dengan email ini sudah diundang ke event ini.');
            }

            // Buat atau dapatkan user
            $participant = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'full_name'    => $validated['full_name'],
                    'institution'  => $validated['institution'],
                    'position'     => $validated['position'],
                    'phone_number' => $validated['phone_number'],
                    'role'         => 'participant',
                    'password'     => bcrypt(Str::random(12)), // Generate random password
                ]
            );

            $event->participants()->attach($participant->id);

            try {
                // Gunakan send() bukan queue() untuk testing
                Mail::to($participant->email)->queue(new EventInvitationMail($event, $participant));
                Log::info('Email invitation sent for external user: ' . $participant->email . ' to event: ' . $event->title);
            } catch (\Exception $e) {
                Log::error('Failed to send external email for user: ' . $participant->email, [
                    'error' => $e->getMessage(),
                    'event_id' => $event->id,
                ]);
            }

            return back()->with('success', 'Peserta eksternal berhasil diundang. Notifikasi email telah dikirim.');
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