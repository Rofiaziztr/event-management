<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    @stack('scripts')

    <script>
        // Inisialisasi flatpickr setelah DOM dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi untuk semua input dengan class 'flatpickr'
            const dateInputs = document.querySelectorAll('.flatpickr');

            dateInputs.forEach(input => {
                flatpickr(input, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                    locale: "id",
                    minDate: "today",
                    clickOpens: true,
                    allowInput: true,
                    // Pastikan kalender muncul di atas elemen lain
                    onReady: function(selectedDates, dateStr, instance) {
                        instance.calendarContainer.style.zIndex = '9999';
                    }
                });
            });

            // Handler untuk klik ikon kalender
            document.querySelectorAll('.datepicker-icon').forEach(icon => {
                icon.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.closest('.relative').querySelector('.flatpickr');
                    if (input && input._flatpickr) {
                        input._flatpickr.open();
                    }
                });
            });
        });
    </script>
</body>

</html>
