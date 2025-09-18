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

    $events = $query->latest()->paginate(10)->withQueryString();

    // Ambil ID semua event yang sudah dihadiri oleh user
    $attendedEventIds = $user->attendances->pluck('event_id')->toArray();

    // Ambil semua kategori untuk dropdown
    $categories = \App\Models\Category::orderBy('name')->get();

    return view('participant.events.index', compact('events', 'attendedEventIds', 'categories'));
}

    public function show(Event $event)
    {
        $user = Auth::user();
        $event->load('documents.user');

        // Cek apakah user sudah memiliki data kehadiran untuk event ini
        $attendance = $user->attendances()->where('event_id', $event->id)->first();

        return view('participant.events.show', compact('event', 'attendance'));
    }
}
