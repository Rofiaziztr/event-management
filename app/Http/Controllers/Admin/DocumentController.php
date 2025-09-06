<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Menyimpan dokumen berupa file (Materi, Foto, Video).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Event $event)
    {
        // 1. Validasi input dari form
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:Materi,Foto,Video',
            'document_file' => 'required|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,avi,mov|max:10240',
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
     * Menyimpan atau memperbarui notulensi.
     * Logika ini dipindahkan dari NotulensiController untuk sentralisasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeOrUpdateNotulensi(Request $request, Event $event)
    {
        $request->validate([
            'content' => 'nullable|string', // Diubah menjadi nullable agar notulensi bisa dikosongkan/dihapus
        ]);

        $content = $request->input('content');

        $notulensi = Document::firstOrNew([
            'event_id' => $event->id,
            'type' => 'Notulensi'
        ]);

        // Jika content kosong dan notulensi sudah ada di database, hapus recordnya
        if (empty($content) && $notulensi->exists) {
            $notulensi->delete();
            return redirect()->route('admin.events.show', $event)
                ->with('success', 'Notulensi berhasil dihapus.');
        }

        // Jika content diisi, simpan atau perbarui data
        if (!empty($content)) {
            $notulensi->fill([
                'title' => 'Notulensi Rapat - ' . $event->title,
                'content' => $content,
                'uploader_id' => auth()->id(),
                'file_path' => null
            ]);

            if ($notulensi->save()) {
                return redirect()->route('admin.events.show', $event)
                    ->with('success', 'Notulensi berhasil disimpan.');
            } else {
                return back()->with('error', 'Gagal menyimpan notulensi.');
            }
        }

        // Jika content kosong dan notulensi belum ada, tidak melakukan apa-apa, cukup kembali
        return redirect()->route('admin.events.show', $event);
    }


    /**
     * Menghapus sebuah dokumen.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Document $document)
    {
        // Pastikan user yang menghapus adalah pemilik atau admin
        // Pengecekan isAdmin() adalah contoh, sesuaikan dengan logic role Anda
        if (auth()->id() !== $document->uploader_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // FIX: Hapus file fisik dari storage hanya jika file_path ada (tidak null)
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Hapus record dari database
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
