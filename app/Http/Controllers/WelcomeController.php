<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get real statistics from database
        $totalEvents = Event::count();
        $totalUsers = User::where('role', 'participant')->count();
        $totalAttendances = Attendance::count();
        
        // Calculate average attendance rate
        $eventsWithParticipants = Event::withCount(['participants', 'attendances'])->get();
        $attendanceRates = [];
        
        foreach ($eventsWithParticipants as $event) {
            if ($event->participants_count > 0) {
                $rate = ($event->attendances_count / $event->participants_count) * 100;
                $attendanceRates[] = $rate;
            }
        }
        
        $averageAttendanceRate = count($attendanceRates) > 0 
            ? round(array_sum($attendanceRates) / count($attendanceRates)) 
            : 0;
        
        // Get upcoming events for showcase
        $upcomingEvents = Event::where('start_time', '>', Carbon::now())
            ->where('status', '!=', 'Dibatalkan')
            ->withCount('participants')
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get();
        
        // Get recent successful events
        $recentEvents = Event::where('status', 'Selesai')
            ->withCount(['participants', 'attendances'])
            ->orderBy('end_time', 'desc')
            ->take(3)
            ->get();
        
        // Monthly growth data
        $monthlyData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthEvents = Event::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $monthlyData->push([
                'month' => $month->format('M'),
                'events' => $monthEvents
            ]);
        }
        
        // Calculate activity metrics
        $thisMonthEvents = Event::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $thisMonthAttendances = Attendance::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        return view('welcome', compact(
            'totalEvents',
            'totalUsers', 
            'totalAttendances',
            'averageAttendanceRate',
            'upcomingEvents',
            'recentEvents',
            'monthlyData',
            'thisMonthEvents',
            'thisMonthAttendances'
        ));
    }
}