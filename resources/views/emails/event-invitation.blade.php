<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Event: {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .email-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .event-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .event-details p {
            margin: 8px 0;
            font-size: 15px;
        }
        .label {
            font-weight: bold;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #777;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Undangan Mengikuti Event</h1>
        </div>

        <div class="content">
            <p>Hai <strong>{{ $participant->full_name }}</strong>,</p>

            <p>Anda secara resmi diundang untuk mengikuti event berikut:</p>

            <div class="event-details">
                <p><span class="label">Judul Event:</span><br><strong>{{ $event->title }}</strong></p>

                @if($event->category)
                    <p><span class="label">Kategori:</span><br>{{ $event->category->name }}</p>
                @endif

                <p><span class="label">Waktu Mulai:</span><br>{{ $event->start_time->timezone('Asia/Jakarta')->format('l, d F Y') }}<br>
                   <strong>{{ $event->start_time->timezone('Asia/Jakarta')->format('H:i') }} WIB</strong></p>

                <p><span class="label">Waktu Selesai:</span><br>{{ $event->end_time->timezone('Asia/Jakarta')->format('l, d F Y') }}<br>
                   <strong>{{ $event->end_time->timezone('Asia/Jakarta')->format('H:i') }} WIB</strong></p>

                <p><span class="label">Lokasi:</span><br>{{ $event->location ?? '–' }}</p>

                @if($event->creator)
                    <p><span class="label">Diselenggarakan oleh:</span><br>{{ $event->creator->full_name }} ({{ $event->creator->division ?? 'Admin' }})</p>
                @endif

                @if($event->description)
                    <p><span class="label">Deskripsi:</span><br>{!! nl2br(e($event->description)) !!}</p>
                @endif
            </div>

            <p>Silakan login ke <strong>Sistem Event Management</strong> untuk:</p>
            <ul>
                <li>Melihat detail lengkap event</li>
                <li>Mengakses dokumen terkait (jika ada)</li>
                <li>Presensi otomatis saat event berlangsung (via QR Code)</li>
            </ul>

            @if ($password)
                <div style="background-color: #fff8e1; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">
                    <p><strong>ℹ️ Akun Anda telah dibuat secara otomatis:</strong></p>
                    <p>Email: <strong>{{ $participant->email }}</strong></p>
                    <p>Password sementara: <strong>{{ $password }}</strong></p>
                    <p><em>⚠️ Untuk keamanan, segera ubah password Anda setelah login pertama kali.</em></p>
                </div>
            @endif

            <p>Terima kasih atas partisipasi Anda!</p>
        </div>

        <div class="footer">
            <hr>
            <p><small>Email ini dikirim secara otomatis oleh <strong>Sistem Event Management PSDMBP</strong>.<br>
            Jika Anda merasa tidak seharusnya menerima email ini, harap abaikan.</small></p>
        </div>
    </div>
</body>
</html>