<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EventFlow - Platform Manajemen Event Terpadu</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #FCD34D 0%, #F59E0B 50%, #D97706 100%);
        }

        .yellow-accent {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        }

        .feature-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateY(0);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(251, 191, 36, 0.25);
        }

        .floating-animation {
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-15px) rotate(2deg);
            }

            66% {
                transform: translateY(-5px) rotate(-2deg);
            }
        }

        .fade-in-up {
            opacity: 0;
            transform: translateY(40px);
            transition: all 1s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
            }

            50% {
                box-shadow: 0 0 40px rgba(251, 191, 36, 0.6);
            }
        }
    </style>
</head>

<body class="antialiased bg-amber-50/50">
    {{-- Navigation --}}
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-sm z-50 border-b border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('images/logo_esdm.png') }}" alt="ESDM Logo"
                                class="w-16 h-16 object-contain">
                            <span
                                class="text-xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">EventFlow</span>
                        </div>
                    </div>
                </div>

                @if (Route::has('login'))
                    <div class="flex items-center space-x-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-amber-400 to-orange-500 text-white font-semibold rounded-xl hover:from-amber-500 hover:to-orange-600 transition-all shadow-md hover:shadow-lg">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-amber-700 hover:text-amber-800 px-4 py-2 text-sm font-medium transition-colors">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center px-5 py-2.5 border-2 border-amber-400 text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-all">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero-gradient relative overflow-hidden pt-20 pb-16 lg:pt-28 lg:pb-24">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-black/5 via-black/5 to-black/10"></div>
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="floating-animation absolute top-20 left-12 w-16 h-16 bg-white/20 rounded-full"></div>
                <div class="floating-animation absolute top-32 right-16 w-20 h-20 bg-white/15 rounded-full"
                    style="animation-delay: -2s;"></div>
                <div class="floating-animation absolute bottom-32 left-1/4 w-12 h-12 bg-white/25 rounded-full"
                    style="animation-delay: -4s;"></div>
                <div class="floating-animation absolute bottom-48 right-1/3 w-8 h-8 bg-white/30 rounded-full"
                    style="animation-delay: -6s;"></div>
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 fade-in-up leading-tight">
                    Kelola Event dengan
                    <br>
                    <span class="bg-gradient-to-r from-white to-amber-100 bg-clip-text text-transparent">
                        Mudah & Profesional
                    </span>
                </h1>
                <p class="text-lg md:text-xl text-amber-50 mb-10 max-w-3xl mx-auto leading-relaxed fade-in-up">
                    Platform manajemen event terpadu dengan sistem presensi QR code dan analisis mendalam untuk membantu
                    organisasi mengelola acara dengan lebih baik.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up mb-12">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-all shadow-lg hover:shadow-xl pulse-glow">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-all shadow-lg hover:shadow-xl pulse-glow">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Mulai Sekarang
                        </a>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-8 py-4 bg-transparent text-white font-semibold rounded-xl border-2 border-white hover:bg-white hover:text-amber-600 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14" />
                            </svg>
                            Masuk
                        </a>
                    @endauth
                </div>

                {{-- Stats Preview --}}
                @if ($totalEvents > 0 || $totalUsers > 0)
                    <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto fade-in-up">
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white mb-1">
                                {{ number_format($totalEvents) }}</div>
                            <div class="text-amber-100 text-sm">Event Terkelola</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ number_format($totalUsers) }}
                            </div>
                            <div class="text-amber-100 text-sm">Pengguna Aktif</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $averageAttendanceRate }}%
                            </div>
                            <div class="text-amber-100 text-sm">Tingkat Kehadiran</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 fade-in-up">
                    Fitur Utama EventFlow
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto fade-in-up">
                    Solusi lengkap untuk mengelola event dari perencanaan hingga evaluasi
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div
                    class="feature-card bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-8 border border-amber-100">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Manajemen Event</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Buat dan kelola event dengan mudah. Atur jadwal, lokasi, dan deskripsi dalam satu platform yang
                        intuitif.
                    </p>
                </div>
                {{-- Feature 2 --}}
                <div
                    class="feature-card bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-8 border border-emerald-100">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M9 16h4.01" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Presensi QR Code</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem presensi digital yang praktis. Peserta cukup scan QR code untuk konfirmasi kehadiran
                        secara real-time.
                    </p>
                </div>
                {{-- Feature 3 --}}
                <div
                    class="feature-card bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Manajemen Peserta</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola peserta internal dan eksternal. Undang peserta berdasarkan divisi dan pantau tingkat
                        partisipasi.
                    </p>
                </div>
                {{-- Feature 4 --}}
                <div
                    class="feature-card bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 border border-purple-100">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Dokumen & Notulensi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Unggah lampiran-lampiran dan buat notulensi dengan editor yang mudah digunakan.
                    </p>
                </div>
                {{-- Feature 5 --}}
                <div
                    class="feature-card bg-gradient-to-br from-rose-50 to-red-50 rounded-2xl p-8 border border-rose-100">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-rose-400 to-red-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Analisis & Laporan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dashboard analisis dengan grafik interaktif untuk memantau kinerja dan evaluasi event.
                    </p>
                </div>
                {{-- Feature 6 --}}
                <div
                    class="feature-card bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-8 border border-cyan-100">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Akses Mobile</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Akses dari mana saja dengan desain responsif yang optimal untuk semua perangkat.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Upcoming Events Section --}}
    @if ($upcomingEvents->count() > 0)
        <section class="py-20 yellow-accent">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4 fade-in-up">
                        Event Mendatang
                    </h2>
                    <p class="text-lg text-gray-600 fade-in-up">
                        Bergabunglah dengan event-event menarik yang akan datang
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($upcomingEvents as $event)
                        <div class="bg-white rounded-xl p-6 shadow-md border border-amber-100 fade-in-up">
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">
                                    {{ $event->start_time->format('d M Y') }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $event->participants_count }} peserta
                                </span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $event->location }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Stats Section --}}
    @if ($totalEvents > 0)
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4 fade-in-up">
                        EventFlow dalam Angka
                    </h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center fade-in-up">
                        <div class="text-3xl md:text-4xl font-bold text-amber-600 mb-2">
                            {{ number_format($totalEvents) }}</div>
                        <div class="text-gray-600">Event Terkelola</div>
                    </div>
                    <div class="text-center fade-in-up">
                        <div class="text-3xl md:text-4xl font-bold text-amber-600 mb-2">
                            {{ number_format($totalUsers) }}</div>
                        <div class="text-gray-600">Pengguna Terdaftar</div>
                    </div>
                    <div class="text-center fade-in-up">
                        <div class="text-3xl md:text-4xl font-bold text-amber-600 mb-2">
                            {{ number_format($totalAttendances) }}</div>
                        <div class="text-gray-600">Total Presensi</div>
                    </div>
                    <div class="text-center fade-in-up">
                        <div class="text-3xl md:text-4xl font-bold text-amber-600 mb-2">{{ $averageAttendanceRate }}%
                        </div>
                        <div class="text-gray-600">Tingkat Kehadiran</div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 fade-in-up">
                Siap Mengelola Event Lebih Baik?
            </h2>
            <p class="text-lg text-amber-50 mb-8 fade-in-up">
                Bergabunglah dengan EventFlow dan rasakan kemudahan mengelola event Anda
            </p>
            <div class="fade-in-up">
                @guest
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Daftar Gratis
                    </a>
                @else
                    <a href="{{ url('/dashboard') }}"
                        class="inline-flex items-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Buka Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo_esdm.png') }}" alt="ESDM Logo" class="w-16 h-16 object-contain">
                    <span
                        class="text-xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">EventFlow</span>

                </div>
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} EventFlow. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    {{-- Script untuk animasi fade-in --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            const elements = document.querySelectorAll('.fade-in-up');
            elements.forEach(el => observer.observe(el));
        });
    </script>
</body>

</html>
