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

class EventParticipantsSheet implements FromView, WithTitle, WithEvents, ShouldAutoSize
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function view(): View
    {
        $totalParticipants = $this->event->participants->count();
        $totalAttended = $this->event->participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();

        return view('exports.event_participants', [
            'event' => $this->event,
            'totalParticipants' => $totalParticipants,
            'totalAttended' => $totalAttended,
            'attendanceRate' => $totalParticipants > 0 ? round(($totalAttended / $totalParticipants) * 100, 1) : 0,
            'participantsByDivision' => $this->event->participants->groupBy('division'),
            'participantsByInstitution' => $this->event->participants->groupBy('institution'),
            'exportDate' => now()->format('d F Y H:i'),
            'showParticipantType' => true
        ]);
    }

    public function title(): string
    {
        return 'Daftar Peserta';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $this->styleEventHeader($sheet);
                $this->styleStatisticsSection($sheet);
                $this->styleTableHeader($sheet);
                $this->styleParticipantsData($sheet);
                $this->styleSummarySection($sheet, $highestRow);

                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(30);
                $sheet->getColumnDimension('I')->setWidth(15);
                $sheet->getColumnDimension('J')->setWidth(20);
            },
        ];
    }

    private function styleEventHeader($sheet)
    {
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F59E0B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D97706']]]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        foreach (range(2, 6) as $row) {
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '374151']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF3C7']]
            ]);
            $sheet->mergeCells("B{$row}:K{$row}");
            $sheet->getRowDimension($row)->setRowHeight(25);
        }
    }

    private function styleStatisticsSection($sheet)
    {
        $sheet->mergeCells('A8:K8');
        $sheet->getStyle('A8')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(8)->setRowHeight(28);

        foreach (range(9, 11) as $row) {
             $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ECFDF5']]
            ]);
            $sheet->mergeCells("B{$row}:K{$row}");
        }
    }

    private function styleTableHeader($sheet)
    {
        $sheet->getStyle('A13:K13')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1D4ED8']]]
        ]);
        $sheet->getRowDimension(13)->setRowHeight(35);
    }

    private function styleParticipantsData($sheet)
    {
        $participantsCount = $this->event->participants->count();
        if ($participantsCount == 0) return;

        $startRow = 14;
        $endRow = $startRow + $participantsCount - 1;

        for ($row = $startRow; $row <= $endRow; $row++) {
            $isEvenRow = ($row - $startRow) % 2 == 0;
            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $isEvenRow ? 'FFFFFF' : 'F9FAFB']]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(20);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $statusCell = $sheet->getCell("I{$row}")->getValue();
            if (strpos($statusCell, 'Hadir') !== false) {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '059669']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']]
                ]);
            } else {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']]
                ]);
            }
        }
    }

     private function styleSummarySection($sheet, $highestRow)
    {
        // Cari baris awal summary, biasanya setelah daftar peserta
        $participantsCount = $this->event->participants->count();
        $summaryStartRow = 14 + $participantsCount + 2; // Memberi jarak 2 baris

        if ($summaryStartRow > $sheet->getHighestRow()) return; // Jika tidak ada summary, keluar

        // Summary header
        $sheet->mergeCells("A{$summaryStartRow}:K{$summaryStartRow}");
        $sheet->getStyle("A{$summaryStartRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B5CF6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($summaryStartRow)->setRowHeight(28);
    }
}