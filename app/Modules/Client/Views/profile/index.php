
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>

<?= $this->section('content') ?>

<?php
/**
 * @var array $user
 */

$nama = old('nama_lengkap', $user['nama_lengkap'] ?? '');
$email = old('email', $user['email'] ?? '');
$telepon = old('no_telepon', $user['no_telepon'] ?? '');
$alamat = old('alamat', $user['alamat'] ?? '');

$parts = preg_split('/\s+/', trim($nama));

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
$errorMessage = session()->getFlashdata('error');
?>

<style>
    /* ==========================================
       CLIENT PROFILE
       CONSISTENT MEDIUM DESIGN
    ========================================== */

    .profile-page {
        --profile-primary: #6d28d9;
        --profile-dark: #5b21b6;
        --profile-text: #292436;
        --profile-muted: #746e80;
        --profile-border: #eae5f1;

        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        color: var(--profile-text);
        font-family: inherit;
        font-size: 14px;
        line-height: 1.6;
    }

    .profile-page *,
    .profile-page *::before,
    .profile-page *::after {
        box-sizing: border-box;
    }

    /* ==========================================
       PAGE HEADING
    ========================================== */

    .profile-page .profile-heading {
        margin-bottom: 22px;
    }

    .profile-page .profile-heading h1 {
        margin: 0 0 7px;
        color: #292436;
        font-size: 27px;
        font-weight: 700;
        letter-spacing: -.5px;
        line-height: 1.3;
    }

    .profile-page .profile-heading p {
        margin: 0;
        color: var(--profile-muted);
        font-size: 14px;
        line-height: 1.7;
    }

    /* ==========================================
       PROFILE BANNER
    ========================================== */

    .profile-page .profile-banner {
        position: relative;
        display: flex;
        align-items: center;
        gap: 20px;
        min-width: 0;
        padding: 30px 34px;
        margin-bottom: 24px;
        border-radius: 18px;
        overflow: hidden;
        background: linear-gradient(
            120deg,
            #5b21b6,
            #7c3aed
        );
        color: #fff;
    }

    .profile-page .profile-banner::after {
        content: "";
        position: absolute;
        top: -90px;
        right: -65px;
        width: 240px;
        height: 240px;
        border: 38px solid rgba(255,255,255,.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .profile-page .profile-banner > * {
        position: relative;
        z-index: 1;
    }

    .profile-page .profile-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 78px;
        height: 78px;
        border: 2px solid rgba(255,255,255,.4);
        border-radius: 50%;
        background: rgba(255,255,255,.17);
        color: #fff;
        font-size: 26px;
        font-weight: 700;
    }

    .profile-page .profile-banner-info {
        flex: 1;
        min-width: 0;
    }

    .profile-page .profile-banner h2 {
        margin: 0 0 6px;
        color: #fff;
        font-size: 23px;
        font-weight: 700;
        line-height: 1.35;
        overflow-wrap: anywhere;
    }

    .profile-page .profile-banner p {
        margin: 0;
        color: #f0e7ff;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .profile-page .profile-tag {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 12px;
        margin-top: 13px;
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 25px;
        background: rgba(255,255,255,.14);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-page .profile-tag .bi {
        font-size: 15px;
    }

    /* ==========================================
       ALERT MESSAGES
    ========================================== */

    .profile-page .profile-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 15px 18px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 11px;
        font-size: 14px;
        line-height: 1.7;
    }

    .profile-page .profile-alert .bi {
        flex-shrink: 0;
        margin-top: 2px;
        font-size: 19px;
    }

    .profile-page .profile-alert-success {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    .profile-page .profile-alert-error {
        border-color: #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }

    /* ==========================================
       GRID LAYOUT
    ========================================== */

    .profile-page .profile-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 315px;
        align-items: start;
        gap: 22px;
    }

    /* ==========================================
       CARD
    ========================================== */

    .profile-page .profile-card {
        min-width: 0;
        padding: 26px;
        border: 1px solid var(--profile-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61,31,100,.025);
    }

    .profile-page .profile-card-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        padding-bottom: 22px;
        margin-bottom: 24px;
        border-bottom: 1px solid #f0edf4;
    }

    .profile-page .profile-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 47px;
        height: 47px;
        border-radius: 12px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 21px;
    }

    .profile-page .profile-card-heading h3 {
        margin: 0 0 5px;
        color: #292436;
        font-size: 19px;
        font-weight: 700;
        line-height: 1.4;
    }

    .profile-page .profile-card-heading p {
        margin: 0;
        color: var(--profile-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    /* ==========================================
       FORM FIELDS
    ========================================== */

    .profile-page .profile-field {
        margin-bottom: 22px;
    }

    .profile-page .profile-field label {
        display: block;
        margin-bottom: 9px;
        color: #3c3449;
        font-size: 14px;
        font-weight: 600;
    }

    .profile-page .profile-required {
        color: #dc2626;
    }

    .profile-page .profile-input-wrap {
        position: relative;
    }

    .profile-page .profile-input-wrap > .bi {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #8b7fa2;
        font-size: 17px;
        pointer-events: none;
    }

    .profile-page .profile-field input,
    .profile-page .profile-field textarea {
        display: block;
        width: 100%;
        min-width: 0;
        border: 1px solid #ded8e8;
        border-radius: 10px;
        background: #fff;
        color: #3c3449;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.5;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .profile-page .profile-field input {
        height: 49px;
        padding: 0 15px 0 44px;
    }

    .profile-page .profile-field textarea {
        min-height: 110px;
        padding: 14px 15px;
        resize: vertical;
    }

    .profile-page .profile-field input:focus,
    .profile-page .profile-field textarea:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(124,58,237,.1);
    }

    .profile-page .profile-field input::placeholder,
    .profile-page .profile-field textarea::placeholder {
        color: #aaa3b8;
    }

    .profile-page .profile-hint {
        display: block;
        margin-top: 8px;
        color: var(--profile-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    /* ==========================================
       FORM ACTIONS
    ========================================== */

    .profile-page .profile-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding-top: 20px;
        margin-top: 5px;
        border-top: 1px solid #f0edf4;
    }

    .profile-page .profile-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 46px;
        padding: 11px 19px;
        border: 1px solid transparent;
        border-radius: 10px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s, border-color .2s;
    }

    .profile-page .profile-btn .bi {
        font-size: 17px;
    }

    .profile-page .profile-btn-primary {
        background: #6d28d9;
        color: #fff;
    }

    .profile-page .profile-btn-primary:hover {
        background: #5b21b6;
        color: #fff;
    }

    .profile-page .profile-btn-secondary {
        border-color: #ded8e8;
        background: #fff;
        color: #615a6d;
    }

    .profile-page .profile-btn-secondary:hover {
        background: #f5f3ff;
        color: #5b21b6;
        text-decoration: none;
    }

    /* ==========================================
       ACCOUNT SUMMARY
    ========================================== */

    .profile-page .profile-summary .profile-card-heading {
        margin-bottom: 7px;
    }

    .profile-page .profile-summary .profile-card-heading h3 {
        font-size: 17px;
    }

    .profile-page .profile-info-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 17px 0;
        border-bottom: 1px solid #f0edf4;
    }

    .profile-page .profile-info-row:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .profile-page .profile-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #f3edff;
        color: #6d28d9;
        font-size: 18px;
    }

    .profile-page .profile-info-content {
        flex: 1;
        min-width: 0;
    }

    .profile-page .profile-info-label {
        margin-bottom: 5px;
        color: var(--profile-muted);
        font-size: 12px;
    }

    .profile-page .profile-info-value {
        color: #3c3449;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.6;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }

    /* ==========================================
       ACCOUNT NOTE
    ========================================== */

    .profile-page .profile-account-note {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 16px;
        margin-top: 18px;
        border: 1px solid #ddd0ff;
        border-radius: 12px;
        background: #f5f0ff;
        color: #615a6d;
        font-size: 13px;
        line-height: 1.7;
    }

    .profile-page .profile-account-note .bi {
        flex-shrink: 0;
        margin-top: 2px;
        color: #6d28d9;
        font-size: 19px;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 900px) {
        .profile-page .profile-grid {
            grid-template-columns: minmax(0, 1fr) 280px;
        }

        .profile-page .profile-card {
            padding: 22px;
        }
    }

    @media (max-width: 760px) {
        .profile-page .profile-grid {
            grid-template-columns: 1fr;
        }

        .profile-page .profile-heading h1 {
            font-size: 25px;
        }

        .profile-page .profile-banner {
            padding: 25px;
        }
    }

    @media (max-width: 480px) {
        .profile-page .profile-banner {
            gap: 14px;
            padding: 23px 19px;
        }

        .profile-page .profile-avatar {
            width: 60px;
            height: 60px;
            font-size: 21px;
        }

        .profile-page .profile-banner h2 {
            font-size: 19px;
        }

        .profile-page .profile-banner p {
            font-size: 12px;
        }

        .profile-page .profile-card {
            padding: 19px;
        }

        .profile-page .profile-card-heading h3 {
            font-size: 17px;
        }

        .profile-page .profile-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .profile-page .profile-btn {
            width: 100%;
        }
    }
</style>

<div class="profile-page">

    <!-- ==========================================
         PAGE HEADING
    ========================================== -->

    <header class="profile-heading">

        <h1>Profil Pengguna</h1>

        <p>
            Kelola informasi pribadi dan detail akun kamu.
        </p>

    </header>

    <!-- ==========================================
         PROFILE BANNER
    ========================================== -->

    <section class="profile-banner">

        <div class="profile-avatar" aria-hidden="true">
            <?= esc($initials) ?>
        </div>

        <div class="profile-banner-info">

            <h2>
                <?= esc($nama !== '' ? $nama : 'Pengguna') ?>
            </h2>

            <p>
                <?= esc($email) ?>
            </p>

            <span class="profile-tag">
                <i class="bi bi-person-check" aria-hidden="true"></i>
                Akun Klien
            </span>

        </div>

    </section>

    <!-- ==========================================
         FLASH MESSAGES
    ========================================== -->

    <?php if ($successMessage): ?>

        <div
            class="profile-alert profile-alert-success"
            role="status"
        >

            <i
                class="bi bi-check-circle"
                aria-hidden="true"
            ></i>

            <span>
                <?= esc($successMessage) ?>
            </span>

        </div>

    <?php endif; ?>

    <?php if ($errorMessage): ?>

        <div
            class="profile-alert profile-alert-error"
            role="alert"
        >

            <i
                class="bi bi-exclamation-circle"
                aria-hidden="true"
            ></i>

            <span>
                <?= esc($errorMessage) ?>
            </span>

        </div>

    <?php endif; ?>

    <!-- ==========================================
         PROFILE CONTENT
    ========================================== -->

    <div class="profile-grid">

        <!-- ======================================
             LEFT: PERSONAL INFORMATION
        ====================================== -->

        <section class="profile-card">

            <div class="profile-card-heading">

                <div class="profile-heading-icon">
                    <i
                        class="bi bi-person-vcard"
                        aria-hidden="true"
                    ></i>
                </div>

                <div>

                    <h3>Informasi Pribadi</h3>

                    <p>
                        Pastikan informasi akun kamu selalu terbaru.
                    </p>

                </div>

            </div>

            <form
                action="<?= site_url('client/profile/update') ?>"
                method="post"
            >

                <?= csrf_field() ?>

                <!-- NAME -->

                <div class="profile-field">

                    <label for="nama_lengkap">
                        Nama Lengkap
                        <span class="profile-required">*</span>
                    </label>

                    <div class="profile-input-wrap">

                        <i
                            class="bi bi-person"
                            aria-hidden="true"
                        ></i>

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

                        <i
                            class="bi bi-envelope"
                            aria-hidden="true"
                        ></i>

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

                <!-- PHONE -->

                <div class="profile-field">

                    <label for="no_telepon">
                        Nomor Telepon
                    </label>

                    <div class="profile-input-wrap">

                        <i
                            class="bi bi-telephone"
                            aria-hidden="true"
                        ></i>

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

                <!-- ADDRESS -->

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
                        Informasi alamat dapat diperbarui
                        sesuai kebutuhan.
                    </span>

                </div>

                <!-- ACTIONS -->

                <div class="profile-actions">

                    <button
                        type="submit"
                        class="profile-btn profile-btn-primary"
                    >

                        <i
                            class="bi bi-floppy"
                            aria-hidden="true"
                        ></i>

                        Simpan Perubahan

                    </button>

                    <a
                        href="<?= site_url('client') ?>"
                        class="profile-btn profile-btn-secondary"
                    >

                        <i
                            class="bi bi-x-circle"
                            aria-hidden="true"
                        ></i>

                        Batal

                    </a>

                </div>

            </form>

        </section>

        <!-- ======================================
             RIGHT: ACCOUNT SUMMARY
        ====================================== -->

        <aside>

            <section class="profile-card profile-summary">

                <div class="profile-card-heading">

                    <div class="profile-heading-icon">
                        <i
                            class="bi bi-person-lines-fill"
                            aria-hidden="true"
                        ></i>
                    </div>

                    <div>

                        <h3>Ringkasan Akun</h3>

                        <p>
                            Informasi akun yang sedang digunakan.
                        </p>

                    </div>

                </div>

                <!-- NAME -->

                <div class="profile-info-row">

                    <div class="profile-info-icon">
                        <i
                            class="bi bi-person"
                            aria-hidden="true"
                        ></i>
                    </div>

                    <div class="profile-info-content">

                        <div class="profile-info-label">
                            Nama Pengguna
                        </div>

                        <div class="profile-info-value">
                            <?= esc($nama !== '' ? $nama : 'Belum diisi') ?>
                        </div>

                    </div>

                </div>

                <!-- EMAIL -->

                <div class="profile-info-row">

                    <div class="profile-info-icon">
                        <i
                            class="bi bi-envelope"
                            aria-hidden="true"
                        ></i>
                    </div>

                    <div class="profile-info-content">

                        <div class="profile-info-label">
                            Email
                        </div>

                        <div class="profile-info-value">
                            <?= esc($email !== '' ? $email : 'Belum diisi') ?>
                        </div>

                    </div>

                </div>

                <!-- PHONE -->

                <div class="profile-info-row">

                    <div class="profile-info-icon">
                        <i
                            class="bi bi-telephone"
                            aria-hidden="true"
                        ></i>
                    </div>

                    <div class="profile-info-content">

                        <div class="profile-info-label">
                            Nomor Telepon
                        </div>

                        <div class="profile-info-value">
                            <?= esc(
                                $telepon !== ''
                                    ? $telepon
                                    : 'Belum diisi'
                            ) ?>
                        </div>

                    </div>

                </div>

                <!-- ADDRESS -->

                <div class="profile-info-row">

                    <div class="profile-info-icon">
                        <i
                            class="bi bi-geo-alt"
                            aria-hidden="true"
                        ></i>
                    </div>

                    <div class="profile-info-content">

                        <div class="profile-info-label">
                            Alamat
                        </div>

                        <div class="profile-info-value">
                            <?= esc(
                                $alamat !== ''
                                    ? $alamat
                                    : 'Belum diisi'
                            ) ?>
                        </div>

                    </div>

                </div>

            </section>

            <!-- ACCOUNT NOTE -->

            <div class="profile-account-note">

                <i
                    class="bi bi-shield-check"
                    aria-hidden="true"
                ></i>

                <span>
                    Pastikan informasi akun selalu akurat
                    agar proses komunikasi dan layanan
                    konseling dapat berjalan dengan baik.
                </span>

            </div>

        </aside>

    </div>

</div>

<?= $this->endSection() ?>
