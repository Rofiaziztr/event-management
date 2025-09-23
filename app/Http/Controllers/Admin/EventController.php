<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('creator', 'participants', 'category');

        // Filter berdasarkan nama event
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('start_time', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('end_time', '<=', $request->end_date);
        }

        $events = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();
        
        // Data Statistik untuk Kartu Info
        $stats = [
            'total' => Event::count(),
            'berlangsung' => Event::where('status', 'Berlangsung')->count(),
            'bulan_ini' => Event::whereMonth('start_time', now()->month)
                                ->whereYear('start_time', now()->year)
                                ->count(),
        ];


        return view('admin.events.index', compact('events', 'categories', 'stats'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.events.create', compact('categories'));
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
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $event = new Event($validated);
        $event->creator_id = auth()->id();
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Event $event)
    {
        // Memuat relasi participants dengan pagination
        $participants = $event->participants()->with(['attendances' => function ($query) use ($event) {
            $query->where('event_id', $event->id);
        }])->paginate(10);

        // Memuat data lain yang mungkin sudah ada (misalnya potentialParticipants)
            $existingParticipantIds = $event->participants()->pluck('users.id');
    $potentialParticipants = User::where('role', 'participant')
        ->whereNotIn('id', $existingParticipantIds)
        ->orderBy('full_name')
        ->get();

        // Ambil tab aktif dari URL, default-nya adalah 'detail'
        $activeTab = $request->query('tab', 'detail');

        // Mengirim data ke view
        return view('admin.events.show', compact('event', 'activeTab', 'potentialParticipants'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.events.edit', compact('event', 'categories'));
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
            'category_id' => 'nullable|exists:categories,id',
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
        $event->load('participants', 'attendances');
        if (!$event->code) {
            abort(404, 'Kode QR untuk event ini tidak ditemukan.');
        }

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