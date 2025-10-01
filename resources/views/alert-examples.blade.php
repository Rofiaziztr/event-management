<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Contoh Penggunaan Alert System</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold mb-4">Alert dari Backend (PHP)</h2>
                        <p class="mb-4 text-sm text-gray-600">Alert ini dikirim dari controller dan akan muncul setelah
                            redirect.</p>

                        <div class="flex flex-col gap-3">
                            <a href="{{ route('alert.success') }}"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-center">
                                Success Alert
                            </a>

                            <a href="{{ route('alert.error') }}"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-center">
                                Error Alert
                            </a>

                            <a href="{{ route('alert.warning') }}"
                                class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition text-center">
                                Warning Alert
                            </a>

                            <a href="{{ route('alert.info') }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-center">
                                Info Alert
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg" x-data="alertDemo()">
                        <h2 class="text-lg font-semibold mb-4">Alert dari Frontend (JavaScript)</h2>
                        <p class="mb-4 text-sm text-gray-600">Alert ini langsung dipicu oleh JavaScript tanpa reload
                            halaman.</p>

                        <div class="flex flex-col gap-3">
                            <button @click="showSuccessAlert()"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-center">
                                Success Alert (JS)
                            </button>

                            <button @click="showErrorAlert()"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-center">
                                Error Alert (JS)
                            </button>

                            <button @click="showWarningAlert()"
                                class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition text-center">
                                Warning Alert (JS)
                            </button>

                            <button @click="showInfoAlert()"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-center">
                                Info Alert (JS)
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4">Kustomisasi Alert</h2>

                    <div x-data="customAlertDemo()" class="flex flex-wrap gap-3">
                        <button @click="showCustomAlert()"
                            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition text-center">
                            Custom Alert
                        </button>

                        <button @click="showLongAlert()"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition text-center">
                            Long Duration Alert
                        </button>

                        <button @click="showAllAlerts()"
                            class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 transition text-center">
                            Show Multiple Alerts
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function alertDemo() {
                return {
                    showSuccessAlert() {
                        window.showSuccess('Operasi berhasil dilakukan!');
                    },
                    showErrorAlert() {
                        window.showError('Maaf, terjadi kesalahan saat memproses permintaan.');
                    },
                    showWarningAlert() {
                        window.showWarning('Harap perhatikan bahwa beberapa fitur mungkin tidak tersedia.');
                    },
                    showInfoAlert() {
                        window.showInfo('Sistem akan melakukan pemeliharaan pada tanggal 30 Mei 2023.');
                    }
                }
            }

            function customAlertDemo() {
                return {
                    showCustomAlert() {
                        window.showAlert('success', 'Ini adalah pesan alert yang dikustomisasi!', 5000, {
                            title: 'Custom Alert',
                            icon: '🚀'
                        });
                    },
                    showLongAlert() {
                        window.showInfo('Ini adalah pesan alert yang akan bertahan lebih lama di layar.', 10000, {
                            title: 'Long Duration Alert'
                        });
                    },
                    showAllAlerts() {
                        window.showSuccess('Operasi berhasil!', 7000);
                        setTimeout(() => window.showInfo('Informasi penting', 10000), 300);
                        setTimeout(() => window.showWarning('Perhatian diperlukan', 12000), 600);
                        setTimeout(() => window.showError('Ada kesalahan terjadi', 15000), 900);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
