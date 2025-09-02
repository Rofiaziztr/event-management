<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi profil dan alamat email akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Nama Lengkap --}}
        <div>
            <x-input-label for="full_name" :value="__('Nama Lengkap')" />
            <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $user->full_name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum terverifikasi.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- NIP --}}
        <div class="mt-4">
            <x-input-label for="nip" :value="__('NIP')" />
            <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full bg-gray-100"
                :value="$user->nip" disabled />
        </div>

        {{-- Jabatan --}}
        <div class="mt-4">
            <x-input-label for="position" :value="__('Jabatan')" />
            <x-text-input id="position" name="position" type="text" class="mt-1 block w-full bg-gray-100"
                :value="$user->position" disabled />
        </div>

        {{-- Unit Kerja --}}
        <div class="mt-4">
            <x-input-label for="work_unit" :value="__('Unit Kerja')" />
            <x-text-input id="work_unit" name="work_unit" type="text" class="mt-1 block w-full bg-gray-100"
                :value="$user->work_unit" disabled />
        </div>

        <div class="flex items-center gap-4">
            <x-bladewind::button can_submit="true">{{ __('Simpan') }}</x-bladewind::button>

            @if (session('status') === 'profile-updated')
                <x-bladewind::alert type="success" show_close_icon="false">
                    Profil berhasil diperbarui.
                </x-bladewind::alert>
            @endif
        </div>
    </form>
</section>
