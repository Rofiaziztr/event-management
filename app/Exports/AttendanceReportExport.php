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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceReportExport implements FromView, WithTitle, WithEvents, ShouldAutoSize
{
    protected $event;

    public function __construct(Event $event)
    {
        // ======================================================================
        // PERBAIKAN UTAMA ADA DI SINI
        // ======================================================================
        $eventId = $event->id;
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
        // Get only participants who attended
        $attendedParticipants = $this->event->participants->filter(function ($participant) {
            return $participant->attendances->isNotEmpty();
        });

        // Group attendees by check-in time periods
        $timeSlots = [
            'early' => $attendedParticipants->filter(function ($p) {
                if ($p->attendances->isEmpty()) return false;
                return $p->attendances->first()->check_in_time->lt($this->event->start_time);
            }),
            'ontime' => $attendedParticipants->filter(function ($p) {
                if ($p->attendances->isEmpty()) return false;
                return $p->attendances->first()->check_in_time->between(
                    $this->event->start_time,
                    $this->event->start_time->copy()->addMinutes(15)
                );
            }),
            'late' => $attendedParticipants->filter(function ($p) {
                if ($p->attendances->isEmpty()) return false;
                return $p->attendances->first()->check_in_time->gt($this->event->start_time->copy()->addMinutes(15));
            })
        ];

        return view('exports.attendance_report', [
            'event' => $this->event,
            'attendedParticipants' => $attendedParticipants->sortBy(function ($p) {
                return optional($p->attendances->first())->check_in_time;
            }),
            'timeSlots' => $timeSlots,
            'exportDate' => now()->format('d F Y H:i'),
            'showParticipantType' => true // untuk view, agar bisa tampilkan kolom tipe peserta
        ]);
    }

    public function title(): string
    {
        return 'Kehadiran_' . Str::limit($this->event->title, 15);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Apply attendance report specific styling
                $this->applyAttendanceStyles($sheet);
            }
        ];
    }

    private function applyAttendanceStyles($sheet)
    {
        // Main header styling
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']], // Green
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Event info styling - remove borders, keep light background
        $sheet->getStyle('A3:H4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ECFDF5']],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_NONE]]
        ]);

        // Statistics header styling
        $sheet->getStyle('A6:H6')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']], // Blue
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_NONE]]
        ]);

        // Participant list header styling
        $sheet->getStyle('A9:H9')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B5CF6']], // Purple
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_NONE]]
        ]);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(6);     // No
        $sheet->getColumnDimension('B')->setWidth(25);    // Nama
        $sheet->getColumnDimension('C')->setWidth(30);    // Email
        $sheet->getColumnDimension('D')->setWidth(20);    // Divisi
        $sheet->getColumnDimension('E')->setWidth(15);    // Waktu Check-in
        $sheet->getColumnDimension('F')->setWidth(15);    // Status
        $sheet->getColumnDimension('G')->setWidth(20);    // Ketepatan
        $sheet->getColumnDimension('H')->setWidth(12);    // Tipe
    }
}
