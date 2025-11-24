 <!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">

     <title>{{ config('app.name', 'Laravel') }}</title>

     <link rel="preconnect" href="https://fonts.bunny.net ">
     <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

     <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
     <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
     <link href="{{ asset('css/modal.css') }}" rel="stylesheet" />

     <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
     <script src="{{ asset('vendor/bladewind/js/select.js') }}"></script>

     @vite(['resources/css/app.css', 'resources/js/app.js'])

     <!-- Alpine.js Components -->
     <script src="{{ asset('js/alpine-components.js') }}" defer></script>
     <script src="{{ asset('js/alpine-performance.js') }}" defer></script>
     <script src="{{ asset('js/alpine-modals.js') }}" defer></script>
     <script src="{{ asset('js/alert.js') }}" defer></script>
     <script defer src=" https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js "></script>

     <style>
         [x-cloak] {
             display: none !important;
         }

         .bladewind-select .placeholder {
             color: #374151 !important;
         }

         /* Hide Flatpickr default toggle button - ALL methods and sizes */
         button.flatpickr-toggle,
         .flatpickr-toggle,
         [data-toggle],
         .flatpickr-input~button,
         input.flatpickr~button {
             display: none !important;
             visibility: hidden !important;
             position: absolute !important;
             left: -9999px !important;
             width: 0 !important;
             height: 0 !important;
             padding: 0 !important;
             margin: 0 !important;
             border: none !important;
             pointer-events: none !important;
             opacity: 0 !important;
         }

         /* Hide Flatpickr mobile interface toggle button */
         .flatpickr-mobile button,
         .flatpickr-mobile [role="button"] {
             display: none !important;
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

         /* Simplified hover effects */
         .nav-link-hover {
             border-radius: 0.75rem;
         }

         .nav-link-hover:hover {
             background: linear-gradient(135deg, #fef3c7, #fde68a);
         }

         .nav-link-active {
             background: linear-gradient(135deg, #fbbf24, #f59e0b);
             color: white !important;
             border-radius: 0.75rem;
         }

         .sidebar-gradient {
             background: linear-gradient(180deg, #ffffff 0%, #fefbf3 50%, #fef3c7 100%);
         }

         .logo-glow {
             filter: drop-shadow(0 4px 6px rgba(245, 158, 11, 0.3));
         }

         /* More unique animations */
         .scale-hover {
             transition: transform 0.3s ease;
         }

         .scale-hover:hover {
             transform: scale(1.05);
         }

         .bounce-in {
             animation: bounce-in 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
         }

         @keyframes bounce-in {
             0% {
                 transform: scale(0.3);
                 opacity: 0;
             }

             40% {
                 transform: scale(1.1);
             }

             80% {
                 transform: scale(0.9);
             }

             100% {
                 transform: scale(1);
                 opacity: 1;
             }
         }

         .float {
             animation: float 5s ease-in-out infinite;
         }

         @keyframes float {
             0% {
                 transform: translateY(0);
             }

             50% {
                 transform: translateY(-10px);
             }

             100% {
                 transform: translateY(0);
             }
         }

         .stats-icon {
             width: 2.5rem;
             height: 2.5rem;
             display: flex;
             align-items: center;
             justify-content: center;
             font-size: 1.25rem;
             border-radius: 0.375rem;
             color: white;
             line-height: 1;
         }

         .stats-icon-yellow {
             background-color: #fbbf24;
         }

         .stats-icon-blue {
             background-color: #3b82f6;
         }

         .stats-icon-green {
             background-color: #10b981;
         }

         .stats-icon-orange {
             background-color: #f97316;
         }

         .stats-icon-purple {
             background-color: #8b5cf6;
         }

         .shimmer {
             background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.8) 50%, rgba(255, 255, 255, 0) 100%);
             background-size: 200% 100%;
             animation: shimmer 2s infinite;
         }

         @keyframes shimmer {
             0% {
                 background-position: -200% 0;
             }

             100% {
                 background-position: 200% 0;
             }
         }

         /* Removed pulse-border effect as requested */

         /* Custom alert animations */
         @keyframes slide-in-bounce {
             0% {
                 transform: translateX(100%) scale(0.7);
                 opacity: 0;
             }

             60% {
                 transform: translateX(-10%) scale(1.1);
                 opacity: 1;
             }

             100% {
                 transform: translateX(0) scale(1);
                 opacity: 1;
             }
         }

         @keyframes slide-out-right {
             0% {
                 transform: translateX(0);
                 opacity: 1;
             }

             100% {
                 transform: translateX(100%);
                 opacity: 0;
             }
         }

         .alert-enter {
             animation: slide-in-bounce 0.5s forwards;
         }

         .alert-leave {
             animation: slide-out-right 0.3s forwards;
         }

         /* Progress bar animation */
         @keyframes shrink {
             from {
                 width: 100%;
             }

             to {
                 width: 0%;
             }
         }

         .progress-bar {
             animation: shrink 5s linear forwards;
             height: 3px;
             position: absolute;
             bottom: 0;
             left: 0;
         }
     </style>
 </head>

 <body class="font-sans antialiased bg-gradient-to-br from-yellow-50 via-white to-yellow-100" x-data="alertSystem"
     x-init="$store.app.sidebarOpen = window.innerWidth >= 768" @resize.window="$store.app.sidebarOpen = window.innerWidth >= 768">
     <div class="flex h-screen">

         {{-- Enhanced Sidebar with Yellow Theme --}}
         <aside
             class="w-64 sidebar-gradient shadow-2xl flex flex-col fixed inset-y-0 left-0 z-30 md:relative md:translate-x-0 border-r border-yellow-200 transition-transform duration-200 ease-in-out"
             :class="$store.app.sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
             x-show="$store.app.sidebarOpen || window.innerWidth >= 768"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-full">

             <div class="p-6 border-b border-yellow-200 h-20 flex items-center justify-between shrink-0">
                 <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                     <x-application-logo class="block h-10 w-auto logo-glow" />
                 </a>

                 <button @click="$store.app.closeSidebar()"
                     class="md:hidden text-gray-500 hover:text-gray-700 btn-animate">
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
                     class="flex items-center space-x-3 p-3 md:p-3.5 lg:p-4 transition-all duration-100 {{ request()->routeIs($dashboardRoute) ? 'nav-link-active' : 'nav-link-hover text-gray-700 hover:text-yellow-700' }}">
                     <svg class="w-5 h-5 md:w-5 md:h-5 lg:w-6 lg:h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round"
                             d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                     </svg>
                     <span class="font-medium text-xs md:text-sm">Dashboard</span>
                 </a>

                 @if (Auth::user()->role === 'participant')
                     <a href="{{ route('scan.index') }}"
                         class="flex items-center space-x-3 p-3 md:p-3.5 lg:p-4 {{ request()->routeIs('scan.index') ? 'nav-link-active' : 'nav-link-hover text-gray-700 hover:text-yellow-700' }}">
                         <svg class="w-5 h-5 md:w-5 md:h-5 lg:w-6 lg:h-6" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h4.5v4.5h-4.5v-4.5z" />
                         </svg>
                         <span class="font-medium text-xs md:text-sm">Scan Presensi</span>
                     </a>
                 @endif

                 @if (Auth::user()->role == 'admin')
                     <a href="{{ route('admin.events.index') }}"
                         class="flex items-center space-x-3 p-3 md:p-3.5 lg:p-4 {{ request()->routeIs('admin.events.*') ? 'nav-link-active' : 'nav-link-hover text-gray-700 hover:text-yellow-700' }}">
                         <svg class="w-5 h-5 md:w-5 md:h-5 lg:w-6 lg:h-6" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 002.25 2.25v7.5" />
                         </svg>
                         <span class="font-medium text-xs md:text-sm">Manajemen Event</span>
                     </a>

                     <a href="{{ route('admin.users.index') }}"
                         class="flex items-center space-x-3 p-3 md:p-3.5 lg:p-4 {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : 'nav-link-hover text-gray-700 hover:text-yellow-700' }}">
                         <svg class="w-4 h-4 md:w-5 md:h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                         </svg>
                         <span class="font-medium text-xs md:text-sm">Manajemen Pengguna</span>
                     </a>

                     <a href="{{ route('admin.categories.index') }}"
                         class="flex items-center space-x-3 p-3 md:p-3.5 lg:p-4 {{ request()->routeIs('admin.categories.*') ? 'nav-link-active' : 'nav-link-hover text-gray-700 hover:text-yellow-700' }}">
                         <svg class="w-4 h-4 md:w-5 md:h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                         </svg>
                         <span class="font-medium text-xs md:text-sm">Manajemen Kategori</span>
                     </a>
                 @endif

                 @if (Auth::user()->role == 'participant')
                     <a href="{{ route('participant.events.index') }}"
                         class="flex items-center space-x-3 p-3 md:p-3.5 lg:p-4 {{ request()->routeIs('participant.events.*') ? 'nav-link-active' : 'nav-link-hover text-gray-700 hover:text-yellow-700' }}">
                         <svg class="w-4 h-4 md:w-5 md:h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M16.5 6v.75m0 3v.75m0 3v.75m0 3v.75M9.75 6v.75m0 3v.75m0 3v.75m0 3v.75M10.5 2.25h3A1.125 1.125 0 0114.625 3.375v.375c0 .621-.504 1.125-1.125 1.125h-3A1.125 1.125 0 019.375 3.75v-.375A1.125 1.125 0 0110.5 2.25z" />
                         </svg>
                         <span class="font-medium text-xs md:text-sm">Event Saya</span>
                     </a>
                 @endif
             </nav>

             <div class="p-4 border-t border-yellow-200 shrink-0 bg-gradient-to-r from-yellow-50 to-white">
                 <div
                     class="mb-4 p-3 bg-white rounded-xl shadow-sm border border-yellow-100 max-w-full overflow-hidden">
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
         <div x-show="$store.app.sidebarOpen && window.innerWidth < 768" @click="$store.app.closeSidebar()"
             x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"></div>

         <div class="flex-1 flex flex-col overflow-y-auto">
             @include('layouts.navigation')

             {{-- Page Title Section --}}
             <div class="bg-white border-b border-yellow-200 sticky top-0 z-10 shadow-sm">
                 <div
                     class="max-w-full md:max-w-7xl lg:max-w-[90%] xl:max-w-[95%] 2xl:max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                     <div class="flex-1">
                         @if (isset($header))
                             {{ $header }}
                         @else
                             <div
                                 class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                                 <div>
                                     <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                                         {{ ucwords(str_replace('.', ' ', Route::currentRouteName())) }}
                                     </h2>
                                 </div>
                             </div>
                         @endif
                     </div>
                 </div>
             </div>
             <main class="flex-grow bg-gradient-to-br from-yellow-50 via-white to-yellow-100 relative">
                 {{-- Enhanced Unique Alert System --}}
                 <div class="fixed top-4 right-4 z-50 space-y-3" x-data="{}" style="max-width: 340px;">
                     <template x-for="alert in $store.app.alerts" :key="alert.id">
                         <div x-show="alert.show" x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform translate-y-[-20px] scale-95"
                             x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 transform translate-y-[-20px] scale-95"
                             class="relative overflow-hidden backdrop-blur-sm border rounded-xl shadow-xl flex flex-col bounce-in"
                             :class="{
                                 'bg-gradient-to-r from-green-500/20 to-green-100/30 border-green-300': alert
                                     .type === 'success',
                                 'bg-gradient-to-r from-red-500/20 to-red-100/30 border-red-300': alert
                                     .type === 'error',
                                 'bg-gradient-to-r from-yellow-500/20 to-yellow-100/30 border-yellow-300': alert
                                     .type === 'warning',
                                 'bg-gradient-to-r from-blue-500/20 to-blue-100/30 border-blue-300': alert
                                     .type === 'info'
                             }">

                             <div class="flex items-start">
                                 <!-- Alert Icon -->
                                 <div class="p-4 flex-shrink-0 flex justify-center items-center w-14 h-full"
                                     :class="{
                                         'bg-gradient-to-br from-green-500/30 to-green-600/30': alert
                                             .type === 'success',
                                         'bg-gradient-to-br from-red-500/30 to-red-600/30': alert.type === 'error',
                                         'bg-gradient-to-br from-yellow-500/30 to-yellow-600/30': alert
                                             .type === 'warning',
                                         'bg-gradient-to-br from-blue-500/30 to-blue-600/30': alert.type === 'info'
                                     }">
                                     <span x-show="alert.type === 'success'"
                                         class="text-2xl text-green-600 animate-bounce" x-text="alert.icon"></span>
                                     <span x-show="alert.type === 'error'" class="text-2xl text-red-600 animate-pulse"
                                         x-text="alert.icon"></span>
                                     <span x-show="alert.type === 'warning'"
                                         class="text-2xl text-yellow-600 animate-pulse" x-text="alert.icon"></span>
                                     <span x-show="alert.type === 'info'" class="text-2xl text-blue-600 animate-pulse"
                                         x-text="alert.icon"></span>
                                 </div>

                                 <!-- Alert Content -->
                                 <div class="flex-1 p-4">
                                     <div class="flex flex-col">
                                         <div class="flex items-start justify-between">
                                             <p class="text-sm font-bold mb-1"
                                                 :class="{
                                                     'text-green-700': alert.type === 'success',
                                                     'text-red-700': alert.type === 'error',
                                                     'text-yellow-700': alert.type === 'warning',
                                                     'text-blue-700': alert.type === 'info'
                                                 }"
                                                 x-text="alert.title"></p>
                                             <button @click="$store.app.removeAlert(alert.id)"
                                                 class="ml-4 inline-flex text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                                 <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                     <path stroke-linecap="round" stroke-linejoin="round"
                                                         stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                 </svg>
                                             </button>
                                         </div>
                                         <p class="text-sm text-gray-700 line-clamp-3" x-text="alert.message"></p>
                                     </div>
                                 </div>
                             </div>

                             <!-- Progress bar for auto-dismiss -->
                             <div class="h-1 w-full bg-gray-200 bg-opacity-50">
                                 <div class="h-full"
                                     :class="{
                                         'bg-green-500': alert.type === 'success',
                                         'bg-red-500': alert.type === 'error',
                                         'bg-yellow-500': alert.type === 'warning',
                                         'bg-blue-500': alert.type === 'info'
                                     }"
                                     :style="`width: ${alert.progress}%`">
                                 </div>
                             </div>
                         </div>
                     </template>
                 </div>

                 {{-- Global Loading Overlay --}}
                 <div x-show="$store.app.loading" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-white bg-opacity-75 z-50 flex items-center justify-center backdrop-blur-sm"
                     style="display: none;">
                     <div class="text-center">
                         <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-600">
                         </div>
                         <p class="mt-4 text-gray-600 font-medium">Memuat...</p>
                     </div>
                 </div>

                 <div x-data="fadeIn(300)" class="animate-fade-in app-wrapper">
                     {{ $slot }}
                 </div>
             </main>
         </div>
     </div>

     <x-alert-handler />

     @stack('scripts')
 </body>

 </html>
