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
        $user->load(['participatedEvents.category', 'attendances.event.category']);
        $historyPeriod = $request->input('history_period', 'all');
        $search = $request->input('search', '');

        $allParticipatedEvents = $user->participatedEvents()
            ->where('status', '!=', 'Dibatalkan')
            ->with(['category', 'attendances' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->get();

        $attendedEventIds = $user->attendances->pluck('event_id')->unique()->toArray();
        $upcomingEvents = $allParticipatedEvents
            ->where('start_time', '>', now())
            ->sortBy('start_time')
            ->take(5);
        $ongoingEvents = $allParticipatedEvents
            ->filter(fn($event) => $event->status === 'Berlangsung')
            ->sortBy('start_time');
        $finishedEvents = $allParticipatedEvents
            ->where('start_time', '<=', now());

        $totalInvitations = $finishedEvents->count();
        $attendedCount = collect($attendedEventIds)->intersect($finishedEvents->pluck('id'))->count();
        $missedEventsCount = $totalInvitations - $attendedCount;
        $attendanceRate = ($totalInvitations > 0) ? round(($attendedCount / $totalInvitations) * 100) : 0;

        // History events with filter, search, and pagination
        $historyEventsQuery = $this->getHistoryEventsQuery($user, $historyPeriod, $search);
        $historyEvents = $historyEventsQuery->paginate(10); // Paginate with 10 items per page
        $totalHistoryEvents = $historyEventsQuery->count();

        return view('participant.dashboard.index', compact(
            'totalInvitations',
            'attendedCount',
            'attendanceRate',
            'missedEventsCount',
            'ongoingEvents',
            'upcomingEvents',
            'attendedEventIds',
            'historyEvents',
            'totalHistoryEvents',
            'historyPeriod',
            'search'
        ));
    }

    private function getHistoryEventsQuery($user, $historyPeriod, $search = '')
    {
        $query = $user->participatedEvents()
            ->with(['category', 'attendances' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->where('status', '!=', 'Dibatalkan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('category', fn($qc) => $qc->where('name', 'like', "%{$search}%"));
            });
        }

        // Apply period filter
        switch ($historyPeriod) {
            case 'last_year':
                $query->where('start_time', '>=', now()->subYear());
                break;
            case 'last_6_months':
                $query->where('start_time', '>=', now()->subMonths(6));
                break;
            case 'this_year':
                $query->whereYear('start_time', now()->year);
                break;
            default: // 'all'
                // No filter
                break;
        }

        return $query->orderBy('start_time', 'desc');
    }
}
