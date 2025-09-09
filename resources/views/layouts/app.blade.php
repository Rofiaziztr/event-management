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

    <style>
        .bladewind-select .placeholder {
            color: #374151 !important;
        }
    </style>
</head>

<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen bg-gray-100">

        {{-- MODIFICATION START: Add classes for mobile responsive sidebar --}}
        <aside
            class="w-64 bg-white shadow-md flex flex-col fixed inset-y-0 left-0 z-30 transform transition duration-300 ease-in-out md:relative md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <div class="p-4 border-b h-16 flex items-center shrink-0">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto" />
                </a>
            </div>

            <nav class="flex-grow p-4 space-y-2 overflow-y-auto">
                {{-- Dashboard link --}}
                @php
                    $role = Auth::user()->role;
                    $dashboardRoute = $role . '.dashboard'; // contoh: "admin.dashboard" atau "participant.dashboard"
                @endphp

                <x-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)">
                    <div class="flex items-center space-x-3">
                        {{-- Icon: Home/Dashboard --}}
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </div>
                </x-nav-link>


                @if (Auth::user()->role === 'participant')
                    <x-nav-link :href="route('scan.index')" :active="request()->routeIs('scan.index')">
                        <div class="flex items-center space-x-3">
                            {{-- Icon: QR Code Scan --}}
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 4.5a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V7.5a3 3 0 00-3-3H3.75zM9 13.5h6M9 10.5h6m-6-3h6m3 1.5v3m0 3v.001M12 18v3" />
                            </svg>
                            <span>{{ __('Scan Presensi') }}</span>
                        </div>
                    </x-nav-link>
                @endif


                @if (Auth::user()->role == 'admin')
                    <x-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                        <div class="flex items-center space-x-3">
                            {{-- Icon: Calendar/Event Management --}}
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25m10.5-2.25v2.25M6.75 21v-2.25h10.5V21M3 10.5h18M3 10.5V21A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V10.5M3 10.5V6A2.25 2.25 0 015.25 3.75h13.5A2.25 2.25 0 0121 6v4.5" />
                            </svg>
                            <span>{{ __('Manajemen Event') }}</span>
                        </div>
                    </x-nav-link>
                @endif

                @if (Auth::user()->role == 'participant')
                    <x-nav-link :href="route('participant.events.index')" :active="request()->routeIs('participant.events.*')">
                        <div class="flex items-center space-x-3">
                            {{-- Icon: Ticket/My Events --}}
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 6v.75m0 3v.75m0 3v.75m0 3v.75M4.5 6v.75m0 3v.75m0 3v.75m0 3v.75M6.75 6h10.5a.75.75 0 01.75.75v10.5a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V6.75a.75.75 0 01.75-.75z" />
                            </svg>
                            <span>{{ __('Event Saya') }}</span>
                        </div>
                    </x-nav-link>
                @endif
                {{-- MODIFICATION END --}}
            </nav>

            <div class="p-4 border-t shrink-0">
                <div class="mb-2">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                {{-- MODIFICATION START: Added icons to Profile and Logout --}}
                <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    <div class="flex items-center space-x-3">
                        {{-- Icon: User Circle/Profile --}}
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ __('Profile') }}</span>
                    </div>
                </x-nav-link>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <x-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                        :active="false">
                        <div class="flex items-center space-x-3">
                            {{-- Icon: Logout --}}
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            <span>{{ __('Log Out') }}</span>
                        </div>
                    </x-nav-link>
                </form>
                {{-- MODIFICATION END --}}
            </div>
        </aside>
        {{-- MODIFICATION END: Sidebar structure complete --}}

        {{-- MODIFICATION START: Add overlay for mobile sidebar --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-20 md:hidden"
            style="display: none;"></div>
        {{-- MODIFICATION END --}}

        <div class="flex-1 flex flex-col overflow-y-auto">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-grow">
                {{ $slot }}
            </main>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    @stack('scripts')
    <script src="{{ asset('vendor/bladewind/js/select.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
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
