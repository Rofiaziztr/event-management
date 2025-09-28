{{-- resources/views/exports/event_summary.blade.php --}}
<table style="width: 100%;">
    {{-- MAIN HEADER --}}
    <thead>
        <tr>
            <th colspan="6"
                style="font-weight: bold; font-size: 16px; text-align: center; background-color: #10B981; color: white; padding: 15px;">
                RINGKASAN EVENT - {{ strtoupper($event->title) }}
            </th>
        </tr>

        {{-- EVENT BASIC INFO --}}
        <tr>
            <td colspan="6" style="height: 10px;"></td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px;">Judul Event</th>
            <td colspan="5" style="padding: 8px;">{{ $event->title }}</td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px;">Waktu Event</th>
            <td colspan="5" style="padding: 8px; border: 1px solid #E5E7EB;">
                {{ $event->start_time->format('d F Y, H:i') }} - {{ $event->end_time->format('H:i') }} WIB
            </td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px;">Kategori</th>
            <td colspan="5" style="padding: 8px; border: 1px solid #E5E7EB;">{{ $event->category->name ?? 'Umum' }}
            </td>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #FEF3C7; color: #92400E; padding: 8px;">Lokasi</th>
            <td colspan="5" style="padding: 8px; border: 1px solid #E5E7EB;">
                {{ $event->location ?? 'Tidak Ditentukan' }}</td>
        </tr>

        {{-- MAIN STATISTICS --}}
        <tr>
            <td colspan="6" style="height: 10px;"></td>
        </tr>
        <tr>
            <th colspan="6"
                style="font-weight: bold; font-size: 14px; text-align: center; background-color: #10B981; color: white; padding: 12px;">
                STATISTIK KEHADIRAN
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #ECFDF5; padding: 8px;">Total Peserta Diundang</th>
            <td style="padding: 8px; text-align: center; font-weight: bold; color: #1F2937;">
                {{ number_format($stats['total_invited']) }}</td>
            <th style="font-weight: bold; background-color: #ECFDF5; padding: 8px;">Total Peserta Hadir</th>
            <td style="padding: 8px; border: 1px solid #E5E7EB; text-align: center; font-weight: bold; color: #059669;">
                {{ number_format($stats['total_attended']) }}</td>
            <th style="font-weight: bold; background-color: #ECFDF5; padding: 8px;">Tingkat Kehadiran</th>
            <td style="padding: 8px; border: 1px solid #E5E7EB; text-align: center; font-weight: bold; color: #7C3AED;">
                {{ $stats['attendance_rate'] }}%</td>
        </tr>

        {{-- DIVISION BREAKDOWN --}}
        <tr>
            <td colspan="6" style="height: 10px;"></td>
        </tr>
        <tr>
            <th colspan="6"
                style="font-weight: bold; font-size: 14px; text-align: center; background-color: #3B82F6; color: white; padding: 12px;">
                BREAKDOWN PER DIVISI
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Divisi</th>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Total Diundang</th>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Total Hadir</th>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Tidak Hadir</th>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Tingkat Kehadiran
            </th>
            <th style="font-weight: bold; background-color: #EFF6FF; color: #1E40AF; padding: 8px;">Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($stats['by_division'] as $division => $data)
            <tr>
                <td style="padding: 8px; font-weight: 500;">{{ $division ?: 'Tidak Diketahui' }}</td>
                <td style="padding: 8px; text-align: center;">{{ $data['total'] }}</td>
                <td style="padding: 8px; text-align: center; color: #059669; font-weight: bold;">
                    {{ $data['attended'] }}</td>
                <td style="padding: 8px; text-align: center; color: #DC2626; font-weight: bold;">
                    {{ $data['total'] - $data['attended'] }}</td>
                <td style="padding: 8px; text-align: center; font-weight: bold;">{{ $data['rate'] }}%</td>
                <td
                    style="padding: 8px; border: 1px solid #E5E7EB; text-align: center; font-weight: bold;
                    @if ($data['rate'] >= 90) color: #059669; @elseif($data['rate'] >= 70) color: #D97706; @else color: #DC2626; @endif">
                    @if ($data['rate'] >= 90)
                        Sangat Baik
                    @elseif($data['rate'] >= 70)
                        Baik
                    @elseif($data['rate'] >= 50)
                        Cukup
                    @else
                        Perlu Perbaikan
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 15px; font-style: italic; color: #6B7280;">
                    Tidak ada data divisi
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- SPACING --}}
<table>
    <tr>
        <td style="height: 15px;"></td>
    </tr>
</table>

{{-- INSTITUTION BREAKDOWN --}}
<table>
    <thead>
        <tr>
            <th colspan="6"
                style="font-weight: bold; font-size: 14px; text-align: center; background-color: #8B5CF6; color: white; padding: 12px;">
                BREAKDOWN PER INSTANSI
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #F3E8FF; color: #6B21A8; padding: 8px;">Instansi</th>
            <th style="font-weight: bold; background-color: #F3E8FF; color: #6B21A8; padding: 8px;">Total Diundang</th>
            <th style="font-weight: bold; background-color: #F3E8FF; color: #6B21A8; padding: 8px;">Total Hadir</th>
            <th style="font-weight: bold; background-color: #F3E8FF; color: #6B21A8; padding: 8px;">Tidak Hadir</th>
            <th style="font-weight: bold; background-color: #F3E8FF; color: #6B21A8; padding: 8px;">Tingkat Kehadiran
            </th>
            <th style="font-weight: bold; background-color: #F3E8FF; color: #6B21A8; padding: 8px;">Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($stats['by_institution'] as $institution => $data)
            <tr>
                <td style="padding: 8px; font-weight: 500;">{{ $institution ?: 'Tidak Diketahui' }}</td>
                <td style="padding: 8px; text-align: center;">{{ $data['total'] }}</td>
                <td style="padding: 8px; text-align: center; color: #059669; font-weight: bold;">
                    {{ $data['attended'] }}</td>
                <td style="padding: 8px; text-align: center; color: #DC2626; font-weight: bold;">
                    {{ $data['total'] - $data['attended'] }}</td>
                <td style="padding: 8px; text-align: center; font-weight: bold;">{{ $data['rate'] }}%</td>
                <td
                    style="padding: 8px; border: 1px solid #E5E7EB; text-align: center; font-weight: bold;
                    @if ($data['rate'] >= 90) color: #059669; @elseif($data['rate'] >= 70) color: #D97706; @else color: #DC2626; @endif">
                    @if ($data['rate'] >= 90)
                        Sangat Baik
                    @elseif($data['rate'] >= 70)
                        Baik
                    @elseif($data['rate'] >= 50)
                        Cukup
                    @else
                        Perlu Perbaikan
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 15px; font-style: italic; color: #6B7280;">
                    Tidak ada data instansi
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- SPACING --}}
<table>
    <tr>
        <td style="height: 15px;"></td>
    </tr>
</table>

{{-- KEY INSIGHTS --}}
<table>
    <thead>
        <tr>
            <th colspan="6"
                style="font-weight: bold; font-size: 14px; text-align: center; background-color: #EF4444; color: white; padding: 12px;">
                INSIGHT DAN REKOMENDASI
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th style="font-weight: bold; background-color: #FEF2F2; color: #991B1B; padding: 8px;">Overall Performance
            </th>
            <td colspan="5" style="padding: 8px; border: 1px solid #E5E7EB;">
                @if ($stats['attendance_rate'] >= 90)
                    Luar biasa! Tingkat kehadiran sangat tinggi ({{ $stats['attendance_rate'] }}%). Event ini sangat
                    sukses.
                @elseif($stats['attendance_rate'] >= 80)
                    Baik! Tingkat kehadiran di atas rata-rata ({{ $stats['attendance_rate'] }}%). Ada potensi untuk
                    peningkatan.
                @elseif($stats['attendance_rate'] >= 70)
                    Cukup baik dengan tingkat kehadiran {{ $stats['attendance_rate'] }}%. Perlu evaluasi untuk
                    peningkatan ke depan.
                @elseif($stats['attendance_rate'] >= 50)
                    Perlu perhatian. Tingkat kehadiran {{ $stats['attendance_rate'] }}% menunjukkan ada tantangan dalam
                    engagement peserta.
                @else
                    Memerlukan evaluasi mendalam. Tingkat kehadiran {{ $stats['attendance_rate'] }}% sangat rendah dan
                    perlu tindakan perbaikan.
                @endif
            </td>
        </tr>

        <tr>
            <th style="font-weight: bold; background-color: #FEF2F2; color: #991B1B; padding: 8px;">Divisi Terbaik</th>
            <td colspan="5" style="padding: 8px; border: 1px solid #E5E7EB;">
                @php
                    $topDivision = $stats['by_division']->sortByDesc('rate')->first();
                    $topDivisionName = $stats['by_division']->sortByDesc('rate')->keys()->first();
                @endphp
                @if ($topDivision)
                    {{ $topDivisionName ?: 'Tidak Diketahui' }} dengan tingkat kehadiran {{ $topDivision['rate'] }}%
                    ({{ $topDivision['attended'] }}/{{ $topDivision['total'] }} peserta)
                @else
                    Tidak ada data divisi
                @endif
            </td>
        </tr>

        <tr>
            <th style="font-weight: bold; background-color: #FEF2F2; color: #991B1B; padding: 8px;">Divisi Perlu
                Perhatian</th>
            <td colspan="5" style="padding: 8px; border: 1px solid #E5E7EB;">
                @php
                    $worstDivision = $stats['by_division']->sortBy('rate')->first();
                    $worstDivisionName = $stats['by_division']->sortBy('rate')->keys()->first();
                @endphp
                @if ($worstDivision && $worstDivision['rate'] < 70)
                    {{ $worstDivisionName ?: 'Tidak Diketahui' }} dengan tingkat kehadiran
                    {{ $worstDivision['rate'] }}% ({{ $worstDivision['attended'] }}/{{ $worstDivision['total'] }}
                    peserta)
                @else
                    Semua divisi menunjukkan performa yang baik
                @endif
            </td>
        </tr>

        <tr>
            <th style="font-weight: bold; background-color: #FEF2F2; color: #991B1B; padding: 8px;">Rekomendasi</th>
            <td colspan="5" style="padding: 8px; border: 1px solid #E5E7EB;">
                @if ($stats['attendance_rate'] < 70)
                    1. Evaluasi waktu dan lokasi event<br>
                    2. Tingkatkan komunikasi dan reminder<br>
                    3. Pertimbangkan insentif kehadiran<br>
                    4. Survey feedback peserta untuk improvement
                @elseif($stats['attendance_rate'] < 85)
                    1. Pertahankan format yang sudah baik<br>
                    2. Follow up peserta yang tidak hadir<br>
                    3. Analisis feedback untuk optimisasi
                @else
                    1. Pertahankan strategi yang sudah sukses<br>
                    2. Dokumentasikan best practices<br>
                    3. Replikasi untuk event mendatang
                @endif
            </td>
        </tr>
    </tbody>
</table>

{{-- SPACING --}}
<table>
    <tr>
        <td style="height: 10px;"></td>
    </tr>
</table>

{{-- FOOTER --}}
<table>
    <tr>
        <td colspan="6"
            style="text-align: center; padding: 15px; background-color: #F3F4F6; font-size: 10px; color: #4B5563;">
            Laporan Ringkasan Event - Dibuat otomatis oleh Sistem Manajemen Event PSDMBP<br>
            Waktu Pembuatan: {{ $exportDate }}<br>
            Untuk pertanyaan terkait laporan ini, silakan hubungi administrator sistem
        </td>
    </tr>
</table>