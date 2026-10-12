<?= $this->extend('layouts/psychologist') ?>

<?= $this->section('content') ?>

<div class="dashboard-header">
    <h1>Dashboard Psikolog</h1>
    <p>Kelola jadwal dan pantau aktivitas konsultasimu.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <span>Total Konsultasi</span>
        <h2>0</h2>
    </div>

    <div class="stat-card">
        <span>Jadwal Hari Ini</span>
        <h2>0</h2>
    </div>

    <div class="stat-card">
        <span>Menunggu Konfirmasi</span>
        <h2>0</h2>
    </div>
</div>

<div class="schedule-card">
    <h2>Jadwal Konsultasi</h2>
    <p>Belum ada jadwal konsultasi untuk ditampilkan.</p>
</div>

<style>
    .dashboard-header {
        margin-bottom: 28px;
    }

    .dashboard-header h1 {
        font-size: 26px;
        color: #3b1678;
        margin-bottom: 8px;
    }

    .dashboard-header p {
        color: #817b8d;
        font-size: 14px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card,
    .schedule-card {
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

    .schedule-card h2 {
        margin-bottom: 18px;
        font-size: 18px;
        color: #3b1678;
    }

    .schedule-card p {
        color: #817b8d;
        font-size: 14px;
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
