<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use App\Exports\EventReportExport; // <-- IMPORT KELAS BARU
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category_id = $request->input('category_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $status = $request->input('status');

        $query = Event::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }
        if ($category_id) {
            $query->where('category_id', $category_id);
        }
        if ($start_date) {
            $query->where('start_time', '>=', $start_date);
        }
        if ($end_date) {
            $query->where('end_time', '<=', $end_date);
        }
        if ($status) {
            $now = now();
            $query->where(function ($q) use ($status, $now) {
                if ($status === 'Terjadwal') {
                    $q->where('start_time', '>', $now)->where('status', '!=', 'Dibatalkan');
                } elseif ($status === 'Berlangsung') {
                    $q->where('start_time', '<=', $now)->where('end_time', '>=', $now)->where('status', '!=', 'Dibatalkan');
                } elseif ($status === 'Selesai') {
                    $q->where('end_time', '<', $now)->where('status', '!=', 'Dibatalkan');
                } elseif ($status === 'Dibatalkan') {
                    $q->where('status', 'Dibatalkan');
                }
            });
        }

        $events = $query->with('category')->paginate(9)->appends(request()->all());
        $categories = Category::all();
        $stats = [
            'total' => Event::count(),
            'berlangsung' => Event::where('start_time', '<=', now())->where('end_time', '>=', now())->count(),
            'bulan_ini' => Event::whereMonth('start_time', now()->month)->count(),
        ];

        return view('admin.events.index', compact('events', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $event = new Event($validated);
        $event->creator_id = auth()->id();
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }

    public function show(Request $request, Event $event)
    {
        $participants = $event->participants()->with(['attendances' => function ($query) use ($event) {
            $query->where('event_id', $event->id);
        }])->paginate(10);

        $existingParticipantIds = $event->participants()->pluck('users.id');
        $potentialParticipants = User::where('role', 'participant')
            ->where('institution', 'PSDMBP')
            ->whereNotIn('id', $existingParticipantIds)
            ->orderBy('full_name')
            ->get();

        $activeTab = $request->query('tab', 'detail');

        return view('admin.events.show', compact('event', 'activeTab', 'potentialParticipants'));
    }

    public function edit(Event $event)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:Terjadwal,Dibatalkan',
            'category_id' => 'required|exists:categories,id',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.show', $event)->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function showQrCode(Event $event)
    {
        $event->load('participants', 'attendances');
        if (!$event->code) {
            abort(404, 'Kode QR untuk event ini tidak ditemukan.');
        }

        $qrCode = new QrCode(
            data: $event->code,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeDataUri = $result->getDataUri();

        return view('admin.events.qrcode', compact('event', 'qrCodeDataUri'));
    }

    /**
     * Mengekspor laporan lengkap event dalam satu file Excel dengan beberapa sheet.
     */
    public function export(Event $event)
    {
        $fileName = 'Laporan Event - ' . Str::slug($event->title) . '.xlsx';
        return (new EventReportExport($event))->download($fileName);
    }

    /**
     * Manual sync event to all participants' Google Calendars
     */
    public function syncCalendar(Event $event)
    {
        try {
            $participants = $event->participants;
            $totalParticipants = $participants->count();

            \Illuminate\Support\Facades\Log::info('Admin manual calendar sync started', [
                'event_id' => $event->id,
                'admin_id' => \Illuminate\Support\Facades\Auth::id(),
                'participant_count' => $totalParticipants
            ]);

            if ($totalParticipants === 0) {
                $message = 'Tidak ada peserta yang terdaftar untuk event ini.';
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message, 'type' => 'warning']);
                }
                return redirect()->back()->with('warning', $message);
            }

            // Get participants with Google Calendar access
            $connectedParticipants = $participants->filter(function ($user) {
                return $user->hasValidGoogleCalendarAccess();
            });

            if ($connectedParticipants->isEmpty()) {
                $message = "Tidak ada peserta yang menghubungkan Google Calendar mereka. Dari {$totalParticipants} peserta, 0 memiliki akses Google Calendar.";
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message, 'type' => 'warning']);
                }
                return redirect()->back()->with('warning', $message);
            }

            // Dispatch background job for sync
            \App\Jobs\SyncCalendarJob::dispatch($event, $connectedParticipants, 'bulk');

            $message = "Sinkronisasi calendar sedang diproses di background untuk {$connectedParticipants->count()} peserta yang terhubung. Anda akan menerima notifikasi saat selesai.";

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'type' => 'info',
                    'stats' => [
                        'total_participants' => $totalParticipants,
                        'connected_participants' => $connectedParticipants->count(),
                        'queued_for_sync' => $connectedParticipants->count()
                    ]
                ]);
            }

            return redirect()->back()->with('info', $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Manual calendar sync failed', [
                'event_id' => $event->id,
                'admin_id' => \Illuminate\Support\Facades\Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $message = 'Terjadi error saat menyinkronkan: ' . $e->getMessage();
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message, 'type' => 'error']);
            }
            return redirect()->back()->with('error', $message);
        }
    }
}
