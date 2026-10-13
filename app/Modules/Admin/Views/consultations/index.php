
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

/**
 * Format nilai agar aman ditampilkan.
 */
$display = static function ($value): string {
    if ($value === null || $value === '') {
        return '-';
    }

    return (string) $value;
};

/**
 * Menampilkan tanggal jika kolom tersedia.
 * Tidak mengasumsikan struktur tanggal tertentu.
 */
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

/**
 * Menentukan kelas badge dari status.
 */
$statusClass = static function ($status): string {
    $status = strtolower(trim((string) $status));

    if (in_array($status, [
        'selesai',
        'completed',
        'berhasil',
    ], true)) {
        return 'consultant-status-aktif';
    }

    if (in_array($status, [
        'dibatalkan',
        'batal',
        'cancelled',
        'ditolak',
    ], true)) {
        return 'consultant-status-rejected';
    }

    return 'consultant-status-pending';
};
?>

<!-- HEADER HALAMAN -->
<div class="dashboard-header">
    <h1>Pemantauan Konsultasi</h1>
    <p>
        Pantau aktivitas konsultasi antara klien dan psikolog
        melalui Counseling Platform.
    </p>
</div>

<!-- POPUP NOTIFIKASI -->
<?php if ($flashMessage !== ''): ?>
    <div
        id="appFlashAlert"
        data-type="<?= esc($flashType, 'attr') ?>"
        data-message="<?= esc($flashMessage, 'attr') ?>"
        hidden
    ></div>
<?php endif; ?>

<!-- RINGKASAN STATISTIK -->
<div class="row g-3 mb-4">

    <div class="col-12 col-md-4">
        <div class="management-card h-100 p-4">
            <div class="text-muted mb-2">
                Total Konsultasi
            </div>

            <h2 class="fw-bold mb-1">
                <?= number_format($totalConsultations, 0, ',', '.') ?>
            </h2>

            <p class="text-muted mb-0">
                Seluruh konsultasi yang tercatat
            </p>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="management-card h-100 p-4">
            <div class="text-muted mb-2">
                Konsultasi Online
            </div>

            <h2 class="fw-bold mb-1">
                <?= number_format($onlineConsultations, 0, ',', '.') ?>
            </h2>

            <p class="text-muted mb-0">
                Konsultasi dengan tipe sesi Online
            </p>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="management-card h-100 p-4">
            <div class="text-muted mb-2">
                Pick-up Session
            </div>

            <h2 class="fw-bold mb-1">
                <?= number_format($pickupConsultations, 0, ',', '.') ?>
            </h2>

            <p class="text-muted mb-0">
                Konsultasi dengan tipe sesi Pick-up
            </p>
        </div>
    </div>

</div>

<!-- DAFTAR KONSULTASI -->
<div class="management-card consultant-page-card">

    <div class="consultant-list-header">
        <div>
            <h2>Daftar Konsultasi</h2>
            <p>
                Lihat informasi administratif dan status
                konsultasi yang tercatat dalam sistem.
            </p>
        </div>
    </div>

    <!-- FORM PENCARIAN DAN FILTER -->
    <form
        action="<?= site_url('admin/consultations') ?>"
        method="get"
        class="consultant-filter-form"
    >

        <!-- CARI NAMA -->
        <div class="consultant-search">
            <label
                for="keyword"
                class="consultant-sr-only"
            >
                Cari Konsultasi
            </label>

            <input
                type="search"
                id="keyword"
                name="keyword"
                placeholder="Cari nama klien atau psikolog..."
                value="<?= esc($keyword, 'attr') ?>"
            >
        </div>

        <!-- FILTER JENIS SESI -->
        <div class="consultant-filter-select">
            <label
                for="type"
                class="consultant-sr-only"
            >
                Jenis Sesi
            </label>

            <select id="type" name="type">
                <option value="">
                    Semua jenis sesi
                </option>

                <option
                    value="Online"
                    <?= $selectedType === 'Online' ? 'selected' : '' ?>
                >
                    Online
                </option>

                <option
                    value="Pick-up"
                    <?= $selectedType === 'Pick-up' ? 'selected' : '' ?>
                >
                    Pick-up
                </option>
            </select>
        </div>

        <!-- FILTER STATUS -->
        <div class="consultant-filter-select">
            <label
                for="status"
                class="consultant-sr-only"
            >
                Status Konsultasi
            </label>

            <select id="status" name="status">
                <option value="">
                    Semua status
                </option>

                <?php foreach ($statusOptions as $option): ?>
                    <?php
                    $value = (string) ($option['status'] ?? '');
                    ?>

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

        <!-- TOMBOL FILTER -->
        <button
            type="submit"
            class="consultant-btn consultant-filter-btn"
        >
            Filter
        </button>

        <!-- RESET -->
        <a
            href="<?= site_url('admin/consultations') ?>"
            class="consultant-btn-secondary consultant-reset-btn"
        >
            Reset
        </a>

    </form>

    <!-- TABEL KONSULTASI -->
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
                    <th class="consultant-actions-heading">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($consultations)): ?>

                    <?php foreach ($consultations as $consultation): ?>

                        <?php
                        $id = (int) (
                            $consultation['id_konsultasi'] ?? 0
                        );

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

                            <!-- ID KONSULTASI -->
                            <td>
                                <span class="consultant-name">
                                    #<?= esc((string) $id) ?>
                                </span>
                            </td>

                            <!-- NAMA KLIEN -->
                            <td>
                                <?= esc($clientName) ?>
                            </td>

                            <!-- PSIKOLOG -->
                            <td>
                                <?= esc($psychologistName) ?>
                            </td>

                            <!-- JENIS SESI -->
                            <td>
                                <?= esc($type) ?>
                            </td>

                            <!-- TANGGAL -->
                            <td>
                                <?= esc($date) ?>
                            </td>

                            <!-- STATUS -->
                            <td>
                                <span
                                    class="consultant-status <?= esc($badgeClass, 'attr') ?>"
                                >
                                    <?= esc($status) ?>
                                </span>
                            </td>

                            <!-- AKSI -->
                            <td>
                                <div class="consultant-table-actions">

                                    <a
                                        href="<?= site_url('admin/consultations/' . $id) ?>"
                                        class="consultant-action consultant-action-detail"
                                    >
                                        Detail
                                    </a>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td
                            colspan="7"
                            class="consultant-empty"
                        >
                            Belum ada data konsultasi yang sesuai
                            dengan pencarian atau filter.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<!-- REKAP STATUS -->
<?php if (!empty($statusSummary)): ?>

    <div class="management-card consultant-page-card mt-4">

        <div class="consultant-form-header">
            <h2>Ringkasan Status Konsultasi</h2>
            <p>
                Jumlah konsultasi berdasarkan status
                yang tercatat dalam sistem.
            </p>
        </div>

        <div class="row g-3">

            <?php foreach ($statusSummary as $item): ?>

                <div class="col-12 col-sm-6 col-lg-4">

                    <div class="border rounded-3 p-3 h-100">

                        <div class="text-muted mb-2">
                            <?= esc(
                                (string) ($item['status'] ?? 'Tidak diketahui')
                            ) ?>
                        </div>

                        <h3 class="fw-bold mb-0">
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

<?= $this->endSection() ?>
