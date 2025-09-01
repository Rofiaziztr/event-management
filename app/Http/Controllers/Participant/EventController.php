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
        $user = Auth::user();
        $event->load('documents.user');

        // Cek apakah user sudah memiliki data kehadiran untuk event ini
        $attendance = $user->attendances()->where('event_id', $event->id)->first();

        return view('participant.events.show', compact('event', 'attendance'));
    }
}
