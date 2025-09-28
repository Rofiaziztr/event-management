<?php

namespace App\Exports\Traits;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\User;

trait PreparesEventData
{
    /**
     * Menyiapkan data event dengan menggabungkan peserta internal dan eksternal.
     *
     * @param Event $event
     * @return Event
     */
    protected function prepareEvent(Event $event): Event
    {
        $eventId = $event->id;
        $event->load(['category']);

        // 1. Ambil peserta internal (yang terdaftar di event_participants)
        // Kita juga langsung mengambil data kehadiran mereka untuk event ini.
        $internalParticipants = $event->participants()->with(['attendances' => function ($query) use ($eventId) {
            $query->where('event_id', $eventId);
        }])->get()->map(function ($user) {
            $user->participant_type = 'Internal'; // Tambahkan atribut custom untuk membedakan tipe
            return $user;
        });

        // 2. Ambil peserta eksternal (yang hanya tercatat di tabel attendances)
        // Pertama, kita cari user_id yang ada di attendances tapi tidak ada di daftar internal.
        $externalUserIds = Attendance::where('event_id', $eventId)
            ->whereNotIn('user_id', $internalParticipants->pluck('id'))
            ->pluck('user_id')
            ->unique();

        // Kedua, ambil data lengkap user untuk peserta eksternal tersebut.
        $externalParticipants = User::whereIn('id', $externalUserIds)
            ->with(['attendances' => function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            }])->get()->map(function ($user) {
                $user->participant_type = 'Eksternal'; // Tambahkan atribut custom
                return $user;
            });

        // 3. Gabungkan kedua jenis peserta menjadi satu koleksi
        $allParticipants = $internalParticipants->concat($externalParticipants);

        // 4. Set relasi 'participants' pada objek $event dengan data yang sudah digabung
        // Ini memungkinkan kita untuk mengakses $event->participants di mana saja
        // seolah-olah semua peserta berasal dari satu sumber.
        $event->setRelation('participants', $allParticipants);

        return $event;
    }
}