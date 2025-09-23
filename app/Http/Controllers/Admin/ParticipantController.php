<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
// use App\Mail\EventInvitationMail; // Tidak digunakan lagi untuk sementara
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Mail; // Tidak digunakan lagi untuk sementara
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Response;

class ParticipantController extends Controller
{
    /**
     * Menyimpan satu atau lebih peserta yang dipilih secara manual.
     */
    public function store(Request $request, Event $event)
    {
        set_time_limit(0);
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

            $existingParticipants = $event->participants()->pluck('users.id')->toArray();
            $newParticipantIds = array_diff($validated['user_ids'], $existingParticipants);
            
            if (empty($newParticipantIds)) {
                Log::debug('No new participants to add');
                return back()->with('info', 'Semua peserta yang dipilih sudah terdaftar dalam event ini.');
            }

            $event->participants()->attach($newParticipantIds);

            // BAGIAN PENGIRIMAN EMAIL DINONAKTIFKAN
            /*
            $newlyInvitedUsers = User::find($newParticipantIds);
            foreach ($newlyInvitedUsers as $user) {
                try {
                    // Mail::to($user->email)->queue(new EventInvitationMail($event, $user));
                    Log::info('Email invitation (TESTING - NOT SENT) for user: ' . $user->email . ' to event: ' . $event->title);
                } catch (\Exception $e) {
                    Log::error('Failed to send email for user: ' . $user->email, [
                        'error' => $e->getMessage(),
                        'event_id' => $event->id,
                    ]);
                }
            }
            */

            return back()->with('success', count($newParticipantIds) . ' peserta berhasil diundang.'); // Pesan diubah
        } catch (ValidationException $e) {
            Log::warning('Validasi undang peserta gagal', ['errors' => $e->errors(), 'request' => $request->all()]);
            if (empty($userIds)) {
                return back()->with('error', 'Anda harus memilih setidaknya satu peserta untuk diundang.')->withInput();
            }
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error undang peserta', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Gagal undang peserta: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan peserta secara massal berdasarkan kriteria (semua atau per divisi).
     */
    public function storeBulk(Request $request, Event $event)
    {
        set_time_limit(0);
        try {
            $validated = $request->validate([
                'invite_method' => 'required|string|in:all,division',
                'division' => 'nullable|required_if:invite_method,division|string',
            ]);

            $query = User::query()->where('role', 'participant');
            if ($validated['invite_method'] == 'division' && !empty($validated['division'])) {
                $query->where('division', $validated['division']);
            }

            $existingParticipantIds = $event->participants()->pluck('users.id')->toArray();
            $potentialParticipants = $query->whereNotIn('id', $existingParticipantIds)->get();

            if ($potentialParticipants->isEmpty()) {
                return back()->with('error', 'Tidak ada peserta baru untuk diundang.');
            }

            $userIds = $potentialParticipants->pluck('id')->toArray();
            $event->participants()->attach($userIds);

            // BAGIAN PENGIRIMAN EMAIL DINONAKTIFKAN
            /*
            $chunks = $potentialParticipants->chunk(10);
            foreach ($chunks as $chunk) {
                foreach ($chunk as $participant) {
                    try {
                        // Mail::to($participant->email)->queue(new EventInvitationMail($event, $participant));
                        Log::info('Email invitation (TESTING - NOT SENT) queued for bulk user: ' . $participant->email);
                    } catch (\Exception $e) {
                        Log::error('Failed to queue bulk email for user: ' . $participant->email);
                    }
                }
                sleep(2);
            }
            */
            
            return back()->with('success', count($userIds) . ' peserta massal berhasil diundang.'); // Pesan diubah
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi undangan massal gagal', ['errors' => $e->errors(), 'request' => $request->all()]);
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error saat undang massal', ['message' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeExternal(Request $request, Event $event)
    {
        set_time_limit(0);
        try {
            $validated = $request->validate([
                'full_name'    => 'required|string|max:255',
                'email'        => 'required|email|max:255',
                'institution'  => 'required|string|max:255',
                'position'     => 'required|string|max:255',
                'phone_number' => 'nullable|string|max:20',
            ]);

            if ($event->participants()->where('email', $validated['email'])->exists()) {
                return back()->with('error', 'Peserta dengan email ini sudah diundang ke event ini.');
            }

            $participant = User::firstOrNew(['email' => $validated['email']]);
            $isNew = !$participant->exists;

            $password = null;
            if ($isNew) {
                $password = Str::random(12);
                $participant->password = bcrypt($password);
                $participant->role = 'participant';
            }

            $participant->fill([
                'full_name'    => $validated['full_name'],
                'institution'  => $validated['institution'],
                'position'     => $validated['position'],
                'phone_number' => $validated['phone_number'],
            ]);
            $participant->save();

            $event->participants()->attach($participant->id);

            // BAGIAN PENGIRIMAN EMAIL DINONAKTIFKAN
            /*
            try {
                // Mail::to($participant->email)->queue(new EventInvitationMail($event, $participant, $isNew ? $password : null));
                Log::info('Email invitation (TESTING - NOT SENT) for external user: ' . $participant->email . ' to event: ' . $event->title);
            } catch (\Exception $e) {
                Log::error('Failed to send external email for user: ' . $participant->email, ['error' => $e->getMessage(), 'event_id' => $event->id]);
            }
            */

            return back()->with('success', 'Peserta eksternal berhasil diundang.'); // Pesan diubah
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator, 'external')->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal undang peserta eksternal', ['message' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat mengundang peserta eksternal.');
        }
    }

    public function manualAttendance(Event $event, User $user)
    {
        if (!$event->isActiveForAttendance()) {
            return back()->with('error', 'Event tidak sedang berlangsung.');
        }

        if (!$event->participants()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User bukan peserta event ini.');
        }

        $alreadyAttended = Attendance::where('event_id', $event->id)->where('user_id', $user->id)->exists();
        if ($alreadyAttended) {
            return back()->with('warning', 'Peserta sudah tercatat hadir.');
        }

        Attendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'check_in_time' => now(),
        ]);

        Log::info('Admin manual attendance', ['admin_id' => Auth::id(), 'user_id' => $user->id, 'event_id' => $event->id]);

        return back()->with('success', 'Peserta berhasil dihadirkan secara manual.');
    }

    public function bulkAttendance(Request $request, Event $event)
    {
        if (!$request->ajax()) {
            abort(404); // Pastikan hanya AJAX request yang diproses
        }

        $userIds = $request->input('user_ids', []);

        if (empty($userIds)) {
            return response()->json(['error' => 'Tidak ada peserta yang dipilih.'], 400);
        }

        if (!$event->isActiveForAttendance()) {
            return response()->json(['error' => 'Event tidak sedang berlangsung.'], 400);
        }

        $attendedCount = 0;
        foreach ($userIds as $userId) {
            if (!$event->participants()->where('user_id', $userId)->exists()) {
                continue;
            }

            $alreadyAttended = Attendance::where('event_id', $event->id)->where('user_id', $userId)->exists();
            if (!$alreadyAttended) {
                Attendance::create([
                    'event_id' => $event->id,
                    'user_id' => $userId,
                    'check_in_time' => now(),
                ]);
                $attendedCount++;
            }
        }

        Log::info('Admin bulk manual attendance', ['admin_id' => Auth::id(), 'user_ids' => $userIds, 'event_id' => $event->id]);

        return response()->json(['success' => "$attendedCount peserta berhasil dihadirkan secara manual."]);
    }

    /**
     * Menghapus seorang peserta dari event.
     */
    public function destroy(Event $event, User $user)
    {
        $event->participants()->detach($user->id);
        return back()->with('success', 'Peserta undangan berhasil dihapus.');
    }

    /**
    * Mengambil daftar peserta untuk DataTables (AJAX).
    */
    public function list(Request $request, Event $event)
    {
        try {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $search = $request->input('search.value');
            
            $query = $event->participants()->with(['attendances' => function ($query) use ($event) {
                $query->where('event_id', $event->id);
            }]);
            
            // Filter berdasarkan pencarian
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                });
            }
            
            $totalRecords = $query->count();
            
            // Pagination
            $participants = $query->offset($start)
                                  ->limit($length)
                                  ->get();
            
            $data = $participants->map(function ($participant, $key) use ($event, $start) { // Tambahkan $key
            $attendance = $participant->attendances->first();
            return [
                'checkbox' => $attendance ? '' :
                    '<div class="flex justify-center"><input type="checkbox" name="user_ids[]" value="' . $participant->id . '" class="participant-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></div>',
                'DT_RowIndex' => $start + $key + 1, // Gunakan $key untuk nomor urut yang benar
                'full_name' => $participant->full_name,
                'email' => $participant->email,
                'attendance_status' => $attendance ?
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir pada ' . $attendance->check_in_time->format('d M Y, H:i') . '</span>' :
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Belum Hadir</span>',
                'actions' => view('admin.events.participants.actions', compact('event', 'participant', 'attendance'))->render(),
            ];
        });
            
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in participants list: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
}
