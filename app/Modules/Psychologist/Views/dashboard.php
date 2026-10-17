
<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>

<?= $this->section('content') ?>

<!-- HEADER DASHBOARD -->
<div class="dashboard-header">
    <h1>Dashboard Psikolog</h1>
    <p>Kelola jadwal dan pantau aktivitas konsultasimu.</p>
</div>

<!-- STATISTIK DASHBOARD -->
<div class="stats-grid">

    <div class="stat-card">
        <span>Total Konsultasi</span>
        <h2><?= esc($totalKonsultasi) ?></h2>
    </div>

    <div class="stat-card">
        <span>Jadwal Hari Ini</span>
        <h2><?= esc($jadwalHariIni) ?></h2>
    </div>

    <div class="stat-card">
        <span>Menunggu Konfirmasi</span>
        <h2><?= esc($menungguKonfirmasi) ?></h2>
    </div>

</div>

<!-- JADWAL KONSULTASI MENDATANG -->
<div class="schedule-card psychologist-schedule">

    <!-- HEADER JADWAL -->
    <div class="psychologist-schedule__header">

        <div>
            <h2>Jadwal Konsultasi Mendatang</h2>
            <p>
                Pantau permintaan dan jadwal konsultasi
                terdekat.
            </p>
        </div>

        <span class="psychologist-schedule__count">
            <?= count($jadwalMendatang) ?> Jadwal
        </span>

    </div>

    <!-- KONTEN JADWAL -->
    <?php if (empty($jadwalMendatang)): ?>

        <div class="psychologist-schedule__empty">
            Belum ada jadwal konsultasi mendatang.
        </div>

    <?php else: ?>

        <div class="psychologist-schedule__table-wrap">

            <table class="psychologist-schedule__table">

                <thead>
                    <tr>
                        <th>Klien</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Tipe Sesi</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($jadwalMendatang as $item): ?>

                        <?php
                            $status = $item['status'];

                            $statusClass = match ($status) {
                                'Menunggu Konfirmasi' => 'pending',
                                'Dikonfirmasi' => 'confirmed',
                                'Berlangsung' => 'ongoing',
                                'Selesai' => 'completed',
                                'Dibatalkan' => 'cancelled',
                                default => 'default',
                            };
                        ?>

                        <tr>

                            <!-- NAMA KLIEN -->
                            <td class="psychologist-schedule__client">
                                <?= esc($item['nama_klien']) ?>
                            </td>

                            <!-- TANGGAL -->
                            <td>
                                <?= esc(date(
                                    'd M Y',
                                    strtotime($item['tanggal'])
                                )) ?>
                            </td>

                            <!-- WAKTU -->
                            <td>
                                <?php if (!empty($item['waktu_mulai'])): ?>
                                    <?= esc(substr(
                                        $item['waktu_mulai'],
                                        0,
                                        5
                                    )) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <!-- TIPE SESI -->
                            <td>
                                <?= esc($item['tipe_sesi']) ?>
                            </td>

                            <!-- STATUS -->
                            <td>
                                <span class="psychologist-status psychologist-status--<?= esc($statusClass) ?>">
                                    <?= esc($status) ?>
                                </span>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>
