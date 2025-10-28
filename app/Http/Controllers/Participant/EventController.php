<?php

namespace App\Http\Controllers\Participant;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->participatedEvents()->with('attendances');

        // Filter berdasarkan nama event
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('start_time', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('end_time', '<=', $request->end_date);
        }

        // Filter berdasarkan status event
        if ($request->has('status') && $request->status != '') {
            $query->byStatus($request->status);
        }

        $events = $query->latest()->paginate(10)->withQueryString();

        // Ambil ID semua event yang sudah dihadiri oleh user
        $attendedEventIds = $user->attendances->pluck('event_id')->toArray();

        // Ambil semua kategori untuk dropdown
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('participant.events.index', compact('events', 'attendedEventIds', 'categories'));
    }

    public function show(Event $event)
    {
        // Pastikan user adalah peserta event ini
        if (!auth()->user()->participatedEvents()->where('event_id', $event->id)->exists()) {
            abort(403, 'Anda tidak diundang ke acara ini.');
        }

        // Muat semua relasi yang dibutuhkan oleh view
        $event->load('creator', 'category', 'documents');

        // Ambil data kehadiran user saat ini untuk event ini
        $attendance = $event->attendances()->where('user_id', auth()->id())->first();

        return view('participant.events.show', compact('event', 'attendance'));
    }

    /**
     * Manual sync user's events to Google Calendar
     */
    public function syncCalendar(Request $request)
    {
        try {
            $user = Auth::user();

            $participatedEvents = $user->participatedEvents;
            $totalEvents = $participatedEvents->count();

            \Illuminate\Support\Facades\Log::info('Participant manual calendar sync started', [
                'user_id' => $user->id,
                'event_count' => $totalEvents
            ]);

            if ($totalEvents === 0) {
                $message = 'Anda belum terdaftar sebagai peserta di event manapun.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                return redirect()->back()->with('warning', $message);
            }

            $calendarService = app(\App\Services\GoogleCalendarService::class);

            // Sync all events that user participates in
            $successCount = 0;
            $failedCount = 0;
            $syncDetails = [];

            foreach ($participatedEvents as $event) {
                try {
                    $result = $calendarService->syncEventToUserCalendar($event, $user);
                    if ($result) {
                        $successCount++;
                        $syncDetails[] = "✓ {$event->title}";
                    } else {
                        $failedCount++;
                        $syncDetails[] = "✗ {$event->title} (gagal)";
                    }
                } catch (\Exception $eventException) {
                    $failedCount++;
                    $syncDetails[] = "✗ {$event->title} (error: {$eventException->getMessage()})";
                    \Illuminate\Support\Facades\Log::warning('Event sync failed in batch', [
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'error' => $eventException->getMessage()
                    ]);
                }
            }

            \Illuminate\Support\Facades\Log::info('Participant manual calendar sync completed', [
                'user_id' => $user->id,
                'total_events' => $totalEvents,
                'successful_syncs' => $successCount,
                'failed_syncs' => $failedCount
            ]);

            if ($successCount > 0) {
                $message = "Berhasil mensinkronkan {$successCount} dari {$totalEvents} event ke Google Calendar Anda.";
                if ($failedCount > 0) {
                    $message .= " {$failedCount} event gagal disinkronkan.";
                }
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'synced_count' => $successCount,
                        'failed_count' => $failedCount,
                        'total_count' => $totalEvents
                    ]);
                }
                return redirect()->back()->with('success', $message);
            } else {
                $message = "Gagal menyinkronkan semua {$totalEvents} event ke Google Calendar. Pastikan koneksi Google Calendar Anda masih aktif dan coba lagi.";
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'synced_count' => 0,
                        'failed_count' => $failedCount,
                        'total_count' => $totalEvents
                    ]);
                }
                return redirect()->back()->with('error', $message);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Participant manual calendar sync failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $message = 'Terjadi error saat menyinkronkan: ' . $e->getMessage();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }
}
