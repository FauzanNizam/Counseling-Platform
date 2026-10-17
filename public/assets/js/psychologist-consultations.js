
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('psyRejectModal');

    if (!modal) return;

    const form = document.getElementById('psyRejectForm');
    const clientName = document.getElementById('psyRejectClientName');
    const openButtons = document.querySelectorAll(
        '.js-open-reject-modal'
    );
    const closeButtons = modal.querySelectorAll(
        '[data-close-reject-modal]'
    );

    let previousFocus = null;

    function openModal(button) {
        previousFocus = document.activeElement;

        const action = button.dataset.action;
        const name = button.dataset.client;

        if (!action) return;

        form.action = action;
        clientName.textContent = name || 'klien ini';

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('psy-modal-open');

        modal.querySelector('.psy-modal__cancel').focus();
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

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (
                !event.shiftKey &&
                document.activeElement === last
            ) {
                event.preventDefault();
                first.focus();
            }
        }
    });
});
