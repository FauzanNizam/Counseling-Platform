
<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>
<?= $this->section('content') ?>

<?php

// ==========================================
// DATA PROFIL PSIKOLOG
// ==========================================

$nama = old('nama_lengkap', $user['nama_lengkap'] ?? '');
$email = old('email', $user['email'] ?? '');
$telepon = old('no_telepon', $user['no_telepon'] ?? '');
$alamat = old('alamat', $user['alamat'] ?? '');

$noStr = old('no_str', $profile['no_str'] ?? '');
$spesialisasi = old('spesialisasi', $profile['spesialisasi'] ?? '');
$pengalaman = old(
    'pengalaman_tahun',
    $profile['pengalaman_tahun'] ?? 0
);

$statusVerifikasi = strtolower(
    trim((string) ($profile['status_verifikasi'] ?? 'pending'))
);

$verificationClass = match ($statusVerifikasi) {
    'verified', 'terverifikasi', 'approved' => 'verified',
    'rejected', 'ditolak' => 'rejected',
    default => 'pending',
};

$verificationLabel = match ($verificationClass) {
    'verified' => 'Terverifikasi',
    'rejected' => 'Ditolak',
    default => 'Menunggu Verifikasi',
};

$namaTampil = trim((string) ($user['nama_lengkap'] ?? 'Psikolog'));

$initial = strtoupper(substr($namaTampil, 0, 1));

if ($initial === '') {
    $initial = 'P';
}

$success = session()->getFlashdata('success');
$error = session()->getFlashdata('error');

?>

<!-- ==========================================
     HEADER
========================================== -->

<div class="dashboard-header">
    <h1>Profil Psikolog</h1>
    <p>
        Kelola informasi akun, kredensial profesional,
        dan keamanan akun Anda.
    </p>
</div>

<div class="psy-profile">

    <!-- ======================================
         NOTIFIKASI
    ======================================= -->

    <?php if ($success): ?>
        <div class="psy-alert psy-alert--success" role="alert">
            <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="psy-alert psy-alert--error" role="alert">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <!-- ======================================
         IDENTITAS PSIKOLOG
    ======================================= -->

    <div class="psy-profile__identity">

        <div class="psy-profile__avatar">
            <?= esc($initial) ?>
        </div>

        <div class="psy-profile__identity-info">
            <h2><?= esc($namaTampil) ?></h2>

            <p>
                <?= esc($user['email'] ?? '-') ?>
            </p>

            <span class="psy-profile__role">
                Psikolog / Konsultan
            </span>
        </div>

        <div
            class="psy-profile__verification psy-profile__verification--<?= esc($verificationClass, 'attr') ?>"
        >
            <?= esc($verificationLabel) ?>
        </div>

    </div>

    <!-- ======================================
         FORM EDIT PROFIL
    ======================================= -->

    <form
        action="<?= site_url('psychologist/profile/update') ?>"
        method="post"
        class="psy-profile__form"
    >

        <?= csrf_field() ?>

        <!-- INFORMASI AKUN -->

        <div class="psy-profile__card">

            <div class="psy-profile__card-header">
                <h3>Informasi Akun</h3>
                <p>
                    Perbarui informasi pribadi yang
                    digunakan dalam Counseling Platform.
                </p>
            </div>

            <div class="psy-profile__grid">

                <div class="psy-profile__field">
                    <label for="nama_lengkap">
                        Nama Lengkap
                        <span class="psy-profile__required">*</span>
                    </label>

                    <input
                        type="text"
                        id="nama_lengkap"
                        name="nama_lengkap"
                        value="<?= esc($nama, 'attr') ?>"
                        minlength="3"
                        maxlength="100"
                        autocomplete="name"
                        required
                    >
                </div>

                <div class="psy-profile__field">
                    <label for="email">
                        Email
                        <span class="psy-profile__required">*</span>
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= esc($email, 'attr') ?>"
                        maxlength="150"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="psy-profile__field">
                    <label for="no_telepon">
                        Nomor Telepon
                    </label>

                    <input
                        type="tel"
                        id="no_telepon"
                        name="no_telepon"
                        value="<?= esc($telepon, 'attr') ?>"
                        maxlength="20"
                        autocomplete="tel"
                        placeholder="Contoh: 081234567890"
                    >
                </div>

                <div class="psy-profile__field">
                    <label for="alamat">
                        Alamat
                    </label>

                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="3"
                        autocomplete="street-address"
                        placeholder="Masukkan alamat lengkap"
                    ><?= esc($alamat) ?></textarea>
                </div>

            </div>
        </div>

        <!-- INFORMASI PROFESIONAL -->

        <div class="psy-profile__card">

            <div class="psy-profile__card-header">
                <h3>Informasi Profesional</h3>
                <p>
                    Lengkapi kredensial, spesialisasi,
                    dan pengalaman praktik Anda.
                </p>
            </div>

            <div class="psy-profile__grid">

                <div class="psy-profile__field">
                    <label for="no_str">
                        Nomor STR
                    </label>

                    <input
                        type="text"
                        id="no_str"
                        name="no_str"
                        value="<?= esc($noStr, 'attr') ?>"
                        maxlength="50"
                        placeholder="Masukkan nomor STR"
                    >

                    <small>
                        Surat Tanda Registrasi profesional.
                    </small>
                </div>

                <div class="psy-profile__field">
                    <label for="spesialisasi">
                        Spesialisasi
                    </label>

                    <input
                        type="text"
                        id="spesialisasi"
                        name="spesialisasi"
                        value="<?= esc($spesialisasi, 'attr') ?>"
                        maxlength="100"
                        placeholder="Contoh: Psikologi Klinis"
                    >
                </div>

                <div class="psy-profile__field">
                    <label for="pengalaman_tahun">
                        Pengalaman Praktik (Tahun)
                    </label>

                    <input
                        type="number"
                        id="pengalaman_tahun"
                        name="pengalaman_tahun"
                        value="<?= esc($pengalaman, 'attr') ?>"
                        min="0"
                        step="1"
                    >
                </div>

            </div>

            <div class="psy-profile__info">
                Status verifikasi profesional dikelola
                oleh administrator dan tidak dapat
                diubah melalui halaman ini.
            </div>

        </div>

        <!-- SIMPAN PROFIL -->

        <div class="psy-profile__actions">
            <button
                type="submit"
                class="psy-btn psy-btn--primary"
            >
                Simpan Perubahan Profil
            </button>
        </div>

    </form>

    <!-- ======================================
         KEAMANAN AKUN
    ======================================= -->

    <div class="psy-profile__card">

        <div class="psy-profile__card-header">
            <h3>Keamanan Akun</h3>
            <p>
                Gunakan password yang kuat
                untuk menjaga keamanan akun Anda.
            </p>
        </div>

        <form
            action="<?= site_url('psychologist/profile/password') ?>"
            method="post"
            class="psy-profile__password-form"
        >

            <?= csrf_field() ?>

            <div class="psy-profile__field">
                <label for="current_password">
                    Password Saat Ini
                </label>

                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div class="psy-profile__field">
                <label for="new_password">
                    Password Baru
                </label>

                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                    required
                >

                <small>
                    Minimal 8 karakter dan berbeda
                    dari password sebelumnya.
                </small>
            </div>

            <div class="psy-profile__field">
                <label for="new_password_confirm">
                    Konfirmasi Password Baru
                </label>

                <input
                    type="password"
                    id="new_password_confirm"
                    name="new_password_confirm"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="psy-profile__actions">
                <button
                    type="submit"
                    class="psy-btn psy-btn--secondary"
                >
                    Ubah Password
                </button>
            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>
