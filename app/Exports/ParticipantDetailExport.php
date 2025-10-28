<?php

namespace App\Exports;

use App\Models\User;
use App\Exports\Traits\ExcelStylingTrait;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantDetailExport implements FromArray, WithTitle, WithEvents
{
    use ExcelStylingTrait;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function array(): array
    {
        $user = $this->user;
        $user->load(['participatedEvents', 'attendances.event']);

        $data = [];

        // Header utama - merged A1:J1
        $data[] = ["LAPORAN DETAIL PESERTA"];
        $data[] = []; // Empty row

        // 👤 INFORMASI PESERTA
        $data[] = ["👤 INFORMASI PESERTA"];
        $data[] = ["🆔 NIP", $user->nip ?? '-'];
        $data[] = ["👤 Nama Lengkap", $user->full_name];
        $data[] = ["📧 Email", $user->email];
        $data[] = ["🏢 Jabatan", $user->position ?? '-'];
        $data[] = ["🏢 Divisi", $user->division ?? '-'];
        $data[] = ["🏛️ Institusi", $user->institution ?? '-'];
        $data[] = ["📱 No. Telepon", $user->phone_number ?? '-'];
        $data[] = ["📅 Tanggal Registrasi", $user->created_at->format('d/m/Y H:i')];
        $data[] = []; // Empty row

        // 📊 STATISTIK KEHADIRAN
        $totalInvitations = $user->participatedEvents->count();
        $totalAttendances = $user->attendances->count();
        $attendanceRate = $totalInvitations > 0 ? round(($totalAttendances / $totalInvitations) * 100, 2) : 0;

        $data[] = ["📊 STATISTIK KEHADIRAN"];
        $data[] = ["🎯 Total Undangan", $totalInvitations . " event"];
        $data[] = ["✅ Total Kehadiran", $totalAttendances . " kali"];
        $data[] = ["❌ Total Tidak Hadir", ($totalInvitations - $totalAttendances) . " kali"];
        $data[] = ["📈 Tingkat Kehadiran", $attendanceRate . "%"];
        $data[] = []; // Empty row

        // 📤 INFORMASI EKSPOR
        $data[] = ["📤 INFORMASI EKSPOR"];
        $data[] = ["📅 Tanggal Ekspor", now()->format('d/m/Y H:i')];
        $data[] = ["👤 Diekspor oleh", Auth::user()->name ?? Auth::user()->full_name ?? 'Sistem'];
        $data[] = []; // Empty row

        // DETAIL EVENT DAN KEHADIRAN - Header tabel
        $data[] = ["DETAIL EVENT DAN KEHADIRAN"];
        $data[] = [
            "No",
            "📅 Tanggal",
            "📝 Nama Event",
            "🏢 Lokasi",
            "⏰ Waktu Mulai",
            "⏰ Waktu Selesai",
            "📊 Status",
            "🕒 Waktu Check-in",
            "📝 Keterangan"
        ];

        // Data event dan kehadiran
        $no = 1;
        foreach ($user->participatedEvents->sortBy('start_time') as $event) {
            $attendance = $user->attendances->where('event_id', $event->id)->first();

            $status = 'Tidak Hadir';
            $checkInTime = '-';
            $notes = '-';

            if ($attendance) {
                $status = 'Hadir';
                $checkInTime = $attendance->check_in_time ? $attendance->check_in_time->format('d/m/Y H:i') : '-';
                $notes = $attendance->notes ?? '-';
            }

            $data[] = [
                $no++,
                $event->start_time->format('d/m/Y'),
                $event->title,
                $event->location ?? '-',
                $event->start_time->format('H:i'),
                $event->end_time->format('H:i'),
                $status,
                $checkInTime,
                $notes
            ];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Detail Peserta - ' . $this->user->full_name;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applyParticipantDetailStyles($sheet);
            },
        ];
    }

    private function applyParticipantDetailStyles(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // Set column widths for better display
        $this->setColumnWidths($sheet, [
            'A' => 8,   // No / Labels
            'B' => 25,  // Values
            'C' => 12,  // Tanggal
            'D' => 35,  // Nama Event
            'E' => 20,  // Lokasi
            'F' => 12,  // Waktu Mulai
            'G' => 12,  // Waktu Selesai
            'H' => 15,  // Status
            'I' => 18,  // Waktu Check-in
            'J' => 25,  // Keterangan
        ]);

        // Style main header
        $this->styleMainHeader($sheet, "A1:{$highestCol}1", $sheet->getCell('A1')->getValue(), self::COLORS['PRIMARY']);

        // Find sections dynamically and apply consistent styling
        $infoPesertaRow = null;
        $statistikRow = null;
        $infoEksporRow = null;
        $detailEventRow = null;
        $tableHeaderRow = null;

        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();

            // Identify section rows
            if ($cellValue === '👤 INFORMASI PESERTA') {
                $infoPesertaRow = $row;
            } elseif ($cellValue === '📊 STATISTIK KEHADIRAN') {
                $statistikRow = $row;
            } elseif ($cellValue === '📤 INFORMASI EKSPOR') {
                $infoEksporRow = $row;
            } elseif ($cellValue === 'DETAIL EVENT DAN KEHADIRAN') {
                $detailEventRow = $row;
            } elseif ($cellValue === 'No' && !$tableHeaderRow) {
                $tableHeaderRow = $row;
                break;
            }
        }

        // Style section headers with proper merged cells and gray background
        if ($infoPesertaRow) {
            // Merge cells for INFORMASI PESERTA header
            $sheet->mergeCells("A{$infoPesertaRow}:{$highestCol}{$infoPesertaRow}");
            $this->styleSectionHeader($sheet, "A{$infoPesertaRow}:{$highestCol}{$infoPesertaRow}", self::COLORS['GRAY_200']);

            // Style info section content (NO gray background for content)
            // Find end of section (empty row before next section)
            $infoEndRow = $statistikRow ? $statistikRow - 2 : ($infoEksporRow ? $infoEksporRow - 2 : ($detailEventRow ? $detailEventRow - 2 : ($tableHeaderRow ? $tableHeaderRow - 2 : $highestRow)));
            for ($row = $infoPesertaRow + 1; $row <= $infoEndRow; $row++) {
                $cellValue = $sheet->getCell("A{$row}")->getValue();
                if (!empty($cellValue)) {
                    // Apply plain styling for ALL cells in info section (both labels and values)
                    $this->stylePlainValues($sheet, "A{$row}:B{$row}");
                }
            }
        }

        if ($statistikRow) {
            // Merge cells for STATISTIK KEHADIRAN header
            $sheet->mergeCells("A{$statistikRow}:{$highestCol}{$statistikRow}");
            $this->styleSectionHeader($sheet, "A{$statistikRow}:{$highestCol}{$statistikRow}", self::COLORS['GRAY_200']);

            // Style statistik section content (NO gray background for content)
            $statistikEndRow = $infoEksporRow ? $infoEksporRow - 2 : ($detailEventRow ? $detailEventRow - 2 : ($tableHeaderRow ? $tableHeaderRow - 2 : $highestRow));
            for ($row = $statistikRow + 1; $row <= $statistikEndRow; $row++) {
                $cellValue = $sheet->getCell("A{$row}")->getValue();
                if (!empty($cellValue)) {
                    // Apply plain styling for ALL cells in statistik section (both labels and values)
                    $this->stylePlainValues($sheet, "A{$row}:B{$row}");
                }
            }
        }

        if ($infoEksporRow) {
            // Merge cells for INFORMASI EKSPOR header
            $sheet->mergeCells("A{$infoEksporRow}:{$highestCol}{$infoEksporRow}");
            $this->styleSectionHeader($sheet, "A{$infoEksporRow}:{$highestCol}{$infoEksporRow}", self::COLORS['GRAY_200']);

            // Style ekspor section content (NO gray background for content)
            $eksporEndRow = $detailEventRow ? $detailEventRow - 2 : ($tableHeaderRow ? $tableHeaderRow - 2 : $highestRow);
            for ($row = $infoEksporRow + 1; $row <= $eksporEndRow; $row++) {
                $cellValue = $sheet->getCell("A{$row}")->getValue();
                if (!empty($cellValue)) {
                    // Apply plain styling for ALL cells in ekspor section (both labels and values)
                    $this->stylePlainValues($sheet, "A{$row}:B{$row}");
                }
            }
        }

        if ($detailEventRow) {
            // Merge cells for DETAIL EVENT DAN KEHADIRAN header
            $sheet->mergeCells("A{$detailEventRow}:{$highestCol}{$detailEventRow}");
            $this->styleSectionHeader($sheet, "A{$detailEventRow}:{$highestCol}{$detailEventRow}", self::COLORS['GRAY_200']);
        }

        // Style data table if found
        if ($tableHeaderRow) {
            $this->styleDataTable(
                $sheet,
                "A{$tableHeaderRow}:{$highestCol}{$tableHeaderRow}",
                "A" . ($tableHeaderRow + 1) . ":{$highestCol}{$highestRow}"
            );

            // Set auto filter untuk tabel (on the correct header row)
            $this->setAutoFilter($sheet, "A{$tableHeaderRow}:{$highestCol}{$tableHeaderRow}");

            // Format NIP column as text to prevent scientific notation
            $this->formatNIP($sheet, "B" . ($tableHeaderRow + 1) . ":B{$highestRow}");
        }

        // Configure print settings
        $this->configurePrintSettings($sheet, 'landscape');
    }
}
