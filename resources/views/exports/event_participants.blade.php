<table style="width: 100%;">
    {{-- MAIN HEADER --}}
    <thead>
        <tr>
            <th colspan="10"
                style="font-weight: bold; font-size: 16px; text-align: center; background-color: #10B981; color: white; padding: 15px;">
                LAPORAN PESERTA EVENT - {{ strtoupper($event->title) }}
            </th>
        </tr>
        <tr>
            <td colspan="10" style="height: 5px;"></td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px;">Judul Event</th>
            <td colspan="9" style="padding: 8px;">{{ $event->title }}</td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px;">Waktu Event</th>
            <td colspan="9" style="padding: 8px;">
                {{ $event->start_time->format('d F Y, H:i') }} - {{ $event->end_time->format('H:i') }} WIB
            </td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px;">Lokasi</th>
            <td colspan="9" style="padding: 8px;">{{ $event->location ?? 'Tidak Ditentukan' }}</td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px;">Total Peserta</th>
            <td colspan="9" style="padding: 8px; font-weight: bold; color: #059669;">
                {{ number_format($event->participants->count()) }} orang
            </td>
        </tr>
        <tr></tr>
        {{-- TABLE HEADER --}}
        <tr>
            <td colspan="10" style="height: 10px;"></td>
        </tr>
        <tr>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                No</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                NIP</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Nama Lengkap</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Email</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Instansi</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Jabatan</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Divisi</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Status Kehadiran</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Waktu Check-in</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                Tipe Peserta</th>
        </tr>
    </thead>
    <tbody>
        @forelse($event->participants as $participant)
            @php
                $attendance = $participant->attendances->where('event_id', $event->id)->first();
                $isEven = $loop->iteration % 2 == 0;
                $rowBgColor = $isEven ? '#FFFFFF' : '#F9FAFB';
            @endphp
            <tr>
                <td style="text-align: center; padding: 8px; background-color: {{ $rowBgColor }};">
                    {{ $loop->iteration }}</td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }};">'{{ $participant->nip ?: '-' }}</td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }}; font-weight: 500;">
                    {{ $participant->full_name }}</td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }};">{{ $participant->email }}</td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }};">
                    {{ $participant->institution ?: '-' }}</td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }};">{{ $participant->position ?: '-' }}
                </td>
                <td style="padding: 8px; background-color: {{ $rowBgColor }};">{{ $participant->division ?: '-' }}
                </td>
                <td
                    style="padding: 8px; text-align: center; background-color: {{ $rowBgColor }}; font-weight: bold; color: {{ $attendance ? '#059669' : '#DC2626' }};">
                    {{ $attendance ? '✅ Hadir' : '❌ Tidak Hadir' }}</td>
                <td style="padding: 8px; text-align: center; background-color: {{ $rowBgColor }};">
                    {{ $attendance ? $attendance->check_in_time->format('d/m/Y H:i:s') : '-' }}</td>
                <td
                    style="padding: 8px; text-align: center; background-color: {{ $rowBgColor }}; font-weight: bold;">
                    {{ $participant->participant_type ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 12px; color: #DC2626;">Tidak ada data peserta
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
