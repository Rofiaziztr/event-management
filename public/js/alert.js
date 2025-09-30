// alert.js - Custom Alert Helper Functions

/**
 * Display a toast notification
 * 
 * @param {string} type - success, error, warning, info
 * @param {string} message - Alert message
 * @param {number} duration - Duration in milliseconds
 * @param {object} options - Additional options
 */
function showAlert(type, message, duration = 5000, options = {}) {
    if (window.Alpine && window.Alpine.store) {
        window.Alpine.store('app').addAlert(type, message, duration, options);
    } else {
        // Fallback if Alpine is not loaded yet
        document.addEventListener('alpine:initialized', () => {
            window.Alpine.store('app').addAlert(type, message, duration, options);
        });
    }
}

/**
 * Display a success alert
 */
function showSuccess(message, duration = 5000, options = {}) {
    showAlert('success', message, duration, options);
}

/**
 * Display an error alert
 */
function showError(message, duration = 5000, options = {}) {
    showAlert('error', message, duration, options);
}

/**
 * Display a warning alert
 */
function showWarning(message, duration = 5000, options = {}) {
    showAlert('warning', message, duration, options);
}

/**
 * Display an info alert
 */
function showInfo(message, duration = 5000, options = {}) {
    showAlert('info', message, duration, options);
}

// Initialize alert system to handle Laravel flash messages
document.addEventListener('DOMContentLoaded', () => {
    // Look for Laravel flash messages
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        const message = successMessage.textContent.trim();
        if (message) {
            showSuccess(message);
        }
        successMessage.remove();
    }
    
    const errorMessage = document.querySelector('[data-error-message]');
    if (errorMessage) {
        const message = errorMessage.textContent.trim();
        if (message) {
            showError(message);
        }
        errorMessage.remove();
    }
    
    // Example: Show welcome message
    // setTimeout(() => {
    //     showInfo('Selamat datang di Sistem Manajemen Event!', 8000);
    // }, 1000);
});

// Expose to global scope
window.showAlert = showAlert;
window.showSuccess = showSuccess;
window.showError = showError;
window.showWarning = showWarning;
window.showInfo = showInfo;