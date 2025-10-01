// alert.js - Custom Alert Helper Functions

// Initialize Alpine store if not exists
document.addEventListener('alpine:init', () => {
    Alpine.store('app', {
        alerts: [],
        loading: false,
        sidebarOpen: true,

        closeSidebar() {
            this.sidebarOpen = false;
        },

        addAlert(type, message, duration = 5000, options = {}) {
            const id = Date.now();
            const titleMap = {
                success: 'Berhasil!',
                error: 'Error!',
                warning: 'Peringatan!',
                info: 'Info'
            };
            const iconMap = {
                success: '✅',
                error: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };

            const alert = {
                id,
                type,
                title: options.title || titleMap[type] || 'Notifikasi',
                message,
                icon: options.icon || iconMap[type] || '📢',
                show: true,
                progress: 100
            };

            this.alerts.push(alert);

            // Start progress bar
            const interval = setInterval(() => {
                alert.progress -= (100 / (duration / 50));
                if (alert.progress <= 0) {
                    this.removeAlert(id);
                    clearInterval(interval);
                }
            }, 50);

            // Auto remove after duration
            setTimeout(() => {
                this.removeAlert(id);
                clearInterval(interval);
            }, duration);
        },

        removeAlert(id) {
            const index = this.alerts.findIndex(a => a.id === id);
            if (index !== -1) {
                this.alerts.splice(index, 1);
            }
        }
    });
});

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
        window.Alpine.store("app").addAlert(type, message, duration, options);
    } else {
        // Fallback if Alpine is not loaded yet
        // Listen for Alpine initialization. Alpine emits 'alpine:init' when ready.
        const onAlpineInit = () => {
            try {
                if (
                    window.Alpine &&
                    typeof window.Alpine.store === "function"
                ) {
                    const store = window.Alpine.store("app");
                    if (store && typeof store.addAlert === "function") {
                        console.log(
                            "[alert.js] alpine:init detected — delivering queued alert",
                            { type, message }
                        );
                        store.addAlert(type, message, duration, options);
                    }
                }
            } catch (e) {
                console.error("showAlert fallback failed after alpine:init", e);
            } finally {
                document.removeEventListener("alpine:init", onAlpineInit);
            }
        };

        document.addEventListener("alpine:init", onAlpineInit);
    }
}

/**
 * Display a success alert
 */
function showSuccess(message, duration = 5000, options = {}) {
    showAlert("success", message, duration, options);
}

/**
 * Display an error alert
 */
function showError(message, duration = 5000, options = {}) {
    showAlert("error", message, duration, options);
}

/**
 * Display a warning alert
 */
function showWarning(message, duration = 5000, options = {}) {
    showAlert("warning", message, duration, options);
}

/**
 * Display an info alert
 */
function showInfo(message, duration = 5000, options = {}) {
    showAlert("info", message, duration, options);
}

// Initialize alert system to handle Laravel flash messages
document.addEventListener("DOMContentLoaded", () => {
    // Look for Laravel flash messages
    const successMessage = document.querySelector("[data-success-message]");
    if (successMessage) {
        const message = successMessage.textContent.trim();
        console.debug("[alert.js] found data-success-message", message);
        if (message) {
            showSuccess(message);
        }
        successMessage.remove();
    }

    const errorMessage = document.querySelector("[data-error-message]");
    if (errorMessage) {
        const message = errorMessage.textContent.trim();
        console.debug("[alert.js] found data-error-message", message);
        if (message) {
            showError(message);
        }
        errorMessage.remove();
    }

    const warningMessage = document.querySelector("[data-warning-message]");
    if (warningMessage) {
        const message = warningMessage.textContent.trim();
        console.debug("[alert.js] found data-warning-message", message);
        if (message) {
            showWarning(message);
        }
        warningMessage.remove();
    }

    const infoMessage = document.querySelector("[data-info-message]");
    if (infoMessage) {
        const message = infoMessage.textContent.trim();
        console.debug("[alert.js] found data-info-message", message);
        if (message) {
            showInfo(message);
        }
        infoMessage.remove();
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