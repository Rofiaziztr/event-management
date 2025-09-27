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
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class EventParticipantsExport implements FromView, WithTitle, WithEvents, ShouldAutoSize
{
    protected $event;
    protected $presentCells = [];
    protected $absentCells = [];



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
        // Set relasi participants ke event
        $event->setRelation('participants', $allParticipants);
        $this->event = $event;
    }

    public function view(): View
    {
        // Reset cell tracking arrays
        $this->presentCells = [];
        $this->absentCells = [];

        // Calculate statistics
        $totalParticipants = $this->event->participants->count();
        $totalAttended = $this->event->participants->filter(function ($participant) {
            return $participant->attendances->isNotEmpty();
        })->count();
        $attendanceRate = $totalParticipants > 0 ? round(($totalAttended / $totalParticipants) * 100, 1) : 0;

        // Group participants by division
        $participantsByDivision = $this->event->participants->groupBy('division');

        // Track cells that need coloring (starting from row 12 which is after headers)
        $row = 12;
        foreach ($this->event->participants as $participant) {
            $hasAttendance = $participant->attendances->where('event_id', $this->event->id)->first() !== null;
            if ($hasAttendance) {
                $this->presentCells[] = 'I' . $row;
            } else {
                $this->absentCells[] = 'I' . $row;
            }
            $row++;
        }

        // Group participants by institution
        $participantsByInstitution = $this->event->participants->groupBy('institution');

        return view('exports.event_participants_professional', [
            'event' => $this->event,
            'totalParticipants' => $totalParticipants,
            'totalAttended' => $totalAttended,
            'attendanceRate' => $attendanceRate,
            'participantsByDivision' => $participantsByDivision,
            'participantsByInstitution' => $participantsByInstitution,
            'exportDate' => now()->format('d F Y H:i'),
            'showParticipantType' => true // untuk view, agar bisa tampilkan kolom tipe peserta
        ]);
    }

    public function title(): string
    {
        return 'Peserta_' . Str::limit($this->event->title, 15);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Set page setup
                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                // Color attendance status cells
                foreach ($this->presentCells as $cell) {
                    $sheet->getStyle($cell)->applyFromArray([
                        'font' => [
                            'color' => ['rgb' => '059669'],
                            'bold' => true,
                        ],
                    ]);
                }
                foreach ($this->absentCells as $cell) {
                    $sheet->getStyle($cell)->applyFromArray([
                        'font' => [
                            'color' => ['rgb' => 'DC2626'],
                            'bold' => true,
                        ],
                    ]);
                }

                // Header styling (Rows 1-8: Event Information)
                $this->styleEventHeader($sheet);

                // Statistics section styling (Rows 10-13)
                $this->styleStatisticsSection($sheet);

                // Participants table header styling (Row 15)
                $this->styleTableHeader($sheet);

                // Participants data styling (From Row 16 onwards)
                $this->styleParticipantsData($sheet, $highestRow);

                // Summary section styling (Bottom section)
                $this->styleSummarySection($sheet, $highestRow);

                // Auto-fit columns
                foreach (range('A', 'K') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Set specific column widths (akan menimpa autoSize)
                $sheet->getColumnDimension('A')->setWidth(6);   // No
                $sheet->getColumnDimension('C')->setWidth(25);  // Nama
                $sheet->getColumnDimension('D')->setWidth(30);  // Email
                $sheet->getColumnDimension('I')->setWidth(15);  // Status
                $sheet->getColumnDimension('J')->setWidth(20);  // Check-in Time
            },
        ];
    }

    private function styleEventHeader($sheet)
    {
        // Main title (A1:K1)
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F59E0B'] // Yellow-500
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D97706']
                ]
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Event details section (A2:K6)
        $detailRows = [2, 3, 4, 5, 6];
        foreach ($detailRows as $row) {
            // Only apply subtle border to the header cell
            $sheet->getStyle("A{$row}")->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'F3F4F6']
                    ]
                ]
            ]);

            // Style labels (column A)
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '374151']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FEF3C7'] // Yellow-100
                ]
            ]);

            // Merge data cells (B to K)
            $sheet->mergeCells("B{$row}:K{$row}");
            $sheet->getRowDimension($row)->setRowHeight(25);
        }
    }

    private function styleStatisticsSection($sheet)
    {
        // Statistics header (A10:K10)
        $sheet->mergeCells('A10:K10');
        $sheet->getStyle('A10')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'] // Green-500
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_NONE
                ]
            ]
        ]);

        // Statistics data (A11:K13)
        for ($row = 11; $row <= 13; $row++) {
            // Remove borders from the entire row
            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_NONE
                    ]
                ]
            ]);

            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'ECFDF5'] // Green-50
                ]
            ]);

            $sheet->mergeCells("B{$row}:K{$row}");
        }
    }

    private function styleTableHeader($sheet)
    {
        // Table header (A15:K15)
        $sheet->getStyle('A15:K15')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6'] // Blue-500
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '1D4ED8']
                ]
            ]
        ]);
        $sheet->getRowDimension(15)->setRowHeight(35);
    }

    private function styleParticipantsData($sheet, $highestRow)
    {
        $dataStartRow = 16;
        // Cari jumlah peserta dari event
        $participantsCount = $this->event->participants->count();
        $participantsEndRow = $dataStartRow + $participantsCount - 1;

        for ($row = $dataStartRow; $row <= $participantsEndRow; $row++) {
            $isEvenRow = ($row - $dataStartRow) % 2 == 0;

            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB']
                    ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $isEvenRow ? 'FFFFFF' : 'F9FAFB'] // Alternating rows
                ]
            ]);
            // Set row height normal
            $sheet->getRowDimension($row)->setRowHeight(20);

            // Center align number column
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Style status column with conditional formatting
            $statusCell = $sheet->getCell("I{$row}");
            if (strpos($statusCell->getValue(), '✅ Hadir') !== false) {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '059669']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'] // Green-100
                    ]
                ]);
            } else if (strpos($statusCell->getValue(), '❌ Tidak Hadir') !== false) {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEE2E2'] // Red-100
                    ]
                ]);
            }
        }
    }

    private function styleSummarySection($sheet, $highestRow)
    {
        $summaryStartRow = $highestRow - 8;

        // Summary header
        $sheet->mergeCells("A{$summaryStartRow}:K{$summaryStartRow}");
        $sheet->getStyle("A{$summaryStartRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '8B5CF6'] // Purple-500
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_NONE
                ]
            ]
        ]);

        // Summary data rows
        for ($row = $summaryStartRow + 1; $row <= $highestRow; $row++) {
            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_NONE
                    ]
                ]
            ]);
        }
    }
}
