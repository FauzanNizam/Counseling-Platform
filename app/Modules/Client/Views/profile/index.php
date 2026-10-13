
<?php
$nama = old('nama_lengkap', $user['nama_lengkap'] ?? '');
$email = old('email', $user['email'] ?? '');
$telepon = old('no_telepon', $user['no_telepon'] ?? '');
$alamat = old('alamat', $user['alamat'] ?? '');

$parts = preg_split('/\s+/', trim($nama));

$initials = '';

foreach (array_slice($parts ?: [], 0, 2) as $part) {
    if ($part !== '') {
        $initials .= mb_substr($part, 0, 1);
    }
}

$initials = strtoupper($initials ?: 'KL');
?>

<?= $this->extend('Modules\Shared\Views\layouts\client') ?>

<?= $this->section('content') ?>

<style>
    .profile-page {
        max-width: 1050px;
        margin: 0 auto;
        color: #1e1c2e;
        font-family: "Plus Jakarta Sans", system-ui, sans-serif;
    }

    .profile-heading {
        margin-bottom: 24px;
    }

    .profile-heading h1 {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -0.6px;
        margin-bottom: 6px;
    }

    .profile-heading p {
        color: #9896a8;
        font-size: 13px;
    }

    .profile-banner {
        background: linear-gradient(120deg, #4c1d95, #7c3aed, #9c5cf5);
        color: white;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .profile-banner::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -50px;
        top: -80px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .profile-avatar {
        width: 66px;
        height: 66px;
        border-radius: 50%;
        background: rgba(255,255,255,.18);
        border: 2px solid rgba(255,255,255,.45);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .profile-banner h2 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .profile-banner p {
        color: rgba(255,255,255,.75);
        font-size: 12px;
    }

    .profile-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        background: rgba(255,255,255,.14);
        font-size: 11px;
        margin-top: 9px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 20px;
        align-items: start;
    }

    .profile-card {
        background: white;
        border: 1px solid #f1f0f5;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(100,80,180,.025);
        min-width: 0;
    }

    .profile-card h3 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .profile-card .description {
        font-size: 12px;
        color: #9896a8;
        margin-bottom: 24px;
    }

    .profile-field {
        margin-bottom: 18px;
    }

    .profile-field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #4e4c62;
        margin-bottom: 7px;
    }

    .profile-field input,
    .profile-field textarea {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #e2e0ea;
        border-radius: 8px;
        background: white;
        color: #353348;
        font: inherit;
        font-size: 13px;
        transition: border-color .15s, box-shadow .15s;
    }

    .profile-field input:focus,
    .profile-field textarea:focus {
        outline: none;
        border-color: #9c5cf5;
        box-shadow: 0 0 0 3px rgba(124,58,237,.09);
    }

    .profile-field textarea {
        min-height: 100px;
        resize: vertical;
    }

    .profile-field input[readonly] {
        background: #f8f8fb;
        color: #6b6880;
    }

    .profile-hint {
        display: block;
        margin-top: 6px;
        color: #9896a8;
        font-size: 11px;
    }

    .profile-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 5px;
    }

    .profile-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border: 0;
        border-radius: 7px;
        font: inherit;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background .15s;
    }

    .profile-btn-primary {
        background: #7c3aed;
        color: white;
    }

    .profile-btn-primary:hover {
        background: #6d28d9;
    }

    .profile-btn-secondary {
        border: 1px solid #e2e0ea;
        background: white;
        color: #4e4c62;
    }

    .profile-btn-secondary:hover {
        background: #f8f8fb;
    }

    .profile-info-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f0f5;
    }

    .profile-info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .profile-info-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f0ff;
        color: #7c3aed;
        flex-shrink: 0;
        font-size: 16px;
    }

    .profile-info-label {
        font-size: 11px;
        color: #9896a8;
        margin-bottom: 4px;
    }

    .profile-info-value {
        font-size: 12px;
        font-weight: 600;
        color: #353348;
        overflow-wrap: anywhere;
    }

    .profile-alert {
        padding: 12px 14px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 12px;
    }

    .profile-alert-success {
        background: #d1fae5;
        color: #065f46;
    }

    .profile-alert-error {
        background: #fee2e2;
        color: #991b1b;
    }

    @media (max-width: 760px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .profile-card {
            padding: 20px;
        }

        .profile-heading h1 {
            font-size: 21px;
        }
    }

    @media (max-width: 420px) {
        .profile-banner {
            padding: 18px;
            gap: 12px;
        }

        .profile-avatar {
            width: 52px;
            height: 52px;
            font-size: 18px;
        }

        .profile-actions > * {
            flex: 1;
        }
    }
</style>

<div class="profile-page">
    <div class="profile-heading">
        <h1>Profil Pengguna</h1>
        <p>Kelola informasi pribadi dan detail akun kamu.</p>
    </div>

    <section class="profile-banner">
        <div class="profile-avatar"><?= esc($initials) ?></div>
        <div>
            <h2><?= esc($nama) ?></h2>
            <p><?= esc($email) ?></p>
            <span class="profile-tag">
                <i class="ti ti-user-check"></i>
                Akun Klien
            </span>
        </div>
    </section>

    <?php if ($success = session()->getFlashdata('success')): ?>
        <div class="profile-alert profile-alert-success">
            <i class="ti ti-circle-check"></i>
            <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="profile-alert profile-alert-error">
            <i class="ti ti-alert-circle"></i>
            <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <div class="profile-grid">
        <section class="profile-card">
            <h3>Informasi Pribadi</h3>
            <p class="description">
                Pastikan informasi akun kamu selalu terbaru.
            </p>

            <form action="<?= site_url('client/profile/update') ?>" method="post">
                <?= csrf_field() ?>

                <div class="profile-field">
                    <label for="nama_lengkap">Nama lengkap</label>
                    <input
                        type="text"
                        id="nama_lengkap"
                        name="nama_lengkap"
                        value="<?= esc($nama) ?>"
                        minlength="3"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="profile-field">
                    <label for="email">Alamat email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= esc($email) ?>"
                        maxlength="150"
                        required
                    >
                    <span class="profile-hint">
                        Email digunakan untuk masuk ke akun.
                    </span>
                </div>

                <div class="profile-field">
                    <label for="no_telepon">Nomor telepon</label>
                    <input
                        type="tel"
                        id="no_telepon"
                        name="no_telepon"
                        value="<?= esc($telepon) ?>"
                        maxlength="20"
                        placeholder="Contoh: 081234567890"
                    >
                </div>

                <div class="profile-field">
                    <label for="alamat">Alamat</label>
                    <textarea
                        id="alamat"
                        name="alamat"
                        placeholder="Masukkan alamat kamu"
                    ><?= esc($alamat) ?></textarea>
                </div>

                <div class="profile-actions">
                    <button class="profile-btn profile-btn-primary" type="submit">
                        <i class="ti ti-device-floppy"></i>
                        Simpan Perubahan
                    </button>

                    <a
                        class="profile-btn profile-btn-secondary"
                        href="<?= site_url('client') ?>"
                    >
                        Batal
                    </a>
                </div>
            </form>
        </section>

        <aside class="profile-card">
            <h3>Ringkasan Akun</h3>
            <p class="description">
                Informasi akun yang sedang digunakan.
            </p>

            <div class="profile-info-row">
                <div class="profile-info-icon">
                    <i class="ti ti-user"></i>
                </div>
                <div>
                    <div class="profile-info-label">Nama pengguna</div>
                    <div class="profile-info-value"><?= esc($nama) ?></div>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-icon">
                    <i class="ti ti-mail"></i>
                </div>
                <div>
                    <div class="profile-info-label">Email</div>
                    <div class="profile-info-value"><?= esc($email) ?></div>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-icon">
                    <i class="ti ti-phone"></i>
                </div>
                <div>
                    <div class="profile-info-label">Nomor telepon</div>
                    <div class="profile-info-value">
                        <?= esc($telepon !== '' ? $telepon : 'Belum diisi') ?>
                    </div>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-icon">
                    <i class="ti ti-map-pin"></i>
                </div>
                <div>
                    <div class="profile-info-label">Alamat</div>
                    <div class="profile-info-value">
                        <?= esc($alamat !== '' ? $alamat : 'Belum diisi') ?>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<?= $this->endSection() ?>
