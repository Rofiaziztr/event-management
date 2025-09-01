<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event: ') }} {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.events.update', $event) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" :value="__('Judul Event')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title', $event->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description"
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $event->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                            <x-date-picker name="start_time" id="start_time" :value="old('start_time', $event->start_time)" />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                            <x-date-picker name="end_time" id="end_time" :value="old('end_time', $event->end_time)" />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="location" :value="__('Lokasi')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location"
                                :value="old('location', $event->location)" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="block mt-1 w-full ...">
                                <option value="Terjadwal" @if (old('status', $event->status) == 'Terjadwal') selected @endif>Terjadwal
                                </option>
                                <option value="Berlangsung" @if (old('status', $event->status) == 'Berlangsung') selected @endif>
                                    Berlangsung</option>
                                <option value="Selesai" @if (old('status', $event->status) == 'Selesai') selected @endif>Selesai
                                </option>
                                <option value="Dibatalkan" @if (old('status', $event->status) == 'Dibatalkan') selected @endif>Dibatalkan
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-bladewind::button tag="a" href="{{ route('admin.events.index') }}"
                                color="secondary" outline="true">
                                Batal
                            </x-bladewind::button>
                            <x-bladewind::button can_submit="true" class="ms-4">
                                Update Event
                            </x-bladewind::button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
