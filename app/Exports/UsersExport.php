<?php

namespace App\Exports;

use App\Models\User;
use App\Exports\Traits\ExcelStylingTrait;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromArray, WithTitle, WithEvents
{
    use ExcelStylingTrait;

    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function array(): array
    {
        $query = User::where('role', 'participant');

        // Apply filters if provided
        if (!empty($this->filters['division'])) {
            $query->where('division', $this->filters['division']);
        }

        if (!empty($this->filters['institution'])) {
            $query->where('institution', $this->filters['institution']);
        }

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $search = $this->filters['search'];
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('full_name')->get();

        // Calculate statistics for header
        $totalUsers = $users->count();
        $activeUsers = $users->where('is_active', true)->count();
        $totalEvents = \App\Models\Event::count();
        $totalParticipations = \App\Models\EventParticipant::count();

        $data = [];

        // Header utama - konsisten dengan export lainnya
        $data[] = ["LAPORAN PENGGUNA SISTEM"];
        $data[] = []; // Empty row

        // INFORMASI EKSPOR - dengan spacing yang lebih baik
        $data[] = ["📋 INFORMASI EKSPOR"];
        $data[] = ["👥 Total Pengguna Terdaftar", number_format($totalUsers) . " pengguna"];
        $data[] = ["✅ Pengguna Aktif", number_format($activeUsers) . " pengguna"];
        $data[] = ["❌ Pengguna Tidak Aktif", number_format($totalUsers - $activeUsers) . " pengguna"];
        $data[] = ["🎯 Total Acara di Sistem", number_format($totalEvents) . " acara"];
        $data[] = ["📊 Total Partisipasi Acara", number_format($totalParticipations) . " partisipasi"];
        $data[] = ["📅 Tanggal Ekspor", now()->format('d/m/Y H:i')];
        $data[] = ["👤 Diekspor oleh", Auth::user()->name ?? Auth::user()->full_name ?? 'Sistem'];

        // Applied filters - dengan formatting yang lebih baik
        if (!empty($this->filters)) {
            $data[] = []; // Empty row untuk spacing
            $data[] = ["🔍 FILTER YANG DITERAPKAN"];
            if (!empty($this->filters['division'])) {
                $data[] = ["🏢 Divisi", $this->filters['division']];
            }
            if (!empty($this->filters['institution'])) {
                $data[] = ["🏛️ Institusi", $this->filters['institution']];
            }
            if (!empty($this->filters['search'])) {
                $data[] = ["🔎 Pencarian", $this->filters['search']];
            }
        }

        $data[] = []; // Empty row untuk spacing sebelum tabel

        // DAFTAR PENGGUNA - dengan header yang lebih jelas
        $data[] = ["👥 DAFTAR PENGGUNA"];
        $data[] = [
            "No",
            "🆔 NIP",
            "👤 Nama Lengkap",
            "📧 Email",
            "🏢 Divisi",
            "🏛️ Institusi",
            "📬 Acara Diundang",
            "✅ Acara Dihadiri",
            "❌ Acara Tidak Hadir",
            "📊 Tingkat Kehadiran",
            "📅 Tanggal Bergabung",
            "🏷️ Status"
        ];

        // Users data dengan informasi event participation
        foreach ($users as $index => $user) {
            // Hitung statistik event untuk user ini
            $eventsInvited = $user->participatedEvents()->count();
            $eventsAttended = $user->attendances()->count();
            $eventsNotAttended = $eventsInvited - $eventsAttended;

            // Hitung tingkat kehadiran
            $attendanceRate = $eventsInvited > 0
                ? round(($eventsAttended / $eventsInvited) * 100, 1)
                : 0;

            // Format tingkat kehadiran dengan indikator
            $attendanceDisplay = $eventsInvited > 0
                ? $attendanceRate . "%"
                : "-";

            $data[] = [
                $index + 1,
                "'" . ($user->nip ?? '-'), // Prevent scientific notation
                $user->full_name,
                $user->email,
                $user->division ?? '-',
                $user->institution ?? '-',
                $eventsInvited,
                $eventsAttended,
                $eventsNotAttended,
                $attendanceDisplay,
                $user->created_at->format('d/m/Y'),
                $user->is_active ? 'Aktif' : 'Tidak Aktif'
            ];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Daftar Pengguna';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applyUsersStyles($sheet);
            }
        ];
    }

    private function applyUsersStyles(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        // Set column widths for better display
        $this->setColumnWidths($sheet, [
            'A' => 5,   // No
            'B' => 15,  // NIP
            'C' => 25,  // Nama
            'D' => 30,  // Email
            'E' => 20,  // Divisi
            'F' => 20,  // Institusi
            'G' => 12,  // Event Diundang
            'H' => 12,  // Event Dihadiri
            'I' => 12,  // Event Tidak Hadir
            'J' => 15,  // Tingkat Kehadiran
            'K' => 15,  // Tanggal Bergabung
            'L' => 12,  // Status
        ]);

        // Style main header
        $this->styleMainHeader($sheet, "A1:{$highestCol}1", $sheet->getCell('A1')->getValue(), self::COLORS['PRIMARY']);

        // Find sections dynamically and apply consistent styling
        $tableHeaderRow = null;
        $infoExportRow = null;
        $filterRow = null;
        $daftarPenggunaRow = null;

        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();

            // Identify section rows
            if ($cellValue === '📋 INFORMASI EKSPOR') {
                $infoExportRow = $row;
            } elseif ($cellValue === '🔍 FILTER YANG DITERAPKAN') {
                $filterRow = $row;
            } elseif ($cellValue === '👥 DAFTAR PENGGUNA') {
                $daftarPenggunaRow = $row;
            } elseif ($cellValue === 'No' && !$tableHeaderRow) {
                $tableHeaderRow = $row;
                break;
            }
        }

        // Style section headers with proper merged cells and gray background
        if ($infoExportRow) {
            // Merge cells for INFORMASI EKSPOR header
            $sheet->mergeCells("A{$infoExportRow}:{$highestCol}{$infoExportRow}");
            $this->styleSectionHeader($sheet, "A{$infoExportRow}:{$highestCol}{$infoExportRow}", self::COLORS['GRAY_200']);

            // Style info section content
            $infoEndRow = ($filterRow ?: $daftarPenggunaRow ?: $tableHeaderRow) - 2;
            for ($row = $infoExportRow + 1; $row <= $infoEndRow; $row++) {
                $cellValue = $sheet->getCell("A{$row}")->getValue();
                if (!empty($cellValue)) {
                    $this->styleInfoLabels($sheet, "A{$row}");
                    $this->styleInfoValues($sheet, "B{$row}");
                }
            }
        }

        if ($filterRow) {
            // Merge cells for FILTER YANG DITERAPKAN header
            $sheet->mergeCells("A{$filterRow}:{$highestCol}{$filterRow}");
            $this->styleSectionHeader($sheet, "A{$filterRow}:{$highestCol}{$filterRow}", self::COLORS['GRAY_200']);

            // Style filter section content
            $filterEndRow = ($daftarPenggunaRow ?: $tableHeaderRow) - 2;
            for ($row = $filterRow + 1; $row <= $filterEndRow; $row++) {
                $cellValue = $sheet->getCell("A{$row}")->getValue();
                if (!empty($cellValue)) {
                    $this->styleInfoLabels($sheet, "A{$row}");
                    $this->styleInfoValues($sheet, "B{$row}");
                }
            }
        }

        if ($daftarPenggunaRow) {
            // Merge cells for DAFTAR PENGGUNA header
            $sheet->mergeCells("A{$daftarPenggunaRow}:{$highestCol}{$daftarPenggunaRow}");
            $this->styleSectionHeader($sheet, "A{$daftarPenggunaRow}:{$highestCol}{$daftarPenggunaRow}", self::COLORS['GRAY_200']);
        }

        // Style data table if found
        if ($tableHeaderRow) {
            $this->styleDataTable(
                $sheet,
                "A{$tableHeaderRow}:{$highestCol}{$tableHeaderRow}",
                "A" . ($tableHeaderRow + 1) . ":{$highestCol}{$highestRow}"
            );

            // Format NIP column as text to prevent scientific notation
            $this->formatNIP($sheet, "B" . ($tableHeaderRow + 1) . ":B{$highestRow}");

            // Apply conditional formatting for status column (L)
            if ($highestRow > $tableHeaderRow) {
                $this->applyStatusConditionalFormatting($sheet, "L" . ($tableHeaderRow + 1) . ":L{$highestRow}");

                // Format date column (K)
                $this->formatDate($sheet, "K" . ($tableHeaderRow + 1) . ":K{$highestRow}");
            }

            // Set freeze panes
            $this->setFreezePanes($sheet, "A" . ($tableHeaderRow + 1));

            // Set auto filter
            $this->setAutoFilter($sheet, "A{$tableHeaderRow}:{$highestCol}{$highestRow}");

            // Set print titles
            $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, $tableHeaderRow);
        }

        // Configure print settings
        $this->configurePrintSettings($sheet, 'landscape');

        // Add spacing improvements for better visual hierarchy
        if ($infoExportRow) {
            $sheet->getRowDimension($infoExportRow)->setRowHeight(25); // Taller section header
        }
        if ($filterRow) {
            $sheet->getRowDimension($filterRow)->setRowHeight(25); // Taller section header
        }
        if ($daftarPenggunaRow) {
            $sheet->getRowDimension($daftarPenggunaRow)->setRowHeight(25); // Taller section header
        }
        if ($tableHeaderRow) {
            $sheet->getRowDimension($tableHeaderRow)->setRowHeight(30); // Taller table header
        }

        // Add subtle background to alternate sections for better visual separation
        if ($infoExportRow && $tableHeaderRow) {
            // Light background for info section
            $infoSectionEnd = $tableHeaderRow - 1;
            $sheet->getStyle("A" . ($infoExportRow + 1) . ":{$highestCol}{$infoSectionEnd}")
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FAFAFA'); // Very light gray
        }

        // PERBAIKAN KHUSUS: Pastikan semua label di kolom A bold
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();

            // PERBAIKAN KHUSUS: Pastikan DAFTAR PENGGUNA mendapat background abu-abu
            if ($cellValue === 'DAFTAR PENGGUNA') {
                $sheet->getStyle("A{$row}:{$highestCol}{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => self::COLORS['GRAY_200']]
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'name' => 'Segoe UI'
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ]
                ]);
            }

            // Bold untuk semua label info (yang tidak kosong dan bukan header section)
            if (!empty($cellValue) && !in_array($cellValue, ['LAPORAN PENGGUNA SISTEM', '📋 INFORMASI EKSPOR', '🔍 FILTER YANG DITERAPKAN', '👥 DAFTAR PENGGUNA', 'No'])) {
                $sheet->getStyle("A{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'name' => 'Segoe UI'
                    ]
                ]);

                // Pastikan nilai di kolom B tidak bold
                if ($sheet->getCell("B{$row}")->getValue()) {
                    $sheet->getStyle("B{$row}")->applyFromArray([
                        'font' => [
                            'bold' => false,
                            'size' => 10,
                            'name' => 'Segoe UI'
                        ]
                    ]);
                }
            }
        }
    }
}
