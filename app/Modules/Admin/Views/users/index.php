<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>

<?= $this->section('content') ?>

<?php
// Ambil pesan dari controller
$successMessage = session()->getFlashdata('success');
$errorMessage = session()->getFlashdata('error');

// Tentukan popup yang ditampilkan
$flashType = '';
$flashMessage = '';

if ($errorMessage) {
    $flashType = 'error';
    $flashMessage = $errorMessage;
} elseif ($successMessage) {
    $flashType = 'success';
    $flashMessage = $successMessage;
}

// Label role pengguna
$roleLabels = [
    'klien' => 'Klien',
    'konsultan' => 'Konsultan / Psikolog',
    'administrator' => 'Administrator',
];
?>

<!-- HEADER HALAMAN -->
<div class="dashboard-header">
    <h1>Kelola Data Pengguna</h1>
    <p>Lihat dan kelola akun pengguna Counseling Platform.</p>
</div>

<div class="users-panel">

    <!-- HEADER PANEL -->
    <div class="users-panel-header">

        <div>
            <h2>Daftar Pengguna</h2>

            <span>
                <?= count($users) ?> pengguna pada halaman ini
            </span>
        </div>

        <a href="<?= site_url('admin/users/create') ?>" class="users-filter-button">
            + Tambah Pengguna
        </a>

    </div>

    <!-- POPUP PESAN SUKSES / ERROR -->
    <?php if ($flashMessage !== ''): ?>
        <div id="appFlashAlert" data-type="<?= esc($flashType, 'attr') ?>" data-message="<?= esc($flashMessage, 'attr') ?>"
            hidden></div>
    <?php endif; ?>

    <!-- FORM PENCARIAN DAN FILTER -->
    <form action="<?= site_url('admin/users') ?>" method="get" class="users-filters">

        <!-- PENCARIAN -->
        <input type="search" name="keyword" placeholder="Cari nama atau email..." value="<?= esc($keyword ?? '') ?>">

        <!-- FILTER ROLE -->
        <select name="role">

            <option value="">Semua Role</option>

            <option value="klien" <?= ($selectedRole ?? '') === 'klien' ? 'selected' : '' ?>>
                Klien
            </option>

            <option value="konsultan" <?= ($selectedRole ?? '') === 'konsultan' ? 'selected' : '' ?>>
                Konsultan
            </option>

            <option value="administrator" <?= ($selectedRole ?? '') === 'administrator' ? 'selected' : '' ?>>
                Administrator
            </option>

        </select>

        <!-- FILTER STATUS -->
        <select name="status">

            <option value="">Semua Status</option>

            <option value="aktif" <?= ($selectedStatus ?? '') === 'aktif' ? 'selected' : '' ?>>
                Aktif
            </option>

            <option value="nonaktif" <?= ($selectedStatus ?? '') === 'nonaktif' ? 'selected' : '' ?>>
                Nonaktif
            </option>

            <option value="ditangguhkan" <?= ($selectedStatus ?? '') === 'ditangguhkan' ? 'selected' : '' ?>>
                Ditangguhkan
            </option>

        </select>

        <!-- TOMBOL FILTER -->
        <button type="submit" class="users-filter-button">
            Cari / Filter
        </button>

        <!-- TOMBOL RESET -->
        <a href="<?= site_url('admin/users') ?>" class="users-reset-button">
            Reset
        </a>

    </form>

    <!-- TABEL DAFTAR PENGGUNA -->
    <div class="users-table-wrapper">

        <table class="users-table">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($users)): ?>

                    <!-- JIKA DATA KOSONG -->
                    <tr>
                        <td colspan="7" class="users-empty">
                            Belum ada pengguna yang sesuai.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php
                    // Penomoran sesuai halaman pagination
                    $currentPage = $pager->getCurrentPage('users');
                    $number = (($currentPage - 1) * 10) + 1;
                    ?>

                    <?php foreach ($users as $user): ?>

                        <?php
                        $role = $user['role'] ?? '';
                        $status = $user['status_akun'] ?? '';

                        $roleLabel = $roleLabels[$role]
                            ?? ucfirst($role);

                        $createdAt = !empty($user['created_at'])
                            ? date('d/m/Y', strtotime($user['created_at']))
                            : '-';
                        ?>

                        <tr>

                            <!-- NOMOR -->
                            <td>
                                <?= $number++ ?>
                            </td>

                            <!-- NAMA LENGKAP -->
                            <td>
                                <?= esc($user['nama_lengkap'] ?? '-') ?>
                            </td>

                            <!-- EMAIL -->
                            <td>
                                <?= esc($user['email'] ?? '-') ?>
                            </td>

                            <!-- ROLE -->
                            <td>
                                <?= esc($roleLabel) ?>
                            </td>

                            <!-- STATUS -->
                            <td>
                                <span class="users-status users-status-<?= esc($status, 'attr') ?>">
                                    <?= esc(ucfirst($status)) ?>
                                </span>
                            </td>

                            <!-- TANGGAL PENDAFTARAN -->
                            <td>
                                <?= esc($createdAt) ?>
                            </td>


                            <!-- AKSI: DETAIL, EDIT, DAN HAPUS -->
                            <td>
                                <div class="users-action-buttons">

                                    <!-- TOMBOL DETAIL -->
                                    <a href="<?= site_url('admin/users/' . $user['id_user']) ?>"
                                        class="btn btn-sm btn-outline-primary" title="Lihat detail pengguna">
                                        Detail
                                    </a>

                                    <!-- TOMBOL EDIT -->
                                    <a href="<?= site_url('admin/users/' . $user['id_user'] . '/edit') ?>"
                                        class="btn btn-sm btn-outline-secondary" title="Edit pengguna">
                                        Edit
                                    </a>

                                    <!-- FORM HAPUS -->
                                    <form action="<?= site_url('admin/users/' . $user['id_user'] . '/delete') ?>" method="post"
                                        class="users-delete-form" data-user-name="<?= esc($user['nama_lengkap'], 'attr') ?>">
                                        <?= csrf_field() ?>

                                        <button type="button" class="btn btn-sm btn-outline-danger users-delete-button">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>


                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <?php if ($pager->getPageCount('users') > 1): ?>

        <div class="users-pagination">

            <?= $pager
                ->only(['keyword', 'role', 'status'])
                ->links('users') ?>

        </div>

    <?php endif; ?>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll(
            '.users-delete-button'
        );

        deleteButtons.forEach(function (button) {
            button.addEventListener('click', async function () {
                const form = button.closest('.users-delete-form');

                if (!form) {
                    return;
                }

                const userName = form.dataset.userName || 'pengguna ini';

                // Gunakan popup Bootstrap yang sudah tersedia.
                if (
                    !window.AppAlert ||
                    typeof window.AppAlert.confirm !== 'function'
                ) {
                    console.error('AppAlert.confirm belum tersedia.');
                    return;
                }

                try {
                    const confirmed = await window.AppAlert.confirm(
                        'Apakah Anda yakin ingin menghapus pengguna '
                        + userName
                        + '? Data yang dihapus tidak dapat dikembalikan.',
                        'Konfirmasi Hapus Pengguna'
                    );

                    if (confirmed === true) {
                        button.disabled = true;

                        // Kirim form setelah admin menyetujui.
                        form.requestSubmit();
                    }
                } catch (error) {
                    console.error('Gagal menampilkan konfirmasi:', error);
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>