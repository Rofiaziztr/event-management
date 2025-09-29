<?php

namespace App\Exports\Sheets;

use App\Models\Event;
use App\Exports\Traits\ExcelStylingTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventParticipantsSheet implements FromArray, WithTitle, WithEvents
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
        $data[] = ["DAFTAR PESERTA EVENT: " . strtoupper($this->event->title)];
        $data[] = []; // Empty row
        
        // Event info section
        $data[] = ["INFORMASI EVENT"];
        $data[] = ["Nama Event", $this->event->title];
        $data[] = ["Tanggal", $this->event->start_time->format('d/m/Y H:i') . ' - ' . $this->event->end_time->format('d/m/Y H:i')];
        $data[] = ["Lokasi", $this->event->location];
        $data[] = ["Total Peserta", $this->event->participants->count()];
        $data[] = ["Tanggal Export", now()->format('d/m/Y H:i')];
        $data[] = []; // Empty row
        
        // Table header
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
            "Waktu Check-in"
        ];
        
        // Participants data
        $participants = $this->event->participants
            ->sortBy('full_name')
            ->values();
            
        foreach ($participants as $index => $participant) {
            $attendance = $participant->attendances->first();
            $attendanceStatus = $attendance ? 'Hadir' : 'Tidak Hadir';
            $checkInTime = $attendance ? $attendance->check_in_time->format('d/m/Y H:i:s') : '-';
            $participantType = $participant->participant_type ?? 'Internal';
            
            $data[] = [
                $index + 1,
                "'" . ($participant->nip ?? '-'), // Add apostrophe to prevent scientific notation
                $participant->full_name,
                $participant->position ?? '-',
                $participant->division ?? '-',
                $participant->institution ?? '-',
                $participant->email,
                $participant->phone_number ?? '-',
                $participantType,
                $attendanceStatus,
                $checkInTime
            ];
        }
        
        return $data;
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
                $this->applyParticipantsStyles($sheet);
            }
        ];
    }

    private function applyParticipantsStyles(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        
        // Set column widths
        $this->setColumnWidths($sheet, [
            'A' => 6,   // No
            'B' => 15,  // NIP
            'C' => 25,  // Nama
            'D' => 20,  // Jabatan
            'E' => 20,  // Divisi
            'F' => 25,  // Institusi
            'G' => 30,  // Email
            'H' => 15,  // Telepon
            'I' => 12,  // Tipe
            'J' => 15,  // Status
            'K' => 18,  // Check-in
        ]);

        // Main header
        $this->styleMainHeader($sheet, "A1:{$highestCol}1", $sheet->getCell('A1')->getValue());

        // Find sections dynamically
        $infoEventRow = null;
        $tableHeaderRow = null;
        
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();
            if (strpos($cellValue, 'INFORMASI EVENT') !== false) {
                $infoEventRow = $row;
            } elseif ($cellValue === 'No') { // Table header
                $tableHeaderRow = $row;
                break;
            }
        }

        // Style info section
        if ($infoEventRow) {
            $this->styleSectionHeader($sheet, "A{$infoEventRow}:{$highestCol}{$infoEventRow}");
            
            $infoEndRow = $tableHeaderRow ? $tableHeaderRow - 2 : $infoEventRow + 5;
            $this->styleInfoSection($sheet, "A" . ($infoEventRow + 1) . ":B{$infoEndRow}");
            $this->styleInfoLabels($sheet, "A" . ($infoEventRow + 1) . ":A{$infoEndRow}");
            $this->styleInfoValues($sheet, "B" . ($infoEventRow + 1) . ":B{$infoEndRow}");
        }
        
        // Style table
        if ($tableHeaderRow) {
            $this->styleDataTable(
                $sheet, 
                "A{$tableHeaderRow}:{$highestCol}{$tableHeaderRow}", // Header
                "A" . ($tableHeaderRow + 1) . ":{$highestCol}{$highestRow}" // Data
            );

            // Format NIP column as text
            $this->formatNIP($sheet, "B" . ($tableHeaderRow + 1) . ":B{$highestRow}");

            // Apply conditional formatting untuk status kehadiran (kolom J)
            if ($highestRow > $tableHeaderRow) {
                // Fix conditional formatting - make sure colors are correct
                $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
                $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
                $conditional1->setText('Hadir');
                $conditional1->getStyle()->getFont()->getColor()->setRGB(self::COLORS['SUCCESS']);
                $conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $conditional1->getStyle()->getFill()->getStartColor()->setRGB('DCFCE7'); // Green-100

                $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
                $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
                $conditional2->setText('Tidak Hadir');
                $conditional2->getStyle()->getFont()->getColor()->setRGB(self::COLORS['DANGER']);
                $conditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $conditional2->getStyle()->getFill()->getStartColor()->setRGB('FEE2E2'); // Red-100

                $conditionalStyles = [$conditional1, $conditional2];
                $sheet->getStyle("J" . ($tableHeaderRow + 1) . ":J{$highestRow}")->setConditionalStyles($conditionalStyles);
            }

            // Set freeze panes (freeze header and info sections)
            $this->setFreezePanes($sheet, "A" . ($tableHeaderRow + 1));
            
            // Set auto filter pada tabel - CORRECT PLACEMENT
            $this->setAutoFilter($sheet, "A{$tableHeaderRow}:{$highestCol}{$highestRow}");

            // Format date columns (K - check-in time)
            if ($highestRow > $tableHeaderRow) {
                $this->formatDate($sheet, "K" . ($tableHeaderRow + 1) . ":K{$highestRow}");
            }
            
            // Set print titles (repeat header on each page)
            $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, $tableHeaderRow);
        }

        // Configure print settings
        $this->configurePrintSettings($sheet, 'landscape');
    }
}