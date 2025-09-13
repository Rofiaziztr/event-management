<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    /**
     * Menyimpan dokumen berupa file (Lampiran umum).
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
            'document_file' => 'required|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,avi,mov|max:10240',
        ]);

        // 2. Simpan file ke storage
        $filePath = $request->file('document_file')->store('documents', 'public');

        // 3. Buat record di database
        $document = new Document();
        $document->event_id = $event->id;
        $document->uploader_id = auth()->id();
        $document->title = $validated['title'];
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function storeOrUpdateNotulensi(Request $request, Event $event)
    {
        $request->validate([
            'content' => 'nullable|string', // Diubah menjadi nullable agar notulensi bisa dikosongkan/dihapus
        ]);

        $content = $request->input('content');

        // Cari dokumen notulensi berdasarkan event_id dan file_path null
        $notulensi = Document::where('event_id', $event->id)
            ->whereNull('file_path')
            ->first();

        // Jika content kosong dan notulensi sudah ada, hapus recordnya
        if (empty($content) && $notulensi) {
            $notulensi->delete();
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Notulensi berhasil dihapus.']);
            }
            return redirect()->route('admin.events.show', $event)
                ->with('success', 'Notulensi berhasil dihapus.');
        }

        // Jika content diisi, simpan atau perbarui data
        if (!empty($content)) {
            if (!$notulensi) {
                $notulensi = new Document();
                $notulensi->event_id = $event->id;
                $notulensi->uploader_id = auth()->id();
            }

            $notulensi->fill([
                'title' => 'Notulensi Rapat - ' . $event->title,
                'content' => $content,
                'file_path' => null,
            ]);

            try {
                if ($notulensi->save()) {
                    if ($request->ajax()) {
                        return response()->json(['success' => true, 'message' => 'Notulensi berhasil disimpan.']);
                    }
                    return redirect()->route('admin.events.show', $event)
                        ->with('success', 'Notulensi berhasil disimpan.');
                } else {
                    Log::error('Gagal menyimpan notulensi untuk event_id: ' . $event->id);
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Gagal menyimpan notulensi. Silakan coba lagi.']);
                    }
                    return back()->with('error', 'Gagal menyimpan notulensi. Silakan coba lagi.');
                }
            } catch (\Exception $e) {
                Log::error('Error menyimpan notulensi: ' . $e->getMessage());
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan notulensi: ' . $e->getMessage()]);
                }
                return back()->with('error', 'Terjadi kesalahan saat menyimpan notulensi: ' . $e->getMessage());
            }
        }

        // Jika content kosong dan notulensi belum ada, kembali tanpa pesan
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tidak ada perubahan.']);
        }
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
        if (auth()->id() !== $document->uploader_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus file fisik dari storage hanya jika file_path ada
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Hapus record dari database
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}