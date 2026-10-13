
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>

<?php
$keyword = (string) ($keyword ?? '');
$statusFilter = (string) ($selectedStatus ?? '');
$verificationFilter = (string) ($selectedVerification ?? '');

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

<!-- HEADER -->
<div class="dashboard-header">
    <h1>Manajemen Konsultan</h1>
    <p>
        Kelola akun dan profil konsultan yang telah
        direkrut oleh Counseling Platform.
    </p>
</div>

<!-- POPUP SUKSES / ERROR -->
<?php if ($flashMessage !== null): ?>
    <div
        id="consultantFlashAlert"
        data-type="<?= esc($flashType, 'attr') ?>"
        data-message="<?= esc($flashMessage, 'attr') ?>"
        hidden
    ></div>
<?php endif; ?>

<!-- KARTU UTAMA -->
<div class="management-card consultant-page-card">

    <div class="consultant-list-header">
        <div>
            <h2>Daftar Konsultan</h2>
            <p>
                Kelola informasi akun, profil profesional,
                dan status verifikasi konsultan.
            </p>
        </div>

        <a
            href="<?= site_url('admin/consultants/create') ?>"
            class="consultant-btn consultant-add-btn"
        >
            <span aria-hidden="true">+</span>
            Tambah Konsultan
        </a>
    </div>

    <!-- FILTER -->
    <form
        action="<?= site_url('admin/consultants') ?>"
        method="get"
        class="consultant-filter-form"
    >

        <div class="consultant-search">
            <label for="keyword" class="consultant-sr-only">
                Cari Konsultan
            </label>

            <input
                type="search"
                id="keyword"
                name="keyword"
                placeholder="Cari nama atau email konsultan..."
                value="<?= esc($keyword, 'attr') ?>"
            >
        </div>

        <div class="consultant-filter-select">
            <label for="status_akun" class="consultant-sr-only">
                Filter Status Akun
            </label>

            <select id="status_akun" name="status">
                <option value="">Semua status akun</option>

                <?php foreach ($statusLabels as $value => $label): ?>
                    <option
                        value="<?= esc($value, 'attr') ?>"
                        <?= $statusFilter === $value ? 'selected' : '' ?>
                    >
                        <?= esc($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="consultant-filter-select">
            <label for="status_verifikasi" class="consultant-sr-only">
                Filter Status Verifikasi
            </label>

            <select id="status_verifikasi" name="verification">
                <option value="">Semua verifikasi</option>

                <?php foreach ($verificationLabels as $value => $label): ?>
                    <option
                        value="<?= esc($value, 'attr') ?>"
                        <?= $verificationFilter === $value ? 'selected' : '' ?>
                    >
                        <?= esc($label) ?>
                    </option>
                <?php endforeach; ?>

                <option
                    value="missing"
                    <?= $verificationFilter === 'missing' ? 'selected' : '' ?>
                >
                    Belum ada profil
                </option>
            </select>
        </div>

        <button
            type="submit"
            class="consultant-btn consultant-filter-btn"
        >
            Filter
        </button>

        <a
            href="<?= site_url('admin/consultants') ?>"
            class="consultant-btn-secondary consultant-reset-btn"
        >
            Reset
        </a>
    </form>

    <!-- TABEL -->
    <div class="consultant-table-wrapper">
        <table class="consultant-table">
            <thead>
                <tr>
                    <th>Nama Konsultan</th>
                    <th>Email</th>
                    <th>Spesialisasi</th>
                    <th>Status Akun</th>
                    <th>Verifikasi</th>
                    <th class="consultant-actions-heading">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($consultants)): ?>

                    <?php foreach ($consultants as $consultant): ?>
                        <?php
                        $id = (int) $consultant['id_user'];
                        $name = (string) ($consultant['nama_lengkap'] ?? '');
                        $email = (string) ($consultant['email'] ?? '');
                        $specialization = (string) ($consultant['spesialisasi'] ?? '');
                        $accountStatus = (string) ($consultant['status_akun'] ?? '');
                        $verificationStatus = (string) ($consultant['status_verifikasi'] ?? '');
                        $hasProfile = !empty($consultant['id_profil']);
                        ?>

                        <tr>
                            <td>
                                <span class="consultant-name">
                                    <?= esc($name) ?>
                                </span>
                            </td>

                            <td><?= esc($email) ?></td>

                            <td>
                                <?= esc($specialization !== '' ? $specialization : '-') ?>
                            </td>

                            <td>
                                <span
                                    class="consultant-status consultant-status-<?= esc($accountStatus, 'attr') ?>"
                                >
                                    <?= esc($statusLabels[$accountStatus] ?? $accountStatus) ?>
                                </span>
                            </td>

                            <td>
                                <?php if (!$hasProfile): ?>
                                    <span class="consultant-status consultant-status-missing">
                                        Belum ada profil
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="consultant-status consultant-status-<?= esc($verificationStatus, 'attr') ?>"
                                    >
                                        <?= esc($verificationLabels[$verificationStatus] ?? 'Pending') ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="consultant-table-actions">

                                    <a
                                        href="<?= site_url('admin/consultants/' . $id) ?>"
                                        class="consultant-action consultant-action-detail"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="<?= site_url('admin/consultants/' . $id . '/edit') ?>"
                                        class="consultant-action consultant-action-edit"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="<?= site_url('admin/consultants/' . $id . '/delete') ?>"
                                        method="post"
                                        class="consultant-delete-form"
                                        data-consultant-name="<?= esc($name, 'attr') ?>"
                                        onsubmit="return false;"
                                    >
                                        <?= csrf_field() ?>

                                        <button
                                            type="submit"
                                            class="consultant-action consultant-action-delete"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="consultant-empty">
                            Belum ada data konsultan yang sesuai
                            dengan pencarian atau filter.
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pager) && $pager): ?>
        <div class="consultant-pagination">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
