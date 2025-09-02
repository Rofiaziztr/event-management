<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator', 'participants')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'required|string|max:255',
        ]);

        $event = new Event($validated);
        $event->creator_id = auth()->id();
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('participants', 'documents', 'creator');

        // --- PERBAIKAN UTAMA QUERY PENGAMBILAN PESERTA ---
        // 1. Ambil ID semua peserta yang SUDAH diundang ke event ini.
        $existingParticipantIds = $event->participants->pluck('id');

        // 2. Ambil semua user dengan role 'participant' yang ID-nya TIDAK TERMASUK dalam daftar yang sudah diundang.
        // Ini adalah metode yang lebih andal daripada query sebelumnya.
        $potentialParticipants = User::where('role', 'participant')
            ->whereNotIn('id', $existingParticipantIds)
            ->orderBy('full_name')
            ->get();
        // --- AKHIR PERBAIKAN ---

        return view('admin.events.show', compact('event', 'potentialParticipants'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:Terjadwal,Berlangsung,Selesai,Dibatalkan',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.show', $event)->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Menampilkan halaman QR Code untuk sebuah event.
     */
    public function showQrCode(Event $event)
    {
        if (!$event->code) {
            abort(404, 'Kode QR untuk event ini tidak ditemukan.');
        }

        // Ganti metode create() dengan inisialisasi langsung
        $qrCode = new QrCode(
            data: $event->code,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeDataUri = $result->getDataUri();

        return view('admin.events.qrcode', compact('event', 'qrCodeDataUri'));
    }
}
