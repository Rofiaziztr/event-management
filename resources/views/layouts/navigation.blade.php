<nav class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Hamburger button to toggle mobile sidebar --}}
            <div class="flex items-center md:hidden">
                <button @click="$store.app.toggleSidebar()"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 focus:outline-none focus:bg-yellow-50 focus:text-yellow-600 transition-all duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            {{-- Settings Dropdown (Desktop view) --}}
            <div class="hidden md:flex items-center ms-auto" x-data="{open: false}">
                <div class="relative">
                    <button @click="open = !open" 
                            @click.away="open = false"
                            class="inline-flex items-center px-4 py-2 border border-yellow-100 text-sm leading-4 font-medium rounded-lg text-gray-600 bg-white hover:text-yellow-700 hover:bg-yellow-50 focus:outline-none transition-all duration-200">
                        <span class="mr-2">👋</span>
                        <div class="truncate max-w-[150px]">{{ Auth::user()->name }}</div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.outside="open = false"
                         @keydown.escape.window="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}" 
                               @click="open = false"
                               class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <hr class="my-2 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
