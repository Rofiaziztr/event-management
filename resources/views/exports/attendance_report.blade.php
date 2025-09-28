<table style="width: 100%;">
    {{-- MAIN HEADER --}}
    <thead>
        <tr>
            <th colspan="9"
                style="font-weight: bold; font-size: 16px; text-align: center; background-color: #10B981; color: white; padding: 15px;">
                LAPORAN KEHADIRAN - {{ strtoupper($event->title) }}
            </th>
        </tr>

        {{-- EVENT INFORMATION --}}
        <tr>
            <td colspan="8" style="height: 5px;"></td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px;">Waktu Event</th>
            <td colspan="7" style="padding: 8px; border: 1px solid #E5E7EB;">
                {{ $event->start_time->format('d F Y, H:i') }} - {{ $event->end_time->format('H:i') }} WIB
            </td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px;">Lokasi</th>
            <td colspan="7" style="padding: 8px; border: 1px solid #E5E7EB;">
                {{ $event->location ?? 'Tidak Ditentukan' }}</td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px;">Total Hadir</th>
            <td colspan="7" style="padding: 8px; border: 1px solid #E5E7EB; font-weight: bold; color: #059669;">
                {{ $attendedParticipants->count() }} dari {{ $event->participants->count() }} peserta
                ({{ $event->participants->count() > 0 ? round(($attendedParticipants->count() / $event->participants->count()) * 100, 1) : 0 }}%)
            </td>
        </tr>

        {{-- TIME SLOT STATISTICS --}}
        <tr>
            <td colspan="8" style="height: 10px;"></td>
        </tr>
        <tr>
            <th colspan="9"
                style="font-weight: bold; font-size: 14px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                STATISTIK WAKTU KEHADIRAN
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Tepat Waktu (0-15
                menit)</th>
            <td style="padding: 8px; text-align: center; font-weight: bold; color: #059669;">
                {{ $timeSlots['ontime']->count() }} orang</td>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Terlambat (>15
                menit)</th>
            <td style="padding: 8px; text-align: center; font-weight: bold; color: #DC2626;">
                {{ $timeSlots['late']->count() }} orang</td>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Dibuat pada</th>
            <td style="padding: 8px; text-align: center;">{{ $exportDate }}</td>
        </tr>

        {{-- TABLE HEADER --}}
        <tr>
            <td colspan="8" style="height: 10px;"></td>
        </tr>
        <tr>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                No</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Nama Lengkap</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Email</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Divisi</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Waktu Check-in</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Selisih Waktu</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Status Waktu Kehadiran</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Keterangan</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Tipe Peserta</th>
        </tr>
    </thead>

    {{-- ATTENDANCE DATA --}}
    <tbody>
        @forelse($attendedParticipants as $participant)
            @php
                $attendance = $participant->attendances->first();
                $isEven = $loop->iteration % 2 == 0;
                $rowBgColor = $isEven ? '#FFFFFF' : '#F9FAFB';

                $checkInTime = $attendance->check_in_time;
                $eventStartTime = $event->start_time;
                $timeDifference = $checkInTime->diffInMinutes($eventStartTime);
                $timeDifferenceFormatted = '';
                $timeStatus = '';
                $timeStatusColor = '';

                if ($checkInTime->lt($eventStartTime)) {
                    $timeDifferenceFormatted = '-' . $timeDifference . ' menit';
                    $timeStatus = 'Lebih Awal';
                    $timeStatusColor = '#3B82F6'; // Blue
                } elseif ($checkInTime->lte($eventStartTime->copy()->addMinutes(15))) {
                    $timeDifferenceFormatted = '+' . $timeDifference . ' menit';
                    $timeStatus = 'Tepat Waktu';
                    $timeStatusColor = '#059669'; // Green
                } else {
                    $timeDifferenceFormatted = '+' . $timeDifference . ' menit';
                    $timeStatus = 'Terlambat';
                    $timeStatusColor = '#DC2626'; // Red
                }
            @endphp
            <tr>
                <td
                    style="text-align: center; font-weight: bold; padding: 8px; background-color: {{ $rowBgColor }}; color: {{ $timeStatusColor }};">
                    {{ $loop->iteration }}</td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }}; font-weight: 500;">
                    {{ $participant->full_name }}</td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }};">{{ $participant->email }}</td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }};">{{ $participant->division ?: '-' }}
                </td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }}; text-align: center;">
                    {{ $checkInTime->format('d/m/Y H:i:s') }}
                </td>
                <td
                    style="padding: 8px; background-color: {{ $rowBgColor }}; text-align: center; font-weight: bold;">
                    {{ $timeDifferenceFormatted }}
                </td>
                <td
                    style="padding: 8px; text-align: center; font-weight: bold; color: {{ $timeStatusColor }}; background-color: {{ $rowBgColor }};">
                    {{ $timeStatus }}
                </td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }};">
                    @if ($timeStatus === 'Tepat Waktu')
                        Kehadiran optimal
                    @elseif($timeStatus === 'Lebih Awal')
                        Sangat antusias
                    @else
                        Perlu evaluasi
                    @endif
                </td>
                <td
                    style="padding: 8px; background-color: {{ $rowBgColor }}; text-align: center; font-weight: bold;">
                    {{ $participant->participant_type ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 12px; color: #DC2626;">Tidak ada data kehadiran
                    peserta.</td>
            </tr>
        @endforelse

        {{-- SUMMARY BY TIME SLOTS (LEFT-ALIGNED, NO EXTRA TABLE WRAPPER) --}}
        <tr>
            <td colspan="9" style="height: 20px;"></td>
        </tr>
        <tr>
            <th colspan="9"
                style="font-weight: bold; font-size: 14px; text-align: center; background-color: #8B5CF6; color: white; padding: 12px;">
                DETAIL BERDASARKAN WAKTU KEHADIRAN
            </th>
        </tr>

        {{-- EARLY ARRIVALS --}}
        @if ($timeSlots['early']->count() > 0)
            <tr>
                <th colspan="9" style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">
                    Datang Lebih Awal ({{ $timeSlots['early']->count() }} orang)
                </th>
            </tr>
            @foreach ($timeSlots['early'] as $participant)
                @php $attendance = $participant->attendances->first(); @endphp
                <tr>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; width: 30px; text-align: center;">
                        {{ $loop->iteration }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; font-weight: 500;">
                        {{ $participant->full_name }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB;">{{ $participant->email }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB;">
                        {{ $participant->division ?: 'Tidak Diketahui' }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; text-align: center;">
                        {{ $attendance->check_in_time->format('H:i:s') }}</td>
                    <td colspan="2"></td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; text-align: center; font-weight: bold;">
                        {{ $participant->participant_type ?? '-' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="9" style="height: 5px;"></td>
            </tr>
        @endif

        {{-- ON TIME ARRIVALS --}}
        @if ($timeSlots['ontime']->count() > 0)
            <tr>
                <th colspan="9" style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px;">
                    Tepat Waktu ({{ $timeSlots['ontime']->count() }} orang)
                </th>
            </tr>
            @foreach ($timeSlots['ontime'] as $participant)
                @php $attendance = $participant->attendances->first(); @endphp
                <tr>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; width: 30px; text-align: center;">
                        {{ $loop->iteration }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; font-weight: 500;">
                        {{ $participant->full_name }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB;">{{ $participant->email }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB;">
                        {{ $participant->division ?: 'Tidak Diketahui' }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; text-align: center;">
                        {{ $attendance->check_in_time->format('H:i:s') }}</td>
                    <td colspan="2"></td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; text-align: center; font-weight: bold;">
                        {{ $participant->participant_type ?? '-' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="9" style="height: 5px;"></td>
            </tr>
        @endif

        {{-- LATE ARRIVALS --}}
        @if ($timeSlots['late']->count() > 0)
            <tr>
                <th colspan="9" style="font-weight: bold; background-color: #FEF2F2; color: #991B1B; padding: 8px;">
                    Terlambat ({{ $timeSlots['late']->count() }} orang)
                </th>
            </tr>
            @foreach ($timeSlots['late'] as $participant)
                @php $attendance = $participant->attendances->first(); @endphp
                <tr>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; width: 30px; text-align: center;">
                        {{ $loop->iteration }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; font-weight: 500;">
                        {{ $participant->full_name }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB;">{{ $participant->email }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB;">
                        {{ $participant->division ?: 'Tidak Diketahui' }}</td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; text-align: center;">
                        {{ $attendance->check_in_time->format('H:i:s') }}</td>
                    <td colspan="2"></td>
                    <td style="padding: 6px; border: 1px solid #E5E7EB; text-align: center; font-weight: bold;">
                        {{ $participant->participant_type ?? '-' }}</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="9" style="height: 10px;"></td>
        </tr>

        {{-- FOOTER --}}
        <tr>
            <td colspan="9"
                style="text-align: center; padding: 15px; background-color: #F3F4F6; font-size: 10px; color: #4B5563;">
                Laporan Kehadiran Event - Dibuat otomatis oleh Sistem Manajemen Event PSDMBP<br>
                Waktu Pembuatan: {{ $exportDate }}<br>
                Untuk pertanyaan terkait laporan ini, silakan hubungi administrator sistem
            </td>
        </tr>
</table>