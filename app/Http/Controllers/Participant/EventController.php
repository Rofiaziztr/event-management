<?php

namespace App\Http\Controllers\Participant;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $events = $user->attendedEvents()->with('attendances')->latest()->paginate(10);

        // Ambil ID semua event yang sudah dihadiri oleh user
        $attendedEventIds = $user->attendances->pluck('event_id')->toArray();

        return view('participant.events.index', compact('events', 'attendedEventIds'));
    }

    public function show(Event $event)
    {
        // Di sini kita bisa menambahkan pengecekan apakah user terdaftar di event ini
        // Namun untuk sekarang, kita tampilkan saja langsung
        return view('participant.events.show', compact('event'));
    }
}
