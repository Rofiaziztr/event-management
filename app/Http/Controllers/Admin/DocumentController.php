<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Menyimpan dokumen baru yang diunggah.
     */
    public function store(Request $request, Event $event)
    {
        // 1. Validasi input dari form
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:Materi,Foto,Video',
            'document_file' => 'required|file|mimes:pdf,ppt,pptx,jpg,jpeg,png,mp4,docx|max:20480', // max 20MB
        ]);

        // 2. Simpan file ke storage
        $filePath = $request->file('document_file')->store('documents', 'public');

        // 3. Buat record di database
        $document = new Document();
        $document->event_id = $event->id;
        $document->uploader_id = auth()->id();
        $document->title = $validated['title'];
        $document->type = $validated['type'];
        $document->file_path = $filePath;
        $document->save();

        // 4. Redirect kembali dengan pesan sukses
        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Menghapus sebuah dokumen.
     */
    public function destroy(Document $document)
    {
        // Pastikan user yang menghapus adalah pemilik atau admin
        if (auth()->id() !== $document->uploader_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus file fisik dari storage
        Storage::disk('public')->delete($document->file_path);

        // Hapus record dari database
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
