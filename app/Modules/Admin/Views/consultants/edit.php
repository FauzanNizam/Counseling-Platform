
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>

<?php
$id = (int) $consultant['id_user'];

$v = static function ($key) use ($consultant) {
    return esc((string) old($key, $consultant[$key] ?? ''), 'attr');
};

$errors = session()->getFlashdata('errors');
$error = session()->getFlashdata('error');

$validationMessage = '';

if (is_array($errors) && !empty($errors)) {
    $validationMessage = implode("\n", array_values($errors));
}
?>

<div class="dashboard-header">
    <h1>Edit Konsultan</h1>
    <p>Perbarui informasi akun dan profil profesional konsultan.</p>
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
        <h2>Form Edit Konsultan</h2>
        <p>Perbarui data konsultan sesuai informasi terbaru.</p>
    </div>

    <form
        action="<?= site_url('admin/consultants/' . $id . '/update') ?>"
        method="post"
        class="consultant-form consultant-edit-form"
        novalidate
        onsubmit="return false;"
    >
        <?= csrf_field() ?>

        <!-- INFORMASI AKUN -->
        <div class="consultant-section-title consultant-field-wide">
            <h3>Informasi Akun</h3>
            <p>Perbarui data identitas dan akses akun konsultan.</p>
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
                value="<?= $v('nama_lengkap') ?>"
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
                value="<?= $v('email') ?>"
                maxlength="150"
                autocomplete="email"
            >
        </div>

        <div class="consultant-field">
            <label for="password">Password Baru</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Kosongkan jika tidak ingin mengubah password"
                autocomplete="new-password"
            >
        </div>

        <div class="consultant-field">
            <label for="password_confirm">
                Konfirmasi Password Baru
            </label>
            <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                placeholder="Ulangi password baru jika diubah"
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
                value="<?= $v('no_telepon') ?>"
                maxlength="20"
                autocomplete="tel"
            >
        </div>

        <div class="consultant-field">
            <label for="status_akun">Status Akun</label>

            <select id="status_akun" name="status_akun">
                <?php
                $statusOptions = [
                    'aktif' => 'Aktif',
                    'nonaktif' => 'Nonaktif',
                    'ditangguhkan' => 'Ditangguhkan',
                ];
                ?>

                <?php foreach ($statusOptions as $key => $label): ?>
                    <option
                        value="<?= esc($key, 'attr') ?>"
                        <?= old('status_akun', $consultant['status_akun']) === $key ? 'selected' : '' ?>
                    >
                        <?= esc($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- INFORMASI PROFESIONAL -->
        <div class="consultant-section-title consultant-field-wide">
            <h3>Informasi Profesional</h3>
            <p>Perbarui informasi keahlian dan pengalaman konsultan.</p>
        </div>

        <div class="consultant-field">
            <label for="no_str">Nomor STR</label>
            <input
                type="text"
                id="no_str"
                name="no_str"
                placeholder="Masukkan nomor STR konsultan"
                value="<?= $v('no_str') ?>"
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
                value="<?= $v('spesialisasi') ?>"
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
                value="<?= $v('pengalaman_tahun') ?>"
                min="0"
                step="1"
            >
        </div>

        <div class="consultant-field consultant-field-wide">
            <label for="alamat">Alamat</label>
            <textarea
                id="alamat"
                name="alamat"
                rows="3"
                placeholder="Masukkan alamat lengkap konsultan"
            ><?= esc((string) old('alamat', $consultant['alamat'] ?? '')) ?></textarea>
        </div>

        <!-- INFORMASI VERIFIKASI -->
        <div class="consultant-note consultant-field-wide">
            <strong>Informasi Status Konsultan</strong>
            <p>
                Untuk mengubah status verifikasi konsultan,
                gunakan halaman Detail Konsultan.
                Akun hanya boleh diaktifkan setelah Verified.
            </p>
        </div>

        <!-- AKSI -->
        <div class="consultant-form-actions consultant-field-wide">
            <button type="submit" class="consultant-btn">
                Simpan Perubahan
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
