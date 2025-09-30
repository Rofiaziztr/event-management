<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 leading-tight">
                    {{ __('Profile') }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">Kelola informasi akun dan preferensi Anda</p>
            </div>
            <div class="shrink-0">
                <div class="flex justify-center items-center w-16 h-16 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 pulse-border">
                    <span class="text-2xl text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Sidebar with user info -->
                <div class="md:col-span-1">
                    <div class="bg-white shadow rounded-xl border border-yellow-100 overflow-hidden">
                        <div class="p-6 bg-gradient-to-r from-yellow-50 to-white">
                            <div class="flex flex-col items-center mb-4">
                                <div class="w-24 h-24 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg float mb-4">
                                    {{ substr(Auth::user()->full_name ?? Auth::user()->name, 0, 1) }}
                                </div>
                                <h2 class="font-semibold text-lg text-center">{{ Auth::user()->full_name ?? Auth::user()->name }}</h2>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                <div class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" 
                                     x-data="{ role: '{{ ucfirst(Auth::user()->role) }}' }"
                                     :class="{ 
                                        'bg-yellow-100 text-yellow-800': role === 'Admin',
                                        'bg-blue-100 text-blue-800': role === 'Participant'
                                     }">
                                    {{ ucfirst(Auth::user()->role) }}
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <dl class="space-y-3">
                                    @if (Auth::user()->nip)
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">NIP</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->nip }}</dd>
                                    </div>
                                    @endif
                                    
                                    @if (Auth::user()->division)
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Divisi</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->division }}</dd>
                                    </div>
                                    @endif
                                    
                                    @if (Auth::user()->institution)
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Institusi</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->institution }}</dd>
                                    </div>
                                    @endif
                                    
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Bergabung Pada</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->created_at->format('d M Y') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Profile Information -->
                    <div class="bg-white shadow rounded-xl border border-yellow-100 overflow-hidden scale-hover" x-data="fadeIn(100)">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Profil
                            </h3>
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="bg-white shadow rounded-xl border border-yellow-100 overflow-hidden scale-hover" x-data="fadeIn(200)">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                Ubah Kata Sandi
                            </h3>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-white shadow rounded-xl border border-red-100 overflow-hidden scale-hover" x-data="fadeIn(300)">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-red-600 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus Akun
                            </h3>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add custom profile page animations
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileAnimations', () => ({
                init() {
                    const profileSections = document.querySelectorAll('.scale-hover');
                    profileSections.forEach((section, index) => {
                        setTimeout(() => {
                            section.classList.add('bounce-in');
                        }, 100 * index);
                    });
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
