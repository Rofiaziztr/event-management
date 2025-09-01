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

        // Debug: lihat data yang dikirim
        logger()->info('Notulensi content received:', ['content' => $request->input('content')]);

        // Cari atau buat notulensi
        $notulensi = Document::firstOrNew([
            'event_id' => $event->id,
            'type' => 'Notulensi'
        ]);

        // Isi data notulensi
        $notulensi->fill([
            'title' => 'Notulensi Rapat - ' . $event->title,
            'content' => $request->input('content'),
            'uploader_id' => auth()->id(),
            'file_path' => null
        ]);

        // Simpan perubahan
        if ($notulensi->save()) {
            logger()->info('Notulensi saved successfully:', ['id' => $notulensi->id]);
            return redirect()->route('admin.events.show', $event)
                ->with('success', 'Notulensi berhasil disimpan.');
        } else {
            logger()->error('Failed to save notulensi');
            return back()->with('error', 'Gagal menyimpan notulensi.');
        }
    }
}
