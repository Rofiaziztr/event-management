@props(['title', 'value', 'color' => 'yellow', 'icon' => 'chart-bar', 'subtitle' => ''])

@php
    $colors = [
        'blue' => [
            'gradient' => 'from-blue-500 to-blue-600',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-600',
            'border' => 'border-blue-200'
        ],
        'green' => [
            'gradient' => 'from-green-500 to-green-600',
            'bg' => 'bg-green-100',
            'text' => 'text-green-600',
            'border' => 'border-green-200'
        ],
        'red' => [
            'gradient' => 'from-red-500 to-red-600',
            'bg' => 'bg-red-100',
            'text' => 'text-red-600',
            'border' => 'border-red-200'
        ],
        'purple' => [
            'gradient' => 'from-purple-500 to-purple-600',
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
            'border' => 'border-purple-200'
        ],
        'yellow' => [
            'gradient' => 'from-yellow-500 to-yellow-600',
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-600',
            'border' => 'border-yellow-200'
        ],
    ];
    
    $colorClasses = $colors[$color] ?? $colors['yellow'];
    
    // Icon mapping
    $icons = [
        'mail-open' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'check-circle' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'x-circle' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        'chart-bar' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'annotation' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'
    ];
    
    $iconPath = $icons[$icon] ?? $icons['chart-bar'];
@endphp

<div class="bg-white rounded-2xl p-6 shadow-xl border {{ $colorClasses['border'] }} transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group">
    <div class="flex items-center space-x-4">
        <div class="p-4 bg-gradient-to-br {{ $colorClasses['gradient'] }} rounded-xl group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm text-gray-500 font-medium">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 group-hover:{{ $colorClasses['text'] }} transition-colors duration-300">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs {{ $colorClasses['text'] }} mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    
    <!-- Optional: Progress bar or additional visual element -->
    <div class="mt-4 h-1 {{ $colorClasses['bg'] }} rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r {{ $colorClasses['gradient'] }} rounded-full transition-all duration-500 group-hover:w-full" style="width: 70%"></div>
    </div>
</div>