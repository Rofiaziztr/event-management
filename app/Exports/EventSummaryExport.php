<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EventSummaryExport implements FromView, WithTitle, WithEvents, ShouldAutoSize
{
    protected $event;

    public function __construct(Event $event)
    {
        $eventId = $event->id;
        $event->load(['category']);
        // Ambil peserta internal (dari event_participants)
        $internalParticipants = $event->participants()->with(['attendances' => function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        }])->get()->map(function ($user) {
            $user->participant_type = 'Internal';
            return $user;
        });

        // Ambil peserta eksternal (hanya di attendances, tidak ada di event_participants)
        $externalUserIds = \App\Models\Attendance::where('event_id', $eventId)
            ->whereNotIn('user_id', $internalParticipants->pluck('id'))
            ->pluck('user_id');
        $externalParticipants = \App\Models\User::whereIn('id', $externalUserIds)
            ->with(['attendances' => function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            }])->get()->map(function ($user) {
                $user->participant_type = 'Eksternal';
                return $user;
            });

        // Gabungkan internal dan eksternal
        $allParticipants = $internalParticipants->concat($externalParticipants);
        $event->setRelation('participants', $allParticipants);
        $this->event = $event;
    }

    public function view(): View
    {
        $stats = [
            'total_invited' => $this->event->participants->count(),
            'total_attended' => $this->event->participants->filter(fn($p) => $p->attendances->isNotEmpty())->count(),
            'attendance_rate' => 0,
            'by_division' => $this->event->participants->groupBy('division')->map(function ($participants, $division) {
                $attended = $participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
                return [
                    'total' => $participants->count(),
                    'attended' => $attended,
                    'rate' => $participants->count() > 0 ? round(($attended / $participants->count()) * 100, 1) : 0
                ];
            }),
            'by_institution' => $this->event->participants->groupBy('institution')->map(function ($participants, $institution) {
                $attended = $participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
                return [
                    'total' => $participants->count(),
                    'attended' => $attended,
                    'rate' => $participants->count() > 0 ? round(($attended / $participants->count()) * 100, 1) : 0
                ];
            })
        ];
        $stats['attendance_rate'] = $stats['total_invited'] > 0
            ? round(($stats['total_attended'] / $stats['total_invited']) * 100, 1)
            : 0;

        return view('exports.event_summary', [
            'event' => $this->event,
            'stats' => $stats,
            'exportDate' => now()->format('d F Y H:i'),
            'showParticipantType' => true // jika ingin tampilkan detail peserta
        ]);
    }

    public function title(): string
    {
        return 'Ringkasan_' . Str::limit($this->event->title, 15);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Apply professional styling
                $this->applySummaryStyles($sheet);
            }
        ];
    }

    private function applySummaryStyles($sheet)
    {
        // Header styling
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F59E0B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Statistics section styling
        $sheet->getStyle('A3:F10')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Make key columns bold
        $sheet->getStyle('A:A')->getFont()->setBold(true);
    }
}
