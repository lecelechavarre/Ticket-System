document.addEventListener('DOMContentLoaded', () => {
    const toasts = document.querySelectorAll('.toast');
    if (toasts.length > 0) {
        setTimeout(() => {
            toasts.forEach((toast) => {
                toast.style.transition = 'opacity 0.4s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 450);
            });
        }, 3500);
    }

    const modal = document.querySelector('.js-resolve-modal');
    const openButtons = document.querySelectorAll('.js-open-resolve-modal');
    const closeButton = document.querySelector('.js-close-resolve-modal');
    const ticketInput = document.querySelector('.js-resolve-ticket-id');

    if (!modal || !ticketInput || openButtons.length === 0) {
        return;
    }

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const ticketId = button.getAttribute('data-ticket-id') || '';
            ticketInput.value = ticketId;
            modal.classList.remove('hidden');
        });
    });

    const closeModal = () => modal.classList.add('hidden');
    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    }

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
});
