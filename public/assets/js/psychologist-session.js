
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('psySessionModal');

    if (!modal) return;

    const form = document.getElementById('psySessionForm');
    const title = document.getElementById('psySessionModalTitle');
    const message = document.getElementById('psySessionModalMessage');
    const confirmButton = document.getElementById('psySessionConfirm');

    const cancelButton = modal.querySelector('.psy-modal__cancel');

    const openButtons = document.querySelectorAll(
        '.js-session-modal'
    );

    const closeButtons = modal.querySelectorAll(
        '[data-close-session-modal]'
    );

    let previousFocus = null;

    function openModal(button) {
        const action = button.dataset.action;

        if (!action) return;

        previousFocus = document.activeElement;

        form.action = action;

        title.textContent = button.dataset.title || 'Konfirmasi';
        message.textContent = button.dataset.message || '';
        confirmButton.textContent = button.dataset.confirm || 'Ya, Lanjutkan';

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('psy-modal-open');

        cancelButton.focus();
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('psy-modal-open');

        form.removeAttribute('action');

        if (previousFocus) {
            previousFocus.focus();
        }
    }

    openButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            openModal(button);
        });
    });

    closeButtons.forEach(function (button) {
        button.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (!modal.classList.contains('is-open')) return;

        if (event.key === 'Escape') {
            closeModal();
        }

        if (event.key === 'Tab') {
            const focusable = Array.from(
                modal.querySelectorAll('button:not([disabled])')
            );

            if (focusable.length === 0) return;

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
});
