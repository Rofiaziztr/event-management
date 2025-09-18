<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Document;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index(Request $request)
{
    // Base query
    $eventQuery = Event::query();

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $eventQuery->whereBetween('start_time', [$startDate, $endDate]);
    }

    // Statistics dasar
    $totalEvents = $eventQuery->count();
    $totalParticipants = User::where('role', 'participant')->count();
    $totalAttendances = Attendance::count();

    // Rata-rata kehadiran
    $attendanceData = $eventQuery->withCount(['participants', 'attendances'])
        ->having('participants_count', '>', 0)
        ->get();

    $averageAttendance = $attendanceData->isNotEmpty()
        ? number_format($attendanceData->avg(fn($e) => ($e->attendances_count / $e->participants_count) * 100), 1)
        : 0;

    // Monthly stats
    $currentMonth =now()->startOfMonth();
    $monthlyEvents = Event::where('created_at', '>=', $currentMonth)->count();
    $monthlyAttendances = Attendance::where('created_at', '>=', $currentMonth)->count();
    $activeEvents = Event::where('status', 'Berlangsung')->count();
    $totalDocuments = Document::count();

    // Chart data
    $groupBy = $request->get('group_by', 'events');
    $topN = $request->get('top_n', 5); // Bisa custom, default 5

    $eventAttendance = $this->getChartData($eventQuery, $groupBy, $request, $topN);

    // Aktivitas terbaru
    $recentEvents = Event::with('participants')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

    return view('admin.dashboard.index', compact(
    'totalEvents',
    'totalParticipants',
    'totalAttendances',
    'averageAttendance',
    'eventAttendance',
    'recentEvents',
    'monthlyEvents',
    'monthlyAttendances',
    'activeEvents',
    'totalDocuments',
    'topN'
));
}

private function getChartData($eventQuery, $groupBy, $request, $topN)
{
    $events = $eventQuery->with(['participants', 'attendances'])->get();

    switch ($groupBy) {
        case 'daily':
            return $events->groupBy(fn($e) => $e->start_time->format('Y-m-d'))
                ->map(function ($group, $date) {
                    $label = Carbon::parse($date)->format('d M Y');
                    return [
                        'label' => $label,
                        'title' => $label,
                        'attendance_count' => $group->sum(fn($e) => $e->attendances->count()),
                        'absent_count' => $group->sum(fn($e) => $e->participants->count() - $e->attendances->count())
                    ];
                })->values();

        case 'monthly':
            return $events->groupBy(fn($e) => $e->start_time->format('Y-m'))
                ->map(function ($group, $month) {
                    $label = Carbon::createFromFormat('Y-m', $month)->format('M Y');
                    return [
                        'label' => $label,
                        'title' => $label,
                        'attendance_count' => $group->sum(fn($e) => $e->attendances->count()),
                        'absent_count' => $group->sum(fn($e) => $e->participants->count() - $e->attendances->count())
                    ];
                })->values();

        default: // events
            return $events->map(fn($e) => [
                    'label' => $e->title . ' (' . $e->start_time->format('d M') . ')',
                    'title' => $e->title,
                    'attendance_count' => $e->attendances->count(),
                    'absent_count' => $e->participants->count() - $e->attendances->count()
                ])
                ->sortByDesc('attendance_count')
                ->take($topN)
                ->values();
    }
}

    public function export(Request $request)
{
    $eventQuery = Event::query();
    
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $eventQuery->whereDate('start_time', '>=', $startDate)
                 ->whereDate('start_time', '<=', $endDate);
    }

    $events = $eventQuery->with('participants', 'attendances')->get();

    $data = [
        'events' => $events->map(fn($event) => [
            'title' => $event->title,
            'start_time' => $event->start_time->format('Y-m-d H:i'),
            'participants_count' => $event->participants_count,
            'attendance_count' => $event->attendances_count,
            'absent_count' => $event->participants_count - $event->attendance_count,
            'attendances' => $event->attendances->map(fn($attendance) => [
                'user_name' => $attendance->user->full_name,
                'check_in_time' => $attendance->check_in_time->format('Y-m-d H:i'),
            ])
        ])
    ];

    return response()->json($data);
}
}