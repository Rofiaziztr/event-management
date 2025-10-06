<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">
    {{-- Main Content - Description & Details --}}
    <div class="lg:col-span-2 space-y-6 md:space-y-8">
        {{-- Event Description --}}
        <div class="bg-white rounded-2xl p-6 md:p-8 lg:p-10 shadow-xl border border-yellow-100">
            <div class="flex items-center mb-4 md:mb-5 lg:mb-6">
                <div class="p-2.5 md:p-3 lg:p-3.5 bg-blue-100 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base md:text-lg lg:text-xl font-semibold text-gray-900 ml-3 md:ml-4">Deskripsi Acara
                </h3>
            </div>
            <div class="prose max-w-none">
                @if ($event->description)
                    <p class="text-gray-700 text-sm md:text-base lg:text-lg leading-relaxed whitespace-pre-wrap">
                        {{ $event->description }}</p>
                @else
                    <p class="text-gray-400 text-sm md:text-base lg:text-lg italic">Belum ada deskripsi untuk acara ini.
                    </p>
                @endif
            </div>
        </div>

        {{-- Notulensi Preview --}}
        @php
            $notulensi = $event->documents->whereNull('file_path')->first();
        @endphp
        <div class="bg-white rounded-2xl p-6 md:p-8 lg:p-10 shadow-xl border border-yellow-100">
            <div class="flex items-center justify-between mb-4 md:mb-6 lg:mb-8">
                <div class="flex items-center">
                    <div class="p-2.5 md:p-3 lg:p-3.5 bg-violet-100 rounded-lg shadow-sm">
                        <svg class="w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 text-violet-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-base md:text-lg lg:text-xl font-semibold text-gray-900 ml-3 md:ml-4">Notulensi Acara
                    </h3>
                </div>
                @if ($notulensi && $notulensi->content)
                    <button type="button" onclick="previewNotulensi()"
                        class="inline-flex items-center px-3 md:px-4 py-1.5 md:py-2 bg-gradient-to-r from-blue-500 to-blue-600 shadow-md text-xs md:text-sm font-medium rounded-lg text-white hover:from-blue-600 hover:to-blue-700 transition-colors duration-200 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-1.5 md:mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="hidden md:inline">Lihat Notulensi</span>
                        <span class="inline md:hidden">Lihat</span>
                    </button>
                @endif
            </div>
            <div class="prose max-w-none">
                @if ($notulensi && $notulensi->content)
                    <div id="notulensi-content" class="text-gray-700 text-sm md:text-base lg:text-lg leading-relaxed">
                        {{ \Illuminate\Support\Str::limit(strip_tags($notulensi->content), 200) }}
                    </div>
                    <div id="notulensi-full-content" class="hidden">{!! $notulensi->content !!}</div>
                @else
                    <p class="text-gray-400 text-sm md:text-base lg:text-lg italic">Belum ada notulensi untuk acara ini.
                    </p>
                @endif
            </div>
        </div>

        {{-- Event Timeline --}}
        <div class="bg-white rounded-2xl p-6 md:p-8 lg:p-10 shadow-xl border border-yellow-100">
            <div class="flex items-center mb-4 md:mb-5 lg:mb-6">
                <div class="p-2.5 md:p-3 lg:p-3.5 bg-indigo-100 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 text-indigo-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base md:text-lg lg:text-xl font-semibold text-gray-900 ml-3 md:ml-4">Timeline Acara</h3>
            </div>

            <div class="space-y-6">
                {{-- Start Time --}}
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 bg-green-500 rounded-full mr-4 md:mr-5 lg:mr-6 shadow-md">
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-sm md:text-base lg:text-lg font-medium text-gray-900">Mulai Acara</span>
                            <span
                                class="text-sm md:text-base text-gray-600 mt-1.5 sm:mt-0 font-medium">{{ $event->start_time->format('d M Y, H:i') }}
                                WIB</span>
                        </div>
                        <div class="text-xs md:text-sm text-gray-500 mt-1">{{ $event->start_time->diffForHumans() }}
                        </div>
                    </div>
                </div>

                {{-- Duration Line --}}
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 w-4 h-16 border-l-2 border-dashed border-gray-300 ml-2 mr-4 md:mr-5 lg:mr-6">
                    </div>
                    <div class="flex-1">
                        <span
                            class="text-sm md:text-base text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg shadow-sm inline-flex items-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Durasi: {{ $event->start_time->diffInHours($event->end_time) }} jam
                                {{ $event->start_time->diffInMinutes($event->end_time) % 60 }} menit</span>
                        </span>
                    </div>
                </div>

                {{-- End Time --}}
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 bg-red-500 rounded-full mr-4 md:mr-5 lg:mr-6 shadow-md">
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-sm md:text-base lg:text-lg font-medium text-gray-900">Selesai Acara</span>
                            <span
                                class="text-sm md:text-base text-gray-600 mt-1.5 sm:mt-0 font-medium">{{ $event->end_time->format('d M Y, H:i') }}
                                WIB</span>
                        </div>
                        <div class="text-xs md:text-sm text-gray-500 mt-1">{{ $event->end_time->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- No action buttons here anymore, moved to sidebar --}}
    </div>

    {{-- Sidebar - Event Info --}}
    <div class="space-y-6 md:space-y-8">
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 md:px-8 lg:px-10 py-4 md:py-5 lg:py-6">
                <h3 class="text-base md:text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 md:w-6 md:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Acara
                </h3>
            </div>
            <div class="p-6 md:p-8 lg:p-10 space-y-4 md:space-y-6 lg:space-y-8">
                {{-- Location --}}
                <div>
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm md:text-base font-medium text-gray-700">Lokasi</span>
                    </div>
                    <p class="text-sm md:text-base font-medium text-gray-900 pl-7 md:pl-7">{{ $event->location }}</p>
                </div>

                <hr class="border-gray-200">

                {{-- Creator --}}
                <div>
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-500 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-sm md:text-base font-medium text-gray-700">Dibuat oleh</span>
                    </div>
                    <p class="text-sm md:text-base font-medium text-gray-900 pl-7">{{ $event->creator->full_name }}</p>
                    @if ($event->creator->position)
                        <p class="text-sm text-gray-600 pl-7 mt-0.5">{{ $event->creator->position }}</p>
                    @endif
                </div>

                {{-- Created Date --}}
                <div>
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm md:text-base font-medium text-gray-700">Dibuat pada</span>
                    </div>
                    <p class="text-sm md:text-base font-medium text-gray-900 pl-7">
                        {{ $event->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
        </div>

        {{-- Google Calendar Sync --}}
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 md:px-8 lg:px-10 py-4 md:py-5 lg:py-6">
                <h3 class="text-base md:text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 md:w-6 md:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                    Integrasi Google Calendar
                </h3>
            </div>
            @php
                $syncDisabled = !config('google-calendar.sync_enabled');
            @endphp
            <div class="p-6 md:p-8 lg:p-10 space-y-5">
                <div class="flex items-center justify-between">
                    <span class="text-sm md:text-base font-medium text-gray-700">Status Sinkronisasi</span>
                    <span
                        class="px-3 py-1 text-xs md:text-sm font-semibold rounded-full {{ $event->google_calendar_sync_badge_color }}">
                        {{ $event->google_calendar_sync_status_label }}
                    </span>
                </div>

                <div>
                    <span class="block text-sm md:text-base font-medium text-gray-700">Terakhir Sinkron</span>
                    <p class="text-sm md:text-base text-gray-600 mt-1">
                        @if ($event->google_calendar_synced_at)
                            {{ $event->google_calendar_synced_at->timezone(config('google-calendar.timezone'))->format('d M Y, H:i') }}
                            {{ config('google-calendar.timezone') }}
                        @else
                            <span class="italic text-gray-500">Belum pernah</span>
                        @endif
                    </p>
                </div>

                @if ($event->google_calendar_event_id)
                    <div class="text-sm text-gray-600">
                        <span class="font-medium text-gray-700">ID Event:</span>
                        <code class="bg-gray-100 text-gray-800 px-2 py-1 rounded-md">{{ $event->google_calendar_event_id }}</code>
                    </div>
                @endif

                <div class="space-y-3">
                    @if ($event->google_calendar_link)
                        <a href="{{ $event->google_calendar_link }}" target="_blank" rel="noopener noreferrer"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg hover:from-sky-600 hover:to-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 010 5.656l-2 2a4 4 0 01-5.656-5.656l1.415-1.414m6.829-1.414a4 4 0 00-5.657 0l-2 2a4 4 0 105.657 5.656l1.414-1.414" />
                            </svg>
                            Buka di Google Calendar
                        </a>
                    @endif

                    @if ($event->google_conference_link)
                        <a href="{{ $event->google_conference_link }}" target="_blank" rel="noopener noreferrer"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg hover:from-amber-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 0l-4.553 2.276A1 1 0 013 15.382V8.618a1 1 0 011.447-.894L9 10m6 4l-6-4 6-4 6 4-6 4z" />
                            </svg>
                            Gabung Meeting
                        </a>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <form method="POST" action="{{ route('admin.events.calendar.sync', $event) }}">
                            @csrf
                            <input type="hidden" name="action" value="sync">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-60 disabled:cursor-not-allowed"
                                @disabled($syncDisabled)>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8 8 0 114.582 9M20 4v5h-5" />
                                </svg>
                                Sinkron Ulang
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.events.calendar.sync', $event) }}"
                            onsubmit="return confirm('Hapus event dari Google Calendar?');">
                            @csrf
                            <input type="hidden" name="action" value="delete">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg hover:from-rose-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 disabled:opacity-60 disabled:cursor-not-allowed"
                                @disabled($syncDisabled)>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Hapus dari Calendar
                            </button>
                        </form>
                    </div>
                </div>

                @if ($event->google_calendar_last_error)
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M5.934 19h12.132a1 1 0 00.894-1.447l-6.066-11.5a1 1 0 00-1.788 0l-6.066 11.5A1 1 0 005.934 19z" />
                            </svg>
                            <span class="font-semibold">Catatan Sinkronisasi</span>
                        </div>
                        <p class="text-sm leading-relaxed">Detail: {{ $event->google_calendar_last_error }}</p>
                    </div>
                @endif

                @if ($syncDisabled)
                    <div class="bg-slate-50 border border-slate-200 text-slate-600 text-xs rounded-xl p-3">
                        <strong>Sinkronisasi dinonaktifkan.</strong> Atur <code class="px-1 bg-slate-100 rounded">GOOGLE_CALENDAR_SYNC_ENABLED=true</code>
                        pada lingkungan produksi setelah kredensial siap.
                    </div>
                @endif
            </div>
        </div>

        {{-- Event Stats --}}
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 md:px-8 lg:px-10 py-4 md:py-5 lg:py-6">
                <h3 class="text-base md:text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 md:w-6 md:h-6 mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistik
                </h3>
            </div>
            <div class="p-6 md:p-8 lg:p-10 space-y-4 md:space-y-5 lg:space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 w-7 h-7 md:w-8 md:h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="text-sm md:text-base text-gray-700">Total Undangan</span>
                    </div>
                    <span
                        class="text-base md:text-lg font-bold text-gray-900 ml-4">{{ $event->participants->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 w-7 h-7 md:w-8 md:h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-sm md:text-base text-gray-700">Sudah Hadir</span>
                    </div>
                    <span
                        class="text-base md:text-lg font-bold text-green-600 ml-4">{{ $event->attendances->count() ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 w-7 h-7 md:w-8 md:h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-orange-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-sm md:text-base text-gray-700">Belum Hadir</span>
                    </div>
                    <span
                        class="text-base md:text-lg font-bold text-orange-600 ml-4">{{ $event->participants->count() - ($event->attendances->count() ?? 0) }}</span>
                </div>

                @if ($event->participants->count() > 0)
                    <hr class="border-gray-200 my-4">
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 w-7 h-7 md:w-8 md:h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="text-sm md:text-base text-gray-700">Tingkat Kehadiran</span>
                            </div>
                            <span
                                class="font-medium bg-green-100 text-green-800 px-2.5 py-1 text-sm rounded-full ml-4">{{ round((($event->attendances->count() ?? 0) / $event->participants->count()) * 100, 1) }}%</span>
                        </div>
                        <div class="flex items-center w-full bg-gray-200 rounded-full h-2 md:h-2.5 mt-2">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 md:h-2.5 rounded-full"
                                style="width: {{ (($event->attendances->count() ?? 0) / $event->participants->count()) * 100 }}%">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Action Buttons Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 md:px-8 lg:px-10 py-4 md:py-5 lg:py-6">
                <h3 class="text-base md:text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 md:w-6 md:h-6 mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0l-3-3m3 3l3-3M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                    </svg>
                    Aksi
                </h3>
            </div>
            <div class="p-6 md:p-8 lg:p-8 space-y-4">
                {{-- Tombol QR Code --}}
                <a href="{{ route('admin.events.qrcode', $event) }}"
                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-base text-white shadow-md hover:shadow-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    <svg class="w-6 h-6 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m6-5h-6m1-11a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V4zm-9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2zm9 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z" />
                    </svg>
                    <span class="whitespace-nowrap font-medium">QR Code Acara</span>
                </a>

                {{-- Tombol Unduh Laporan --}}
                <a href="{{ route('admin.events.export', $event) }}"
                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl text-base text-white shadow-md hover:shadow-lg hover:from-yellow-600 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200">
                    <svg class="w-6 h-6 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="whitespace-nowrap font-medium">Unduh Laporan</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div id="preview-modal" style="display:none;" class="fixed inset-0 z-50">
    <!-- Backdrop dengan animasi fade-in -->
    <div id="modal-backdrop" class="fixed inset-0 transition-opacity duration-300 ease-out opacity-0"
        style="background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px);" onclick="closePreview()"></div>

    <div class="flex items-center justify-center min-h-screen p-4 relative z-[51]">
        <!-- Modal dengan animasi slide-up -->
        <div id="modal-content"
            class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[85vh] overflow-hidden border border-yellow-100 transition-all duration-300 ease-out transform translate-y-8 opacity-0">

            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-white">
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Notulensi Lengkap</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $event->title }}</p>
                </div>
                <button onclick="closePreview()"
                    class="bg-white hover:bg-gray-200 transition-colors duration-200 rounded-full p-1.5 border border-gray-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal content -->
            <div id="preview-content-wrapper" class="p-6 overflow-y-auto max-h-[60vh] bg-white">
                <div id="preview-content" class="prose max-w-none text-sm md:text-base bg-white">
                    <!-- Konten notulensi akan ditampilkan di sini -->
                </div>
                <!-- Fallback untuk konten kosong -->
                <div id="empty-content" class="hidden text-center py-12 bg-white">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-4 text-gray-500">Belum ada isi notulensi untuk acara ini</p>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="flex justify-between items-center p-4 border-t border-gray-200 bg-white">
                <div class="text-xs text-gray-500">
                    @if ($notulensi)
                        Diperbarui: {{ $notulensi->updated_at->format('d M Y, H:i') }} WIB
                    @endif
                </div>
                <button onclick="closePreview()"
                    class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Definisi fungsi global untuk modal notulensi
    function previewNotulensi() {
        try {
            const fullContentElement = document.getElementById('notulensi-full-content');
            const previewContent = document.getElementById('preview-content');
            const emptyContent = document.getElementById('empty-content');
            const modal = document.getElementById('preview-modal');
            const modalBackdrop = document.getElementById('modal-backdrop');
            const modalContent = document.getElementById('modal-content');

            if (!previewContent || !emptyContent || !modal || !modalBackdrop || !modalContent) {
                console.error('Required preview elements not found');
                return;
            }

            // Cek apakah ada konten notulensi
            if (fullContentElement && fullContentElement.innerHTML.trim()) {
                // Render as HTML (safe because content is from admin)
                previewContent.innerHTML = fullContentElement.innerHTML;
                previewContent.classList.remove('hidden');
                emptyContent.classList.add('hidden');
            } else {
                // Tampilkan pesan konten kosong
                previewContent.classList.add('hidden');
                emptyContent.classList.remove('hidden');
            }

            // Tampilkan modal dengan animasi
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling

            // Trigger animasi
            setTimeout(() => {
                modalBackdrop.classList.add('opacity-100');
                modalContent.classList.remove('translate-y-8', 'opacity-0');
            }, 10);
        } catch (error) {
            console.error('Error in previewNotulensi:', error);
        }
    }

    function closePreview() {
        try {
            const modal = document.getElementById('preview-modal');
            const modalBackdrop = document.getElementById('modal-backdrop');
            const modalContent = document.getElementById('modal-content');

            if (!modal || !modalBackdrop || !modalContent) {
                console.error('Modal elements not found');
                return;
            }

            // Animasi keluar
            modalBackdrop.classList.remove('opacity-100');
            modalContent.classList.add('translate-y-8', 'opacity-0');

            // Tunggu animasi selesai, lalu sembunyikan modal
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Re-enable scrolling
            }, 300);
        } catch (error) {
            console.error('Error in closePreview:', error);
        }
    }
</script>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                try {
                    const modal = document.getElementById('preview-modal');
                    if (event.key === 'Escape' && modal && modal.style.display === 'block') {
                        closePreview();
                    }
                } catch (error) {
                    console.error('Error in keydown handler:', error);
                }
            });
        });
    </script>
@endpush
