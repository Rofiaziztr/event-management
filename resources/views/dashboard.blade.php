<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- BLOK ALERT BARU --}}
                    @if (session('success'))
                        <x-bladewind::alert type="success">
                            {{ session('success') }}
                        </x-bladewind::alert>
                    @endif
                    @if (session('error'))
                        <x-bladewind::alert type="error">
                            {{ session('error') }}
                        </x-bladewind::alert>
                    @endif
                    @if (session('warning'))
                        <x-bladewind::alert type="warning">
                            {{ session('warning') }}
                        </x-bladewind::alert>
                    @endif

                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
