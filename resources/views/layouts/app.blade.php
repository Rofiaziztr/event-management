<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />

    @vite(['resources/css/app.css'])

    <style>
        .bladewind-select .placeholder {
            color: #374151 !important;
        }

        /* Custom yellow theme colors */
        .bg-yellow-gradient {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
        }

        .text-yellow-gradient {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Enhanced hover effects */
        .nav-link-hover {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
        }

        .nav-link-hover:hover {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            transform: translateX(4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .nav-link-active {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white !important;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.5);
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #ffffff 0%, #fefbf3 50%, #fef3c7 100%);
        }

        .logo-glow {
            filter: drop-shadow(0 4px 6px rgba(245, 158, 11, 0.3));
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-yellow-50 via-white to-yellow-100" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen">

        {{-- Enhanced Sidebar with Yellow Theme --}}
        <aside
            class="w-64 sidebar-gradient shadow-2xl flex flex-col fixed inset-y-0 left-0 z-30 transform transition duration-300 ease-in-out md:relative md:translate-x-0 border-r border-yellow-200"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <div class="p-6 border-b border-yellow-200 h-20 flex items-center justify-between shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <x-application-logo class="block h-10 w-auto logo-glow" />
                </a>

                <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-grow p-4 space-y-3 overflow-y-auto">
                {{-- Dashboard link --}}
                @php
                    $role = Auth::user()->role;
                    $dashboardRoute = $role . '.dashboard';
                @endphp

                <a href="{{ route($dashboardRoute) }}"
                    class="flex items-center space-x-3 p-3 nav-link-hover {{ request()->routeIs($dashboardRoute) ? 'nav-link-active' : 'text-gray-700 hover:text-yellow-700' }}">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                    </svg>
                    <span class="font-medium">{{ __('Dashboard') }}</span>
                </a>

                @if (Auth::user()->role === 'participant')
                    <a href="{{ route('scan.index') }}"
                        class="flex items-center space-x-3 p-3 nav-link-hover {{ request()->routeIs('scan.index') ? 'nav-link-active' : 'text-gray-700 hover:text-yellow-700' }}">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h4.5v4.5h-4.5v-4.5z" />
                        </svg>
                        <span class="font-medium">{{ __('Scan Presensi') }}</span>
                    </a>
                @endif

                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.events.index') }}"
                        class="flex items-center space-x-3 p-3 nav-link-hover {{ request()->routeIs('admin.events.*') ? 'nav-link-active' : 'text-gray-700 hover:text-yellow-700' }}">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 002.25 2.25v7.5" />
                        </svg>
                        <span class="font-medium">{{ __('Manajemen Event') }}</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center space-x-3 p-3 nav-link-hover {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : 'text-gray-700 hover:text-yellow-700' }}">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span class="font-medium">{{ __('Manajemen Pengguna') }}</span>
                    </a>
                @endif

                @if (Auth::user()->role == 'participant')
                    <a href="{{ route('participant.events.index') }}"
                        class="flex items-center space-x-3 p-3 nav-link-hover {{ request()->routeIs('participant.events.*') ? 'nav-link-active' : 'text-gray-700 hover:text-yellow-700' }}">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 6v.75m0 3v.75m0 3v.75m0 3v.75M9.75 6v.75m0 3v.75m0 3v.75m0 3v.75M10.5 2.25h3A1.125 1.125 0 0114.625 3.375v.375c0 .621-.504 1.125-1.125 1.125h-3A1.125 1.125 0 019.375 3.75v-.375A1.125 1.125 0 0110.5 2.25z" />
                        </svg>
                        <span class="font-medium">{{ __('Event Saya') }}</span>
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-yellow-200 shrink-0 bg-gradient-to-r from-yellow-50 to-white">
                <div class="mb-4 p-3 bg-white rounded-xl shadow-sm border border-yellow-100 max-w-full overflow-hidden">
                    <div class="font-semibold text-gray-800 text-sm line-clamp-2 break-words">
                        {{ Auth::user()->full_name ?? Auth::user()->name }}
                    </div>
                    <div class="text-xs text-gray-500 truncate mt-1">
                        {{ Auth::user()->email }}
                    </div>
                    <div class="text-xs text-yellow-600 font-medium mt-1">
                        {{ ucfirst(Auth::user()->role) }}
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center space-x-3 p-2 nav-link-hover text-sm {{ request()->routeIs('profile.edit') ? 'nav-link-active' : 'text-gray-600 hover:text-yellow-700' }}">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ __('Profile') }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center space-x-3 p-2 nav-link-hover text-sm text-gray-600 hover:text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            <span>{{ __('Log Out') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 bg-black opacity-50 z-20 md:hidden" style="display: none;"></div>

        <div class="flex-1 flex flex-col overflow-y-auto">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white shadow-sm border-b border-yellow-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-grow bg-gradient-to-br from-yellow-50 via-white to-yellow-100">
                {{ $slot }}
            </main>
        </div>
    </div>

    @vite(['resources/js/app.js'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    <script src="{{ asset('vendor/bladewind/js/select.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.min.js"
        integrity="sha512-n/G+dROKbKL3GVngGWmWfwK0yPctjZQM752diVYnXZtD/48agpUKLIn0xDQL9ydZ91x6BiOmTIFwWjjFi2kEFg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @stack('styles')
    @stack('scripts')
</body>

</html>
