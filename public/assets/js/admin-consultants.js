
/*
 * F-22 - Manajemen Konsultan
 * Popup validasi, konfirmasi, dan notifikasi
 * menggunakan komponen AppAlert milik Admin.
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const alertApi = window.AppAlert;

    // ==================================================
    // HELPER POPUP
    // ==================================================

    async function showPopup(message, title, type = 'warning') {
        if (alertApi) {
            const methods = type === 'success'
                ? ['success', 'showSuccess', 'show', 'alert']
                : type === 'error'
                    ? ['error', 'showError', 'warning', 'show', 'alert']
                    : ['warning', 'showWarning', 'error', 'show', 'alert'];

            for (const method of methods) {
                if (typeof alertApi[method] === 'function') {
                    try {
                        if (method === 'show') {
                            await alertApi.show({
                                title: title,
                                message: message,
                                type: type
                            });
                        } else {
                            await alertApi[method](message, title);
                        }
                        return;
                    } catch (error) {
                        console.error('AppAlert popup error:', error);
                    }
                }
            }
        }

        // Fallback khusus notifikasi, bukan penghapusan.
        window.alert(title + '\n\n' + message);
    }

    async function confirmPopup(message, title) {
        if (
            !alertApi ||
            typeof alertApi.confirm !== 'function'
        ) {
            console.error(
                'AppAlert.confirm tidak tersedia. ' +
                'Pengiriman form dibatalkan.'
            );

            return false;
        }

        try {
            return (await alertApi.confirm(message, title)) === true;
        } catch (error) {
            console.error('Konfirmasi gagal:', error);
            return false;
        }
    }

    // ==================================================
    // VALIDASI FORM
    // ==================================================

    function validateForm(form) {
        const value = (name) => {
            const field = form.elements.namedItem(name);
            return field ? String(field.value || '').trim() : '';
        };

        const isCreate = form.classList.contains(
            'consultant-create-form'
        );

        const isEdit = form.classList.contains(
            'consultant-edit-form'
        );

        if (!isCreate && !isEdit) {
            return null;
        }

        if (!value('nama_lengkap')) {
            return 'Kolom Nama Lengkap wajib diisi.';
        }

        if (value('nama_lengkap').length < 3) {
            return 'Nama Lengkap minimal 3 karakter.';
        }

        if (!value('email')) {
            return 'Kolom Alamat Email wajib diisi.';
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(value('email'))) {
            return 'Format Alamat Email tidak valid.';
        }

        const password = value('password');
        const passwordConfirm = value('password_confirm');

        if (isCreate && !password) {
            return 'Kolom Password wajib diisi.';
        }

        if (password && password.length < 8) {
            return 'Password minimal 8 karakter.';
        }

        if (password.length > 72) {
            return 'Password maksimal 72 karakter.';
        }

        if (isCreate && !passwordConfirm) {
            return 'Kolom Konfirmasi Password wajib diisi.';
        }

        if (password !== passwordConfirm) {
            return 'Password dan Konfirmasi Password tidak sama.';
        }

        const phone = value('no_telepon');

        if (phone.length > 20) {
            return 'Nomor Telepon maksimal 20 karakter.';
        }

        if (value('no_str').length > 50) {
            return 'Nomor STR maksimal 50 karakter.';
        }

        if (value('spesialisasi').length > 100) {
            return 'Spesialisasi maksimal 100 karakter.';
        }

        const experience = value('pengalaman_tahun');

        if (experience !== '') {
            if (
                !/^\d+$/.test(experience) ||
                Number(experience) < 0
            ) {
                return 'Pengalaman harus berupa angka bulat 0 atau lebih.';
            }
        }

        return null;
    }

    // ==================================================
    // HELPER SUBMIT AMAN
    // ==================================================

    function submitApproved(form) {
        // Lewati event submit agar popup tidak muncul berulang.
        HTMLFormElement.prototype.submit.call(form);
    }

    // ==================================================
    // FORM TAMBAH DAN EDIT KONSULTAN
    // ==================================================

    document.querySelectorAll(
        '.consultant-create-form, .consultant-edit-form'
    ).forEach(function (form) {

        let processing = false;

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (processing) {
                return;
            }

            processing = true;

            const validationError = validateForm(form);

            if (validationError) {
                await showPopup(
                    validationError,
                    'Form Belum Lengkap!',
                    'warning'
                );

                processing = false;
                return;
            }

            const isCreate = form.classList.contains(
                'consultant-create-form'
            );

            const confirmed = await confirmPopup(
                isCreate
                    ? 'Apakah Anda yakin ingin menambahkan konsultan ini?'
                    : 'Apakah Anda yakin ingin menyimpan perubahan data konsultan ini?',
                isCreate
                    ? 'Konfirmasi Tambah Konsultan'
                    : 'Simpan Perubahan?'
            );

            if (!confirmed) {
                processing = false;
                return;
            }

            submitApproved(form);
        });
    });

    // ==================================================
    // FORM VERIFIKASI MANUAL
    // ==================================================

    document.querySelectorAll(
        '.consultant-verify-form'
    ).forEach(function (form) {

        let processing = false;

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (processing) {
                return;
            }

            processing = true;

            const decisionField =
                form.elements.namedItem('decision');

            const decision = decisionField
                ? decisionField.value
                : '';

            if (!['pending', 'verified', 'rejected'].includes(decision)) {
                await showPopup(
                    'Pilih keputusan verifikasi yang valid.',
                    'Form Belum Lengkap!',
                    'warning'
                );

                processing = false;
                return;
            }

            const labels = {
                pending: 'Pending',
                verified: 'Verified',
                rejected: 'Rejected'
            };

            const confirmed = await confirmPopup(
                'Apakah Anda yakin ingin mengubah status verifikasi menjadi ' +
                labels[decision] + '?',
                'Konfirmasi Verifikasi Konsultan'
            );

            if (!confirmed) {
                processing = false;
                return;
            }

            submitApproved(form);
        });
    });

    // ==================================================
    // KONFIRMASI HAPUS KONSULTAN
    // ==================================================

    document.querySelectorAll(
        '.consultant-delete-form'
    ).forEach(function (form) {

        let processing = false;

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (processing) {
                return;
            }

            processing = true;

            const name =
                form.dataset.consultantName || 'ini';

            const confirmed = await confirmPopup(
                'Apakah Anda yakin ingin menghapus konsultan ' +
                name +
                '? Data yang dihapus tidak dapat dikembalikan.',
                'Konfirmasi Hapus Konsultan'
            );

            if (!confirmed) {
                processing = false;
                return;
            }

            submitApproved(form);
        });
    });

    // ==================================================
    // FLASH NOTIFIKASI SETELAH REDIRECT
    // ==================================================

    const flash = document.querySelector(
        '#consultantFlashAlert'
    );

    if (flash) {
        const type = flash.dataset.type || 'success';
        const message = flash.dataset.message || '';

        if (message) {
            const title = type === 'success'
                ? 'Berhasil!'
                : 'Terjadi Kesalahan!';

            showPopup(message, title, type);
        }
    }

    // ==================================================
    // ERROR VALIDASI DARI CONTROLLER
    // ==================================================

    const validationFlash = document.querySelector(
        '#consultantValidationAlert'
    );

    if (validationFlash) {
        const message =
            validationFlash.dataset.message || '';

        if (message) {
            showPopup(
                message,
                'Form Belum Lengkap!',
                'warning'
            );
        }
    }
});
