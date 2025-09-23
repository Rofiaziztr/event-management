<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $categoryPeriod = $request->input('category_period', 'this_month');
        $selectedCategory = $request->input('category_filter', 'all'); // Filter for category
        
        // Basic Statistics
        $totalEvents = Event::count();
        $totalParticipants = User::where('role', 'participant')->count();
        $totalAttendances = Attendance::count();
        $totalCategories = Category::count();

        // This month statistics
        $thisMonth = now()->startOfMonth();
        $eventsThisMonth = Event::where('created_at', '>=', $thisMonth)->count();
        $attendancesThisMonth = Attendance::where('created_at', '>=', $thisMonth)->count();
        $activeEvents = Event::where('status', 'Berlangsung')->count();
        $upcomingEvents = Event::where('status', 'Terjadwal')
            ->where('start_time', '>', now())
            ->count();

        // Average attendance calculation
        $avgAttendanceData = Event::withCount(['participants', 'attendances'])
            ->having('participants_count', '>', 0)
            ->get();
        
        $averageAttendanceRate = $avgAttendanceData->isNotEmpty() 
            ? round($avgAttendanceData->avg(fn($e) => ($e->attendances_count / $e->participants_count) * 100), 1)
            : 0;

        // Category Analysis - Most attended categories
        $categoryStats = $this->getCategoryAttendanceStats($categoryPeriod);
        
        // Event trend per category (last 12 months)
        $eventTrendStats = $this->getEventTrendStats($selectedCategory);
        $categories = Category::all(); // For category filter dropdown
        
        // Upcoming events (next 7 days)
        $upcomingEventsDetailed = Event::with(['participants', 'attendances', 'category'])
            ->where('start_time', '>', now())
            ->where('start_time', '<=', now()->addDays(7))
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();
            
        // Recent high-performing events
        $topPerformingEvents = Event::withCount(['participants', 'attendances'])
            ->having('participants_count', '>', 0)
            ->whereDate('start_time', '>=', now()->subDays(30))
            ->get()
            ->sortByDesc(function($event) {
                return $event->participants_count > 0 
                    ? ($event->attendances_count / $event->participants_count) * 100 
                    : 0;
            })
            ->take(5);

        // Event status distribution
        $eventStatusData = [
            'Terjadwal' => Event::where('status', 'Terjadwal')->count(),
            'Berlangsung' => Event::where('status', 'Berlangsung')->count(),
            'Selesai' => Event::where('status', 'Selesai')->count(),
            'Dibatalkan' => Event::where('status', 'Dibatalkan')->count(),
        ];

        // Monthly comparison
        $lastMonth = now()->subMonth()->startOfMonth();
        $eventsLastMonth = Event::whereBetween('created_at', [
            $lastMonth, 
            $lastMonth->copy()->endOfMonth()
        ])->count();
        
        $attendancesLastMonth = Attendance::whereBetween('created_at', [
            $lastMonth, 
            $lastMonth->copy()->endOfMonth()
        ])->count();

        $eventGrowth = $eventsLastMonth > 0 
            ? round((($eventsThisMonth - $eventsLastMonth) / $eventsLastMonth) * 100, 1)
            : ($eventsThisMonth > 0 ? 100 : 0);
            
        $attendanceGrowth = $attendancesLastMonth > 0 
            ? round((($attendancesThisMonth - $attendancesLastMonth) / $attendancesLastMonth) * 100, 1)
            : ($attendancesThisMonth > 0 ? 100 : 0);

        return view('admin.dashboard.index', compact(
            'totalEvents',
            'totalParticipants',
            'totalAttendances',
            'totalCategories',
            'eventsThisMonth',
            'attendancesThisMonth', 
            'activeEvents',
            'upcomingEvents',
            'averageAttendanceRate',
            'categoryStats',
            'eventTrendStats',
            'categories',
            'selectedCategory',
            'upcomingEventsDetailed',
            'topPerformingEvents',
            'eventStatusData',
            'eventGrowth',
            'attendanceGrowth',
            'period',
            'categoryPeriod'
        ));
    }

    private function getCategoryAttendanceStats($period)
    {
        $query = DB::table('categories')
            ->join('events', 'categories.id', '=', 'events.category_id')
            ->join('attendances', 'events.id', '=', 'attendances.event_id')
            ->select(
                'categories.name as category_name',
                'categories.id as category_id',
                DB::raw('COUNT(attendances.id) as total_attendances'),
                DB::raw('COUNT(DISTINCT events.id) as total_events')
            )
            ->groupBy('categories.id', 'categories.name');

        // Apply period filter
        switch ($period) {
            case 'this_week':
                $query->where('attendances.created_at', '>=', now()->startOfWeek());
                break;
            case 'this_month':
                $query->where('attendances.created_at', '>=', now()->startOfMonth());
                break;
            case 'this_year':
                $query->where('attendances.created_at', '>=', now()->startOfYear());
                break;
            case 'last_6_months':
                $query->where('attendances.created_at', '>=', now()->subMonths(6));
                break;
        }

        return $query->orderByDesc('total_attendances')
            ->limit(8)
            ->get();
    }

    private function getEventTrendStats($selectedCategory)
    {
        $now = Carbon::now();
        $labels = collect();
        $datasets = collect();

        // Generate labels for last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $labels->push($month->format('M Y'));
        }

        if ($selectedCategory === 'all') {
            // For all categories: Multi-line dataset
            $categories = Category::all();
            foreach ($categories as $category) {
                $eventCounts = collect();
                for ($i = 11; $i >= 0; $i--) {
                    $month = $now->copy()->subMonths($i);
                    $count = Event::where('category_id', $category->id)
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->count();
                    $eventCounts->push($count);
                }
                $datasets->push([
                    'label' => $category->name,
                    'data' => $eventCounts->toArray(),
                ]);
            }
        } else {
            // For single category: Single line
            $category = Category::where('slug', $selectedCategory)->first();
            if ($category) {
                $eventCounts = collect();
                for ($i = 11; $i >= 0; $i--) {
                    $month = $now->copy()->subMonths($i);
                    $count = Event::where('category_id', $category->id)
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->count();
                    $eventCounts->push($count);
                }
                $datasets->push([
                    'label' => $category->name,
                    'data' => $eventCounts->toArray(),
                ]);
            }
        }

        return [
            'labels' => $labels->toArray(),
            'datasets' => $datasets->toArray(),
        ];
    }
}