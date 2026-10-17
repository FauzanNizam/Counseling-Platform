<?php
$nama        = old('nama_lengkap', $user['nama_lengkap'] ?? '');
$email       = old('email', $user['email'] ?? '');
$telepon     = old('no_telepon', $user['no_telepon'] ?? '');
$alamat      = old('alamat', $user['alamat'] ?? '');
$statusAkun  = $user['status_akun'] ?? 'aktif';

$noStr       = old('no_str', $profile['no_str'] ?? '');
$spesialisasi = old('spesialisasi', $profile['spesialisasi'] ?? '');
$pengalaman  = old('pengalaman_tahun', $profile['pengalaman_tahun'] ?? 0);
$statusVerif = strtolower((string) ($profile['status_verifikasi'] ?? 'pending'));

// Inisial untuk avatar
$parts = preg_split('/\s+/', trim((string) $nama));
$initials = '';
foreach (array_slice($parts ?: [], 0, 2) as $part) {
    if ($part !== '') {
        $initials .= mb_substr($part, 0, 1);
    }
}
$initials = strtoupper($initials ?: 'PS');
?>

<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>

<?= $this->section('content') ?>

<style>
    .psy-profile-container {
        max-width: 1100px;
        margin: 0 auto;
        color: #242234;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .psy-profile-header {
        margin-bottom: 24px;
    }

    .psy-profile-header h1 {
        font-size: 26px;
        font-weight: 700;
        color: #3b1678;
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .psy-profile-header p {
        color: #716e82;
        font-size: 14px;
        margin: 0;
    }

    /* BANNER HERO */
    .psy-banner {
        background: linear-gradient(135deg, #3b1678 0%, #5b21b6 50%, #7c3aed 100%);
        color: white;
        border-radius: 18px;
        padding: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 26px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(91, 33, 182, 0.25);
    }

    .psy-banner::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -60px;
        top: -90px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
        pointer-events: none;
    }

    .psy-banner-left {
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    .psy-avatar-circle {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.18);
        border: 2px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        font-weight: 700;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }

    .psy-banner-info h2 {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #ffffff;
    }

    .psy-banner-info p {
        margin: 0 0 10px 0;
        color: rgba(255, 255, 255, 0.85);
        font-size: 13px;
    }

    .psy-badge-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .psy-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .psy-badge-role {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        backdrop-filter: blur(4px);
    }

    .psy-badge-verif-verified {
        background: #10b981;
        color: #ffffff;
    }

    .psy-badge-verif-pending {
        background: #f59e0b;
        color: #ffffff;
    }

    .psy-badge-verif-rejected {
        background: #ef4444;
        color: #ffffff;
    }

    /* FLASH ALERTS */
    .psy-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 22px;
        font-size: 14px;
        font-weight: 500;
    }

    .psy-alert-success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .psy-alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* GRID UTAMA */
    .psy-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 24px;
        align-items: start;
    }

    /* CARD STYLE */
    .psy-card {
        background: #ffffff;
        border: 1px solid #ece8f3;
        border-radius: 16px;
        padding: 26px;
        box-shadow: 0 4px 12px rgba(59, 22, 120, 0.03);
        margin-bottom: 24px;
    }

    .psy-card:last-child {
        margin-bottom: 0;
    }

    .psy-card-header {
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f2eff7;
    }

    .psy-card-header h2,
    .psy-card-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: #3b1678;
        margin: 0 0 4px 0;
    }

    .psy-card-header p {
        font-size: 13px;
        color: #716e82;
        margin: 0;
    }

    .psy-section-divider {
        margin: 24px 0 20px 0;
        padding-top: 20px;
        border-top: 1px dashed #e5e0ee;
    }

    .psy-section-divider h4 {
        font-size: 14px;
        font-weight: 700;
        color: #5b21b6;
        margin: 0 0 4px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .psy-section-divider p {
        font-size: 12px;
        color: #8b889b;
        margin: 0;
    }

    /* FORM FIELDS */
    .psy-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .psy-field {
        margin-bottom: 18px;
    }

    .psy-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #3e3a4e;
        margin-bottom: 7px;
    }

    .psy-field label .req {
        color: #dc2626;
        margin-left: 2px;
    }

    .psy-field input,
    .psy-field textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #dcd7e7;
        border-radius: 9px;
        background: #ffffff;
        color: #242234;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .psy-field input:focus,
    .psy-field textarea:focus {
        outline: none;
        border-color: #6d28d9;
        box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.12);
    }

    .psy-field textarea {
        min-height: 90px;
        resize: vertical;
    }

    .psy-field-hint {
        display: block;
        font-size: 11px;
        color: #8b889b;
        margin-top: 6px;
    }

    .psy-btn-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
        padding-top: 8px;
    }

    .psy-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
    }

    .psy-btn-primary {
        background: #5b21b6;
        color: #ffffff;
    }

    .psy-btn-primary:hover {
        background: #4c1d95;
        box-shadow: 0 4px 12px rgba(91, 33, 182, 0.2);
    }

    .psy-btn-secondary {
        background: #f7f5fb;
        color: #4b465c;
        border: 1px solid #ddd7e8;
    }

    .psy-btn-secondary:hover {
        background: #eeeaf6;
    }

    /* SIDEBAR INFO ROWS */
    .psy-info-row {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 13px 0;
        border-bottom: 1px solid #f4f1f8;
    }

    .psy-info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .psy-info-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f4effe;
        color: #6d28d9;
        font-size: 16px;
        flex-shrink: 0;
    }

    .psy-info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8b889b;
        margin-bottom: 3px;
        font-weight: 600;
    }

    .psy-info-value {
        font-size: 13px;
        font-weight: 600;
        color: #242234;
        word-break: break-word;
    }

    .psy-verif-box {
        padding: 14px;
        border-radius: 12px;
        margin-top: 14px;
        font-size: 12px;
        line-height: 1.6;
    }

    .psy-verif-box.box-verified {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    .psy-verif-box.box-pending {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
    }

    .psy-verif-box.box-rejected {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .psy-verif-box strong {
        display: block;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .psy-tips-card {
        background: #faf8fd;
        border: 1px dashed #cfc5e2;
        border-radius: 14px;
        padding: 18px;
    }

    .psy-tips-card h4 {
        margin: 0 0 8px 0;
        font-size: 13px;
        font-weight: 700;
        color: #5b21b6;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .psy-tips-card ul {
        margin: 0;
        padding-left: 18px;
        font-size: 12px;
        color: #635f73;
        line-height: 1.6;
    }

    .psy-tips-card li {
        margin-bottom: 6px;
    }

    .psy-tips-card li:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 860px) {
        .psy-grid {
            grid-template-columns: 1fr;
        }

        .psy-field-row {
            grid-template-columns: 1fr;
        }

        .psy-banner {
            flex-direction: column;
            align-items: flex-start;
            padding: 22px;
        }
    }
</style>

<div class="psy-profile-container">

    <!-- HEADER TITLE -->
    <div class="psy-profile-header">
        <h1>Profil Psikolog / Konsultan</h1>
        <p>Kelola data pribadi, informasi kredensial Surat Tanda Registrasi (STR), dan keamanan akun Anda.</p>
    </div>

    <!-- BANNER HERO -->
    <div class="psy-banner">
        <div class="psy-banner-left">
            <div class="psy-avatar-circle"><?= esc($initials) ?></div>
            <div class="psy-banner-info">
                <h2><?= esc($nama) ?></h2>
                <p><?= esc($email) ?></p>
                <div class="psy-badge-group">
                    <span class="psy-badge psy-badge-role">
                        <span>●</span> Psikolog Terdaftar
                    </span>

                    <?php if ($statusVerif === 'verified'): ?>
                        <span class="psy-badge psy-badge-verif-verified">
                            <span>✓</span> Terverifikasi Resmi
                        </span>
                    <?php elseif ($statusVerif === 'rejected'): ?>
                        <span class="psy-badge psy-badge-verif-rejected">
                            <span>✕</span> Verifikasi Ditolak
                        </span>
                    <?php else: ?>
                        <span class="psy-badge psy-badge-verif-pending">
                            <span>⏳</span> Menunggu Verifikasi Admin
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if ($success = session()->getFlashdata('success')): ?>
        <div class="psy-alert psy-alert-success" role="alert">
            <span style="font-size: 18px;">✓</span>
            <div><?= esc($success) ?></div>
        </div>
    <?php endif; ?>

    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="psy-alert psy-alert-error" role="alert">
            <span style="font-size: 18px;">⚠</span>
            <div><?= esc($error) ?></div>
        </div>
    <?php endif; ?>

    <!-- MAIN TWO COLUMN GRID -->
    <div class="psy-grid">

        <!-- KOLOM UTAMA (FORMULIR DATA PRIBADI & KREDENSIAL) -->
        <div class="psy-main-col">

            <!-- FORM PROFIL & KREDENSIAL -->
            <div class="psy-card">
                <div class="psy-card-header">
                    <h2>Data Pribadi & Kredensial Profesional</h2>
                    <p>Informasi ini ditampilkan kepada klien yang mencari psikolog/konsultan di platform ini.</p>
                </div>

                <form action="<?= site_url('psychologist/profile/update') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- SECTION 1: DATA PERSONAL -->
                    <div class="psy-field">
                        <label for="nama_lengkap">Nama Lengkap & Gelar <span class="req">*</span></label>
                        <input
                            type="text"
                            id="nama_lengkap"
                            name="nama_lengkap"
                            value="<?= esc($nama) ?>"
                            minlength="3"
                            maxlength="100"
                            placeholder="Contoh: drg. / Psikolog Rihadatul Aisyah, M.Psi."
                            required
                        >
                        <span class="psy-field-hint">Gunakan nama lengkap beserta gelar profesional Anda.</span>
                    </div>

                    <div class="psy-field-row">
                        <div class="psy-field">
                            <label for="email">Alamat Email <span class="req">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= esc($email) ?>"
                                maxlength="150"
                                placeholder="nama@email.com"
                                required
                            >
                            <span class="psy-field-hint">Email aktif untuk masuk dan notifikasi janji konseling.</span>
                        </div>

                        <div class="psy-field">
                            <label for="no_telepon">Nomor Telepon / WhatsApp</label>
                            <input
                                type="tel"
                                id="no_telepon"
                                name="no_telepon"
                                value="<?= esc($telepon) ?>"
                                maxlength="20"
                                placeholder="Contoh: 081234567890"
                            >
                            <span class="psy-field-hint">Dapat digunakan untuk komunikasi jadwal konsultasi.</span>
                        </div>
                    </div>

                    <div class="psy-field">
                        <label for="alamat">Alamat Praktik / Domisili</label>
                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="2"
                            placeholder="Masukkan kota domisili atau alamat klinik/praktik Anda"
                        ><?= esc($alamat) ?></textarea>
                    </div>

                    <!-- SECTION 2: KREDENSIAL PROFESIONAL -->
                    <div class="psy-section-divider">
                        <h4>
                            <span>✚</span> Kredensial & Keahlian Psikolog
                        </h4>
                        <p>Pastikan data kredensial sesuai dengan bukti izin praktik resmi Anda.</p>
                    </div>

                    <div class="psy-field-row">
                        <div class="psy-field">
                            <label for="no_str">Nomor STR (Surat Tanda Registrasi)</label>
                            <input
                                type="text"
                                id="no_str"
                                name="no_str"
                                value="<?= esc($noStr) ?>"
                                maxlength="50"
                                placeholder="Contoh: STR-12345678-2024"
                            >
                            <span class="psy-field-hint">Nomor STR resmi Anda dari lembaga/asosiasi psikologi.</span>
                        </div>

                        <div class="psy-field">
                            <label for="pengalaman_tahun">Pengalaman Praktik (Tahun)</label>
                            <input
                                type="number"
                                id="pengalaman_tahun"
                                name="pengalaman_tahun"
                                value="<?= esc($pengalaman) ?>"
                                min="0"
                                max="80"
                                step="1"
                                placeholder="Contoh: 5"
                            >
                            <span class="psy-field-hint">Lama pengalaman praktik klinis atau konseling Anda.</span>
                        </div>
                    </div>

                    <div class="psy-field">
                        <label for="spesialisasi">Spesialisasi & Bidang Fokus</label>
                        <input
                            type="text"
                            id="spesialisasi"
                            name="spesialisasi"
                            value="<?= esc($spesialisasi) ?>"
                            maxlength="100"
                            placeholder="Contoh: Psikologi Klinis Dewasa, Masalah Kecemasan & Depresi"
                        >
                        <span class="psy-field-hint">Spesialisasi topik utama bantuan psikologis yang Anda tangani.</span>
                    </div>

                    <div class="psy-btn-group">
                        <button type="submit" class="psy-btn psy-btn-primary">
                            <span>💾</span> Simpan Perubahan Profil
                        </button>
                        <a href="<?= site_url('psychologist') ?>" class="psy-btn psy-btn-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- FORM GANTI PASSWORD -->
            <div class="psy-card">
                <div class="psy-card-header">
                    <h3>Keamanan Akun & Ubah Password</h3>
                    <p>Perbarui password Anda secara berkala untuk menjaga kerahasiaan akun dan data sesi konseling.</p>
                </div>

                <form action="<?= site_url('psychologist/profile/password') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="psy-field">
                        <label for="current_password">Password Saat Ini <span class="req">*</span></label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            placeholder="Masukkan password Anda saat ini"
                            required
                            autocomplete="current-password"
                        >
                    </div>

                    <div class="psy-field-row">
                        <div class="psy-field">
                            <label for="new_password">Password Baru <span class="req">*</span></label>
                            <input
                                type="password"
                                id="new_password"
                                name="new_password"
                                minlength="8"
                                maxlength="72"
                                placeholder="Minimal 8 karakter"
                                required
                                autocomplete="new-password"
                            >
                        </div>

                        <div class="psy-field">
                            <label for="new_password_confirm">Konfirmasi Password Baru <span class="req">*</span></label>
                            <input
                                type="password"
                                id="new_password_confirm"
                                name="new_password_confirm"
                                minlength="8"
                                maxlength="72"
                                placeholder="Ketik ulang password baru"
                                required
                                autocomplete="new-password"
                            >
                        </div>
                    </div>

                    <div class="psy-btn-group">
                        <button type="submit" class="psy-btn psy-btn-primary">
                            <span>🔒</span> Perbarui Password
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- KOLOM SAMPING (RINGKASAN & STATUS VERIFIKASI) -->
        <div class="psy-side-col">

            <!-- KARTU RINGKASAN PROFIL -->
            <div class="psy-card">
                <div class="psy-card-header">
                    <h3>Ringkasan Kredensial</h3>
                    <p>Status akun dan tampilan publik Anda.</p>
                </div>

                <div class="psy-info-row">
                    <div class="psy-info-icon">👤</div>
                    <div>
                        <div class="psy-info-label">Nama Konsultan</div>
                        <div class="psy-info-value"><?= esc($nama) ?></div>
                    </div>
                </div>

                <div class="psy-info-row">
                    <div class="psy-info-icon">✉</div>
                    <div>
                        <div class="psy-info-label">Email Terdaftar</div>
                        <div class="psy-info-value"><?= esc($email) ?></div>
                    </div>
                </div>

                <div class="psy-info-row">
                    <div class="psy-info-icon">📞</div>
                    <div>
                        <div class="psy-info-label">Kontak / Telepon</div>
                        <div class="psy-info-value"><?= esc($telepon ?: 'Belum diisi') ?></div>
                    </div>
                </div>

                <div class="psy-info-row">
                    <div class="psy-info-icon">📜</div>
                    <div>
                        <div class="psy-info-label">Nomor STR</div>
                        <div class="psy-info-value"><?= esc($noStr ?: 'Belum dilengkapi') ?></div>
                    </div>
                </div>

                <div class="psy-info-row">
                    <div class="psy-info-icon">🎯</div>
                    <div>
                        <div class="psy-info-label">Spesialisasi</div>
                        <div class="psy-info-value"><?= esc($spesialisasi ?: 'Belum ditentukan') ?></div>
                    </div>
                </div>

                <div class="psy-info-row">
                    <div class="psy-info-icon">⏱</div>
                    <div>
                        <div class="psy-info-label">Pengalaman</div>
                        <div class="psy-info-value"><?= (int) $pengalaman ?> Tahun</div>
                    </div>
                </div>

                <div class="psy-info-row">
                    <div class="psy-info-icon">🛡</div>
                    <div>
                        <div class="psy-info-label">Status Akun</div>
                        <div class="psy-info-value" style="color: <?= $statusAkun === 'aktif' ? '#059669' : '#dc2626' ?>;">
                            ● <?= ucfirst(esc($statusAkun)) ?>
                        </div>
                    </div>
                </div>

                <!-- KOTAK STATUS VERIFIKASI -->
                <?php if ($statusVerif === 'verified'): ?>
                    <div class="psy-verif-box box-verified">
                        <strong>✓ Status: Terverifikasi</strong>
                        Data kredensial dan STR Anda telah diverifikasi oleh Administrator. Akun Anda dapat ditemukan oleh klien dan siap menerima janji konseling.
                    </div>
                <?php elseif ($statusVerif === 'rejected'): ?>
                    <div class="psy-verif-box box-rejected">
                        <strong>✕ Status: Verifikasi Ditolak</strong>
                        Kredensial Anda belum disetujui oleh Administrator. Silakan periksa kembali kebenaran Nomor STR atau hubungi pihak admin.
                    </div>
                <?php else: ?>
                    <div class="psy-verif-box box-pending">
                        <strong>⏳ Status: Menunggu Verifikasi</strong>
                        Data profil dan STR Anda sedang ditinjau oleh Administrator platform. Akun Anda akan aktif penuh segera setelah proses verifikasi selesai.
                    </div>
                <?php endif; ?>
            </div>

            <!-- KARTU PANDUAN -->
            <div class="psy-tips-card">
                <h4>
                    <span>💡</span> Tips Profil Konsultan
                </h4>
                <ul>
                    <li>Pastikan Nomor STR valid untuk mempercepat proses verifikasi oleh Admin.</li>
                    <li>Sebutkan spesialisasi secara spesifik agar klien dengan kebutuhan yang sesuai dapat menemukan profil Anda.</li>
                    <li>Nomor kontak yang aktif memudahkan konfirmasi sesi konseling darurat atau pergeseran jadwal.</li>
                </ul>
            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
