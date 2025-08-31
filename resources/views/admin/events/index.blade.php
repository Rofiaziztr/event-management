<x-app-layout>
    {{-- BAGIAN 1: HANYA UNTUK JUDUL HALAMAN --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Event') }}
        </h2>
    </x-slot>

    {{-- BAGIAN 2: SEMUA KONTEN HALAMAN ADA DI SINI --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="p-6 text-gray-900">

                        {{-- Tambahkan blok kode ini untuk menampilkan pesan sukses --}}
                        @if (session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif
                        <div class="flex justify-end mb-4">
                            <a href="{{ route('admin.events.create') }}"
                                class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Buat Event Baru
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Judul</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Mulai</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Lokasi</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($events as $event)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                                <a href="{{ route('admin.events.show', $event) }}"
                                                    class="hover:text-indigo-600">
                                                    {{ $event->title }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if ($event->status == 'Scheduled') bg-blue-100 text-blue-800 
                                                @elseif($event->status == 'Ongoing') bg-green-100 text-green-800 
                                                @elseif($event->status == 'Completed') bg-gray-100 text-gray-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                    {{ $event->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $event->start_time->format('d M Y, H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ Str::limit($event->location, 30) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.events.edit', $event) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                                <form action="{{ route('admin.events.destroy', $event) }}"
                                                    method="POST" class="inline-block ml-4">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                                Tidak ada event ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
