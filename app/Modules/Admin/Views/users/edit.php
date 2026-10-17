
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>

<?= $this->section('content') ?>

<?php
    $errors = session()->getFlashdata('errors') ?? [];
    $error = session()->getFlashdata('error');

    if (! is_array($errors)) {
        $errors = [$errors];
    }

    if ($error) {
        $errors[] = $error;
    }

    $errorMessage = implode("\n", array_map(
        static fn ($item) => (string) $item,
        $errors
    ));

    $id = $user['id_user'];
?>

<div class="dashboard-header">
    <h1>Edit Pengguna</h1>
    <p>Perbarui informasi akun pengguna Counseling Platform.</p>
</div>

<div class="users-panel">

    <div class="users-panel-header">
        <div>
            <h2>Form Edit Pengguna</h2>
            <span>ID Pengguna: #<?= esc($id) ?></span>
        </div>

        <a
            href="<?= site_url('admin/users/' . $id) ?>"
            class="users-reset-button"
        >
            Kembali
        </a>
    </div>

    <?php if ($errorMessage !== ''): ?>
        <div
            id="appFlashAlert"
            data-type="error"
            data-message="<?= esc($errorMessage, 'attr') ?>"
            hidden
        ></div>
    <?php endif; ?>

    <form
        id="editUserForm"
        action="<?= site_url('admin/users/' . $id . '/update') ?>"
        method="post"
        class="users-form"
        novalidate
    >
        <?= csrf_field() ?>

        <div class="users-form-grid">

            <!-- NAMA LENGKAP -->
            <div class="users-form-group">
                <label for="nama_lengkap">Nama Lengkap *</label>
                <input
                    type="text"
                    id="nama_lengkap"
                    name="nama_lengkap"
                    placeholder="Masukkan nama lengkap pengguna"
                    value="<?= esc(old('nama_lengkap', $user['nama_lengkap'])) ?>"
                    minlength="3"
                    maxlength="100"
                    autocomplete="name"
                    required
                >
            </div>

            <!-- EMAIL -->
            <div class="users-form-group">
                <label for="email">Email *</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Contoh: nama@email.com"
                    value="<?= esc(old('email', $user['email'])) ?>"
                    maxlength="150"
                    autocomplete="email"
                    required
                >
            </div>

            <!-- PASSWORD BARU -->
            <div class="users-form-group">
                <label for="password">Password Baru</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Kosongkan jika tidak ingin mengganti"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                >
                <small>
                    Opsional. Kosongkan jika password tidak diubah.
                </small>
            </div>

            <!-- KONFIRMASI PASSWORD -->
            <div class="users-form-group">
                <label for="password_confirm">
                    Konfirmasi Password Baru
                </label>
                <input
                    type="password"
                    id="password_confirm"
                    name="password_confirm"
                    placeholder="Ulangi password baru"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                >
            </div>

            <!-- NOMOR TELEPON -->
            <div class="users-form-group">
                <label for="no_telepon">Nomor Telepon</label>
                <input
                    type="tel"
                    id="no_telepon"
                    name="no_telepon"
                    placeholder="Contoh: 081234567890"
                    value="<?= esc(old('no_telepon', $user['no_telepon'] ?? '')) ?>"
                    maxlength="20"
                    autocomplete="tel"
                >
            </div>

            <!-- ROLE -->
            <div class="users-form-group">
                <label for="role">Role *</label>

                <?php $selectedRole = old('role', $user['role']); ?>

                <select id="role" name="role" required>
                    <option value="">Pilih Role Pengguna</option>

                    <option
                        value="klien"
                        <?= $selectedRole === 'klien' ? 'selected' : '' ?>
                    >
                        Klien
                    </option>

                    <option
                        value="konsultan"
                        <?= $selectedRole === 'konsultan' ? 'selected' : '' ?>
                    >
                        Konsultan / Psikolog
                    </option>

                    <option
                        value="administrator"
                        <?= $selectedRole === 'administrator' ? 'selected' : '' ?>
                    >
                        Administrator
                    </option>
                </select>
            </div>

            <!-- STATUS AKUN -->
            <div class="users-form-group">
                <label for="status_akun">Status Akun *</label>

                <?php
                    $selectedStatus = old(
                        'status_akun',
                        $user['status_akun']
                    );
                ?>

                <select id="status_akun" name="status_akun" required>
                    <option
                        value="aktif"
                        <?= $selectedStatus === 'aktif' ? 'selected' : '' ?>
                    >
                        Aktif
                    </option>

                    <option
                        value="nonaktif"
                        <?= $selectedStatus === 'nonaktif' ? 'selected' : '' ?>
                    >
                        Nonaktif
                    </option>

                    <option
                        value="ditangguhkan"
                        <?= $selectedStatus === 'ditangguhkan' ? 'selected' : '' ?>
                    >
                        Ditangguhkan
                    </option>
                </select>
            </div>

            <!-- ALAMAT -->
            <div class="users-form-group users-form-full">
                <label for="alamat">Alamat</label>
                <textarea
                    id="alamat"
                    name="alamat"
                    rows="4"
                    placeholder="Masukkan alamat lengkap pengguna"
                ><?= esc(old('alamat', $user['alamat'] ?? '')) ?></textarea>
            </div>

        </div>

        <div class="users-form-actions">
            <a
                href="<?= site_url('admin/users/' . $id) ?>"
                class="users-reset-button"
            >
                Batal
            </a>

            <button
                type="submit"
                class="users-filter-button"
            >
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('editUserForm');

    if (!form) {
        return;
    }

    let isSubmitting = false;

    async function showWarning(input, message, title) {
        await window.AppAlert.warning(message, title);

        if (input) {
            input.focus();
        }
    }

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        if (isSubmitting) {
            return;
        }

        const requiredFields = [
            { name: 'nama_lengkap', label: 'Nama Lengkap' },
            { name: 'email', label: 'Email' },
            { name: 'role', label: 'Role' },
            { name: 'status_akun', label: 'Status Akun' }
        ];

        for (const field of requiredFields) {
            const input = form.elements.namedItem(field.name);

            if (!input || !input.value.trim()) {
                await showWarning(
                    input,
                    'Kolom ' + field.label + ' wajib diisi.',
                    'Form Belum Lengkap!'
                );
                return;
            }
        }

        const name = form.elements.namedItem('nama_lengkap');

        if (name.value.trim().length < 3) {
            await showWarning(
                name,
                'Nama lengkap minimal 3 karakter.',
                'Nama Terlalu Pendek!'
            );
            return;
        }

        const email = form.elements.namedItem('email');

        if (!email.checkValidity()) {
            await showWarning(
                email,
                'Masukkan alamat email dengan format yang benar.',
                'Email Tidak Valid!'
            );
            return;
        }

        const password = form.elements.namedItem('password');
        const confirmation = form.elements.namedItem('password_confirm');

        if (password.value !== '' || confirmation.value !== '') {

            if (password.value.length < 8) {
                await showWarning(
                    password,
                    'Password baru minimal 8 karakter.',
                    'Password Terlalu Pendek!'
                );
                return;
            }

            if (password.value.length > 72) {
                await showWarning(
                    password,
                    'Password baru maksimal 72 karakter.',
                    'Password Terlalu Panjang!'
                );
                return;
            }

            if (password.value !== confirmation.value) {
                await showWarning(
                    confirmation,
                    'Password dan konfirmasi password harus sama.',
                    'Password Tidak Cocok!'
                );
                return;
            }
        }

        const phone = form.elements.namedItem('no_telepon');

        if (phone.value.length > 20) {
            await showWarning(
                phone,
                'Nomor telepon maksimal 20 karakter.',
                'Nomor Telepon Tidak Valid!'
            );
            return;
        }

        const confirmed = await window.AppAlert.confirm(
            'Apakah kamu yakin ingin menyimpan perubahan data pengguna ini?',
            'Simpan Perubahan?'
        );

        if (!confirmed) {
            return;
        }

        isSubmitting = true;

        const submitButton = form.querySelector(
            'button[type="submit"]'
        );

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';
        }

        HTMLFormElement.prototype.submit.call(form);
    });
});
</script>

<?= $this->endSection() ?>
