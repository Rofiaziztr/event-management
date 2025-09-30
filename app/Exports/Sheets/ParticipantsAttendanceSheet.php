<?php

namespace App\Exports\Sheets;

use App\Models\Event;
use App\Exports\Traits\ExcelStylingTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantsAttendanceSheet implements FromArray, WithTitle, WithEvents
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
        $data[] = ["DAFTAR PESERTA & KEHADIRAN: " . strtoupper($this->event->title)];
        $data[] = []; // Empty row

        // Participants and attendance table
        $data[] = [
            "No",
            "NIP",
            "Nama Lengkap",
            "Jabatan",
            "Divisi",
            "Institusi",
            "Email",
            "No. Telepon",
            "Tipe Peserta",
            "Status Kehadiran",
            "Waktu Check-in",
            "Selisih dari Mulai Event",
            "Kategori Waktu"
        ];

        // Get attended participants for timing calculation
        $attendedParticipants = $this->event->participants->filter(fn($p) => $p->attendances->isNotEmpty());

        // All participants data (attended and not attended)
        $allParticipants = $this->event->participants
            ->sortBy(function ($participant) {
                // Sort by attendance status first (attended first), then by name
                $attendance = $participant->attendances->first();
                return ($attendance ? '0' : '1') . $participant->full_name;
            })
            ->values();

        foreach ($allParticipants as $index => $participant) {
            $attendance = $participant->attendances->first();

            if ($attendance) {
                // For attended participants
                $attendanceStatus = 'Hadir';
                $checkInTime = $attendance->check_in_time;
                $checkInTimeStr = $checkInTime->format('d/m/Y H:i:s');
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
            } else {
                // For non-attended participants
                $attendanceStatus = 'Tidak Hadir';
                $checkInTimeStr = '-';
                $timeDiff = '-';
                $timingCategory = '-';
            }

            $data[] = [
                $index + 1,
                "'" . ($participant->nip ?? '-'), // Prevent scientific notation
                $participant->full_name,
                $participant->position ?? '-',
                $participant->division ?? '-',
                $participant->institution ?? '-',
                $participant->email,
                $participant->phone_number ?? '-',
                $participant->participant_type ?? 'Internal',
                $attendanceStatus,
                $checkInTimeStr,
                $timeDiff,
                $timingCategory
            ];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Peserta & Kehadiran';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applyParticipantsAttendanceStyles($sheet);
            }
        ];
    }

    private function applyParticipantsAttendanceStyles(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // Set column widths
        $this->setColumnWidths($sheet, [
            'A' => 6,   // No
            'B' => 15,  // NIP
            'C' => 25,  // Nama
            'D' => 18,  // Jabatan
            'E' => 18,  // Divisi
            'F' => 20,  // Institusi
            'G' => 28,  // Email
            'H' => 15,  // Telepon
            'I' => 12,  // Tipe
            'J' => 15,  // Status
            'K' => 18,  // Check-in
            'L' => 15,  // Time diff
            'M' => 15,  // Category
        ]);

        // Main header
        $this->styleMainHeader($sheet, "A1:{$highestCol}1", $sheet->getCell('A1')->getValue(), self::COLORS['PRIMARY']); // Yellow color

        // Find table header row
        $tableHeaderRow = null;
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();
            if ($cellValue === 'No') { // Table header
                $tableHeaderRow = $row;
                break;
            }
        }

        // Style main participants table
        if ($tableHeaderRow) {
            $this->styleDataTable(
                $sheet,
                "A{$tableHeaderRow}:{$highestCol}{$tableHeaderRow}",
                "A" . ($tableHeaderRow + 1) . ":{$highestCol}{$highestRow}"
            );

            // Format NIP column as text
            $this->formatNIP($sheet, "B" . ($tableHeaderRow + 1) . ":B{$highestRow}");

            // Apply conditional formatting for attendance status (column J)
            $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
            $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
            $conditional1->setText('Hadir');
            $conditional1->getStyle()->getFont()->getColor()->setRGB(self::COLORS['SUCCESS']);
            $conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $conditional1->getStyle()->getFill()->getStartColor()->setRGB('DCFCE7'); // Green-100

            $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
            $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
            $conditional2->setText('Tidak Hadir');
            $conditional2->getStyle()->getFont()->getColor()->setRGB(self::COLORS['DANGER']);
            $conditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $conditional2->getStyle()->getFill()->getStartColor()->setRGB('FEE2E2'); // Red-100

            $conditionalStyles = [$conditional1, $conditional2];
            $sheet->getStyle("J" . ($tableHeaderRow + 1) . ":J{$highestRow}")->setConditionalStyles($conditionalStyles);

            // Apply conditional formatting for timing categories (column M)
            $conditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
            $conditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
            $conditional3->setText('Lebih Awal');
            $conditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $conditional3->getStyle()->getFill()->getStartColor()->setRGB('DBEAFE'); // Blue-100
            $conditional3->getStyle()->getFont()->getColor()->setRGB(self::COLORS['PRIMARY']);

            $conditional4 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
            $conditional4->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
            $conditional4->setText('Tepat Waktu');
            $conditional4->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $conditional4->getStyle()->getFill()->getStartColor()->setRGB('DCFCE7'); // Green-100
            $conditional4->getStyle()->getFont()->getColor()->setRGB(self::COLORS['SUCCESS']);

            $conditional5 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
            $conditional5->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
            $conditional5->setText('Terlambat');
            $conditional5->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $conditional5->getStyle()->getFill()->getStartColor()->setRGB('FEE2E2'); // Red-100
            $conditional5->getStyle()->getFont()->getColor()->setRGB(self::COLORS['DANGER']);

            $timingConditionalStyles = [$conditional3, $conditional4, $conditional5];
            $sheet->getStyle("M" . ($tableHeaderRow + 1) . ":M{$highestRow}")->setConditionalStyles($timingConditionalStyles);

            // Format date column (K - check-in time)
            $this->formatDate($sheet, "K" . ($tableHeaderRow + 1) . ":K{$highestRow}");

            // Set freeze panes
            $this->setFreezePanes($sheet, "A" . ($tableHeaderRow + 1));

            // Set auto filter
            $this->setAutoFilter($sheet, "A{$tableHeaderRow}:{$highestCol}{$highestRow}");

            // Set print titles
            $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, $tableHeaderRow);
        }

        // Configure print settings
        $this->configurePrintSettings($sheet, 'landscape');
    }
}
