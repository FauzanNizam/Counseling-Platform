<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>
<?= $this->section('content') ?>
<div class="dashboard-header">
    <h1>Dashboard Psikolog</h1>
    <p>Kelola jadwal dan pantau aktivitas konsultasimu.</p>
</div>
<div class="stats-grid">
    <div class="stat-card"><span>Total Konsultasi</span>
        <h2>0</h2>
    </div>
    <div class="stat-card"><span>Jadwal Hari Ini</span>
        <h2>0</h2>
    </div>
    <div class="stat-card"><span>Menunggu Konfirmasi</span>
        <h2>0</h2>
    </div>
</div>
<div class="schedule-card">
    <h2>Jadwal Konsultasi</h2>
    <p>Belum ada jadwal konsultasi untuk ditampilkan.</p>
</div>
<?= $this->endSection() ?>