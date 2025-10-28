<?php

namespace App\Exports\Sheets;

use App\Models\Event;
use App\Exports\Traits\ExcelStylingTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventSummarySheet implements FromArray, WithTitle, WithEvents
{
    use ExcelStylingTrait;

    protected Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function array(): array
    {
        $totalInvited = $this->event->participants->count();
        $totalAttended = $this->event->participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
        $attendanceRate = $totalInvited > 0 ? round(($totalAttended / $totalInvited) * 100, 1) : 0;

        // Group by division
        $byDivision = $this->event->participants->groupBy('division')->map(function ($participants) {
            $attended = $participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
            $total = $participants->count();
            return [
                'division' => $participants->first()->division ?? 'Tidak Diketahui',
                'total' => $total,
                'attended' => $attended,
                'rate' => $total > 0 ? round(($attended / $total) * 100, 1) : 0
            ];
        })->values();

        // Group by institution
        $byInstitution = $this->event->participants->groupBy('institution')->map(function ($participants) {
            $attended = $participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
            $total = $participants->count();
            return [
                'institution' => $participants->first()->institution ?? 'Tidak Diketahui',
                'total' => $total,
                'attended' => $attended,
                'rate' => $total > 0 ? round(($attended / $total) * 100, 1) : 0
            ];
        })->values();

        $data = [];

        // Header utama - lebih simple
        $data[] = ["RINGKASAN: " . strtoupper($this->event->title)];
        $data[] = []; // Empty row

        // Overview singkat dalam satu section
        $data[] = ["📋 EVENT OVERVIEW"];
        $data[] = ["🎯 Event", $this->event->title];
        $data[] = ["📅 Tanggal & Waktu", $this->event->start_time->format('d/m/Y H:i') . ' - ' . $this->event->end_time->format('d/m/Y H:i')];
        $data[] = ["📍 Lokasi", $this->event->location];
        $data[] = ["🏷️ Status", $this->event->status];
        $data[] = []; // Empty row

        // Key metrics dalam format yang lebih menarik
        $data[] = ["📊 RINGKASAN KEHADIRAN"];
        $data[] = ["📊 Total Peserta", $totalInvited . " orang"];
        $data[] = ["✅ Hadir", $totalAttended . " orang"];
        $data[] = ["📈 Tingkat Kehadiran", $attendanceRate . "%"];

        // Analyze attendance timing
        $attendedParticipants = $this->event->participants->filter(fn($p) => $p->attendances->isNotEmpty());
        $ontimeCount = $attendedParticipants->filter(function ($p) {
            return $p->attendances->first()->check_in_time->lte($this->event->start_time->copy()->addMinutes(15));
        })->count();
        $lateCount = $totalAttended - $ontimeCount;

        $data[] = ["⏰ Tepat Waktu", $ontimeCount . " orang (" . round(($ontimeCount / max($totalAttended, 1)) * 100, 1) . "%)"];
        $data[] = ["⏳ Terlambat", $lateCount . " orang (" . round(($lateCount / max($totalAttended, 1)) * 100, 1) . "%)"];
        $data[] = []; // Empty row

        // Breakdown per Division - lebih compact
        if ($byDivision->isNotEmpty() && $byDivision->count() > 1) {
            $data[] = ["🏢 BREAKDOWN PER DIVISI"];
            $data[] = ["🏢 Divisi", "👥 Peserta", "✅ Hadir", "📊 Rate (%)"];

            foreach ($byDivision->sortByDesc('rate') as $division) {
                $rateIcon = $division['rate'] >= 80 ? "🟢" : ($division['rate'] >= 60 ? "🟡" : "🔴");
                $data[] = [
                    $division['division'],
                    $division['total'],
                    $division['attended'],
                    $rateIcon . " " . $division['rate'] . "%"
                ];
            }
            $data[] = []; // Empty row
        }

        // Breakdown per Institution - hanya jika lebih dari 1 institusi
        if ($byInstitution->isNotEmpty() && $byInstitution->count() > 1) {
            $data[] = ["�️ BREAKDOWN PER INSTITUSI"];
            $data[] = ["🏛️ Institusi", "👥 Peserta", "✅ Hadir", "📊 Rate (%)"];

            foreach ($byInstitution->sortByDesc('rate') as $institution) {
                $rateIcon = $institution['rate'] >= 80 ? "🟢" : ($institution['rate'] >= 60 ? "🟡" : "🔴");
                $data[] = [
                    $institution['institution'],
                    $institution['total'],
                    $institution['attended'],
                    $rateIcon . " " . $institution['rate'] . "%"
                ];
            }
        }

        return $data;
    }

    public function title(): string
    {
        return 'Ringkasan Event';
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

    private function applySummaryStyles(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // Set column widths for better readability
        $this->setColumnWidths($sheet, [
            'A' => 30,  // Labels - wider for emojis and text
            'B' => 35,  // Values - wider for better appearance
            'C' => 15,  // Numbers
            'D' => 20,  // Percentages/rates
        ]);

        // Main header - merge to actual width
        $this->styleMainHeader($sheet, "A1:{$highestCol}1", $sheet->getCell('A1')->getValue(), self::COLORS['PRIMARY']);

        // Find different sections dynamically
        $overviewRow = null;
        $ringkasanRow = null;
        $divisiRow = null;
        $institusiRow = null;

        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();
            if (strpos($cellValue, 'EVENT OVERVIEW') !== false) {
                $overviewRow = $row;
            } elseif (strpos($cellValue, 'RINGKASAN KEHADIRAN') !== false) {
                $ringkasanRow = $row;
            } elseif (strpos($cellValue, 'BREAKDOWN PER DIVISI') !== false) {
                $divisiRow = $row;
            } elseif (strpos($cellValue, 'BREAKDOWN PER INSTITUSI') !== false) {
                $institusiRow = $row;
            }
        }

        // Style section headers with proper merge
        if ($overviewRow) {
            // Merge header across appropriate columns (A:D for overview section)
            $sheet->mergeCells("A{$overviewRow}:D{$overviewRow}");
            $this->styleSectionHeader($sheet, "A{$overviewRow}:D{$overviewRow}");

            // Style overview section
            $overviewEndRow = ($ringkasanRow ? $ringkasanRow - 2 : $overviewRow + 5);
            $this->styleInfoSection($sheet, "A" . ($overviewRow + 1) . ":B{$overviewEndRow}");
            $this->styleInfoLabels($sheet, "A" . ($overviewRow + 1) . ":A{$overviewEndRow}");
            $this->stylePlainValues($sheet, "B" . ($overviewRow + 1) . ":B{$overviewEndRow}");

            // Ensure consistent white background for overview section
            $sheet->getStyle("A" . ($overviewRow + 1) . ":B{$overviewEndRow}")->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => self::COLORS['WHITE']]
                ]
            ]);
        }

        if ($ringkasanRow) {
            // Merge header across appropriate columns (A:D for ringkasan section)
            $sheet->mergeCells("A{$ringkasanRow}:D{$ringkasanRow}");
            $this->styleSectionHeader($sheet, "A{$ringkasanRow}:D{$ringkasanRow}");

            // Style ringkasan section dengan emoji styling
            $ringkasanEndRow = ($divisiRow ? $divisiRow - 2 : ($institusiRow ? $institusiRow - 2 : $ringkasanRow + 6));
            $this->styleInfoSection($sheet, "A" . ($ringkasanRow + 1) . ":B{$ringkasanEndRow}");
            $this->styleInfoLabels($sheet, "A" . ($ringkasanRow + 1) . ":A{$ringkasanEndRow}");
            $this->stylePlainValues($sheet, "B" . ($ringkasanRow + 1) . ":B{$ringkasanEndRow}");

            // Ensure consistent white background for ringkasan section
            $sheet->getStyle("A" . ($ringkasanRow + 1) . ":B{$ringkasanEndRow}")->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => self::COLORS['WHITE']]
                ],
                'font' => [
                    'size' => 11,
                    'name' => 'Segoe UI Emoji, Segoe UI'
                ]
            ]);

            // Make emoji labels bold (column A in ringkasan section)
            $sheet->getStyle("A" . ($ringkasanRow + 1) . ":A{$ringkasanEndRow}")->applyFromArray([
                'font' => ['bold' => true]
            ]);
        }

        if ($divisiRow) {
            // Merge header across table columns (A:D for division table)
            $sheet->mergeCells("A{$divisiRow}:D{$divisiRow}");
            $this->styleSectionHeader($sheet, "A{$divisiRow}:D{$divisiRow}");

            $divisiTableRow = $divisiRow + 1;
            $divisiEndRow = $institusiRow ? $institusiRow - 2 : $highestRow;

            if ($divisiEndRow > $divisiTableRow) {
                $this->styleDataTable($sheet, "A{$divisiTableRow}:D{$divisiTableRow}", "A" . ($divisiTableRow + 1) . ":D{$divisiEndRow}");

                // Format ALL rate column cells to be center aligned
                $sheet->getStyle("D" . ($divisiTableRow + 1) . ":D{$divisiEndRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'font' => ['bold' => false] // Ensure consistent font weight
                ]);

                // Override alternating colors - ensure consistent background for division table
                for ($row = $divisiTableRow + 1; $row <= $divisiEndRow; $row++) {
                    $bgColor = ($row % 2 == 0) ? self::COLORS['GRAY_100'] : self::COLORS['WHITE'];
                    $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $bgColor]
                        ]
                    ]);
                }
            }
        }

        if ($institusiRow) {
            // Merge header across table columns (A:D for institution table)
            $sheet->mergeCells("A{$institusiRow}:D{$institusiRow}");
            $this->styleSectionHeader($sheet, "A{$institusiRow}:D{$institusiRow}");

            $institusiTableRow = $institusiRow + 1;

            if ($highestRow > $institusiTableRow) {
                $this->styleDataTable($sheet, "A{$institusiTableRow}:D{$institusiTableRow}", "A" . ($institusiTableRow + 1) . ":D{$highestRow}");

                // Format ALL rate column cells to be center aligned
                $sheet->getStyle("D" . ($institusiTableRow + 1) . ":D{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'font' => ['bold' => false] // Ensure consistent font weight
                ]);

                // Override alternating colors - ensure consistent background for institution table
                for ($row = $institusiTableRow + 1; $row <= $highestRow; $row++) {
                    $bgColor = ($row % 2 == 0) ? self::COLORS['GRAY_100'] : self::COLORS['WHITE'];
                    $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $bgColor]
                        ]
                    ]);
                }
            }
        }

        // Configure print settings
        $this->configurePrintSettings($sheet, 'portrait');

        // FINAL STYLING: Apply bold to Status and Terlambat LABELS (done last to prevent override)
        for ($row = 1; $row <= $highestRow; $row++) {
            $labelValue = $sheet->getCell("A{$row}")->getValue();

            // Style Status LABEL (column A)
            if ($labelValue === 'Status') {
                $sheet->getStyle("A{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'name' => 'Segoe UI'
                    ]
                ]);
            }

            // Style Terlambat LABEL (column A)
            if (strpos($labelValue, '⏳ Terlambat') !== false || strpos($labelValue, 'Terlambat') !== false) {
                $sheet->getStyle("A{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'name' => 'Segoe UI Emoji, Segoe UI'
                    ]
                ]);
            }
        }

        // FINAL ALIGNMENT FIX: Ensure ALL Rate (%) columns are center aligned (done last to prevent override)
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("D{$row}")->getValue();
            // If column D contains percentage or rate data, ensure center alignment
            if (is_string($cellValue) && (strpos($cellValue, '%') !== false || strpos($cellValue, 'Rate') !== false)) {
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Also fix any numeric values in rate columns (for data rows)
                if (is_numeric(str_replace(['%', '🟢', '🟡', '🔴', ' '], '', $cellValue))) {
                    $sheet->getStyle("D{$row}")->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                        ]
                    ]);
                }
            }
        }
    }
}
