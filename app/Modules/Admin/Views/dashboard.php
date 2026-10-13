
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>

<?= $this->section('content') ?>

<!-- HEADER DASHBOARD -->
<div class="dashboard-header">
    <h1>Dashboard Admin</h1>
    <p>
        Kelola pengguna dan pantau aktivitas Counseling Platform.
    </p>
</div>

<!-- ========================================
     STATISTIK PENGGUNA BERDASARKAN ROLE
======================================== -->
<div class="stats-grid">

    <!-- TOTAL PENGGUNA -->
    <div class="stat-card">
        <span>Total Pengguna</span>
        <h2><?= esc((string) ($totalUsers ?? 0)) ?></h2>
    </div>

    <!-- TOTAL KLIEN -->
    <div class="stat-card">
        <span>Total Klien</span>
        <h2><?= esc((string) ($totalClients ?? 0)) ?></h2>
    </div>

    <!-- TOTAL PSIKOLOG -->
    <div class="stat-card">
        <span>Total Psikolog</span>
        <h2><?= esc((string) ($totalPsychologists ?? 0)) ?></h2>
    </div>

    <!-- TOTAL ADMINISTRATOR -->
    <div class="stat-card">
        <span>Total Administrator</span>
        <h2><?= esc((string) ($totalAdmins ?? 0)) ?></h2>
    </div>

</div>

<!-- ========================================
     STATISTIK STATUS AKUN
======================================== -->
<div class="dashboard-header" style="margin-top: 28px;">
    <h2 style="font-size: 20px; font-weight: 700;">
        Status Akun Pengguna
    </h2>
    <p>
        Ringkasan status akun yang terdaftar di sistem.
    </p>
</div>

<div class="stats-grid">

    <!-- AKUN AKTIF -->
    <div class="stat-card">
        <span>Akun Aktif</span>
        <h2><?= esc((string) ($totalActive ?? 0)) ?></h2>
    </div>

    <!-- AKUN NONAKTIF -->
    <div class="stat-card">
        <span>Akun Nonaktif</span>
        <h2><?= esc((string) ($totalInactive ?? 0)) ?></h2>
    </div>

    <!-- AKUN DITANGGUHKAN -->
    <div class="stat-card">
        <span>Akun Ditangguhkan</span>
        <h2><?= esc((string) ($totalSuspended ?? 0)) ?></h2>
    </div>

</div>

<!-- ========================================
     MANAJEMEN PENGGUNA
======================================== -->
<div class="management-card">

    <h2>Manajemen Pengguna</h2>

    <p>
        Kelola data klien, psikolog, dan administrator
        yang terdaftar di Counseling Platform.
    </p>

    <div class="management-actions">

        <!-- MENUJU DAFTAR KLIEN -->
        <a
            href="<?= site_url('admin/users?role=klien') ?>"
            class="dashboard-management-link"
        >
            Kelola Klien
        </a>

        <!-- MENUJU DAFTAR PSIKOLOG -->
        <a
            href="<?= site_url('admin/users?role=konsultan') ?>"
            class="dashboard-management-link"
        >
            Kelola Psikolog
        </a>

        <!-- MENUJU SEMUA PENGGUNA -->
        <a
            href="<?= site_url('admin/users') ?>"
            class="dashboard-management-link"
        >
            Semua Pengguna
        </a>

    </div>

</div>

<?= $this->endSection() ?>
