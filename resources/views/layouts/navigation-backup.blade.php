<nav class="bg-white border-b border-yellow-200 shadow-md sticky top-0 z-20">
    <div class="max-w-full px-2 sm:px-4 lg:px-8">
        <div class="flex justify-between items-center h-14 sm:h-16 lg:h-20">
            {{-- Left: Hamburger + Logo Brand (Mobile/Tablet) --}}
            <div class="flex items-center gap-2">
                <button @click="$store.app.toggleSidebar()"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-all duration-200 min-h-10 min-w-10">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Logo PSDMBP (Mobile: Small, Desktop: with text) --}}
                <a href="{{ route('dashboard') }}" class="hidden md:flex items-center gap-2 flex-shrink-0">
                    <img src="{{ asset('images/logo_psdmbp.png') }}" alt="PSDMBP" class="h-8 lg:h-10 w-auto">
                    <div class="hidden lg:flex flex-col leading-none">
                        <span class="text-xs font-bold text-gray-900">PSDMBP</span>
                        <span class="text-xs text-yellow-600 font-medium">Events</span>
                    </div>
                </a>
            </div>

            {{-- Center: Logo for mobile ONLY --}}
            <div class="md:hidden flex-1 flex justify-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('images/logo_psdmbp.png') }}" alt="PSDMBP" class="h-7 w-auto">
                </a>
            </div>

            {{-- Right: User Menu (Desktop Version) --}}
            <div class="flex items-center ml-auto" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" @click.away="open = false"
                        class="inline-flex items-center justify-center gap-1.5 px-2.5 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm border border-yellow-100 rounded-lg text-gray-600 bg-white hover:bg-yellow-50 hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-1 transition-all duration-200 min-h-12">
                        {{-- Avatar for mobile --}}
                        <div class="w-6 h-6 sm:hidden rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        {{-- Icon for desktop --}}
                        <span class="hidden sm:inline text-lg">�</span>
                        {{-- Name for desktop only --}}
                        <div class="hidden sm:block truncate max-w-[150px] text-xs sm:text-sm font-medium">{{ Auth::user()->name }}</div>
                        {{-- Chevron --}}
                        <svg class="fill-current h-3.5 w-3.5 sm:h-4 sm:w-4 flex-shrink-0 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" :class="{ 'rotate-180': open }">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false" @keydown.escape.window="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-50 mt-2 w-52 sm:w-56 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        {{-- User Info Header --}}
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Peserta' }}</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}" @click="open = false"
                                class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition-colors duration-200 gap-3 min-h-10">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Edit Profil</span>
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition-colors duration-200 gap-3 min-h-10">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9">
                                        </path>
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
