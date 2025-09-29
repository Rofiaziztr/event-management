<?php

namespace App\Exports;

use App\Models\Event;
use App\Exports\Traits\ExcelStylingTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventComparisonExport implements FromArray, WithTitle, WithEvents
{
    use ExcelStylingTrait;

    protected $events;

    public function __construct($eventIds)
    {
        $this->events = Event::with(['participants.attendances', 'category'])
            ->whereIn('id', $eventIds)
            ->orderBy('start_time')
            ->get();
    }

    public function array(): array
    {
        $data = [];
        
        // Header utama
        $data[] = ["📊 PERBANDINGAN KEHADIRAN MULTI EVENT"];
        $data[] = []; // Empty row
        
        // Export info
        $data[] = ["📋 INFORMASI EKSPOR"];
        $data[] = ["🎯 Jumlah Event", $this->events->count()];
        $data[] = ["📅 Tanggal Ekspor", now()->format('d/m/Y H:i')];
        $data[] = []; // Empty row
        
        // Summary comparison
        $data[] = ["📈 RINGKASAN PERBANDINGAN"];
        $data[] = [
            "No",
            "🎯 Nama Event",
            "📅 Tanggal",
            "🏷️ Kategori",
            "📬 Total Diundang",
            "✅ Hadir",
            "📊 Tingkat Kehadiran (%)",
            "🏷️ Status Event"
        ];
        
        foreach ($this->events as $index => $event) {
            $totalInvited = $event->participants->count();
            $totalAttended = $event->participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
            $attendanceRate = $totalInvited > 0 ? round(($totalAttended / $totalInvited) * 100, 1) : 0;
            
            $data[] = [
                $index + 1,
                $event->title,
                $event->start_time->format('d/m/Y'),
                $event->category->name ?? 'Tidak Berkategori',
                $totalInvited,
                $totalAttended,
                $attendanceRate,
                $event->status
            ];
        }
        
        $data[] = []; // Empty row
        
        // Detailed participant tracking across events
        $allParticipants = collect();
        foreach ($this->events as $event) {
            $allParticipants = $allParticipants->merge($event->participants);
        }
        
        $uniqueParticipants = $allParticipants->unique('id')->sortBy('full_name');
        
        if ($uniqueParticipants->isNotEmpty()) {
            $data[] = ["👥 TRACKING PESERTA LINTAS EVENT"];
            
            // Build dynamic header
            $header = ["No", "🆔 NIP", "👤 Nama Lengkap", "🏢 Divisi", "🏛️ Institusi"];
            foreach ($this->events as $event) {
                $header[] = $event->title . " (" . $event->start_time->format('d/m') . ")";
            }
            $header[] = "📬 Total Event Diundang";
            $header[] = "✅ Total Event Hadir";
            $header[] = "📊 Tingkat Partisipasi (%)";
            
            $data[] = $header;
            
            foreach ($uniqueParticipants as $index => $participant) {
                $row = [
                    $index + 1,
                    $participant->nip ?? '-',
                    $participant->full_name,
                    $participant->division ?? '-',
                    $participant->institution ?? '-'
                ];
                
                $invitedCount = 0;
                $attendedCount = 0;
                
                foreach ($this->events as $event) {
                    $isInvited = $event->participants->contains('id', $participant->id);
                    $isAttended = false;
                    
                    if ($isInvited) {
                        $invitedCount++;
                        $attendance = $event->attendances->where('user_id', $participant->id)->first();
                        if ($attendance) {
                            $isAttended = true;
                            $attendedCount++;
                        }
                    }
                    
                    $row[] = $isInvited ? ($isAttended ? 'Hadir' : 'Tidak Hadir') : 'Tidak Diundang';
                }
                
                $row[] = $invitedCount;
                $row[] = $attendedCount;
                $row[] = $invitedCount > 0 ? round(($attendedCount / $invitedCount) * 100, 1) : 0;
                
                $data[] = $row;
            }
        }
        
        return $data;
    }

    public function title(): string
    {
        return 'Perbandingan Event';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applyComparisonStyles($sheet);
            }
        ];
    }

    private function applyComparisonStyles(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        
        // Set basic column widths
        $this->setColumnWidths($sheet, [
            'A' => 6,   // No
            'B' => 15,  // NIP
            'C' => 25,  // Nama
            'D' => 20,  // Divisi
            'E' => 25,  // Institusi
        ]);

        // Main header
        $this->styleMainHeader($sheet, "A1:{$highestCol}1", $sheet->getCell('A1')->getValue(), self::COLORS['WARNING']);

        // Find different sections
        $summaryHeaderRow = null;
        $participantHeaderRow = null;
        
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();
            if (strpos($cellValue, 'RINGKASAN PERBANDINGAN') !== false) {
                $summaryHeaderRow = $row;
            } elseif (strpos($cellValue, 'TRACKING PESERTA') !== false) {
                $participantHeaderRow = $row;
            }
        }

        // Style info section
        $this->styleSectionHeader($sheet, "A3:{$highestCol}3");
        $this->styleInfoSection($sheet, 'A4:B5');
        $this->styleInfoLabels($sheet, 'A4:A5');

        // Style summary section
        if ($summaryHeaderRow) {
            $this->styleSectionHeader($sheet, "A{$summaryHeaderRow}:{$highestCol}{$summaryHeaderRow}");
            $summaryTableRow = $summaryHeaderRow + 1;
            
            // Find end of summary table
            $summaryEndRow = $summaryTableRow;
            for ($row = $summaryTableRow + 1; $row <= $highestRow; $row++) {
                if (empty($sheet->getCell("A{$row}")->getValue()) || 
                    !is_numeric($sheet->getCell("A{$row}")->getValue())) {
                    $summaryEndRow = $row - 1;
                    break;
                }
            }
            
            $this->styleDataTable(
                $sheet, 
                "A{$summaryTableRow}:H{$summaryTableRow}", 
                "A" . ($summaryTableRow + 1) . ":H{$summaryEndRow}"
            );
            
            // Format percentage column
            $this->formatNumbers($sheet, "G" . ($summaryTableRow + 1) . ":G{$summaryEndRow}", '0.0');
        }

        // Style participant tracking section
        if ($participantHeaderRow) {
            $this->styleSectionHeader($sheet, "A{$participantHeaderRow}:{$highestCol}{$participantHeaderRow}");
            $participantTableRow = $participantHeaderRow + 1;
            
            $this->styleDataTable(
                $sheet, 
                "A{$participantTableRow}:{$highestCol}{$participantTableRow}", 
                "A" . ($participantTableRow + 1) . ":{$highestCol}{$highestRow}"
            );
            
            // Apply conditional formatting for attendance status
            $eventColumns = [];
            $headerRow = $sheet->rangeToArray("A{$participantTableRow}:{$highestCol}{$participantTableRow}")[0];
            
            foreach ($headerRow as $colIndex => $headerValue) {
                if (strpos($headerValue, '(') !== false && strpos($headerValue, ')') !== false) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                    $eventColumns[] = $columnLetter;
                }
            }
            
            foreach ($eventColumns as $col) {
                $this->applyStatusConditionalFormatting($sheet, "{$col}" . ($participantTableRow + 1) . ":{$col}{$highestRow}");
            }
            
            // Set freeze panes
            $this->setFreezePanes($sheet, "A" . ($participantTableRow + 1));
            
            // Set auto filter
            $this->setAutoFilter($sheet, "A{$participantTableRow}:{$highestCol}{$highestRow}");
        }

        // Configure print settings
        $this->configurePrintSettings($sheet, 'landscape');
    }
}