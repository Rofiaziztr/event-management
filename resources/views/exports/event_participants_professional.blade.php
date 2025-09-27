{{-- resources/views/exports/event_participants_professional.blade.php --}}
<table style="width: 100%;">
    {{-- MAIN HEADER --}}
    <thead>
        <tr>
            <th colspan="11"
                style="font-weight: bold; font-size: 18px; text-align: center; background-color: #F59E0B; color: white; padding: 15px;">
                📋 LAPORAN PESERTA EVENT - {{ strtoupper($event->title) }}
            </th>
        </tr>

        {{-- EVENT INFORMATION SECTION --}}
        <tr>
            <td colspan="11" style="height: 10px;"></td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px;">📅 Judul Event</th>
            <td colspan="10" style="padding: 8px; background-color: white;">{{ $event->title }}</td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px;">🕒 Waktu Event</th>
            <td colspan="10" style="padding: 8px; background-color: white;">
                {{ $event->start_time->format('d F Y, H:i') }} - {{ $event->end_time->format('H:i') }} WIB
            </td>
        </tr>
        <tr>
            <th
                style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px; border: 1px solid #F59E0B;">
                📍 Lokasi</th>
            <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB; background-color: white;">
                {{ $event->location ?? 'Tidak Ditentukan' }}</td>
        </tr>
        <tr>
            <th
                style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px; border: 1px solid #F59E0B;">
                🏷️ Kategori</th>
            <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB; background-color: white;">
                {{ $event->category->name ?? 'Umum' }}</td>
        </tr>
        <tr>
            <th
                style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px; border: 1px solid #F59E0B;">
                📊 Status Event</th>
            <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB; background-color: white;">
                @if ($event->start_time > now())
                    🟡 Terjadwal
                @elseif($event->start_time <= now() && $event->end_time >= now())
                    🟢 Sedang Berlangsung
                @else
                    ⚫ Selesai
                @endif
            </td>
        </tr>
        <tr>
            <th
                style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px; border: 1px solid #F59E0B;">
                📄 Dibuat pada</th>
            <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB; background-color: white;">
                {{ $exportDate }}</td>
        </tr>

        {{-- EMPTY ROW FOR SPACING --}}
        <tr>
            <td colspan="11" style="height: 10px;"></td>
        </tr>

        {{-- STATISTICS SUMMARY --}}
        <tr>
            <th colspan="11"
                style="font-weight: bold; font-size: 14px; text-align: center; background-color: #10B981; color: white; padding: 12px;">
                📈 RINGKASAN STATISTIK KEHADIRAN
            </th>
        </tr>
        <tr>
            <th
                style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px; border: 1px solid #10B981;">
                👥 Total Peserta Diundang</th>
            <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB; font-weight: bold; color: #1F2937;">
                {{ number_format($totalParticipants) }} orang</td>
        </tr>
        <tr>
            <th
                style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px; border: 1px solid #10B981;">
                ✅ Total Peserta Hadir</th>
            <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB; font-weight: bold; color: #059669;">
                {{ number_format($totalAttended) }} orang</td>
        </tr>
        <tr>
            <th
                style="font-weight: bold; background-color: #ECFDF5; color: #065F46; padding: 8px; border: 1px solid #10B981;">
                📊 Tingkat Kehadiran</th>
            <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB; font-weight: bold; color: #7C3AED;">
                {{ $attendanceRate }}%</td>
        </tr>

        {{-- EMPTY ROW FOR SPACING --}}
        <tr>
            <td colspan="11" style="height: 10px;"></td>
        </tr>

        {{-- PARTICIPANTS TABLE HEADER --}}
        <tr>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                No</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                NIP</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Nama Lengkap</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Email</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Instansi</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Jabatan</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Divisi</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                No. Telepon</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Status Kehadiran</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Waktu Check-in</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Keterangan</th>
            <th
                style="font-weight: bold; font-size: 11px; text-align: center; background-color: #3B82F6; color: white; padding: 12px; border: 2px solid #1D4ED8;">
                Tipe Peserta</th>
        </tr>
    </thead>

    {{-- PARTICIPANTS DATA --}}
    <tbody>
        @forelse($event->participants as $participant)
            @php
                $attendance = $participant->attendances->where('event_id', $event->id)->first();
                $isEven = $loop->iteration % 2 == 0;
                $rowBgColor = $isEven ? '#FFFFFF' : '#F9FAFB';
            @endphp
            <tr>
                <td
                    style="text-align: center; padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }};">
                    {{ $loop->iteration }}</td>
                <td style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }};">
                    '{{ $participant->nip ?: '-' }}</td>
                <td
                    style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }}; font-weight: 500;">
                    {{ $participant->full_name }}</td>
                <td style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }};">
                    {{ $participant->email }}</td>
                <td style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }};">
                    {{ $participant->institution ?: 'PSDMBP' }}</td>
                <td style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }};">
                    {{ $participant->position ?: '-' }}</td>
                <td style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }};">
                    {{ $participant->division ?: '-' }}</td>
                <td style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }};">
                    {{ $participant->phone_number ?: '-' }}</td>
                <td style="padding: 8px; text-align: center; font-weight: bold;"
                    class="attendance-status {{ $attendance ? 'present' : 'absent' }}">
                    @if ($attendance)
                        ✅ Hadir
                    @else
                        ❌ Tidak Hadir
                    @endif
                </td>
                <td
                    style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }}; text-align: center;">
                    {{ $attendance ? $attendance->check_in_time->format('d/m/Y H:i:s') : '-' }}
                </td>
                <td style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }};">
                    @if ($attendance)
                        @if ($attendance->check_in_time->between($event->start_time, $event->start_time->copy()->addMinutes(15)))
                            🟢 Tepat Waktu
                        @elseif($attendance->check_in_time->gt($event->start_time->copy()->addMinutes(15)))
                            🟡 Terlambat
                        @else
                            🔵 Lebih Awal
                        @endif
                    @else
                        🔴 Absen
                    @endif
                </td>
                <td
                    style="padding: 8px; border: 1px solid #E5E7EB; background-color: {{ $rowBgColor }}; text-align: center; font-weight: bold;">
                    {{ $participant->participant_type ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="11"
                    style="text-align: center; padding: 20px; font-style: italic; color: #6B7280; background-color: #F9FAFB;">
                    Tidak ada data peserta untuk ditampilkan
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- SPACING --}}
<table>
    <tr>
        <td style="height: 20px;"></td>
    </tr>
</table>

{{-- DETAILED SUMMARY SECTION --}}
<table>
    <tr>
        <th colspan="11"
            style="font-weight: bold; font-size: 14px; text-align: center; background-color: #8B5CF6; color: white; padding: 12px;">
            📋 RINGKASAN DETAIL BERDASARKAN KATEGORI
        </th>
    </tr>

    {{-- Summary by Division --}}
    <tr>
        <th
            style="font-weight: bold; background-color: #F3E8FF; color: #6B21A8; padding: 8px; border: 1px solid #8B5CF6;">
            🏢 Ringkasan per Divisi:</th>
        <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB;"></td>
    </tr>
    @foreach ($participantsByDivision as $division => $participants)
        @php
            $divisionAttended = $participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
            $divisionRate =
                $participants->count() > 0 ? round(($divisionAttended / $participants->count()) * 100, 1) : 0;
        @endphp
        <tr>
            <td style="padding: 6px; border: 1px solid #E5E7EB; font-weight: 500; padding-left: 20px;">
                {{ $division ?: 'Tidak Diketahui' }}</td>
            <td colspan="10" style="padding: 6px; border: 1px solid #E5E7EB;">
                {{ $divisionAttended }}/{{ $participants->count() }} hadir ({{ $divisionRate }}%)
            </td>
        </tr>
    @endforeach

    {{-- Summary by Institution --}}
    <tr>
        <th
            style="font-weight: bold; background-color: #F3E8FF; color: #6B21A8; padding: 8px; border: 1px solid #8B5CF6;">
            🏛️ Ringkasan per Instansi:</th>
        <td colspan="10" style="padding: 8px; border: 1px solid #E5E7EB;"></td>
    </tr>
    @foreach ($participantsByInstitution as $institution => $participants)
        @php
            $institutionAttended = $participants->filter(fn($p) => $p->attendances->isNotEmpty())->count();
            $institutionRate =
                $participants->count() > 0 ? round(($institutionAttended / $participants->count()) * 100, 1) : 0;
        @endphp
        <tr>
            <td style="padding: 6px; border: 1px solid #E5E7EB; font-weight: 500; padding-left: 20px;">
                {{ $institution ?: 'Tidak Diketahui' }}</td>
            <td colspan="10" style="padding: 6px; border: 1px solid #E5E7EB;">
                {{ $institutionAttended }}/{{ $participants->count() }} hadir ({{ $institutionRate }}%)
            </td>
        </tr>
    @endforeach

    {{-- Footer Information --}}
    <tr>
        <td colspan="11" style="height: 10px;"></td>
    </tr>
    <tr>
        <td colspan="11"
            style="text-align: center; padding: 15px; background-color: #F3F4F6; border: 2px solid #9CA3AF; font-size: 10px; color: #4B5563;">
            📄 Laporan ini dibuat secara otomatis oleh Sistem Manajemen Event PSDMBP<br>
            🕐 Waktu Pembuatan: {{ $exportDate }}<br>
            💡 Untuk pertanyaan terkait laporan ini, silakan hubungi administrator sistem
        </td>
    </tr>
</table>
