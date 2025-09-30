<div>
    @if (session('alert_message'))
        <div x-data x-init="$nextTick(() => {
            const options = {{ session('alert_options', '{}') }};
            window.showAlert('{{ session('alert_type', 'info') }}', '{{ session('alert_message') }}', options.duration || 5000, options);
        })"></div>
    @endif

    {{-- Hidden elements for flash messages --}}
    @if (session('success'))
        <div data-success-message class="hidden">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div data-error-message class="hidden">{{ session('error') }}</div>
    @endif
</div>