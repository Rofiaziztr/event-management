@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 disabled:bg-gray-50 disabled:cursor-not-allowed']) !!}>
