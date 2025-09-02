<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="full_name" :value="__('Nama Lengkap')" />
            <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="nip" :value="__('NIP')" />
            <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip')"
                required />
            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="position" :value="__('Jabatan')" />
            <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position')"
                required />
            <x-input-error :messages="$errors->get('position')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="work_unit" :value="__('Unit Kerja')" />
            <x-text-input id="work_unit" class="block mt-1 w-full" type="text" name="work_unit" :value="old('work_unit')"
                required />
            <x-input-error :messages="$errors->get('work_unit')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Sudah terdaftar?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
