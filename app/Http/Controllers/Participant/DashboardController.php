<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $user->load(['participatedEvents', 'attendances.event']);
        $period = $request->input('period', 'monthly');
        $chartType = $request->input('chart_type', 'line');
        $allParticipatedEvents = $user->participatedEvents()
            ->where('status', '!=', 'Dibatalkan')
            ->get();

        $attendedEventIds = $user->attendances->pluck('event_id')->unique()->toArray();
        $upcomingEvents = $allParticipatedEvents
            ->where('start_time', '>', now())
            ->sortBy('start_time');
        $ongoingEvents = $allParticipatedEvents
            ->filter(fn($event) => $event->status === 'Berlangsung')
            ->sortBy('start_time');
        $finishedEvents = $allParticipatedEvents
            ->where('start_time', '<=', now());

        $totalInvitations = $finishedEvents->count();
        $attendedCount = collect($attendedEventIds)->intersect($finishedEvents->pluck('id'))->count();
        $missedEventsCount = $totalInvitations - $attendedCount;
        $attendanceRate = ($totalInvitations > 0) ? round(($attendedCount / $totalInvitations) * 100) : 0;
        $chartData = $this->getChartData($user, $period);
        return view('participant.dashboard.index', compact(
            'totalInvitations',
            'attendedCount',
            'attendanceRate',
            'missedEventsCount',
            'ongoingEvents',
            'upcomingEvents',
            'attendedEventIds',
            'chartData',
            'period',
            'chartType'
        ));
    }
    private function getChartData($user, $period)
    {
        $data = collect();
        $labels = collect();
        $now = Carbon::now();
        if ($period == 'yearly') {
            for ($i = 11; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                $labels->push($month->format('M Y'));
                $eventsInMonth = $user->participatedEvents()->whereYear('start_time', $month->year)->whereMonth('start_time', $month->month)->where('status', '!=', 'Dibatalkan')->count();
                $attendanceInMonth = $user->attendances()->whereYear('check_in_time', $month->year)->whereMonth('check_in_time', $month->month)->count();
                $data->push($eventsInMonth > 0 ? round(($attendanceInMonth / $eventsInMonth) * 100) : 0);
            }
        } elseif ($period == 'monthly') {
            for ($i = 29; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $labels->push($date->format('d M'));
                $eventsOnDate = $user->participatedEvents()->whereDate('start_time', $date)->where('status', '!=', 'Dibatalkan')->count();
                $attendanceOnDate = $user->attendances()->whereDate('check_in_time', $date)->count();
                $data->push($eventsOnDate > 0 ? round(($attendanceOnDate / $eventsOnDate) * 100) : 0);
            }
        } else { // weekly
            for ($i = 6; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $labels->push($date->format('D, d M'));
                $eventsOnDate = $user->participatedEvents()->whereDate('start_time', $date)->where('status', '!=', 'Dibatalkan')->count();
                $attendanceOnDate = $user->attendances()->whereDate('check_in_time', $date)->count();
                $data->push($eventsOnDate > 0 ? round(($attendanceOnDate / $eventsOnDate) * 100) : 0);
            }
        }
        // Pastikan mengembalikan array dengan struktur yang benar
        return [
            'labels' => $labels->toArray(),
            'data' => $data->toArray(),
        ];
    }
}
