
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>

<?= $this->section('content') ?>

<?php
    // Label role yang mudah dibaca.
    $roleLabels = [
        'klien'         => 'Klien',
        'konsultan'     => 'Konsultan / Psikolog',
        'administrator' => 'Administrator',
    ];

    $statusLabels = [
        'aktif'        => 'Aktif',
        'nonaktif'     => 'Nonaktif',
        'ditangguhkan' => 'Ditangguhkan',
    ];

    $role = $user['role'] ?? '';
    $status = $user['status_akun'] ?? '';

    $roleLabel = $roleLabels[$role] ?? 'Tidak diketahui';
    $statusLabel = $statusLabels[$status] ?? 'Tidak diketahui';

    $createdAt = ! empty($user['created_at'])
        ? date('d-m-Y H:i', strtotime($user['created_at']))
        : '-';

    $updatedAt = ! empty($user['updated_at'])
        ? date('d-m-Y H:i', strtotime($user['updated_at']))
        : '-';
?>

<div class="dashboard-header">
    <h1>Detail Pengguna</h1>
    <p>Lihat informasi lengkap akun pengguna Counseling Platform.</p>
</div>

<div class="users-panel">

    <div class="users-panel-header">
        <div>
            <h2>Informasi Pengguna</h2>
            <span>
                ID Pengguna:
                #<?= esc($user['id_user']) ?>
            </span>
        </div>

        <a
            href="<?= site_url('admin/users') ?>"
            class="users-reset-button"
        >
            Kembali
        </a>
    </div>

    <div class="user-detail-profile">

        <div class="user-detail-avatar">
            <?= esc(strtoupper(substr($user['nama_lengkap'] ?? 'P', 0, 1))) ?>
        </div>

        <div class="user-detail-identity">
            <h3><?= esc($user['nama_lengkap'] ?? '-') ?></h3>
            <p><?= esc($user['email'] ?? '-') ?></p>

            <div class="user-detail-badges">
                <span class="user-detail-role">
                    <?= esc($roleLabel) ?>
                </span>

                <span class="user-detail-status status-<?= esc($status, 'attr') ?>">
                    <?= esc($statusLabel) ?>
                </span>
            </div>
        </div>

    </div>

    <div class="user-detail-section">
        <h3>Data Pribadi</h3>

        <div class="user-detail-grid">

            <div class="user-detail-item">
                <span class="user-detail-label">Nama Lengkap</span>
                <strong><?= esc($user['nama_lengkap'] ?? '-') ?></strong>
            </div>

            <div class="user-detail-item">
                <span class="user-detail-label">Email</span>
                <strong><?= esc($user['email'] ?? '-') ?></strong>
            </div>

            <div class="user-detail-item">
                <span class="user-detail-label">Nomor Telepon</span>
                <strong>
                    <?= esc($user['no_telepon'] ?: '-') ?>
                </strong>
            </div>

            <div class="user-detail-item">
                <span class="user-detail-label">Role Pengguna</span>
                <strong><?= esc($roleLabel) ?></strong>
            </div>

            <div class="user-detail-item user-detail-full">
                <span class="user-detail-label">Alamat</span>
                <strong>
                    <?= nl2br(esc($user['alamat'] ?: '-')) ?>
                </strong>
            </div>

        </div>
    </div>

    <div class="user-detail-section">
        <h3>Informasi Akun</h3>

        <div class="user-detail-grid">

            <div class="user-detail-item">
                <span class="user-detail-label">Status Akun</span>
                <strong><?= esc($statusLabel) ?></strong>
            </div>

            <div class="user-detail-item">
                <span class="user-detail-label">ID Pengguna</span>
                <strong>#<?= esc($user['id_user']) ?></strong>
            </div>

            <div class="user-detail-item">
                <span class="user-detail-label">Tanggal Pendaftaran</span>
                <strong><?= esc($createdAt) ?></strong>
            </div>

            <div class="user-detail-item">
                <span class="user-detail-label">Terakhir Diperbarui</span>
                <strong><?= esc($updatedAt) ?></strong>
            </div>

        </div>
    </div>

    <div class="users-form-actions">
        <a
            href="<?= site_url('admin/users') ?>"
            class="users-reset-button"
        >
            Kembali ke Daftar
        </a>
    </div>

</div>

<?= $this->endSection() ?>
