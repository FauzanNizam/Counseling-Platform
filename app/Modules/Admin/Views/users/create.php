
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>

<?= $this->section('content') ?>

<div class="dashboard-header">
    <h1>Tambah Pengguna</h1>
    <p>Isi formulir berikut untuk membuat akun pengguna baru.</p>
</div>

<div class="users-panel">

    <div class="users-panel-header">
        <h2>Form Tambah Pengguna</h2>

        <a
            href="<?= site_url('admin/users') ?>"
            class="users-reset-button"
        >
            Kembali
        </a>
    </div>

    <!-- PESAN VALIDASI DARI SERVER -->
    <?php
        $errors = session()->getFlashdata('errors') ?? [];

        if (! is_array($errors)) {
            $errors = [$errors];
        }

        $errorMessage = implode("\n", array_map(
            static fn ($error) => (string) $error,
            $errors
        ));
    ?>

    <?php if ($errorMessage !== ''): ?>
        <div
            id="appFlashAlert"
            data-type="error"
            data-message="<?= esc($errorMessage, 'attr') ?>"
            hidden
        ></div>
    <?php endif; ?>

    <!-- FORM TAMBAH PENGGUNA -->
    <form
        id="createUserForm"
        action="<?= site_url('admin/users/store') ?>"
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
                    value="<?= esc(old('nama_lengkap')) ?>"
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
                    value="<?= esc(old('email')) ?>"
                    maxlength="150"
                    autocomplete="email"
                    required
                >
            </div>

            <!-- PASSWORD -->
            <div class="users-form-group">
                <label for="password">Password *</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password minimal 8 karakter"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                    required
                >
            </div>

            <!-- KONFIRMASI PASSWORD -->
            <div class="users-form-group">
                <label for="password_confirm">
                    Konfirmasi Password *
                </label>
                <input
                    type="password"
                    id="password_confirm"
                    name="password_confirm"
                    placeholder="Masukkan ulang password yang sama"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                    required
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
                    value="<?= esc(old('no_telepon')) ?>"
                    maxlength="20"
                    autocomplete="tel"
                >
            </div>

            <!-- ROLE -->
            <div class="users-form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" required>
                    <option value="">Pilih Role Pengguna</option>

                    <option
                        value="klien"
                        <?= old('role') === 'klien' ? 'selected' : '' ?>
                    >
                        Klien
                    </option>

                    <option
                        value="konsultan"
                        <?= old('role') === 'konsultan' ? 'selected' : '' ?>
                    >
                        Konsultan / Psikolog
                    </option>

                    <option
                        value="administrator"
                        <?= old('role') === 'administrator' ? 'selected' : '' ?>
                    >
                        Administrator
                    </option>
                </select>
            </div>

            <!-- STATUS AKUN -->
            <div class="users-form-group">
                <label for="status_akun">Status Akun *</label>
                <select id="status_akun" name="status_akun" required>

                    <option
                        value="aktif"
                        <?= old('status_akun', 'aktif') === 'aktif' ? 'selected' : '' ?>
                    >
                        Aktif
                    </option>

                    <option
                        value="nonaktif"
                        <?= old('status_akun') === 'nonaktif' ? 'selected' : '' ?>
                    >
                        Nonaktif
                    </option>

                    <option
                        value="ditangguhkan"
                        <?= old('status_akun') === 'ditangguhkan' ? 'selected' : '' ?>
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
                    placeholder="Masukkan alamat lengkap pengguna (opsional)"
                ><?= esc(old('alamat')) ?></textarea>
            </div>

        </div>

        <!-- TOMBOL AKSI -->
        <div class="users-form-actions">

            <a
                href="<?= site_url('admin/users') ?>"
                class="users-reset-button"
            >
                Batal
            </a>

            <button
                type="submit"
                class="users-filter-button"
            >
                Simpan Pengguna
            </button>

        </div>
    </form>

</div>

<!-- VALIDASI FORM MENGGUNAKAN BOOTSTRAP MODAL -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('createUserForm');

    if (!form) {
        return;
    }

    let isSubmitting = false;

    // Seluruh peringatan menggunakan popup Bootstrap
    async function showWarning(message, title = 'Perhatian!') {
        await window.AppAlert.warning(message, title);
    }

    // Tampilkan popup, kemudian fokus ke kolom terkait
    async function invalidField(input, message, title) {
        await showWarning(message, title);

        if (input) {
            input.focus();
        }
    }

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        if (isSubmitting) {
            return;
        }

        // 1. VALIDASI KOLOM WAJIB
        const requiredFields = [
            { name: 'nama_lengkap', label: 'Nama Lengkap' },
            { name: 'email', label: 'Email' },
            { name: 'password', label: 'Password' },
            { name: 'password_confirm', label: 'Konfirmasi Password' },
            { name: 'role', label: 'Role' },
            { name: 'status_akun', label: 'Status Akun' }
        ];

        for (const field of requiredFields) {
            const input = form.elements.namedItem(field.name);

            if (!input || !input.value.trim()) {
                await invalidField(
                    input,
                    'Kolom ' + field.label + ' wajib diisi.',
                    'Form Belum Lengkap!'
                );
                return;
            }
        }

        // 2. VALIDASI NAMA
        const name = form.elements.namedItem('nama_lengkap');

        if (name.value.trim().length < 3) {
            await invalidField(
                name,
                'Nama lengkap harus memiliki minimal 3 karakter.',
                'Nama Terlalu Pendek!'
            );
            return;
        }

        // 3. VALIDASI EMAIL
        const email = form.elements.namedItem('email');

        if (!email.checkValidity()) {
            await invalidField(
                email,
                'Masukkan alamat email dengan format yang benar.',
                'Email Tidak Valid!'
            );
            return;
        }

        // 4. VALIDASI PASSWORD
        const password = form.elements.namedItem('password');

        if (password.value.length < 8) {
            await invalidField(
                password,
                'Password harus memiliki minimal 8 karakter.',
                'Password Terlalu Pendek!'
            );
            return;
        }

        if (password.value.length > 72) {
            await invalidField(
                password,
                'Password tidak boleh lebih dari 72 karakter.',
                'Password Terlalu Panjang!'
            );
            return;
        }

        // 5. VALIDASI KONFIRMASI PASSWORD
        const confirmation = form.elements.namedItem('password_confirm');

        if (password.value !== confirmation.value) {
            await invalidField(
                confirmation,
                'Password dan konfirmasi password harus sama.',
                'Password Tidak Cocok!'
            );
            return;
        }

        // 6. VALIDASI NOMOR TELEPON
        const phone = form.elements.namedItem('no_telepon');

        if (phone.value.length > 20) {
            await invalidField(
                phone,
                'Nomor telepon tidak boleh lebih dari 20 karakter.',
                'Nomor Telepon Tidak Valid!'
            );
            return;
        }

        // 7. KONFIRMASI SIMPAN DENGAN BOOTSTRAP MODAL
        const confirmed = await window.AppAlert.confirm(
            'Pastikan data pengguna yang dimasukkan sudah benar.',
            'Simpan Pengguna?'
        );

        if (!confirmed) {
            return;
        }

        // 8. KIRIM FORM KE CONTROLLER
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
