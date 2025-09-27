import 'preline';
import './bootstrap';
import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import 'flatpickr/dist/l10n/id.js';
import Chart from 'chart.js/auto';

window.Chart = Chart;

document.addEventListener('DOMContentLoaded', function () {

    // --- Date Picker Biasa (untuk create/edit) ---
    const regularPickers = document.querySelectorAll('.flatpickr:not(.flatpickr-all)');
    regularPickers.forEach(input => {
        if (input._flatpickr) input._flatpickr.destroy();

        flatpickr(input, {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "id",
            allowInput: true,
            clickOpens: true,
            minDate: new Date(), // Cegah masa lalu
            onReady: (selectedDates, dateStr, instance) => {
                instance.calendarContainer.style.zIndex = '9999';
            }
        });
    });

    // --- Date Picker Khusus: Izinkan Semua Tanggal (Hanya Tanggal) ---
    const dateOnlyPickers = document.querySelectorAll('.flatpickr-date-only');
    dateOnlyPickers.forEach(input => {
        if (input._flatpickr) input._flatpickr.destroy();

        flatpickr(input, {
            enableTime: false,           // 🔴 Matikan waktu
            dateFormat: "Y-m-d",         // Format YYYY-MM-DD
            time_24hr: false,
            locale: "id",
            allowInput: true,
            clickOpens: true,
            minDate: null,               // 🔥 Bebas pilih masa lalu
            onReady: (selectedDates, dateStr, instance) => {
                instance.calendarContainer.style.zIndex = '9999';
            }
        });
    });

    // --- Date Picker Khusus: Izinkan Semua Tanggal ---
    const allDatePickers = document.querySelectorAll('.flatpickr-all');
    allDatePickers.forEach(input => {
        if (input._flatpickr) input._flatpickr.destroy();

        flatpickr(input, {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "id",
            allowInput: true,
            clickOpens: true,
            minDate: null, // 🔥 Izinkan semua tanggal
            onReady: (selectedDates, dateStr, instance) => {
                instance.calendarContainer.style.zIndex = '9999';
            }
        });
    });

    // Klik ikon kalender buka picker (untuk kedua jenis)
    document.querySelectorAll('.datepicker-icon, .pointer-events-none').forEach(icon => {
        icon.closest('.relative')?.addEventListener('click', function (e) {
            const input = this.querySelector('.flatpickr, .flatpickr-all');
            if (input && input._flatpickr) {
                input._flatpickr.open();
            }
        });
    });
});

window.Alpine = Alpine;
Alpine.start();