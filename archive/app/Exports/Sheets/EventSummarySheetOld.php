<?php

namespace App\Exports\Sheets;

use App\Models\Event;
use App\Exports\Traits\ExcelStylingTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EventSummarySheet implements FromArray, WithTitle, WithEvents
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function view(): View
    {
        $totalInvited = $this->event->participants->count();
        $totalAttended = $this->event->participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();

        $stats = [
            'total_invited' => $totalInvited,
            'total_attended' => $totalAttended,
            'attendance_rate' => $totalInvited > 0 ? round(($totalAttended / $totalInvited) * 100, 1) : 0,
            'by_division' => $this->event->participants->groupBy('division')->map(function ($participants) {
                $attended = $participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
                return [
                    'total' => $participants->count(),
                    'attended' => $attended,
                    'rate' => $participants->count() > 0 ? round(($attended / $participants->count()) * 100, 1) : 0
                ];
            }),
            'by_institution' => $this->event->participants->groupBy('institution')->map(function ($participants) {
                $attended = $participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
                return [
                    'total' => $participants->count(),
                    'attended' => $attended,
                    'rate' => $participants->count() > 0 ? round(($attended / $participants->count()) * 100, 1) : 0
                ];
            })
        ];

        return view('exports.event_summary', [
            'event' => $this->event,
            'stats' => $stats,
            'exportDate' => now()->format('d F Y H:i'),
        ]);
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applySummaryStyles($sheet);
            }
        ];
    }

    private function applySummaryStyles($sheet)
    {
        // Header styling
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F59E0B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);


        // Section headers
        $sheet->getStyle('A3:F3')->getFont()->setBold(true);
        $sheet->getStyle('A8:F8')->getFont()->setBold(true);
        $sheet->getStyle('A15:F15')->getFont()->setBold(true);

        // General Info & Overall Stats
        $sheet->getStyle('A4:C6')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']]],
        ]);
         $sheet->getStyle('A9:D12')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']]],
        ]);

        // Bold labels
        $sheet->getStyle('A4:A6')->getFont()->setBold(true);
        $sheet->getStyle('A9:A12')->getFont()->setBold(true);
    }
}
