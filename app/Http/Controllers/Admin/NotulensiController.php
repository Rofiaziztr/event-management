<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotulensiController extends Controller
{
    public function storeOrUpdate(Request $request, Event $event)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        // Gunakan updateOrCreate untuk efisiensi
        // Cari dokumen dengan event_id dan tipe 'Notulensi',
        // jika ada, perbarui 'content'-nya.
        // Jika tidak ada, buat record baru dengan data ini.
        Document::updateOrCreate(
            [
                'event_id' => $event->id,
                'type'     => 'Notulensi',
            ],
            [
                'content'     => $request->input('content'),
                'uploader_id' => auth()->id(),
                'title'       => 'Notulensi Rapat - ' . $event->title, // Judul otomatis
            ]
        );

        return back()->with('success', 'Notulensi berhasil disimpan.');
    }
}
