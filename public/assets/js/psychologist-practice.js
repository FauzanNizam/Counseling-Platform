
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('practiceEditModal');
    const toggleModal = document.getElementById('practiceToggleModal');

    if (!editModal || !toggleModal) return;

    const editForm = document.getElementById('practiceEditForm');
    const editHari = document.getElementById('editHari');
    const editStart = document.getElementById('editStart');
    const editEnd = document.getElementById('editEnd');
    const editQuota = document.getElementById('editQuota');

    const toggleTitle = document.getElementById('practiceToggleTitle');
    const toggleMessage = document.getElementById('practiceToggleMessage');
    const toggleConfirm = document.getElementById('practiceToggleConfirm');

    let currentToggleForm = null;
    let previousFocus = null;

    function openModal(modal) {
        previousFocus = document.activeElement;

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('psy-modal-open');

        const firstButton = modal.querySelector('button');

        if (firstButton) {
            firstButton.focus();
        }
    }

    function closeModal(modal) {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('psy-modal-open');

        if (modal === editModal) {
            editForm.removeAttribute('action');
        }

        if (modal === toggleModal) {
            currentToggleForm = null;
        }

        if (previousFocus && previousFocus.isConnected) {
            previousFocus.focus();
        }
    }

    // ==========================================
    // EDIT JADWAL
    // ==========================================

    document.querySelectorAll('.js-edit-practice').forEach(function (button) {
        button.addEventListener('click', function () {
            if (!button.dataset.action) return;

            editForm.action = button.dataset.action;

            editHari.value = button.dataset.hari || '';
            editStart.value = button.dataset.start || '';
            editEnd.value = button.dataset.end || '';
            editQuota.value = button.dataset.quota || '';

            openModal(editModal);
            editHari.focus();
        });
    });

    document.querySelectorAll('[data-close-practice-edit]').forEach(function (button) {
        button.addEventListener('click', function () {
            closeModal(editModal);
        });
    });

    // ==========================================
    // AKTIF / NONAKTIF
    // ==========================================

    document.querySelectorAll('.js-toggle-practice').forEach(function (button) {
        button.addEventListener('click', function () {
            currentToggleForm = button.closest('form');

            if (!currentToggleForm) return;

            const action = button.dataset.label || 'Ubah';

            toggleTitle.textContent = 'Konfirmasi ' + action + ' Jadwal';

            toggleMessage.textContent =
                action === 'Nonaktifkan'
                    ? 'Jadwal ini tidak akan tersedia untuk pemesanan baru. Konsultasi yang sudah terjadwal tetap berlaku.'
                    : 'Apakah Anda yakin ingin mengaktifkan kembali jadwal praktik ini?';

            toggleConfirm.textContent = 'Ya, ' + action;

            openModal(toggleModal);
        });
    });

    document.querySelectorAll('[data-close-practice-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            closeModal(toggleModal);
        });
    });

    toggleConfirm.addEventListener('click', function () {
        if (!currentToggleForm) return;

        const form = currentToggleForm;
        currentToggleForm = null;

        toggleConfirm.disabled = true;

        // Submit form asli agar CSRF token tetap terkirim.
        form.submit();
    });

    // ==========================================
    // KEYBOARD
    // ==========================================

    document.addEventListener('keydown', function (event) {
        const modal = editModal.classList.contains('is-open')
            ? editModal
            : toggleModal.classList.contains('is-open')
                ? toggleModal
                : null;

        if (!modal) return;

        if (event.key === 'Escape') {
            closeModal(modal);
            return;
        }

        if (event.key !== 'Tab') return;

        const focusable = Array.from(
            modal.querySelectorAll(
                'button:not([disabled]), input:not([disabled]), select:not([disabled])'
            )
        ).filter(function (element) {
            return element.getClientRects().length > 0;
        });

        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });
});
