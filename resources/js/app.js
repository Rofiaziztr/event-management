import "preline";
import "./bootstrap";
import Alpine from "alpinejs";
import "./alpine-components";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/l10n/id.js";
import Chart from "chart.js/auto";

window.Chart = Chart;
window.Alpine = Alpine;

document.addEventListener("DOMContentLoaded", function () {
    // --- Date Picker Biasa (untuk create/edit) ---
    const regularPickers = document.querySelectorAll(
        ".flatpickr:not(.flatpickr-all):not(.flatpickr-date-only)"
    );
    regularPickers.forEach((input) => {
        if (input._flatpickr) input._flatpickr.destroy();

        flatpickr(input, {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "id",
            allowInput: true,
            clickOpens: true,
            minDate: new Date(),
            disableMobile: true, // Force desktop mode on all devices
        });
    });

    // --- Date Picker Khusus: Izinkan Semua Tanggal (Hanya Tanggal) ---
    const dateOnlyPickers = document.querySelectorAll(".flatpickr-date-only");
    dateOnlyPickers.forEach((input) => {
        if (input._flatpickr) input._flatpickr.destroy();

        flatpickr(input, {
            enableTime: false,
            dateFormat: "Y-m-d",
            time_24hr: false,
            locale: "id",
            allowInput: true,
            clickOpens: true,
            minDate: null,
            disableMobile: true, // Force desktop mode on all devices
        });
    });

    // --- Date Picker Khusus: Izinkan Semua Tanggal ---
    const allDatePickers = document.querySelectorAll(".flatpickr-all");
    allDatePickers.forEach((input) => {
        if (input._flatpickr) input._flatpickr.destroy();

        flatpickr(input, {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "id",
            allowInput: true,
            clickOpens: true,
            minDate: null,
            disableMobile: true, // Force desktop mode on all devices
        });
    });
});

window.Alpine = Alpine;
Alpine.start();
