<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .gradient-bg {
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
            }
            
            .glass-effect {
                backdrop-filter: blur(16px);
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .logo-float {
                animation: float 3s ease-in-out infinite;
            }
            
            .form-appear {
                animation: fadeInUp 0.6s ease-out;
            }
            
            .bg-pattern {
                background-image: radial-gradient(circle at 20% 50%, rgba(251, 191, 36, 0.1) 0%, transparent 50%),
                                  radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
                                  radial-gradient(circle at 40% 80%, rgba(217, 119, 6, 0.1) 0%, transparent 50%);
            }
            
            .input-focus {
                transition: all 0.3s ease;
            }
            
            .input-focus:focus {
                border-color: #f59e0b;
                box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
                transform: translateY(-1px);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 gradient-bg bg-pattern relative overflow-hidden">
            
            <!-- Background Elements -->
            <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/5 rounded-full blur-xl animate-pulse" style="animation-delay: 2s;"></div>
            
            <!-- Logo Section -->
            <div class="logo-float mb-8">
                <a href="/" class="flex flex-col items-center space-y-4">
                    <div class="p-4 bg-white/20 rounded-2xl shadow-2xl backdrop-blur-sm border border-white/30">
                        <x-application-logo class="w-16 h-16 text-white drop-shadow-lg" />
                    </div>
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-black drop-shadow-lg">{{ config('app.name', 'EventFlow') }}</h1>
                        <p class="text-black-100 text-sm">Sistem Manajemen Event</p>
                    </div>
                </a>
            </div>
            
            <!-- Form Container -->
            <div class="w-full sm:max-w-md form-appear">
                <div class="glass-effect shadow-2xl rounded-2xl overflow-hidden border-white/20">
                    <div class="px-8 py-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-white/80 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add focus effects to inputs
                const inputs = document.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.classList.add('input-focus');
                });
            });
        </script>
    </body>
</html>