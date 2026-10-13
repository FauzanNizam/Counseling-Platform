
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>

<?php
// ============================================
// DATA STATISTIK
// ============================================

$totalConsultations = (int) ($totalConsultations ?? 0);
$completedConsultations = (int) ($completedConsultations ?? 0);
$cancelledConsultations = (int) ($cancelledConsultations ?? 0);

$onlineConsultations = (int) ($onlineConsultations ?? 0);
$pickupConsultations = (int) ($pickupConsultations ?? 0);

$totalTransactions = (int) ($totalTransactions ?? 0);
$successfulTransactions = (int) ($successfulTransactions ?? 0);
$pendingTransactions = (int) ($pendingTransactions ?? 0);
$failedTransactions = (int) ($failedTransactions ?? 0);

$totalRevenue = (float) ($totalRevenue ?? 0);

$consultationSummary = $consultationSummary ?? [];
$sessionSummary = $sessionSummary ?? [];
$paymentSummary = $paymentSummary ?? [];

// ============================================
// HELPER FORMAT
// ============================================

$formatNumber = static function ($value): string {
    return number_format((int) $value, 0, ',', '.');
};

$formatCurrency = static function ($value): string {
    return 'Rp ' . number_format(
        (float) $value,
        0,
        ',',
        '.'
    );
};

$getPercentage = static function (
    $value,
    $total
): float {
    if ((int) $total <= 0) {
        return 0;
    }

    return round(
        ((int) $value / (int) $total) * 100,
        1
    );
};

$getConsultationBadge = static function (
    string $status
): string {
    switch ($status) {
        case 'Selesai':
            return 'bg-success-subtle text-success';

        case 'Dibatalkan':
            return 'bg-danger-subtle text-danger';

        case 'Dikonfirmasi':
            return 'bg-primary-subtle text-primary';

        case 'Berlangsung':
            return 'bg-info-subtle text-info';

        default:
            return 'bg-warning-subtle text-warning-emphasis';
    }
};

$getPaymentBadge = static function (
    string $status
): string {
    switch ($status) {
        case 'Sukses':
            return 'bg-success-subtle text-success';

        case 'Gagal':
            return 'bg-danger-subtle text-danger';

        case 'Refund':
            return 'bg-info-subtle text-info';

        default:
            return 'bg-warning-subtle text-warning-emphasis';
    }
};
?>

<!-- ============================================
     HEADER
============================================ -->

<div class="dashboard-header mb-4">

    <h1>Laporan Aktivitas Sistem</h1>

    <p>
        Ringkasan aktivitas konsultasi dan transaksi
        pembayaran pada Counseling Platform.
    </p>

</div>

<!-- ============================================
     STATISTIK KONSULTASI
============================================ -->

<div class="mb-3">

    <h2 class="h5 fw-bold mb-1">
        Ringkasan Konsultasi
    </h2>

    <p class="text-muted mb-0">
        Statistik seluruh sesi konsultasi yang tercatat.
    </p>

</div>

<div class="row g-3 mb-4">

    <!-- TOTAL KONSULTASI -->
    <div class="col-12 col-sm-6 col-xl-4">

        <div class="management-card p-4 h-100">

            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <p class="text-muted mb-2">
                        Total Konsultasi
                    </p>

                    <h2 class="fw-bold mb-2">
                        <?= $formatNumber($totalConsultations) ?>
                    </h2>

                    <small class="text-muted">
                        Semua sesi yang terdaftar
                    </small>
                </div>

                <div class="rounded-3 bg-primary-subtle text-primary p-3">
                    <i class="bi bi-calendar-check"></i>
                    <span aria-hidden="true">▣</span>
                </div>

            </div>

        </div>

    </div>

    <!-- KONSULTASI SELESAI -->
    <div class="col-12 col-sm-6 col-xl-4">

        <div class="management-card p-4 h-100">

            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <p class="text-muted mb-2">
                        Konsultasi Selesai
                    </p>

                    <h2 class="fw-bold mb-2">
                        <?= $formatNumber($completedConsultations) ?>
                    </h2>

                    <small class="text-success">
                        <?= $getPercentage(
                            $completedConsultations,
                            $totalConsultations
                        ) ?>% dari total konsultasi
                    </small>
                </div>

                <div class="rounded-3 bg-success-subtle text-success p-3">
                    <span aria-hidden="true">✓</span>
                </div>

            </div>

        </div>

    </div>

    <!-- KONSULTASI DIBATALKAN -->
    <div class="col-12 col-sm-6 col-xl-4">

        <div class="management-card p-4 h-100">

            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <p class="text-muted mb-2">
                        Konsultasi Dibatalkan
                    </p>

                    <h2 class="fw-bold mb-2">
                        <?= $formatNumber($cancelledConsultations) ?>
                    </h2>

                    <small class="text-danger">
                        <?= $getPercentage(
                            $cancelledConsultations,
                            $totalConsultations
                        ) ?>% dari total konsultasi
                    </small>
                </div>

                <div class="rounded-3 bg-danger-subtle text-danger p-3">
                    <span aria-hidden="true">✕</span>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- ============================================
     STATISTIK PEMBAYARAN
============================================ -->

<div class="mb-3">

    <h2 class="h5 fw-bold mb-1">
        Ringkasan Pembayaran
    </h2>

    <p class="text-muted mb-0">
        Informasi transaksi pembayaran yang tercatat.
    </p>

</div>

<div class="row g-3 mb-4">

    <!-- TOTAL PEMASUKAN -->
    <div class="col-12 col-sm-6 col-xl-3">

        <div class="management-card p-4 h-100">

            <p class="text-muted mb-2">
                Total Pemasukan
            </p>

            <h3 class="fw-bold mb-2 fs-4">
                <?= $formatCurrency($totalRevenue) ?>
            </h3>

            <small class="text-muted">
                Nominal pembayaran sukses
            </small>

        </div>

    </div>

    <!-- TOTAL TRANSAKSI -->
    <div class="col-12 col-sm-6 col-xl-3">

        <div class="management-card p-4 h-100">

            <p class="text-muted mb-2">
                Total Transaksi
            </p>

            <h3 class="fw-bold mb-2">
                <?= $formatNumber($totalTransactions) ?>
            </h3>

            <small class="text-muted">
                Semua transaksi pembayaran
            </small>

        </div>

    </div>

    <!-- PEMBAYARAN BERHASIL -->
    <div class="col-12 col-sm-6 col-xl-3">

        <div class="management-card p-4 h-100">

            <p class="text-muted mb-2">
                Pembayaran Berhasil
            </p>

            <h3 class="fw-bold mb-2 text-success">
                <?= $formatNumber($successfulTransactions) ?>
            </h3>

            <small class="text-muted">
                Status pembayaran Sukses
            </small>

        </div>

    </div>

    <!-- PEMBAYARAN PENDING -->
    <div class="col-12 col-sm-6 col-xl-3">

        <div class="management-card p-4 h-100">

            <p class="text-muted mb-2">
                Pembayaran Pending
            </p>

            <h3 class="fw-bold mb-2 text-warning">
                <?= $formatNumber($pendingTransactions) ?>
            </h3>

            <small class="text-muted">
                Menunggu proses pembayaran
            </small>

        </div>

    </div>

</div>

<!-- ============================================
     REKAP JENIS SESI
============================================ -->

<div class="row g-3 mb-4">

    <div class="col-12 col-lg-6">

        <div class="management-card p-4 h-100">

            <h2 class="h5 fw-bold mb-2">
                Jenis Sesi Konsultasi
            </h2>

            <p class="text-muted mb-4">
                Perbandingan konsultasi Online dan Pick-up.
            </p>

            <!-- ONLINE -->
            <div class="mb-4">

                <div class="d-flex justify-content-between mb-2">

                    <span class="fw-semibold">
                        Online
                    </span>

                    <span>
                        <?= $formatNumber($onlineConsultations) ?>
                        (<?= $getPercentage(
                            $onlineConsultations,
                            $totalConsultations
                        ) ?>%)
                    </span>

                </div>

                <div
                    class="progress"
                    role="progressbar"
                    aria-label="Konsultasi Online"
                    aria-valuenow="<?= $getPercentage(
                        $onlineConsultations,
                        $totalConsultations
                    ) ?>"
                    aria-valuemin="0"
                    aria-valuemax="100"
                >
                    <div
                        class="progress-bar bg-primary"
                        style="width: <?= $getPercentage(
                            $onlineConsultations,
                            $totalConsultations
                        ) ?>%"
                    ></div>
                </div>

            </div>

            <!-- PICK-UP -->
            <div>

                <div class="d-flex justify-content-between mb-2">

                    <span class="fw-semibold">
                        Pick-up
                    </span>

                    <span>
                        <?= $formatNumber($pickupConsultations) ?>
                        (<?= $getPercentage(
                            $pickupConsultations,
                            $totalConsultations
                        ) ?>%)
                    </span>

                </div>

                <div
                    class="progress"
                    role="progressbar"
                    aria-label="Konsultasi Pick-up"
                    aria-valuenow="<?= $getPercentage(
                        $pickupConsultations,
                        $totalConsultations
                    ) ?>"
                    aria-valuemin="0"
                    aria-valuemax="100"
                >
                    <div
                        class="progress-bar bg-info"
                        style="width: <?= $getPercentage(
                            $pickupConsultations,
                            $totalConsultations
                        ) ?>%"
                    ></div>
                </div>

            </div>

        </div>

    </div>

    <!-- RINGKASAN STATUS -->
    <div class="col-12 col-lg-6">

        <div class="management-card p-4 h-100">

            <h2 class="h5 fw-bold mb-2">
                Status Konsultasi
            </h2>

            <p class="text-muted mb-3">
                Rekap jumlah konsultasi berdasarkan status.
            </p>

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Status</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($consultationSummary)): ?>

                            <?php foreach ($consultationSummary as $item): ?>

                                <?php
                                $status = (string) (
                                    $item['status'] ?? '-'
                                );

                                $total = (int) (
                                    $item['total'] ?? 0
                                );
                                ?>

                                <tr>

                                    <td>
                                        <span
                                            class="badge rounded-pill <?= esc(
                                                $getConsultationBadge($status),
                                                'attr'
                                            ) ?> px-3 py-2"
                                        >
                                            <?= esc($status) ?>
                                        </span>
                                    </td>

                                    <td class="text-end fw-semibold">
                                        <?= $formatNumber($total) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td
                                    colspan="2"
                                    class="text-center text-muted py-4"
                                >
                                    Belum ada data konsultasi.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- ============================================
     REKAP PEMBAYARAN
============================================ -->

<div class="management-card p-4 mb-4">

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">

        <div>

            <h2 class="h5 fw-bold mb-2">
                Rekap Status Pembayaran
            </h2>

            <p class="text-muted mb-0">
                Jumlah transaksi berdasarkan status pembayaran.
            </p>

        </div>

        <div class="text-muted small">
            Total:
            <strong>
                <?= $formatNumber($totalTransactions) ?>
                transaksi
            </strong>
        </div>

    </div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead>
                <tr>
                    <th>Status Pembayaran</th>
                    <th class="text-end">Jumlah Transaksi</th>
                    <th class="text-end">Persentase</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($paymentSummary)): ?>

                    <?php foreach ($paymentSummary as $item): ?>

                        <?php
                        $status = (string) (
                            $item['status_bayar'] ?? '-'
                        );

                        $total = (int) (
                            $item['total'] ?? 0
                        );

                        $percentage = $getPercentage(
                            $total,
                            $totalTransactions
                        );
                        ?>

                        <tr>

                            <td>
                                <span
                                    class="badge rounded-pill <?= esc(
                                        $getPaymentBadge($status),
                                        'attr'
                                    ) ?> px-3 py-2"
                                >
                                    <?= esc($status) ?>
                                </span>
                            </td>

                            <td class="text-end fw-semibold">
                                <?= $formatNumber($total) ?>
                            </td>

                            <td class="text-end">
                                <?= $percentage ?>%
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td
                            colspan="3"
                            class="text-center text-muted py-4"
                        >
                            Belum ada transaksi pembayaran.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<!-- ============================================
     INFORMASI LAPORAN
============================================ -->

<div class="management-card p-4 mb-4">

    <h2 class="h5 fw-bold mb-2">
        Informasi Laporan
    </h2>

    <p class="text-muted mb-0">
        Laporan ini menampilkan ringkasan aktivitas
        konsultasi dan pembayaran berdasarkan data
        yang tersimpan di sistem. Informasi pribadi
        seperti isi percakapan dan catatan konseling
        tidak ditampilkan untuk menjaga kerahasiaan
        data klien.
    </p>

</div>

<?= $this->endSection() ?>
