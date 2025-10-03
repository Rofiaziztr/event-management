<x-guest-layout>
    <!-- Logo Section -->
    <div class="text-center mb-8 animate-fade-in">
        <a href="/" class="inline-flex flex-col items-center space-y-3">
            <div class="relative">
                <!-- Main Logo Container -->
                <div class="p-5 bg-white/20 rounded-3xl shadow-2xl backdrop-blur-sm border border-white/30">
                    <x-application-logo class="w-16 h-16 text-white drop-shadow-lg" />
                </div>
                <!-- Decorative Elements -->
                <div class="absolute -top-2 -right-2 w-5 h-5 bg-yellow-400 rounded-full animate-pulse"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-orange-400 rounded-full animate-pulse"
                    style="animation-delay: 0.5s;"></div>
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-black drop-shadow-lg">
                    {{ config('app.name', 'EventFlow') }}</h1>
            </div>
        </a>
    </div>

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Lupa Password?</h2>
        <p class="text-gray-600 text-center max-w-md mx-auto">
            Tidak masalah! Masukkan alamat email Anda dan kami akan mengirimkan link untuk mereset password.
        </p>
    </div>

    <!-- Status Message -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-green-800 font-medium text-sm mb-1">Email Terkirim!</h4>
                    <p class="text-green-700 text-sm">{{ session('status') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                        </path>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                    placeholder="Masukkan email Anda">
            </div>
            @if ($errors->get('email'))
                <div class="mt-2">
                    @foreach ($errors->get('email') as $error)
                        <p class="text-red-600 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-yellow-500 to-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                </path>
            </svg>
            Kirim Link Reset Password
        </button>

        <!-- Back to Login -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Ingat password Anda?
                <a href="{{ route('login') }}" class="font-medium text-yellow-600">
                    Kembali ke login
                </a>
            </p>
        </div>
    </form>

    <!-- Help Section -->
    <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-1">Butuh bantuan?</h4>
                <p class="text-sm text-gray-600">
                    Jika Anda tidak menerima email reset dalam beberapa menit, periksa folder spam Anda atau hubungi
                    administrator sistem.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
