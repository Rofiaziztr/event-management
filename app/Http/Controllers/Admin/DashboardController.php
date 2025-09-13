<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Document;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Base query untuk filtering
        $eventQuery = Event::query();
        
        // Apply date filter jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $eventQuery->whereBetween('start_time', [$startDate, $endDate]);
        }

        // Basic statistics
        $totalEvents = $eventQuery->count();
        $totalParticipants = User::where('role', 'participant')->count();
        $totalAttendances = Attendance::count();

        // Calculate average attendance rate
        $attendanceData = Event::withCount(['participants', 'attendances'])
            ->having('participants_count', '>', 0)
            ->get();

        $averageAttendance = 0;
        if ($attendanceData->count() > 0) {
            $totalRate = $attendanceData->sum(function ($event) {
                return ($event->attendances_count / $event->participants_count) * 100;
            });
            $averageAttendance = $totalRate / $attendanceData->count();
        }

        // Monthly statistics
        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyEvents = Event::where('created_at', '>=', $currentMonth)->count();
        $monthlyAttendances = Attendance::where('created_at', '>=', $currentMonth)->count();
        $activeEvents = Event::where('status', 'Berlangsung')->count();
        $totalDocuments = Document::count();

        // Chart data berdasarkan group_by parameter
        $groupBy = $request->get('group_by', 'events');
        $eventAttendance = $this->getChartData($eventQuery, $groupBy, $request);

        // Recent events untuk activity feed
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
            'totalDocuments'
        ));
    }

    private function getChartData($eventQuery, $groupBy, $request)
    {
        switch ($groupBy) {
            case 'daily':
                return $this->getDailyAttendanceData($request);
            
            case 'weekly':
                return $this->getWeeklyAttendanceData($request);
            
            case 'monthly':
                return $this->getMonthlyAttendanceData($request);
            
            default: // 'events'
                return $eventQuery->withCount('attendances')
                    ->orderBy('attendances_count', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function ($event) {
                        return [
                            'title' => $event->title,
                            'attendance_count' => $event->attendances_count,
                        ];
                    });
        }
    }

    private function getDailyAttendanceData($request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)
            : Carbon::now()->subDays(6);
        
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $dailyData = collect();
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $count = Attendance::whereDate('created_at', $current)
                ->count();
            
            $dailyData->push([
                'title' => $current->format('d M'),
                'attendance_count' => $count
            ]);
            
            $current->addDay();
        }

        return $dailyData;
    }

    private function getWeeklyAttendanceData($request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfWeek()
            : Carbon::now()->subWeeks(3)->startOfWeek();
        
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfWeek()
            : Carbon::now()->endOfWeek();

        $weeklyData = collect();
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $weekEnd = $current->copy()->endOfWeek();
            
            $count = Attendance::whereBetween('created_at', [
                $current->startOfWeek(),
                $weekEnd
            ])->count();
            
            $weeklyData->push([
                'title' => 'Minggu ' . $current->format('d M'),
                'attendance_count' => $count
            ]);
            
            $current->addWeek();
        }

        return $weeklyData;
    }

    private function getMonthlyAttendanceData($request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfMonth()
            : Carbon::now()->subMonths(5)->startOfMonth();
        
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfMonth()
            : Carbon::now()->endOfMonth();

        $monthlyData = collect();
        $current = $startDate->copy();
    }
}