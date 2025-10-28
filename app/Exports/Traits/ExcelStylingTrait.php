<?php

namespace App\Exports\Traits;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait ExcelStylingTrait
{
    /**
     * Brand colors untuk konsistensi
     */
    protected const COLORS = [
        'PRIMARY' => 'FFD700',      // True Yellow/Gold
        'BLUE' => '3B82F6',         // Blue-500
        'SUCCESS' => '10B981',      // Emerald-500
        'WARNING' => 'F59E0B',      // Amber-500
        'DANGER' => 'EF4444',       // Red-500
        'GRAY_100' => 'F3F4F6',     // Gray-100
        'GRAY_200' => 'E5E7EB',     // Gray-200
        'GRAY_600' => '4B5563',     // Gray-600
        'GRAY_800' => '1F2937',     // Gray-800
        'WHITE' => 'FFFFFF',
    ];

    /**
     * Style header utama untuk setiap sheet
     */
    protected function styleMainHeader(Worksheet $sheet, string $range, string $title, ?string $bgColor = null): void
    {
        $sheet->mergeCells($range);
        $sheet->setCellValue(explode(':', $range)[0], $title);

        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => self::COLORS['WHITE']],
                'name' => 'Segoe UI'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgColor ?? self::COLORS['PRIMARY']]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => self::COLORS['GRAY_200']]
                ]
            ]
        ]);

        // Set row height
        $row = (int) filter_var(explode(':', $range)[0], FILTER_SANITIZE_NUMBER_INT);
        $sheet->getRowDimension($row)->setRowHeight(35);
    }

    /**
     * Style untuk section header
     */
    protected function styleSectionHeader(Worksheet $sheet, string $range, ?string $bgColor = null): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => self::COLORS['GRAY_800']],
                'name' => 'Segoe UI'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgColor ?? self::COLORS['GRAY_100']]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['GRAY_200']]
                ]
            ]
        ]);
    }

    /**
     * Style untuk tabel data dengan alternating rows
     */
    protected function styleDataTable(Worksheet $sheet, string $headerRange, string $dataRange): void
    {
        // Header table
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => self::COLORS['WHITE']],
                'name' => 'Segoe UI'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::COLORS['GRAY_600']]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => self::COLORS['GRAY_200']]
                ]
            ]
        ]);

        // Data rows
        $sheet->getStyle($dataRange)->applyFromArray([
            'font' => [
                'size' => 10,
                'name' => 'Segoe UI'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => self::COLORS['GRAY_200']]
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Apply alternating row colors untuk readability
        $this->applyAlternatingRows($sheet, $dataRange);
    }

    /**
     * Terapkan warna bergantian pada baris data
     */
    private function applyAlternatingRows(Worksheet $sheet, string $range): void
    {
        $rangeArr = explode(':', $range);
        $startCell = $rangeArr[0];
        $endCell = $rangeArr[1];

        preg_match('/([A-Z]+)(\d+)/', $startCell, $startMatches);
        preg_match('/([A-Z]+)(\d+)/', $endCell, $endMatches);

        $startRow = (int) $startMatches[2];
        $endRow = (int) $endMatches[2];
        $startCol = $startMatches[1];
        $endCol = $endMatches[1];

        for ($row = $startRow; $row <= $endRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => self::COLORS['GRAY_100']]
                    ]
                ]);
            }
        }
    }

    /**
     * Style untuk informasi umum/metadata
     */
    protected function styleInfoSection(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'size' => 10,
                'name' => 'Segoe UI'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => self::COLORS['GRAY_200']]
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT
            ]
        ]);
    }

    /**
     * Style untuk label/key di info section
     */
    protected function styleInfoLabels(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'name' => 'Segoe UI'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => self::COLORS['GRAY_100']]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }

    /**
     * Style untuk value di info section (plain tanpa bold/background)
     */
    protected function stylePlainValues(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => false,
                'size' => 10,
                'name' => 'Segoe UI'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }

    /**
     * Format untuk angka dan persentase
     */
    protected function formatNumbers(Worksheet $sheet, string $range, string $format = '#,##0'): void
    {
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode($format);
    }

    /**
     * Format untuk NIP agar tidak menjadi scientific notation
     */
    protected function formatNIP(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode('@'); // Text format
    }

    /**
     * Format untuk persentase
     */
    protected function formatPercentage(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode('0.0%');
    }

    /**
     * Format untuk tanggal
     */
    protected function formatDate(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm');
    }

    /**
     * Conditional formatting untuk status
     */
    protected function applyStatusConditionalFormatting(Worksheet $sheet, string $range): void
    {
        $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
        $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
        $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
        $conditional1->setText('Hadir');
        $conditional1->getStyle()->getFont()->getColor()->setRGB(self::COLORS['SUCCESS']);
        $conditional1->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
        $conditional1->getStyle()->getFill()->getStartColor()->setRGB('DCFCE7'); // Green-100

        $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
        $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
        $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
        $conditional2->setText('Tidak Hadir');
        $conditional2->getStyle()->getFont()->getColor()->setRGB(self::COLORS['DANGER']);
        $conditional2->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
        $conditional2->getStyle()->getFill()->getStartColor()->setRGB('FEE2E2'); // Red-100

        $conditionalStyles = [$conditional1, $conditional2];
        $sheet->getStyle($range)->setConditionalStyles($conditionalStyles);
    }

    /**
     * Set freeze panes untuk tabel
     */
    protected function setFreezePanes(Worksheet $sheet, string $cell): void
    {
        $sheet->freezePane($cell);
    }

    /**
     * Set auto filter untuk tabel
     */
    protected function setAutoFilter(Worksheet $sheet, string $range): void
    {
        $sheet->setAutoFilter($range);
    }

    /**
     * Set print area dan orientasi
     */
    protected function configurePrintSettings(Worksheet $sheet, string $orientation = 'landscape'): void
    {
        $pageSetup = $sheet->getPageSetup();

        if ($orientation === 'landscape') {
            $pageSetup->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        } else {
            $pageSetup->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        }

        $pageSetup->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $pageSetup->setFitToPage(true);
        $pageSetup->setFitToWidth(1);
        $pageSetup->setFitToHeight(0);

        // Margins
        $sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setBottom(0.75);

        // Header & Footer
        $sheet->getHeaderFooter()
            ->setOddHeader('&C&B' . config('app.name', 'Event Management System'))
            ->setOddFooter('&L&D &T&C&BLaporan Event&R&P dari &N');
    }

    /**
     * Set column widths secara otomatis atau manual
     */
    protected function setColumnWidths(Worksheet $sheet, array $widths): void
    {
        foreach ($widths as $column => $width) {
            if ($width === 'auto') {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            } else {
                $sheet->getColumnDimension($column)->setWidth($width);
            }
        }
    }
}
