<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>
<div class="dashboard-header">
    <h1>Dashboard Admin</h1>
    <p>Kelola pengguna dan pantau aktivitas Counseling Platform.</p>
</div>
<div class="stats-grid">
    <div class="stat-card"><span>Total Pengguna</span>
        <h2><?= esc((string) ($totalUsers ?? 0)) ?></h2>
    </div>
    <div class="stat-card"><span>Total Psikolog</span>
        <h2><?= esc((string) ($totalPsychologists ?? 0)) ?></h2>
    </div>
    <div class="stat-card"><span>Permintaan Konsultasi</span>
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
<?= $this->endSection() ?>