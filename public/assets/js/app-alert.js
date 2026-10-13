
(function () {
    'use strict';

    let modalInstance = null;

    function getModal() {
        const element = document.getElementById('appAlertModal');

        if (!element || !window.bootstrap?.Modal) {
            throw new Error('Bootstrap Modal belum tersedia.');
        }

        if (!modalInstance) {
            modalInstance = bootstrap.Modal.getOrCreateInstance(element);
        }

        return { element, modal: modalInstance };
    }

    function show(options = {}) {
        return new Promise(function (resolve) {
            let modalData;

            try {
                modalData = getModal();
            } catch (error) {
                console.error(error);
                resolve(false);
                return;
            }

            const { element, modal } = modalData;

            const icon = document.getElementById('appAlertIcon');
            const title = document.getElementById('appAlertTitle');
            const message = document.getElementById('appAlertMessage');
            const confirmButton = document.getElementById('appAlertConfirm');
            const cancelButton = document.getElementById('appAlertCancel');

            const type = options.type || 'warning';
            const isConfirm = options.confirm === true;

            icon.className = 'app-alert-icon';

            if (type === 'success') {
                icon.classList.add('is-success');
                icon.textContent = '✓';
            } else if (type === 'error') {
                icon.classList.add('is-error');
                icon.textContent = '×';
            } else {
                icon.textContent = '!';
            }

            title.textContent = options.title || 'Perhatian!';
            message.textContent = options.message || '';

            confirmButton.textContent = options.confirmText ||
                (isConfirm ? 'Ya' : 'Mengerti');

            cancelButton.hidden = !isConfirm;

            let confirmed = false;

            function handleConfirm() {
                confirmed = true;
                modal.hide();
            }

            function handleHidden() {
                confirmButton.removeEventListener('click', handleConfirm);
                element.removeEventListener('hidden.bs.modal', handleHidden);
                resolve(confirmed);
            }

            confirmButton.addEventListener('click', handleConfirm);
            element.addEventListener('hidden.bs.modal', handleHidden, {
                once: true
            });

            modal.show();
        });
    }

    window.AppAlert = {
        warning(message, title = 'Perhatian!') {
            return show({ type: 'warning', title, message });
        },

        error(message, title = 'Terjadi Kesalahan!') {
            return show({ type: 'error', title, message });
        },

        success(message, title = 'Berhasil!') {
            return show({ type: 'success', title, message });
        },

        info(message, title = 'Informasi') {
            return show({ type: 'info', title, message });
        },

        confirm(message, title = 'Konfirmasi') {
            return show({
                type: 'warning',
                title,
                message,
                confirm: true,
                confirmText: 'Ya, Lanjutkan'
            });
        },

        confirmDelete(message = 'Data yang dihapus tidak dapat dikembalikan.') {
            return show({
                type: 'warning',
                title: 'Hapus Data?',
                message,
                confirm: true,
                confirmText: 'Ya, Hapus'
            });
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const flash = document.getElementById('appFlashAlert');

        if (!flash) {
            return;
        }

        const type = flash.dataset.type || 'info';
        const message = flash.dataset.message || '';

        if (!message) {
            return;
        }

        const titles = {
            success: 'Berhasil!',
            error: 'Terjadi Kesalahan!',
            warning: 'Perhatian!',
            info: 'Informasi'
        };

        show({
            type,
            title: titles[type] || 'Informasi',
            message
        });
    });
})();
