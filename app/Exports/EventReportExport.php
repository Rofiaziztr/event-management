<?php

namespace App\Exports;

use App\Exports\Sheets\EventSummarySheet;
use App\Exports\Sheets\ParticipantsAttendanceSheet;
use App\Exports\Traits\PreparesEventData;
use App\Models\Event;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventReportExport implements WithMultipleSheets
{
    use Exportable, PreparesEventData;

    protected Event $event;

    /**
     * Menyiapkan data event sekali saja saat kelas diinisiasi.
     *
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        // Panggil trait untuk mengambil dan menggabungkan semua data peserta.
        // Hasilnya akan disimpan dalam properti $this->event.
        $this->event = $this->prepareEvent($event);
    }

    /**
     * Mendaftarkan semua sheet yang akan diekspor.
     * Maatwebsite/Excel akan secara otomatis memanggil setiap kelas sheet
     * dan menggabungkannya ke dalam satu file.
     *
     * @return array
     */
    public function sheets(): array
    {
        return [
            new EventSummarySheet($this->event),
            new ParticipantsAttendanceSheet($this->event),
        ];
    }
}
