
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>

<?php
$id = (int) $consultant['id_user'];

$statusLabels = [
    'aktif' => 'Aktif',
    'nonaktif' => 'Nonaktif',
    'ditangguhkan' => 'Ditangguhkan',
];

$verificationLabels = [
    'pending' => 'Pending',
    'verified' => 'Verified',
    'rejected' => 'Rejected',
];

$accountStatus = (string) ($consultant['status_akun'] ?? 'nonaktif');
$verificationStatus = (string) ($consultant['status_verifikasi'] ?? 'pending');
$hasProfile = !empty($consultant['id_profil']);

$displayValue = static function ($key) use ($consultant) {
    $value = $consultant[$key] ?? null;

    return ($value === null || $value === '')
        ? '-'
        : (string) $value;
};
?>

<!-- HEADER -->
<div class="dashboard-header">
    <h1>Detail Konsultan</h1>
    <p>Lihat informasi akun dan profil profesional konsultan.</p>
</div>

<!-- POPUP NOTIFIKASI -->
<?php
$flashType = null;
$flashMessage = null;

if ($message = session()->getFlashdata('success')) {
    $flashType = 'success';
    $flashMessage = $message;
} elseif ($message = session()->getFlashdata('error')) {
    $flashType = 'error';
    $flashMessage = $message;
}
?>

<?php if ($flashMessage !== null): ?>
    <div
        id="consultantFlashAlert"
        data-type="<?= esc($flashType, 'attr') ?>"
        data-message="<?= esc($flashMessage, 'attr') ?>"
        hidden
    ></div>
<?php endif; ?>

<!-- KARTU DETAIL -->
<div class="management-card consultant-page-card">

    <div class="consultant-detail-header">
        <div>
            <h2>Informasi Konsultan</h2>
            <p>Data identitas, akun, dan informasi profesional.</p>
        </div>

        <div class="consultant-detail-actions">
            <a
                href="<?= site_url('admin/consultants/' . $id . '/edit') ?>"
                class="consultant-btn"
            >
                Edit Konsultan
            </a>

            <a
                href="<?= site_url('admin/consultants') ?>"
                class="consultant-btn-secondary"
            >
                Kembali
            </a>
        </div>
    </div>

    <!-- INFORMASI AKUN -->
    <div class="consultant-detail-section">
        <h3>Informasi Akun</h3>

        <div class="consultant-detail-grid">

            <div class="consultant-detail">
                <span>Nama Lengkap</span>
                <strong><?= esc($displayValue('nama_lengkap')) ?></strong>
            </div>

            <div class="consultant-detail">
                <span>Alamat Email</span>
                <strong><?= esc($displayValue('email')) ?></strong>
            </div>

            <div class="consultant-detail">
                <span>Nomor Telepon</span>
                <strong><?= esc($displayValue('no_telepon')) ?></strong>
            </div>

            <div class="consultant-detail">
                <span>Status Akun</span>
                <div>
                    <span class="consultant-status consultant-status-<?= esc($accountStatus, 'attr') ?>">
                        <?= esc($statusLabels[$accountStatus] ?? $accountStatus) ?>
                    </span>
                </div>
            </div>

            <div class="consultant-detail consultant-detail-full">
                <span>Alamat</span>
                <strong><?= esc($displayValue('alamat')) ?></strong>
            </div>

        </div>
    </div>

    <!-- INFORMASI PROFESIONAL -->
    <div class="consultant-detail-section">
        <h3>Informasi Profesional</h3>

        <div class="consultant-detail-grid">

            <div class="consultant-detail">
                <span>Nomor STR</span>
                <strong><?= esc($displayValue('no_str')) ?></strong>
            </div>

            <div class="consultant-detail">
                <span>Spesialisasi</span>
                <strong><?= esc($displayValue('spesialisasi')) ?></strong>
            </div>

            <div class="consultant-detail">
                <span>Pengalaman</span>
                <strong>
                    <?= esc($displayValue('pengalaman_tahun')) ?>
                    <?= $displayValue('pengalaman_tahun') !== '-' ? 'tahun' : '' ?>
                </strong>
            </div>

            <div class="consultant-detail">
                <span>Status Verifikasi</span>
                <div>
                    <?php if (!$hasProfile): ?>
                        <span class="consultant-status consultant-status-missing">
                            Belum ada profil
                        </span>
                    <?php else: ?>
                        <span class="consultant-status consultant-status-<?= esc($verificationStatus, 'attr') ?>">
                            <?= esc($verificationLabels[$verificationStatus] ?? 'Pending') ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- KARTU VERIFIKASI -->
<div class="management-card consultant-page-card consultant-verification-card">

    <div class="consultant-form-header">
        <h2>Verifikasi Manual</h2>
        <p>
            Periksa nomor STR dan informasi profesional sebelum
            menentukan status verifikasi konsultan.
        </p>
    </div>

    <div class="consultant-note consultant-verification-note">
        <strong>Perhatian</strong>
        <p>
            Status Verified tidak otomatis mengaktifkan akun.
            Setelah diverifikasi, Admin dapat mengaktifkan
            akun melalui halaman Edit Konsultan.
        </p>
    </div>

    <?php if (!$hasProfile): ?>

        <div class="consultant-note">
            <strong>Profil Belum Tersedia</strong>
            <p>
                Lengkapi profil konsultan melalui halaman Edit
                sebelum melakukan verifikasi.
            </p>
        </div>

        <div class="consultant-form-actions">
            <a
                href="<?= site_url('admin/consultants/' . $id . '/edit') ?>"
                class="consultant-btn"
            >
                Lengkapi Profil
            </a>
        </div>

    <?php else: ?>

        <form
            action="<?= site_url('admin/consultants/' . $id . '/verify') ?>"
            method="post"
            class="consultant-verify-form"
            onsubmit="return false;"
        >
            <?= csrf_field() ?>

            <div class="consultant-field">
                <label for="decision">Keputusan Verifikasi</label>

                <select id="decision" name="decision">
                    <?php foreach ($verificationLabels as $value => $label): ?>
                        <option
                            value="<?= esc($value, 'attr') ?>"
                            <?= $verificationStatus === $value ? 'selected' : '' ?>
                        >
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="consultant-form-actions">
                <button type="submit" class="consultant-btn">
                    Simpan Keputusan
                </button>
            </div>
        </form>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>
