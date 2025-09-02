<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Menambahkan peserta ke sebuah event.
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Cek agar tidak duplikat
        if ($event->participants()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'Peserta ini sudah diundang.');
        }

        $event->participants()->attach($request->user_id);

        return back()->with('success', 'Peserta berhasil diundang.');
    }

    /**
     * Menghapus peserta dari sebuah event.
     */
    public function destroy(Event $event, User $user)
    {
        $event->participants()->detach($user->id);

        return back()->with('success', 'Peserta berhasil dihapus.');
    }
}
