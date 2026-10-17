
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>

<?php
$keyword = (string) ($keyword ?? '');
$selectedType = (string) ($selectedType ?? '');
$selectedStatus = (string) ($selectedStatus ?? '');

$consultations = $consultations ?? [];
$statusOptions = $statusOptions ?? [];
$statusSummary = $statusSummary ?? [];

$totalConsultations = (int) ($totalConsultations ?? 0);
$onlineConsultations = (int) ($onlineConsultations ?? 0);
$pickupConsultations = (int) ($pickupConsultations ?? 0);

$flashType = '';
$flashMessage = '';

if ($message = session()->getFlashdata('success')) {
    $flashType = 'success';
    $flashMessage = $message;
} elseif ($message = session()->getFlashdata('error')) {
    $flashType = 'error';
    $flashMessage = $message;
}

$display = static function ($value): string {
    return $value === null || $value === ''
        ? '-'
        : (string) $value;
};

$getDate = static function (array $row): string {
    foreach ([
        'tanggal_konsultasi',
        'tanggal',
        'tanggal_sesi',
        'created_at',
    ] as $column) {
        if (!empty($row[$column])) {
            return (string) $row[$column];
        }
    }

    return '-';
};

$statusClass = static function ($status): string {
    $status = strtolower(trim((string) $status));

    if (in_array($status, [
        'selesai', 'completed', 'berhasil'
    ], true)) {
        return 'consultant-status-aktif';
    }

    if (in_array($status, [
        'dibatalkan', 'batal', 'cancelled', 'ditolak'
    ], true)) {
        return 'consultant-status-rejected';
    }

    return 'consultant-status-pending';
};
?>

<div class="admin-consultations-page">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h1>Pemantauan Konsultasi</h1>
        <p>
            Pantau aktivitas konsultasi antara klien dan psikolog
            melalui Counseling Platform.
        </p>
    </div>

    <!-- NOTIFIKASI -->
    <?php if ($flashMessage !== ''): ?>
        <div
            id="appFlashAlert"
            data-type="<?= esc($flashType, 'attr') ?>"
            data-message="<?= esc($flashMessage, 'attr') ?>"
            hidden
        ></div>
    <?php endif; ?>

    <!-- STATISTIK -->
    <div class="consultation-stats-grid">

        <div class="consultation-stat-card">
            <div class="consultation-stat-icon">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
            </div>

            <div class="consultation-stat-info">
                <div class="consultation-stat-label">
                    Total Konsultasi
                </div>

                <h2 class="consultation-stat-number">
                    <?= number_format($totalConsultations, 0, ',', '.') ?>
                </h2>

                <p class="consultation-stat-description">
                    Seluruh konsultasi yang tercatat
                </p>
            </div>
        </div>

        <div class="consultation-stat-card">
            <div class="consultation-stat-icon">
                <i class="bi bi-camera-video" aria-hidden="true"></i>
            </div>

            <div class="consultation-stat-info">
                <div class="consultation-stat-label">
                    Konsultasi Online
                </div>

                <h2 class="consultation-stat-number">
                    <?= number_format($onlineConsultations, 0, ',', '.') ?>
                </h2>

                <p class="consultation-stat-description">
                    Konsultasi dengan tipe sesi Online
                </p>
            </div>
        </div>

        <div class="consultation-stat-card">
            <div class="consultation-stat-icon">
                <i class="bi bi-geo-alt" aria-hidden="true"></i>
            </div>

            <div class="consultation-stat-info">
                <div class="consultation-stat-label">
                    Pick-up Session
                </div>

                <h2 class="consultation-stat-number">
                    <?= number_format($pickupConsultations, 0, ',', '.') ?>
                </h2>

                <p class="consultation-stat-description">
                    Konsultasi dengan tipe sesi Pick-up
                </p>
            </div>
        </div>

    </div>

    <!-- DAFTAR KONSULTASI -->
    <div class="management-card consultant-page-card consultation-list-card">

        <div class="consultant-list-header">
            <div>
                <h2>Daftar Konsultasi</h2>
                <p>
                    Lihat informasi administratif dan status
                    konsultasi yang tercatat dalam sistem.
                </p>
            </div>
        </div>

        <!-- FILTER -->
        <form
            action="<?= site_url('admin/consultations') ?>"
            method="get"
            class="consultant-filter-form consultation-filter-form"
        >

            <div class="consultant-search consultation-search">
                <label for="keyword" class="consultant-sr-only">
                    Cari Konsultasi
                </label>

                <i class="bi bi-search consultation-input-icon"
                   aria-hidden="true"></i>

                <input
                    type="search"
                    id="keyword"
                    name="keyword"
                    placeholder="Cari nama klien atau psikolog..."
                    value="<?= esc($keyword, 'attr') ?>"
                >
            </div>

            <div class="consultant-filter-select consultation-select-wrap">
                <label for="type" class="consultant-sr-only">
                    Jenis Sesi
                </label>

                <i class="bi bi-camera-video consultation-select-icon"
                   aria-hidden="true"></i>

                <select id="type" name="type">
                    <option value="">Semua jenis sesi</option>

                    <option value="Online"
                        <?= $selectedType === 'Online' ? 'selected' : '' ?>>
                        Online
                    </option>

                    <option value="Pick-up"
                        <?= $selectedType === 'Pick-up' ? 'selected' : '' ?>>
                        Pick-up
                    </option>
                </select>
            </div>

            <div class="consultant-filter-select consultation-select-wrap">
                <label for="status" class="consultant-sr-only">
                    Status Konsultasi
                </label>

                <i class="bi bi-list-ul consultation-select-icon"
                   aria-hidden="true"></i>

                <select id="status" name="status">
                    <option value="">Semua status</option>

                    <?php foreach ($statusOptions as $option): ?>
                        <?php $value = (string) ($option['status'] ?? ''); ?>

                        <?php if ($value !== ''): ?>
                            <option
                                value="<?= esc($value, 'attr') ?>"
                                <?= $selectedStatus === $value ? 'selected' : '' ?>
                            >
                                <?= esc($value) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <button
                type="submit"
                class="consultant-btn consultant-filter-btn"
            >
                <i class="bi bi-funnel" aria-hidden="true"></i>
                Filter
            </button>

            <a
                href="<?= site_url('admin/consultations') ?>"
                class="consultant-btn-secondary consultant-reset-btn"
            >
                <i class="bi bi-arrow-counterclockwise"
                   aria-hidden="true"></i>
                Reset
            </a>
        </form>

        <!-- TABEL -->
        <div class="consultant-table-wrapper">
            <table class="consultant-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Klien</th>
                        <th>Psikolog</th>
                        <th>Jenis Sesi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="consultant-actions-heading">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($consultations)): ?>

                        <?php foreach ($consultations as $consultation): ?>
                            <?php
                            $id = (int) ($consultation['id_konsultasi'] ?? 0);

                            $clientName = $display(
                                $consultation['nama_klien'] ?? null
                            );

                            $psychologistName = $display(
                                $consultation['nama_psikolog'] ?? null
                            );

                            $type = $display(
                                $consultation['tipe_sesi'] ?? null
                            );

                            $date = $getDate($consultation);

                            $status = $display(
                                $consultation['status'] ?? null
                            );

                            $badgeClass = $statusClass($status);
                            ?>

                            <tr>
                                <td>
                                    <span class="consultant-name">
                                        #<?= esc((string) $id) ?>
                                    </span>
                                </td>

                                <td><?= esc($clientName) ?></td>
                                <td><?= esc($psychologistName) ?></td>
                                <td><?= esc($type) ?></td>
                                <td><?= esc($date) ?></td>

                                <td>
                                    <span class="consultant-status <?= esc($badgeClass, 'attr') ?>">
                                        <?= esc($status) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="consultant-table-actions">
                                        <a
                                            href="<?= site_url('admin/consultations/' . $id) ?>"
                                            class="consultant-action consultant-action-detail"
                                            title="Lihat detail konsultasi"
                                        >
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="consultant-empty">
                                Belum ada data konsultasi yang sesuai
                                dengan pencarian atau filter.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- RINGKASAN STATUS -->
    <?php if (!empty($statusSummary)): ?>

        <div class="management-card consultant-page-card consultation-summary-card">

            <div class="consultant-form-header">
                <h2>
                    <i class="bi bi-bar-chart" aria-hidden="true"></i>
                    Ringkasan Status Konsultasi
                </h2>

                <p>
                    Jumlah konsultasi berdasarkan status
                    yang tercatat dalam sistem.
                </p>
            </div>

            <div class="row g-3">
                <?php foreach ($statusSummary as $item): ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="consultation-summary-item h-100">

                            <div class="consultation-summary-label">
                                <?= esc((string) (
                                    $item['status'] ?? 'Tidak diketahui'
                                )) ?>
                            </div>

                            <h3 class="consultation-summary-number">
                                <?= number_format(
                                    (int) ($item['total'] ?? 0),
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </h3>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>
