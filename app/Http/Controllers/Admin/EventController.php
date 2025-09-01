<?php

namespace App\Http\Controllers\Admin;

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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::latest()->paginate(10);
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
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            // Tambahkan validasi untuk status
            'status' => ['required', Rule::in(['Scheduled', 'Ongoing', 'Completed', 'Cancelled'])],
        ]);

        $validatedData['creator_id'] = auth()->id();
        Event::create($validatedData);

        return redirect()->route('admin.events.index')->with('success', 'Event baru berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
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
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'status' => ['required', Rule::in(['Scheduled', 'Ongoing', 'Completed', 'Cancelled'])],
        ]);

        $event->update($validatedData);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
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
