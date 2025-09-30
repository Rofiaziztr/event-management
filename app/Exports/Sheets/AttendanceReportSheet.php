<?php

namespace App\Exports\Sheets;

use App\Models\Event;
use App\Exports\Traits\ExcelStylingTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceReportSheet implements FromArray, WithTitle, WithEvents
{
    use ExcelStylingTrait;

    protected Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function array(): array
    {
        $data = [];

        // Header utama
        $data[] = ["LAPORAN KEHADIRAN EVENT: " . strtoupper($this->event->title)];
        $data[] = []; // Empty row

        // Event info section
        $data[] = ["INFORMASI EVENT"];
        $data[] = ["Nama Event", $this->event->title];
        $data[] = ["Tanggal", $this->event->start_time->format('d/m/Y H:i') . ' - ' . $this->event->end_time->format('d/m/Y H:i')];
        $data[] = ["Lokasi", $this->event->location];

        // Statistics
        $attendedParticipants = $this->event->participants->filter(fn($p) => $p->attendances->isNotEmpty());
        $totalParticipants = $this->event->participants->count();
        $attendanceRate = $totalParticipants > 0 ? round(($attendedParticipants->count() / $totalParticipants) * 100, 1) : 0;

        $data[] = ["Total Peserta", $totalParticipants];
        $data[] = ["Hadir", $attendedParticipants->count()];
        $data[] = ["Tingkat Kehadiran", $attendanceRate . "%"];
        $data[] = ["Tanggal Export", now()->format('d/m/Y H:i')];
        $data[] = []; // Empty row

        // Analyze attendance timing
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

        // Timing statistics
        $data[] = ["ANALISIS WAKTU KEHADIRAN"];
        $data[] = ["Kategori", "Jumlah", "Persentase"];
        $data[] = ["Datang Lebih Awal", $timeSlots['early']->count(), round(($timeSlots['early']->count() / max($attendedParticipants->count(), 1)) * 100, 1) . "%"];
        $data[] = ["Tepat Waktu (0-15 menit)", $timeSlots['ontime']->count(), round(($timeSlots['ontime']->count() / max($attendedParticipants->count(), 1)) * 100, 1) . "%"];
        $data[] = ["Terlambat (>15 menit)", $timeSlots['late']->count(), round(($timeSlots['late']->count() / max($attendedParticipants->count(), 1)) * 100, 1) . "%"];
        $data[] = []; // Empty row

        // Detailed attendance list
        $data[] = ["DAFTAR PESERTA YANG HADIR"];
        $data[] = [
            "No",
            "NIP",
            "Nama Lengkap",
            "Divisi",
            "Institusi",
            "Tipe Peserta",
            "Waktu Check-in",
            "Selisih dari Mulai Event",
            "Kategori Waktu"
        ];

        // Sort by check-in time
        $sortedAttendees = $attendedParticipants
            ->sortBy(fn($p) => $p->attendances->first()->check_in_time)
            ->values();

        foreach ($sortedAttendees as $index => $participant) {
            $attendance = $participant->attendances->first();
            $checkInTime = $attendance->check_in_time;
            $diffInMinutes = $checkInTime->diffInMinutes($this->event->start_time, false);

            // Determine timing category
            if ($checkInTime->lt($this->event->start_time)) {
                $timingCategory = "Lebih Awal";
                $timeDiff = "-" . abs($diffInMinutes) . " menit";
            } elseif ($diffInMinutes <= 15) {
                $timingCategory = "Tepat Waktu";
                $timeDiff = "+" . $diffInMinutes . " menit";
            } else {
                $timingCategory = "Terlambat";
                $timeDiff = "+" . $diffInMinutes . " menit";
            }

            $data[] = [
                $index + 1,
                "'" . ($participant->nip ?? '-'), // Add apostrophe to prevent scientific notation
                $participant->full_name,
                $participant->division ?? '-',
                $participant->institution ?? '-',
                $participant->participant_type ?? 'Internal',
                $checkInTime->format('d/m/Y H:i:s'),
                $timeDiff,
                $timingCategory
            ];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Laporan Kehadiran';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applyAttendanceStyles($sheet);
            }
        ];
    }

    private function applyAttendanceStyles(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // Set column widths
        $this->setColumnWidths($sheet, [
            'A' => 6,   // No
            'B' => 15,  // NIP
            'C' => 25,  // Nama
            'D' => 20,  // Divisi
            'E' => 25,  // Institusi
            'F' => 12,  // Tipe
            'G' => 18,  // Check-in time
            'H' => 15,  // Time diff
            'I' => 15,  // Category
        ]);

        // Main header
        $this->styleMainHeader($sheet, "A1:{$highestCol}1", $sheet->getCell('A1')->getValue(), self::COLORS['SUCCESS']);

        // Find sections dynamically
        $infoEventRow = null;
        $analisisRow = null;
        $daftarPesertaRow = null;
        $tableHeaderRow = null;

        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();
            if (strpos($cellValue, 'INFORMASI EVENT') !== false) {
                $infoEventRow = $row;
            } elseif (strpos($cellValue, 'ANALISIS WAKTU KEHADIRAN') !== false) {
                $analisisRow = $row;
            } elseif (strpos($cellValue, 'DAFTAR PESERTA YANG HADIR') !== false) {
                $daftarPesertaRow = $row;
            } elseif ($cellValue === 'No') { // Table header
                $tableHeaderRow = $row;
                break;
            }
        }

        // Style info section
        if ($infoEventRow) {
            $this->styleSectionHeader($sheet, "A{$infoEventRow}:{$highestCol}{$infoEventRow}");

            $infoEndRow = $analisisRow ? $analisisRow - 2 : $infoEventRow + 7;
            $this->styleInfoSection($sheet, "A" . ($infoEventRow + 1) . ":B{$infoEndRow}");
            $this->styleInfoLabels($sheet, "A" . ($infoEventRow + 1) . ":A{$infoEndRow}");
            $this->styleInfoValues($sheet, "B" . ($infoEventRow + 1) . ":B{$infoEndRow}");
        }

        // Style timing analysis section
        if ($analisisRow) {
            $this->styleSectionHeader($sheet, "A{$analisisRow}:{$highestCol}{$analisisRow}");

            $analisisTableRow = $analisisRow + 1;
            $analisisEndRow = $daftarPesertaRow ? $daftarPesertaRow - 2 : $analisisRow + 4;

            $this->styleDataTable($sheet, "A{$analisisTableRow}:C{$analisisTableRow}", "A" . ($analisisTableRow + 1) . ":C{$analisisEndRow}");
        }

        // Style attendance list section
        if ($daftarPesertaRow) {
            $this->styleSectionHeader($sheet, "A{$daftarPesertaRow}:{$highestCol}{$daftarPesertaRow}");
        }

        // Style detailed attendance table
        if ($tableHeaderRow) {
            $this->styleDataTable(
                $sheet,
                "A{$tableHeaderRow}:{$highestCol}{$tableHeaderRow}",
                "A" . ($tableHeaderRow + 1) . ":{$highestCol}{$highestRow}"
            );

            // Format NIP column as text
            $this->formatNIP($sheet, "B" . ($tableHeaderRow + 1) . ":B{$highestRow}");

            // Apply conditional formatting for timing categories (column I)
            $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
            $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
            $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
            $conditional1->setText('Lebih Awal');
            $conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $conditional1->getStyle()->getFill()->getStartColor()->setRGB('DBEAFE'); // Blue-100
            $conditional1->getStyle()->getFont()->getColor()->setRGB(self::COLORS['PRIMARY']);

            $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
            $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
            $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
            $conditional2->setText('Tepat Waktu');
            $conditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $conditional2->getStyle()->getFill()->getStartColor()->setRGB('DCFCE7'); // Green-100
            $conditional2->getStyle()->getFont()->getColor()->setRGB(self::COLORS['SUCCESS']);

            $conditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
            $conditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
            $conditional3->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
            $conditional3->setText('Terlambat');
            $conditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $conditional3->getStyle()->getFill()->getStartColor()->setRGB('FEE2E2'); // Red-100
            $conditional3->getStyle()->getFont()->getColor()->setRGB(self::COLORS['DANGER']);

            $conditionalStyles = [$conditional1, $conditional2, $conditional3];
            $sheet->getStyle("I" . ($tableHeaderRow + 1) . ":I{$highestRow}")->setConditionalStyles($conditionalStyles);

            // Format date column (G)
            $this->formatDate($sheet, "G" . ($tableHeaderRow + 1) . ":G{$highestRow}");

            // Set freeze panes
            $this->setFreezePanes($sheet, "A" . ($tableHeaderRow + 1));

            // Set auto filter - CORRECT PLACEMENT
            $this->setAutoFilter($sheet, "A{$tableHeaderRow}:{$highestCol}{$highestRow}");

            // Set print titles
            $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, $tableHeaderRow);
        }

        // Configure print settings
        $this->configurePrintSettings($sheet, 'landscape');
    }
}
