
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>

<?php
$errors = session()->getFlashdata('errors');
$error = session()->getFlashdata('error');

$validationMessage = '';

if (is_array($errors) && !empty($errors)) {
    $validationMessage = implode("\n", array_values($errors));
}
?>

<div class="dashboard-header">
    <h1>Tambah Konsultan</h1>
    <p>Daftarkan konsultan yang telah direkrut oleh Counseling Platform.</p>
</div>

<!-- POPUP ERROR -->
<?php if ($error): ?>
    <div
        id="consultantFlashAlert"
        data-type="error"
        data-message="<?= esc($error, 'attr') ?>"
        hidden
    ></div>
<?php elseif ($validationMessage !== ''): ?>
    <div
        id="consultantValidationAlert"
        data-message="<?= esc($validationMessage, 'attr') ?>"
        hidden
    ></div>
<?php endif; ?>

<div class="management-card consultant-page-card">

    <div class="consultant-form-header">
        <h2>Form Tambah Konsultan</h2>
        <p>Lengkapi informasi akun dan profil profesional konsultan.</p>
    </div>

    <form
        action="<?= site_url('admin/consultants/store') ?>"
        method="post"
        class="consultant-form consultant-create-form"
        novalidate
        onsubmit="return false;"
    >
        <?= csrf_field() ?>

        <!-- INFORMASI AKUN -->
        <div class="consultant-section-title consultant-field-wide">
            <h3>Informasi Akun</h3>
            <p>Data yang digunakan konsultan untuk masuk ke platform.</p>
        </div>

        <div class="consultant-field">
            <label for="nama_lengkap">
                Nama Lengkap <span>*</span>
            </label>
            <input
                type="text"
                id="nama_lengkap"
                name="nama_lengkap"
                placeholder="Contoh: Rihadatul Aisyah"
                value="<?= esc(old('nama_lengkap'), 'attr') ?>"
                maxlength="100"
                autocomplete="name"
            >
        </div>

        <div class="consultant-field">
            <label for="email">
                Alamat Email <span>*</span>
            </label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Contoh: konsultan@gmail.com"
                value="<?= esc(old('email'), 'attr') ?>"
                maxlength="150"
                autocomplete="email"
            >
        </div>

        <div class="consultant-field">
            <label for="password">
                Password <span>*</span>
            </label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
            >
        </div>

        <div class="consultant-field">
            <label for="password_confirm">
                Konfirmasi Password <span>*</span>
            </label>
            <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                placeholder="Masukkan ulang password"
                autocomplete="new-password"
            >
        </div>

        <div class="consultant-field">
            <label for="no_telepon">Nomor Telepon</label>
            <input
                type="tel"
                id="no_telepon"
                name="no_telepon"
                placeholder="Contoh: 081234567890"
                value="<?= esc(old('no_telepon'), 'attr') ?>"
                maxlength="20"
                autocomplete="tel"
            >
        </div>

        <div class="consultant-field">
            <label for="alamat">Alamat</label>
            <textarea
                id="alamat"
                name="alamat"
                rows="3"
                placeholder="Masukkan alamat lengkap konsultan"
            ><?= esc(old('alamat')) ?></textarea>
        </div>

        <!-- INFORMASI PROFESIONAL -->
        <div class="consultant-section-title consultant-field-wide">
            <h3>Informasi Profesional</h3>
            <p>Informasi keahlian dan pengalaman konsultan.</p>
        </div>

        <div class="consultant-field">
            <label for="no_str">Nomor STR</label>
            <input
                type="text"
                id="no_str"
                name="no_str"
                placeholder="Masukkan nomor STR konsultan"
                value="<?= esc(old('no_str'), 'attr') ?>"
                maxlength="50"
            >
        </div>

        <div class="consultant-field">
            <label for="spesialisasi">Spesialisasi</label>
            <input
                type="text"
                id="spesialisasi"
                name="spesialisasi"
                placeholder="Contoh: Psikologi Klinis"
                value="<?= esc(old('spesialisasi'), 'attr') ?>"
                maxlength="100"
            >
        </div>

        <div class="consultant-field">
            <label for="pengalaman_tahun">
                Pengalaman (Tahun)
            </label>
            <input
                type="number"
                id="pengalaman_tahun"
                name="pengalaman_tahun"
                placeholder="Contoh: 5"
                value="<?= esc(old('pengalaman_tahun'), 'attr') ?>"
                min="0"
                step="1"
            >
        </div>

        <!-- INFORMASI STATUS -->
        <div class="consultant-note consultant-field-wide">
            <strong>Informasi Pendaftaran</strong>
            <p>
                Role akun otomatis sebagai Konsultan.
                Status akun awal Nonaktif dan status verifikasi Pending.
                Admin dapat mengaktifkan akun setelah proses verifikasi.
            </p>
        </div>

        <!-- TOMBOL AKSI -->
        <div class="consultant-form-actions consultant-field-wide">
            <button type="submit" class="consultant-btn">
                Simpan Konsultan
            </button>

            <a
                href="<?= site_url('admin/consultants') ?>"
                class="consultant-btn-secondary"
            >
                Batal
            </a>
        </div>

    </form>

</div>

<?= $this->endSection() ?>
