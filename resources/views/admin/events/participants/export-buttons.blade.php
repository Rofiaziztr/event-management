{{-- Export Buttons Component --}}
<div class="flex flex-wrap gap-3 mb-6" x-data="{ exportOpen: false }">
    {{-- Main Export Dropdown --}}
    <div class="relative">
        <button @click="exportOpen = !exportOpen" 
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export Data
            <svg class="w-4 h-4 ml-2 transition-transform duration-200" :class="{'rotate-180': exportOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        
        <div x-show="exportOpen" 
             x-transition
             @click.away="exportOpen = false"
             class="absolute left-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 z-50">
            
            <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-t-xl border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Pilih Jenis Export</h3>
            </div>
            
            <div class="py-2">
                {{-- Detailed Export --}}
                <a href="{{ route('admin.events.participants.export', ['event' => $event, 'type' => 'detailed']) }}" 
                   class="flex items-start px-4 py-3 hover:bg-yellow-50 transition-colors duration-150 border-b border-gray-100">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-800">Laporan Detail Lengkap</h4>
                        <p class="text-sm text-gray-600">Semua data peserta dengan statistik dan breakdown detail</p>
                    </div>
                </a>
                
                {{-- Summary Export --}}
                <a href="{{ route('admin.events.participants.export', ['event' => $event, 'type' => 'summary']) }}" 
                   class="flex items-start px-4 py-3 hover:bg-yellow-50 transition-colors duration-150 border-b border-gray-100">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-800">Ringkasan Statistik</h4>
                        <p class="text-sm text-gray-600">Fokus pada statistik kehadiran dan breakdown per divisi</p>
                    </div>
                </a>
                
                {{-- Attendance Only Export --}}
                <a href="{{ route('admin.events.participants.export', ['event' => $event, 'type' => 'attendance_only']) }}" 
                   class="flex items-start px-4 py-3 hover:bg-yellow-50 transition-colors duration-150">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-800">Laporan Kehadiran</h4>
                        <p class="text-sm text-gray-600">Hanya peserta yang hadir dengan detail waktu check-in</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    {{-- Quick Export Button --}}
    <a href="{{ route('admin.events.participants.export', ['event' => $event, 'type' => 'detailed']) }}" 
       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-blue-600 hover:to-blue-700">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Quick Export
    </a>
</div>