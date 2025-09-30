document.addEventListener('alpine:init', function() {
    // Create a global Alpine store for managing modal states
    Alpine.store('modalState', {
        open: false,
        toggleModal() {
            this.open = !this.open;
        },
        openModal() {
            this.open = true;
        },
        closeModal() {
            this.open = false;
        }
    });
});