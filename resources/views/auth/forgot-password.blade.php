<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Lupa password Anda? Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan link untuk mereset password.') }}
    </div>

    @if (session('status'))
        <x-bladewind::alert type="success" class="mb-4">
            {{ session('status') }}
        </x-bladewind::alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Kirim Link Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
