
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/** @var array $user */

$nama    = old('nama_lengkap', $user['nama_lengkap'] ?? '');
$email   = old('email', $user['email'] ?? '');
$telepon = old('no_telepon', $user['no_telepon'] ?? '');
$alamat  = old('alamat', $user['alamat'] ?? '');

$parts = preg_split('/\s+/', trim((string) $nama));
$initials = '';

foreach (array_slice($parts ?: [], 0, 2) as $part) {
    if ($part !== '') {
        $initials .= function_exists('mb_substr')
            ? mb_substr($part, 0, 1, 'UTF-8')
            : substr($part, 0, 1);
    }
}

$initials = $initials ?: 'KL';
$initials = function_exists('mb_strtoupper')
    ? mb_strtoupper($initials, 'UTF-8')
    : strtoupper($initials);

$successMessage = session()->getFlashdata('success');
$errorMessage   = session()->getFlashdata('error');
?>

<style>
/* ==========================================
   CLIENT PROFILE - GENERAL
========================================== */

.profile-page {
    --profile-primary: #6d28d9;
    --profile-dark: #4c1d95;
    --profile-text: #292436;
    --profile-muted: #746e80;
    --profile-border: #eae5f1;

    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    color: var(--profile-text);
}

.profile-page,
.profile-page * {
    box-sizing: border-box;
}

.profile-heading {
    margin-bottom: 26px;
}

.profile-heading h1 {
    margin: 0 0 8px;
    color: var(--profile-dark);
    font-size: 30px;
    font-weight: 700;
}

.profile-heading p {
    margin: 0;
    color: var(--profile-muted);
    font-size: 15px;
}

/* ==========================================
   PROFILE BANNER
========================================== */

.profile-banner {
    display: flex;
    align-items: center;
    gap: 22px;
    padding: 28px 30px;
    margin-bottom: 26px;
    border: 1px solid var(--profile-border);
    border-radius: 18px;
    background: #fff;
}

.profile-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 84px;
    width: 84px;
    height: 84px;
    border-radius: 50%;
    background: #ede2ff;
    color: var(--profile-primary);
    font-size: 28px;
    font-weight: 700;
}

.profile-banner-info {
    flex: 1;
    min-width: 0;
}

.profile-banner-info h2 {
    margin: 0 0 6px;
    color: var(--profile-dark);
    font-size: 23px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.profile-banner-info p {
    margin: 0 0 12px;
    color: var(--profile-muted);
    font-size: 14px;
    overflow-wrap: anywhere;
}

.profile-tag {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 12px;
    border-radius: 999px;
    background: #f2eaff;
    color: var(--profile-primary);
    font-size: 12px;
    font-weight: 600;
}

/* ==========================================
   PROFILE GRID
========================================== */

.profile-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
    align-items: start;
    gap: 24px;
}

.profile-card {
    min-width: 0;
    padding: 28px;
    border: 1px solid var(--profile-border);
    border-radius: 18px;
    background: #fff;
}

.profile-card-heading {
    display: flex;
    align-items: center;
    gap: 16px;
    padding-bottom: 22px;
    margin-bottom: 24px;
    border-bottom: 1px solid var(--profile-border);
}

.profile-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 58px;
    width: 58px;
    height: 58px;
    border-radius: 16px;
    background: #efe4ff;
    color: var(--profile-primary);
    font-size: 25px;
}

.profile-card-heading h3 {
    margin: 0 0 5px;
    color: var(--profile-text);
    font-size: 20px;
    font-weight: 700;
}

.profile-card-heading p {
    margin: 0;
    color: var(--profile-muted);
    font-size: 13px;
    line-height: 1.6;
}

/* ==========================================
   FORM INFORMASI PRIBADI
========================================== */

.profile-field {
    display: flex;
    flex-direction: column;
    gap: 9px;
    margin-bottom: 22px;
}

.profile-field label {
    color: var(--profile-text);
    font-size: 14px;
    font-weight: 600;
}

.profile-required {
    color: #dc2626;
}

.profile-input-wrap {
    position: relative;
    width: 100%;
}

.profile-input-wrap > i {
    position: absolute;
    top: 50%;
    left: 16px;
    transform: translateY(-50%);
    color: var(--profile-primary);
    font-size: 18px;
    pointer-events: none;
}

.profile-input-wrap input {
    width: 100%;
    min-height: 48px;
    padding: 12px 15px 12px 46px;
    border: 1px solid #ded7eb;
    border-radius: 10px;
    background: #fff;
    color: var(--profile-text);
    font-family: inherit;
    font-size: 14px;
}

.profile-field textarea {
    width: 100%;
    min-height: 115px;
    padding: 13px 15px;
    border: 1px solid #ded7eb;
    border-radius: 10px;
    background: #fff;
    color: var(--profile-text);
    font-family: inherit;
    font-size: 14px;
    line-height: 1.6;
    resize: vertical;
}

.profile-input-wrap input:focus,
.profile-field textarea:focus {
    outline: none;
    border-color: var(--profile-primary);
    box-shadow: 0 0 0 3px rgba(109, 40, 217, .10);
}

.profile-hint {
    color: var(--profile-muted);
    font-size: 12px;
    line-height: 1.5;
}

/* ==========================================
   FORM BUTTONS
========================================== */

.profile-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 26px;
}

.profile-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 44px;
    padding: 11px 18px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: background-color .2s ease;
}

.profile-btn-primary {
    background: var(--profile-primary);
    color: #fff;
}

.profile-btn-primary:hover {
    background: var(--profile-dark);
    color: #fff;
}

.profile-btn-secondary {
    border-color: #e3d9ef;
    background: #f5f0fc;
    color: var(--profile-dark);
}

.profile-btn-secondary:hover {
    background: #ece3f8;
    color: var(--profile-dark);
}

/* ==========================================
   ACCOUNT SUMMARY - FIXED ALIGNMENT
========================================== */

.profile-summary {
    width: 100%;
}

.profile-summary .profile-card-heading {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 0;
    padding-bottom: 22px;
}

/* Setiap informasi ditampilkan satu baris rapi */
.profile-summary .profile-info-row {
    display: flex;
    align-items: center;
    gap: 16px;
    width: 100%;
    min-width: 0;
    padding: 20px 0;
    border-bottom: 1px solid #f0edf4;
}

.profile-summary .profile-info-row:last-of-type {
    border-bottom: none;
}

/* Kotak ikon di tengah kelompok teks */
.profile-summary .profile-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 46px;
    width: 46px;
    height: 46px;
    margin: 0;
    padding: 0;
    border-radius: 13px;
    background: #f3ebff;
    color: var(--profile-primary);
    font-size: 21px;
    align-self: center;
}

.profile-summary .profile-info-icon i {
    display: inline-block;
    line-height: 1;
}

/* Label dan nilai tetap berdekatan */
.profile-summary .profile-info-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    flex: 1 1 0;
    min-width: 0;
    width: auto;
    margin: 0;
    padding: 0;
    gap: 5px;
    text-align: left;
}

.profile-summary .profile-info-label {
    display: block;
    width: 100%;
    margin: 0;
    padding: 0;
    color: var(--profile-muted);
    font-size: 13px;
    font-weight: 400;
    line-height: 1.5;
    text-align: left;
}

.profile-summary .profile-info-value {
    display: block;
    width: 100%;
    margin: 0;
    padding: 0;
    color: var(--profile-text);
    font-size: 15px;
    font-weight: 600;
    line-height: 1.5;
    text-align: left;
    overflow-wrap: anywhere;
    word-break: normal;
    white-space: pre-line;
}

/* Catatan akun */
.profile-account-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-top: 22px;
    padding: 15px;
    border: 1px solid #e9ddfb;
    border-radius: 12px;
    background: #f9f6ff;
    color: #65527f;
    font-size: 13px;
    line-height: 1.6;
}

.profile-account-note i {
    flex-shrink: 0;
    color: var(--profile-primary);
    font-size: 18px;
}

/* ==========================================
   FLASH MESSAGES
========================================== */

.profile-flash {
    margin-bottom: 20px;
    padding: 14px 18px;
    border-radius: 10px;
    font-size: 14px;
    line-height: 1.6;
}

.profile-flash-success {
    border: 1px solid #bbf7d0;
    background: #f0fdf4;
    color: #166534;
}

.profile-flash-error {
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #991b1b;
}

/* ==========================================
   RESPONSIVE
========================================== */

@media (max-width: 1100px) {
    .profile-grid {
        grid-template-columns: minmax(0, 1fr);
    }
}

@media (max-width: 768px) {
    .profile-heading h1 {
        font-size: 25px;
    }

    .profile-banner {
        padding: 22px;
    }

    .profile-card {
        padding: 22px;
    }
}

@media (max-width: 480px) {
    .profile-banner {
        align-items: flex-start;
        gap: 14px;
        padding: 18px;
    }

    .profile-avatar {
        flex-basis: 60px;
        width: 60px;
        height: 60px;
        font-size: 22px;
    }

    .profile-banner-info h2 {
        font-size: 18px;
    }

    .profile-card {
        padding: 18px;
    }

    .profile-card-heading {
        align-items: center;
        gap: 12px;
    }

    .profile-heading-icon {
        flex-basis: 48px;
        width: 48px;
        height: 48px;
        font-size: 21px;
    }

    .profile-card-heading h3 {
        font-size: 17px;
    }

    .profile-summary .profile-info-row {
        gap: 12px;
        padding: 16px 0;
    }

    .profile-summary .profile-info-icon {
        flex-basis: 42px;
        width: 42px;
        height: 42px;
        font-size: 19px;
    }

    .profile-summary .profile-info-value {
        font-size: 14px;
    }

    .profile-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .profile-actions .profile-btn {
        width: 100%;
    }
}


/* ==========================================
   FINAL FIX - CLIENT ACCOUNT SUMMARY
   Compact layout dan alignment
========================================== */

.profile-page .profile-summary .profile-info-row {
    display: grid !important;
    grid-template-columns: 48px minmax(0, 1fr) !important;
    align-items: center !important;
    column-gap: 16px !important;

    min-height: 0 !important;
    height: auto !important;
    padding: 16px 0 !important;
    margin: 0 !important;

    border-bottom: 1px solid #eeeaf3;
}

/* Ikon */
.profile-page .profile-summary .profile-info-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    width: 48px !important;
    height: 48px !important;
    min-width: 48px !important;

    margin: 0 !important;
    padding: 0 !important;

    border-radius: 13px;
    background: #f3eaff;
    color: #6d28d9;

    align-self: center !important;
}

/* Kelompok label dan nilai */
.profile-page .profile-summary .profile-info-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    justify-content: center !important;

    width: 100% !important;
    min-width: 0 !important;
    min-height: 0 !important;
    height: auto !important;

    gap: 4px !important;
    margin: 0 !important;
    padding: 0 !important;

    text-align: left !important;
}

/* Label */
.profile-page .profile-summary .profile-info-label {
    display: block !important;
    position: static !important;

    width: auto !important;
    min-height: 0 !important;
    height: auto !important;

    margin: 0 !important;
    padding: 0 !important;

    font-size: 13px !important;
    font-weight: 400 !important;
    line-height: 1.4 !important;
    text-align: left !important;
}

/* Nilai */
.profile-page .profile-summary .profile-info-value {
    display: block !important;
    position: static !important;

    width: 100% !important;
    min-height: 0 !important;
    height: auto !important;

    margin: 0 !important;
    padding: 0 !important;

    color: #292436 !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    line-height: 1.45 !important;
    text-align: left !important;

    overflow-wrap: anywhere;
    white-space: normal !important;
}

/* Baris terakhir */
.profile-page .profile-summary .profile-info-row:last-of-type {
    border-bottom: none;
}

/* Mobile */
@media (max-width: 480px) {
    .profile-page .profile-summary .profile-info-row {
        grid-template-columns: 42px minmax(0, 1fr) !important;
        column-gap: 12px !important;
        padding: 14px 0 !important;
    }

    .profile-page .profile-summary .profile-info-icon {
        width: 42px !important;
        height: 42px !important;
        min-width: 42px !important;
    }

    .profile-page .profile-summary .profile-info-value {
        font-size: 14px !important;
    }
}

</style>

<div class="profile-page">

    <!-- HEADER HALAMAN -->
    <header class="profile-heading">
        <h1>Profil Pengguna</h1>
        <p>Kelola informasi pribadi dan detail akun kamu.</p>
    </header>

    <!-- NOTIFIKASI -->
    <?php if ($successMessage): ?>
        <div class="profile-flash profile-flash-success" role="status">
            <?= esc($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="profile-flash profile-flash-error" role="alert">
            <?= esc($errorMessage) ?>
        </div>
    <?php endif; ?>

    <!-- PROFILE BANNER -->
    <section class="profile-banner">

        <div class="profile-avatar" aria-hidden="true">
            <?= esc($initials) ?>
        </div>

        <div class="profile-banner-info">
            <h2><?= esc($nama !== '' ? $nama : 'Pengguna') ?></h2>

            <p><?= esc($email) ?></p>

            <span class="profile-tag">
                <i class="bi bi-person-check" aria-hidden="true"></i>
                Akun Klien
            </span>
        </div>

    </section>

    <!-- GRID UTAMA -->
    <div class="profile-grid">

        <!-- =====================================
             INFORMASI PRIBADI
        ====================================== -->
        <section class="profile-card">

            <div class="profile-card-heading">

                <div class="profile-heading-icon">
                    <i class="bi bi-person-vcard" aria-hidden="true"></i>
                </div>

                <div>
                    <h3>Informasi Pribadi</h3>
                    <p>Pastikan informasi akun kamu selalu terbaru.</p>
                </div>

            </div>

            <form
                action="<?= site_url('client/profile/update') ?>"
                method="post"
            >
                <?= csrf_field() ?>

                <!-- NAMA LENGKAP -->
                <div class="profile-field">

                    <label for="nama_lengkap">
                        Nama Lengkap
                        <span class="profile-required">*</span>
                    </label>

                    <div class="profile-input-wrap">
                        <i class="bi bi-person" aria-hidden="true"></i>

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

                </div>

                <!-- EMAIL -->
                <div class="profile-field">

                    <label for="email">
                        Alamat Email
                        <span class="profile-required">*</span>
                    </label>

                    <div class="profile-input-wrap">
                        <i class="bi bi-envelope" aria-hidden="true"></i>

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

                    <span class="profile-hint">
                        Email digunakan untuk masuk ke akun.
                    </span>

                </div>

                <!-- NOMOR TELEPON -->
                <div class="profile-field">

                    <label for="no_telepon">
                        Nomor Telepon
                    </label>

                    <div class="profile-input-wrap">
                        <i class="bi bi-telephone" aria-hidden="true"></i>

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

                </div>

                <!-- ALAMAT -->
                <div class="profile-field">

                    <label for="alamat">
                        Alamat
                    </label>

                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="4"
                        autocomplete="street-address"
                        placeholder="Masukkan alamat lengkap kamu"
                    ><?= esc($alamat) ?></textarea>

                    <span class="profile-hint">
                        Informasi alamat dapat diperbarui sesuai kebutuhan.
                    </span>

                </div>

                <!-- TOMBOL AKSI -->
                <div class="profile-actions">

                    <button
                        type="submit"
                        class="profile-btn profile-btn-primary"
                    >
                        <i class="bi bi-floppy" aria-hidden="true"></i>
                        Simpan Perubahan
                    </button>

                    <a
                        href="<?= site_url('client') ?>"
                        class="profile-btn profile-btn-secondary"
                    >
                        <i class="bi bi-x-circle" aria-hidden="true"></i>
                        Batal
                    </a>

                </div>

            </form>

        </section>

        <!-- =====================================
             RINGKASAN AKUN
        ====================================== -->
        <section class="profile-card profile-summary">

            <!-- HEADER -->
            <div class="profile-card-heading">

                <div class="profile-heading-icon">
                    <i
                        class="bi bi-person-lines-fill"
                        aria-hidden="true"
                    ></i>
                </div>

                <div>
                    <h3>Ringkasan Akun</h3>
                    <p>Informasi akun yang sedang digunakan.</p>
                </div>

            </div>

            <!-- NAMA PENGGUNA -->
            <div class="profile-info-row">

                <div class="profile-info-icon">
                    <i class="bi bi-person" aria-hidden="true"></i>
                </div>

                <div class="profile-info-content">

                    <span class="profile-info-label">
                        Nama Pengguna
                    </span>

                    <strong class="profile-info-value">
                        <?= esc($user['nama_lengkap'] ?? '-') ?>
                    </strong>

                </div>

            </div>

            <!-- EMAIL -->
            <div class="profile-info-row">

                <div class="profile-info-icon">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                </div>

                <div class="profile-info-content">

                    <span class="profile-info-label">
                        Email
                    </span>

                    <strong class="profile-info-value">
                        <?= esc($user['email'] ?? '-') ?>
                    </strong>

                </div>

            </div>

            <!-- NOMOR TELEPON -->
            <div class="profile-info-row">

                <div class="profile-info-icon">
                    <i class="bi bi-telephone" aria-hidden="true"></i>
                </div>

                <div class="profile-info-content">

                    <span class="profile-info-label">
                        Nomor Telepon
                    </span>

                    <strong class="profile-info-value">
                        <?= esc(
                            !empty($user['no_telepon'])
                                ? $user['no_telepon']
                                : '-'
                        ) ?>
                    </strong>

                </div>

            </div>

            <!-- ALAMAT -->
            <div class="profile-info-row">

                <div class="profile-info-icon">
                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                </div>

                <div class="profile-info-content">

                    <span class="profile-info-label">
                        Alamat
                    </span>

                    <strong class="profile-info-value">
                        <?= esc(
                            !empty($user['alamat'])
                                ? $user['alamat']
                                : 'Belum ditambahkan'
                        ) ?>
                    </strong>

                </div>

            </div>

            <!-- CATATAN -->
            <div class="profile-account-note">

                <i class="bi bi-shield-check" aria-hidden="true"></i>

                <span>
                    Pastikan informasi akun selalu benar dan
                    terbaru agar layanan konsultasi dapat
                    digunakan dengan baik.
                </span>

            </div>

        </section>

    </div>

</div>

<?= $this->endSection() ?>
