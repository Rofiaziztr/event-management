<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\EventParticipantsExport;
use App\Exports\EventSummaryExport;
use App\Exports\AttendanceReportExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventInvitationMail;
use Illuminate\Support\Facades\Log;

class ParticipantController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $userIds = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ])['user_ids'];

        $existing = $event->participants()->pluck('user_id');
        $newIds = array_diff($userIds, $existing->toArray());

        if (empty($newIds)) {
            return back()->with('info', 'Semua peserta yang dipilih sudah terdaftar.');
        }

        $event->participants()->attach($newIds);

        // Kirim email undangan ke peserta baru
        $newUsers = User::find($newIds);
        foreach ($newUsers as $user) {
            try {
                Mail::to($user->email)->queue(new EventInvitationMail($event, $user));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email undangan ke: ' . $user->email, [
                    'error' => $e->getMessage(),
                    'event_id' => $event->id,
                ]);
            }
        }

        return back()->with('success', count($newIds) . ' peserta berhasil diundang. Notifikasi email telah dikirim.');
    }

    public function inviteAllAvailable(Request $request, Event $event)
    {
        $query = User::where('role', 'participant')->where('institution', 'PSDMBP');
        $existing = $event->participants()->pluck('user_id');
        $newUsers = $query->whereNotIn('id', $existing)->get();

        if ($newUsers->isEmpty()) {
            return back()->with('info', 'Tidak ada peserta baru untuk diundang.');
        }

        $event->participants()->attach($newUsers->pluck('id'));

        // Kirim email undangan ke semua peserta baru
        foreach ($newUsers as $user) {
            try {
                Mail::to($user->email)->queue(new EventInvitationMail($event, $user));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email undangan massal (semua) ke: ' . $user->email, [
                    'error' => $e->getMessage(),
                    'event_id' => $event->id,
                ]);
            }
        }

        return back()->with('success', $newUsers->count() . ' peserta berhasil diundang. Notifikasi email telah dikirim.');
    }

    public function inviteByDivision(Request $request, Event $event)
    {
        $division = $request->validate(['division' => 'required|string'])['division'];

        $query = User::where('role', 'participant')
            ->where('institution', 'PSDMBP')
            ->where('division', $division);

        $existing = $event->participants()->pluck('user_id');
        $newUsers = $query->whereNotIn('id', $existing)->get();

        if ($newUsers->isEmpty()) {
            return back()->with('info', "Tidak ada peserta baru di divisi '$division'.");
        }

        $event->participants()->attach($newUsers->pluck('id'));

        // Kirim email undangan ke peserta baru per divisi
        foreach ($newUsers as $user) {
            try {
                Mail::to($user->email)->queue(new EventInvitationMail($event, $user));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email undangan massal (divisi) ke: ' . $user->email, [
                    'error' => $e->getMessage(),
                    'event_id' => $event->id,
                    'division' => $division,
                ]);
            }
        }

        return back()->with('success', count($newUsers) . " peserta dari divisi '$division' berhasil diundang. Notifikasi email telah dikirim.");
    }

    public function storeExternal(Request $request, Event $event)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'institution' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user = User::firstOrNew(['email' => $data['email']]);
        $isNew = !$user->exists;
        $password = null;

        if ($isNew) {
            $password = Str::random(12);
            $user->password = bcrypt($password);
            $user->role = 'participant';
        }

        $user->fill($data);
        $user->save();

        if ($event->participants()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Peserta ini sudah diundang ke event ini.');
        }

        $event->participants()->attach($user->id);

        // Kirim email undangan (sertakan password jika user baru)
        try {
            Mail::to($user->email)->queue(new EventInvitationMail($event, $user, $isNew ? $password : null));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email undangan eksternal ke: ' . $user->email, [
                'error' => $e->getMessage(),
                'event_id' => $event->id,
                'is_new_user' => $isNew,
            ]);
        }

        return back()->with('success', 'Peserta eksternal berhasil diundang. Notifikasi email telah dikirim.');
    }

    public function bulkAttendance(Request $request, Event $event)
    {
        if (!$request->ajax()) abort(404);

        $userIds = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ])['user_ids'];

        if (!$event->isActiveForAttendance()) {
            return response()->json(['error' => 'Event tidak sedang berlangsung.'], 422);
        }

        $attended = 0;
        foreach ($userIds as $id) {
            if (
                $event->participants()->where('user_id', $id)->exists() &&
                !Attendance::where('event_id', $event->id)->where('user_id', $id)->exists()
            ) {
                Attendance::create(['event_id' => $event->id, 'user_id' => $id, 'check_in_time' => now()]);
                $attended++;
            }
        }

        return response()->json(['success' => "$attended peserta berhasil dihadirkan."]);
    }

    public function destroy(Event $event, User $user)
    {
        // Hapus attendance terkait
        Attendance::where('event_id', $event->id)->where('user_id', $user->id)->delete();
        $event->participants()->detach($user->id);
        return back()->with('success', 'Peserta berhasil dihapus.');
    }

    public function list(Request $request, Event $event)
    {
        $search = $request->input('search.value');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $query = $event->participants()->with(['attendances' => fn($q) => $q->where('event_id', $event->id)]);

        if ($search) {
            $query->where(fn($q) => $q->where('full_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('division', 'like', "%$search%"));
        }

        $total = $query->count();
        $participants = $query->skip($start)->take($length)->get();

        $data = $participants->map(fn($p, $i) => [
            'checkbox' => $p->attendances->isEmpty()
                ? "<input type='checkbox' name='user_ids[]' value='{$p->id}' class='bulk-checkbox'>"
                : '',
            'DT_RowIndex' => $start + $i + 1,
            'full_name' => e($p->full_name),
            'email' => e($p->email),
            'attendance_status' => $p->attendances->isEmpty()
                ? '<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Belum Hadir</span>'
                : '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Hadir</span>',
            'actions' => view('admin.events.participants.actions', compact('event', 'p'))->render(),
        ]);

        return response()->json([
            'draw' => $request->input('draw', 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    public function manualAttendance(Request $request, Event $event, User $user)
    {
        if (!$event->participants()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User bukan peserta event ini.');
        }

        if (Attendance::where('event_id', $event->id)->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'Peserta sudah tercatat hadir.');
        }

        Attendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'check_in_time' => now()
        ]);

        return back()->with('success', 'Peserta berhasil dihadirkan secara manual.');
    }

    /**
     * Export detailed participants report with professional styling
     */
    public function export(Event $event, Request $request)
    {
        $type = $request->get('type', 'detailed'); // detailed, summary, attendance_only

        $filename = $this->generateExportFilename($event, $type);

        switch ($type) {
            case 'summary':
                return Excel::download(new EventSummaryExport($event), $filename);
            case 'attendance_only':
                return Excel::download(new AttendanceReportExport($event), $filename);
            case 'detailed':
            default:
                return Excel::download(new EventParticipantsExport($event), $filename);
        }
    }



    /**
     * Export participants with custom filters
     */
    public function exportFiltered(Event $event, Request $request)
    {
        $filters = $request->only(['division', 'institution', 'attendance_status']);
        $filename = $this->generateExportFilename($event, 'filtered', $filters);

        return Excel::download(new EventParticipantsExport($event, $filters), $filename);
    }

    /**
     * Generate appropriate filename for exports
     */
    private function generateExportFilename(Event $event, string $type, array $filters = []): string
    {
        $baseTitle = 'Peserta_' . Str::slug($event->title, '_');
        $timestamp = now()->format('Y-m-d_H-i');

        $typeMap = [
            'detailed' => 'Detail',
            'summary' => 'Ringkasan',
            'attendance_only' => 'Kehadiran',
            'filtered' => 'Filter'
        ];

        $filename = $baseTitle . '_' . ($typeMap[$type] ?? 'Export') . '_' . $timestamp;

        // Add filter info to filename
        if (!empty($filters)) {
            $filterParts = [];
            if (isset($filters['division'])) {
                $filterParts[] = 'Div-' . Str::slug($filters['division']);
            }
            if (isset($filters['attendance_status'])) {
                $filterParts[] = $filters['attendance_status'] === 'attended' ? 'Hadir' : 'TidakHadir';
            }
            if (!empty($filterParts)) {
                $filename .= '_' . implode('_', $filterParts);
            }
        }

        return $filename . '.xlsx';
    }
}
