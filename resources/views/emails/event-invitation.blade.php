<!DOCTYPE html>
<html>
<head>
    <title>Undangan Event: {{ $event->title }}</title>
</head>
<body>
    <p>Hai {{ $participant->full_name }},</p>
    
    <p>Anda telah diundang untuk mengikuti event <strong>{{ $event->title }}</strong>.</p>
    
    <p><strong>Tanggal:</strong> {{ $event->start_time->format('d F Y, H:i') }} WIB</p>
    <p><strong>Lokasi:</strong> {{ $event->location }}</p>
    
    @if($event->description)
    <p><strong>Deskripsi:</strong> {{ $event->description }}</p>
    @endif
    
    <p>Silakan login ke aplikasi untuk detail lebih lanjut.</p>
    
    <hr>
    <p><small>Email ini dikirim otomatis oleh Sistem Event Management.</small></p>
</body>
</html>