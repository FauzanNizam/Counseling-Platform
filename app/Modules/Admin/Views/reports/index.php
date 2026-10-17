
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>

<?php

// ==========================================
// DATA STATISTIK KONSULTASI
// ==========================================

$totalConsultations = (int) ($totalConsultations ?? 0);
$completedConsultations = (int) ($completedConsultations ?? 0);
$cancelledConsultations = (int) ($cancelledConsultations ?? 0);

$onlineConsultations = (int) ($onlineConsultations ?? 0);
$pickupConsultations = (int) ($pickupConsultations ?? 0);


// ==========================================
// DATA STATISTIK PEMBAYARAN
// ==========================================

$totalTransactions = (int) ($totalTransactions ?? 0);
$successfulTransactions = (int) ($successfulTransactions ?? 0);
$pendingTransactions = (int) ($pendingTransactions ?? 0);
$failedTransactions = (int) ($failedTransactions ?? 0);

$totalRevenue = (float) ($totalRevenue ?? 0);


// ==========================================
// DATA REKAP
// ==========================================

$consultationSummary = $consultationSummary ?? [];
$sessionSummary = $sessionSummary ?? [];
$paymentSummary = $paymentSummary ?? [];


// ==========================================
// HELPER FORMAT
// ==========================================

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

$getPercentage = static function ($value, $total): float {
    if ((int) $total <= 0) {
        return 0;
    }

    return round(
        ((int) $value / (int) $total) * 100,
        1
    );
};


// ==========================================
// HELPER STATUS KONSULTASI
// ==========================================

$getConsultationBadge = static function (string $status): string {
    switch (strtolower(trim($status))) {
        case 'selesai':
            return 'report-badge-success';

        case 'dibatalkan':
            return 'report-badge-danger';

        case 'dikonfirmasi':
            return 'report-badge-purple';

        case 'berlangsung':
            return 'report-badge-info';

        default:
            return 'report-badge-warning';
    }
};


// ==========================================
// HELPER STATUS PEMBAYARAN
// ==========================================

$getPaymentBadge = static function (string $status): string {
    switch (strtolower(trim($status))) {
        case 'sukses':
            return 'report-badge-success';

        case 'gagal':
            return 'report-badge-danger';

        case 'refund':
            return 'report-badge-info';

        default:
            return 'report-badge-warning';
    }
};

$onlinePercentage = $getPercentage(
    $onlineConsultations,
    $totalConsultations
);

$pickupPercentage = $getPercentage(
    $pickupConsultations,
    $totalConsultations
);

?>

<div class="admin-reports-page">

    <!-- =====================================
         HEADER HALAMAN
    ====================================== -->

    <div class="dashboard-header report-page-header">
        <h1>Laporan Aktivitas Sistem</h1>

        <p>
            Ringkasan aktivitas konsultasi dan transaksi
            pembayaran pada Counseling Platform.
        </p>
    </div>


    <!-- =====================================
         RINGKASAN KONSULTASI
    ====================================== -->

    <div class="report-section-heading">
        <h2>Ringkasan Konsultasi</h2>
        <p>Statistik seluruh sesi konsultasi yang tercatat.</p>
    </div>

    <div class="report-stats-grid report-stats-three">

        <!-- TOTAL KONSULTASI -->
        <div class="report-stat-card">

            <div class="report-stat-icon">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
            </div>

            <div class="report-stat-info">
                <p class="report-stat-label">
                    Total Konsultasi
                </p>

                <h3 class="report-stat-value">
                    <?= $formatNumber($totalConsultations) ?>
                </h3>

                <p class="report-stat-description">
                    Semua sesi yang terdaftar
                </p>
            </div>

        </div>


        <!-- KONSULTASI SELESAI -->
        <div class="report-stat-card">

            <div class="report-stat-icon">
                <i class="bi bi-check-circle" aria-hidden="true"></i>
            </div>

            <div class="report-stat-info">
                <p class="report-stat-label">
                    Konsultasi Selesai
                </p>

                <h3 class="report-stat-value">
                    <?= $formatNumber($completedConsultations) ?>
                </h3>

                <p class="report-stat-description">
                    <?= $getPercentage(
                        $completedConsultations,
                        $totalConsultations
                    ) ?>% dari total konsultasi
                </p>
            </div>

        </div>


        <!-- KONSULTASI DIBATALKAN -->
        <div class="report-stat-card">

            <div class="report-stat-icon">
                <i class="bi bi-x-circle" aria-hidden="true"></i>
            </div>

            <div class="report-stat-info">
                <p class="report-stat-label">
                    Konsultasi Dibatalkan
                </p>

                <h3 class="report-stat-value">
                    <?= $formatNumber($cancelledConsultations) ?>
                </h3>

                <p class="report-stat-description">
                    <?= $getPercentage(
                        $cancelledConsultations,
                        $totalConsultations
                    ) ?>% dari total konsultasi
                </p>
            </div>

        </div>

    </div>


    <!-- =====================================
         RINGKASAN PEMBAYARAN
    ====================================== -->

    <div class="report-section-heading">
        <h2>Ringkasan Pembayaran</h2>
        <p>Informasi transaksi pembayaran yang tercatat.</p>
    </div>

    <div class="report-stats-grid report-stats-four">

        <!-- TOTAL PEMASUKAN -->
        <div class="report-stat-card">

            <div class="report-stat-icon">
                <i class="bi bi-wallet2" aria-hidden="true"></i>
            </div>

            <div class="report-stat-info">
                <p class="report-stat-label">
                    Total Pemasukan
                </p>

                <h3 class="report-stat-value report-revenue-value">
                    <?= $formatCurrency($totalRevenue) ?>
                </h3>

                <p class="report-stat-description">
                    Nominal pembayaran sukses
                </p>
            </div>

        </div>


        <!-- TOTAL TRANSAKSI -->
        <div class="report-stat-card">

            <div class="report-stat-icon">
                <i class="bi bi-receipt" aria-hidden="true"></i>
            </div>

            <div class="report-stat-info">
                <p class="report-stat-label">
                    Total Transaksi
                </p>

                <h3 class="report-stat-value">
                    <?= $formatNumber($totalTransactions) ?>
                </h3>

                <p class="report-stat-description">
                    Semua transaksi pembayaran
                </p>
            </div>

        </div>


        <!-- PEMBAYARAN BERHASIL -->
        <div class="report-stat-card">

            <div class="report-stat-icon">
                <i class="bi bi-credit-card-2-front" aria-hidden="true"></i>
            </div>

            <div class="report-stat-info">
                <p class="report-stat-label">
                    Pembayaran Berhasil
                </p>

                <h3 class="report-stat-value">
                    <?= $formatNumber($successfulTransactions) ?>
                </h3>

                <p class="report-stat-description">
                    Status pembayaran Sukses
                </p>
            </div>

        </div>


        <!-- PEMBAYARAN PENDING -->
        <div class="report-stat-card">

            <div class="report-stat-icon">
                <i class="bi bi-clock-history" aria-hidden="true"></i>
            </div>

            <div class="report-stat-info">
                <p class="report-stat-label">
                    Pembayaran Pending
                </p>

                <h3 class="report-stat-value">
                    <?= $formatNumber($pendingTransactions) ?>
                </h3>

                <p class="report-stat-description">
                    Menunggu proses pembayaran
                </p>
            </div>

        </div>

    </div>


    <!-- =====================================
         REKAP JENIS SESI & STATUS KONSULTASI
    ====================================== -->

    <div class="report-content-grid">

        <!-- JENIS SESI -->
        <div class="management-card report-content-card">

            <div class="report-card-header">
                <h2>
                    <i class="bi bi-pie-chart" aria-hidden="true"></i>
                    Jenis Sesi Konsultasi
                </h2>

                <p>
                    Perbandingan konsultasi Online dan Pick-up.
                </p>
            </div>


            <!-- ONLINE -->
            <div class="report-progress-item">

                <div class="report-progress-label">
                    <span>Online</span>

                    <strong>
                        <?= $formatNumber($onlineConsultations) ?>
                        (<?= $onlinePercentage ?>%)
                    </strong>
                </div>

                <div
                    class="report-progress-track"
                    role="progressbar"
                    aria-label="Konsultasi Online"
                    aria-valuenow="<?= $onlinePercentage ?>"
                    aria-valuemin="0"
                    aria-valuemax="100"
                >
                    <div
                        class="report-progress-fill"
                        style="width: <?= min(100, max(0, $onlinePercentage)) ?>%"
                    ></div>
                </div>

            </div>


            <!-- PICK-UP -->
            <div class="report-progress-item">

                <div class="report-progress-label">
                    <span>Pick-up</span>

                    <strong>
                        <?= $formatNumber($pickupConsultations) ?>
                        (<?= $pickupPercentage ?>%)
                    </strong>
                </div>

                <div
                    class="report-progress-track"
                    role="progressbar"
                    aria-label="Konsultasi Pick-up"
                    aria-valuenow="<?= $pickupPercentage ?>"
                    aria-valuemin="0"
                    aria-valuemax="100"
                >
                    <div
                        class="report-progress-fill report-progress-pickup"
                        style="width: <?= min(100, max(0, $pickupPercentage)) ?>%"
                    ></div>
                </div>

            </div>

        </div>


        <!-- STATUS KONSULTASI -->
        <div class="management-card report-content-card">

            <div class="report-card-header">
                <h2>
                    <i class="bi bi-clipboard-data" aria-hidden="true"></i>
                    Status Konsultasi
                </h2>

                <p>
                    Rekap jumlah konsultasi berdasarkan status.
                </p>
            </div>

            <div class="report-table-wrapper">
                <table class="report-table">

                    <thead>
                        <tr>
                            <th>Status</th>
                            <th class="report-text-right">Jumlah</th>
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
                                        <span class="report-badge <?= esc(
                                            $getConsultationBadge($status),
                                            'attr'
                                        ) ?>">
                                            <?= esc($status) ?>
                                        </span>
                                    </td>

                                    <td class="report-text-right report-count">
                                        <?= $formatNumber($total) ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="2" class="report-empty">
                                    Belum ada data konsultasi.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>

    </div>


    <!-- =====================================
         REKAP STATUS PEMBAYARAN
    ====================================== -->

    <div class="management-card report-content-card report-payment-card">

        <div class="report-card-header report-card-header-between">

            <div>
                <h2>
                    <i class="bi bi-credit-card" aria-hidden="true"></i>
                    Rekap Status Pembayaran
                </h2>

                <p>
                    Jumlah transaksi berdasarkan status pembayaran.
                </p>
            </div>

            <div class="report-total-label">
                Total:
                <strong>
                    <?= $formatNumber($totalTransactions) ?>
                    transaksi
                </strong>
            </div>

        </div>


        <div class="report-table-wrapper">
            <table class="report-table">

                <thead>
                    <tr>
                        <th>Status Pembayaran</th>
                        <th class="report-text-right">Jumlah Transaksi</th>
                        <th class="report-text-right">Persentase</th>
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
                                    <span class="report-badge <?= esc(
                                        $getPaymentBadge($status),
                                        'attr'
                                    ) ?>">
                                        <?= esc($status) ?>
                                    </span>
                                </td>

                                <td class="report-text-right report-count">
                                    <?= $formatNumber($total) ?>
                                </td>

                                <td class="report-text-right">
                                    <?= $percentage ?>%
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="3" class="report-empty">
                                Belum ada transaksi pembayaran.
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>

            </table>
        </div>

    </div>


    <!-- =====================================
         INFORMASI LAPORAN
    ====================================== -->

    <div class="management-card report-content-card report-info-card">

        <div class="report-card-header">
            <h2>
                <i class="bi bi-info-circle" aria-hidden="true"></i>
                Informasi Laporan
            </h2>
        </div>

        <p class="report-info-text">
            Laporan ini menampilkan ringkasan aktivitas
            konsultasi dan pembayaran berdasarkan data
            yang tersimpan di sistem. Informasi pribadi
            seperti isi percakapan dan catatan konseling
            tidak ditampilkan untuk menjaga kerahasiaan
            data klien.
        </p>

    </div>

</div>

<?= $this->endSection() ?>
