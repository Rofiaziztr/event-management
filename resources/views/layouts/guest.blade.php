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

    <!-- Alpine.js Components -->
    <script src="{{ asset('js/alpine-components.js') }}" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .bg-pattern {
            background-image: radial-gradient(circle at 20% 50%, rgba(251, 191, 36, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(217, 119, 6, 0.1) 0%, transparent 50%);
        }

        .input-focus {
            /* Focus effects without transitions for better performance */
        }

        .input-focus:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased" x-data="alertSystem">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 gradient-bg bg-pattern relative overflow-hidden"
        <!-- Background Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl animate-pulse"
            style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/5 rounded-full blur-xl animate-pulse"
            style="animation-delay: 2s;"></div>

        <!-- Alert System -->
        <div class="fixed top-4 right-4 z-50 space-y-2" style="max-width: 320px;">
            <template x-for="alert in $store.app.alerts" :key="alert.id">
                <div x-show="alert.show"
                    class="alert-container p-4 rounded-xl shadow-lg max-w-sm glass-effect"
                    :class="{
                        'border-green-300 text-green-800': alert.type === 'success',
                        'border-red-300 text-red-800': alert.type === 'error',
                        'border-yellow-300 text-yellow-800': alert.type === 'warning',
                        'border-blue-300 text-blue-800': alert.type === 'info'
                    }">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div class="mr-3" x-show="alert.type === 'success'">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="mr-3" x-show="alert.type === 'error'">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium" x-text="alert.message"></p>
                        </div>
                        <button @click="$store.app.removeAlert(alert.id)"
                            class="ml-4 inline-flex text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Form Container -->
        <div class="w-full sm:max-w-md animate-fade-in" style="animation-delay: 0.3s;">
            <div
                class="glass-effect shadow-2xl rounded-2xl overflow-hidden border-white/20">
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
