<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $user = auth()->user();
        
        // Load relationships dengan eager loading untuk optimasi
        $user->load(['participatedEvents', 'attendances']);
        
        $participatedEvents = $user->participatedEvents;
        $attendances = $user->attendances;

        // Filter out canceled events from all calculations
        $validEvents = $participatedEvents->reject(function ($event) {
            return $event->status === 'Dibatalkan';
        });

        // Get attended event IDs only for valid (non-canceled) events
        $attendedValidEvents = $attendances->filter(function ($attendance) use ($validEvents) {
            return $validEvents->contains('id', $attendance->event_id);
        });

        return view('participant.dashboard', [
            'totalEvents' => $validEvents->count(),
            'myAttendance' => $attendedValidEvents->count(),
            'activeEvents' => $validEvents->where('status', 'Berlangsung')->count(),
            'attendanceRate' => $validEvents->count() > 0 ? 
                round(($attendedValidEvents->count() / $validEvents->count()) * 100) : 0,
            'recentEvents' => $validEvents->sortByDesc('created_at')->take(10),
            'ongoingEvents' => $validEvents->where('status', 'Berlangsung'),
            'attendedEventIds' => $attendedValidEvents->pluck('event_id')->toArray(),
            // Tambahkan juga data untuk ditampilkan di view jika diperlukan
            'allEvents' => $participatedEvents, // Semua event termasuk yang dibatalkan
            'canceledEvents' => $participatedEvents->where('status', 'Dibatalkan')->count(), // Jumlah event yang dibatalkan
        ]);
    }
}
