<!-- Enhanced Form Components with Alpine.js -->
<!-- This file contains reusable form components with animations and validation -->

<!-- Enhanced Input Component -->
@props([
    'id',
    'name',
    'type' => 'text',
    'label',
    'placeholder' => '',
    'required' => false,
    'icon' => null,
    'value' => '',
    'error' => null,
])

<div class="form-group" x-data="{ focused: false }">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if ($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 transition-colors duration-200"
                    :class="focused ? 'text-yellow-500' : 'text-gray-400'" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
        @endif

        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
            value="{{ old($name, $value) }}" @if ($required) required @endif @focus="focused = true"
            @blur="focused = false"
            class="block w-full {{ $icon ? 'pl-10' : 'pl-3' }} pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 input-animate hover-lift"
            :class="{
                'border-red-300 focus:border-red-500 focus:ring-red-500': $wire && $wire.errors && $wire.errors.has(
                    '{{ $name }}'),
                'border-green-300 focus:border-green-500 focus:ring-green-500': $wire && $wire.success && $wire.success
                    .has('{{ $name }}')
            }"
            placeholder="{{ $placeholder }}" x-transition:focus>
    </div>

    @if ($error || $errors->has($name))
        <div class="mt-2 animate-slide-in-up">
            @foreach ($errors->get($name) as $message)
                <p class="text-red-600 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </p>
            @endforeach
        </div>
    @endif
</div>

<!-- Enhanced Button Component -->
@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'icon' => null,
    'href' => null,
])

@php
    $classes = [
        'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 btn-animate hover-glow focus:outline-none focus:ring-2',
        'px-4 py-2 text-sm' => $size === 'sm',
        'px-6 py-3 text-base' => $size === 'md',
        'px-8 py-4 text-lg' => $size === 'lg',
        'bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white focus:ring-yellow-500' =>
            $variant === 'primary',
        'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white focus:ring-green-500' =>
            $variant === 'success',
        'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white focus:ring-red-500' =>
            $variant === 'danger',
        'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 focus:ring-gray-500' =>
            $variant === 'secondary',
    ];
    $classString = collect($classes)->filter()->keys()->implode(' ');
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $classString }}">
    @else
        <button type="{{ $type }}" class="{{ $classString }}"
            @if ($loading) :disabled="loading" :class="loading ? 'opacity-75 cursor-not-allowed' : ''" @endif>
@endif

@if ($loading)
    <svg x-show="loading" class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" style="display: none;">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
        </path>
    </svg>
@endif

@if ($icon && !$loading)
    <svg x-show="!loading" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icon !!}
    </svg>
@endif

<span @if ($loading) x-text="loading ? 'Memproses...' : '{{ $slot }}'" @endif>
    {{ $slot }}
</span>

@if ($href)
    </a>
@else
    </button>
@endif

<!-- Enhanced Card Component -->
@props(['title' => null, 'subtitle' => null, 'icon' => null, 'animation' => 'fadeIn', 'delay' => 0])

<div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden card-animate hover-lift"
    x-data="{{ $animation }}({{ $delay }})">
    @if ($title || $subtitle || $icon)
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center">
                @if ($icon)
                    <div class="flex-shrink-0 mr-4">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl">{!! $icon !!}</span>
                        </div>
                    </div>
                @endif
                <div>
                    @if ($title)
                        <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
                    @endif
                    @if ($subtitle)
                        <p class="text-sm text-gray-600">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="px-6 py-6">
        {{ $slot }}
    </div>
</div>

<!-- Enhanced Alert Component -->
@props(['type' => 'info', 'title' => null, 'dismissible' => true, 'icon' => true])

@php
    $alertClasses = [
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
    ];

    $iconPaths = [
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' =>
            'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
        'error' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="border rounded-xl p-4 {{ $alertClasses[$type] }}">

    <div class="flex items-start">
        @if ($icon)
            <div class="flex-shrink-0 mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$type] }}">
                    </path>
                </svg>
            </div>
        @endif

        <div class="flex-1">
            @if ($title)
                <h4 class="font-semibold mb-1">{{ $title }}</h4>
            @endif
            <div class="text-sm">{{ $slot }}</div>
        </div>

        @if ($dismissible)
            <button @click="show = false"
                class="flex-shrink-0 ml-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        @endif
    </div>
</div>

<!-- Enhanced Modal Component -->
@props(['id', 'title' => null, 'size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-2xl',
        'lg' => 'max-w-4xl',
        'xl' => 'max-w-6xl',
    ];
@endphp

<div x-data="modal(false)" x-show="open" @{{ $id }}.window="show()" @keydown.escape.window="hide()"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

    <!-- Backdrop -->
    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="hide()">
    </div>

    <!-- Modal Panel -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-2xl shadow-2xl w-full {{ $sizeClasses[$size] }} mx-auto">

            @if ($title)
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-white rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
                        <button @click="hide()"
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <div class="px-6 py-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Table Component -->
@props(['headers' => [], 'sortable' => false])

<div x-data="dataTable()" class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
    @if ($sortable)
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input x-model="search" type="text" placeholder="Cari data..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 input-animate">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <span>Tampilkan:</span>
                    <select x-model="itemsPerPage"
                        class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>entri</span>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    @foreach ($headers as $header)
                        <th @if ($sortable) @click="sort('{{ $header['key'] ?? '' }}')" @endif
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider {{ $sortable ? 'cursor-pointer hover:bg-gray-200 transition-colors duration-200' : '' }}">
                            <div class="flex items-center space-x-1">
                                <span>{{ $header['label'] ?? $header }}</span>
                                @if ($sortable && isset($header['key']))
                                    <svg class="w-4 h-4 text-gray-400"
                                        :class="{
                                            'text-yellow-600': sortBy === '{{ $header['key'] }}',
                                            'transform rotate-180': sortBy === '{{ $header['key'] }}' &&
                                                sortDirection === 'desc'
                                        }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
