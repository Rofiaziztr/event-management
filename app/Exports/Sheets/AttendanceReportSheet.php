<?php

namespace App\Exports\Sheets;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceReportSheet implements FromView, WithTitle, WithEvents, ShouldAutoSize
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function view(): View
    {
        $attendedParticipants = $this->event->participants->filter(fn($p) => $p->attendances->isNotEmpty());

        $timeSlots = [
            'early' => $attendedParticipants->filter(function ($p) {
                return $p->attendances->first()->check_in_time->lt($this->event->start_time);
            }),
            'ontime' => $attendedParticipants->filter(function ($p) {
                return $p->attendances->first()->check_in_time->between(
                    $this->event->start_time,
                    $this->event->start_time->copy()->addMinutes(15)
                );
            }),
            'late' => $attendedParticipants->filter(function ($p) {
                return $p->attendances->first()->check_in_time->gt($this->event->start_time->copy()->addMinutes(15));
            })
        ];

        return view('exports.attendance_report', [
            'event' => $this->event,
            'attendedParticipants' => $attendedParticipants->sortBy(fn($p) => optional($p->attendances->first())->check_in_time),
            'timeSlots' => $timeSlots,
            'exportDate' => now()->format('d F Y H:i'),
            'showParticipantType' => true
        ]);
    }

    public function title(): string
    {
        return 'Laporan Kehadiran';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->applyAttendanceStyles($event->sheet->getDelegate());
            }
        ];
    }

    private function applyAttendanceStyles($sheet)
    {
        // Main header
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Event info
        $sheet->getStyle('A3:H4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ECFDF5']],
        ]);

        // Statistics header
        $sheet->mergeCells('A6:H6');
        $sheet->getStyle('A6:H6')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Participant list header
        $sheet->getStyle('A9:H9')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B5CF6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Set column widths
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(20);
    }
}