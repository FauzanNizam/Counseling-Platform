
<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="dashboard-header">
    <h1>Dashboard Admin</h1>
    <p>Kelola pengguna dan pantau aktivitas Counseling Platform.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <span>Total Pengguna</span>
        <h2><?= esc((string) ($totalUsers ?? 0)) ?></h2>
    </div>

    <div class="stat-card">
        <span>Total Psikolog</span>
        <h2><?= esc((string) ($totalPsychologists ?? 0)) ?></h2>
    </div>

    <div class="stat-card">
        <span>Permintaan Konsultasi</span>
        <h2>0</h2>
    </div>
</div>

<div class="management-card">
    <h2>Manajemen Pengguna</h2>
    <p>Kelola data klien dan psikolog yang terdaftar di platform.</p>

    <div class="management-actions">
        <button type="button" disabled>Kelola Klien</button>
        <button type="button" disabled>Kelola Psikolog</button>
    </div>
</div>

<style>
    .dashboard-header {
        margin-bottom: 28px;
    }

    .dashboard-header h1 {
        margin-bottom: 8px;
        color: #3b1678;
        font-size: 26px;
    }

    .dashboard-header p,
    .management-card p {
        color: #817b8d;
        font-size: 14px;
        line-height: 1.6;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card,
    .management-card {
        padding: 24px;
        background: #fff;
        border: 1px solid #eeeaf3;
        border-radius: 14px;
    }

    .stat-card span {
        color: #817b8d;
        font-size: 13px;
    }

    .stat-card h2 {
        margin-top: 12px;
        color: #3b1678;
        font-size: 30px;
    }

    .management-card h2 {
        margin-bottom: 10px;
        color: #3b1678;
        font-size: 18px;
    }

    .management-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }

    .management-actions button {
        padding: 11px 16px;
        border: none;
        border-radius: 9px;
        color: white;
        background: #6d28d9;
        cursor: not-allowed;
        opacity: 0.6;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-header h1 {
            font-size: 22px;
        }
    }
</style>

<?= $this->endSection() ?>
