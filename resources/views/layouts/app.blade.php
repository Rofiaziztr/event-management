<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>

    <script src="./node_modules/preline/dist/preline.js"></script>

    @vite(['resources/css/app.css'])
    @vite('resources/js/app.js')

    <style>
        .bladewind-select .placeholder {
            color: #374151 !important;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- ======================================================= --}}
    {{--    KUMPULKAN SEMUA SCRIPT DI BAGIAN BAWAH BODY INI      --}}
    {{-- ======================================================= --}}

    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    <script src="{{ asset('vendor/bladewind/js/select.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    @vite(['resources/js/app.js'])

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    onReady: function(selectedDates, dateStr, instance) {
                        instance.calendarContainer.style.zIndex = '9999';
                    }
                });
            });
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
