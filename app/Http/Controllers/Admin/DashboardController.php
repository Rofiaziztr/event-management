<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic statistics
        $totalEvents = Event::count();
        $totalParticipants = User::where('role', 'participant')->count();
        $activeEvents = Event::where('status', 'Berlangsung')->count();
        $totalAttendances = Attendance::count();

        // Monthly statistics
        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyEvents = Event::where('created_at', '>=', $currentMonth)->count();
        $monthlyAttendances = Attendance::where('created_at', '>=', $currentMonth)->count();

        // Average attendance rate - exclude canceled events
        $eventsWithParticipants = Event::withCount(['participants', 'attendances'])
            ->where('status', '!=', 'Dibatalkan')
            ->get();
        
        $totalRate = 0;
        $eventCount = 0;
        
        foreach ($eventsWithParticipants as $event) {
            if ($event->participants_count > 0) {
                $totalRate += ($event->attendances_count / $event->participants_count) * 100;
                $eventCount++;
            }
        }
        
        $averageAttendanceRate = $eventCount > 0 ? round($totalRate / $eventCount) : 0;

        // Recent events (last 10)
        $recentEvents = Event::with(['participants', 'attendances'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Events needing attention (starting soon or ongoing)
        $eventsNeedingAttention = Event::where(function ($query) {
            $query->where('status', 'Berlangsung')
                  ->orWhere(function ($subQuery) {
                      $subQuery->where('status', 'Terjadwal')
                               ->where('start_time', '<=', Carbon::now()->addHours(2));
                  });
        })->get();

        // Total documents
        $totalDocuments = Document::count();

        // NEW: Upcoming events in the next 7 days
        $upcomingEvents = Event::where('status', 'Terjadwal')
            ->whereBetween('start_time', [Carbon::now(), Carbon::now()->addDays(7)])
            ->orderBy('start_time')
            ->get();

        // NEW: Events with low attendance rate (less than 50%)
        $eventsWithAttendance = Event::withCount(['participants', 'attendances'])
            ->where('status', '!=', 'Dibatalkan')
            ->get()
            ->filter(function ($event) {
                return $event->participants_count > 0;
            })
            ->map(function ($event) {
                $event->attendance_rate = $event->participants_count > 0 
                    ? round(($event->attendances_count / $event->participants_count) * 100)
                    : 0;
                return $event;
            });
        
        $lowAttendanceEvents = $eventsWithAttendance->where('attendance_rate', '<', 50)->take(5);

        // NEW: Recent attendees (last 10)
        $recentAttendances = Attendance::with(['user', 'event'])
            ->orderBy('check_in_time', 'desc')
            ->take(10)
            ->get();

        // NEW: Event status distribution
        $eventStatusStats = Event::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // NEW: Daily attendance trend (last 7 days)
        $attendanceTrend = Attendance::select(DB::raw('DATE(check_in_time) as date'), DB::raw('count(*) as count'))
            ->where('check_in_time', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // System health (more realistic calculation)
        $systemHealth = 100; // Base value
        // Deduct points for events needing attention
        if ($eventsNeedingAttention->count() > 3) {
            $systemHealth -= 10;
        }
        // Deduct points for low attendance events
        if ($lowAttendanceEvents->count() > 2) {
            $systemHealth -= 5;
        }

        // Recent activities (more dynamic based on actual data)
        $recentActivities = collect();
        
        // Add recent event creations
        $recentEventCreations = Event::orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($event) {
                return [
                    'type' => 'event_created',
                    'message' => 'Event baru "' . $event->title . '" telah dibuat',
                    'time' => $event->created_at->diffForHumans()
                ];
            });
        
        $recentActivities = $recentActivities->merge($recentEventCreations);
        
        // Add recent attendances if available
        if ($recentAttendances->count() > 0) {
            $recentActivities->push([
                'type' => 'attendance',
                'message' => $recentAttendances->count() . ' presensi tercatat baru-baru ini',
                'time' => $recentAttendances->first()->check_in_time->diffForHumans()
            ]);
        }

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalParticipants', 
            'activeEvents',
            'totalAttendances',
            'monthlyEvents',
            'monthlyAttendances',
            'averageAttendanceRate',
            'recentEvents',
            'eventsNeedingAttention',
            'totalDocuments',
            'systemHealth',
            'recentActivities',
            'upcomingEvents',
            'lowAttendanceEvents',
            'recentAttendances',
            'eventStatusStats',
            'attendanceTrend'
        ));
    }
}