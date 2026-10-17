<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>

<?= $this->section('content') ?>

<?php
$timezone = new DateTimeZone('Asia/Makassar');
$now = new DateTimeImmutable('now', $timezone);

$success = session()->getFlashdata('success');
$error = session()->getFlashdata('error');
?>

<!-- ==========================================
     HEADER HALAMAN
========================================== -->

<div class="dashboard-header">
    <h1>Jadwal Konsultasi</h1>
    <p>Kelola permintaan dan jadwal konsultasi klien.</p>
</div>


<!-- ==========================================
     DAFTAR KONSULTASI
========================================== -->

<div class="schedule-card psychologist-schedule">

    <!-- HEADER CARD -->
    <div class="psychologist-schedule__header">

        <div>
            <h2>Daftar Konsultasi</h2>
            <p>Seluruh konsultasi yang terhubung dengan akunmu.</p>
        </div>

        <span class="psychologist-schedule__count">
            <?= count($consultations ?? []) ?> Konsultasi
        </span>

    </div>


    <!-- ==========================================
         NOTIFIKASI
    ========================================== -->

    <?php if ($success): ?>
        <div class="psy-alert psy-alert--success" role="alert">
            <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="psy-alert psy-alert--error" role="alert">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>


    <!-- ==========================================
         TABEL KONSULTASI
    ========================================== -->

    <?php if (empty($consultations)): ?>

        <div class="psychologist-schedule__empty">
            Belum ada konsultasi untuk ditampilkan.
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
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($consultations as $item): ?>

                        <?php
                        // ==================================
                        // DATA KONSULTASI
                        // ==================================

                        $consultationId = (int) $item['id_konsultasi'];

                        $clientName = $item['nama_klien'] ?? 'Klien';

                        $status = $item['status'] ?? '-';

                        $statusClass = match ($status) {
                            'Menunggu Konfirmasi' => 'pending',
                            'Dikonfirmasi' => 'confirmed',
                            'Berlangsung' => 'ongoing',
                            'Selesai' => 'completed',
                            'Dibatalkan' => 'cancelled',
                            default => 'default',
                        };

                        // ==================================
                        // VALIDASI WAKTU
                        // ==================================

                        $sessionStart = false;

                        if (
                            !empty($item['tanggal']) &&
                            !empty($item['waktu_mulai'])
                        ) {
                            $sessionStart = DateTimeImmutable::createFromFormat(
                                '!Y-m-d H:i:s',
                                $item['tanggal'] . ' ' .
                                date('H:i:s', strtotime($item['waktu_mulai'])),
                                $timezone
                            );
                        }

                        // Psikolog dapat merespons permintaan
                        // // selama status masih Menunggu Konfirmasi.

                        $canRespond = $status === 'Menunggu Konfirmasi';
                        ?>

                        <tr>

                            <!-- NAMA KLIEN -->
                            <td class="psychologist-schedule__client">
                                <?= esc($clientName) ?>
                            </td>


                            <!-- TANGGAL -->
                            <td>
                                <?= !empty($item['tanggal'])
                                    ? esc(date(
                                        'd/m/Y',
                                        strtotime($item['tanggal'])
                                    ))
                                    : '-' ?>
                            </td>


                            <!-- WAKTU -->
                            <td>
                                <?= !empty($item['waktu_mulai'])
                                    ? esc(substr(
                                        $item['waktu_mulai'],
                                        0,
                                        5
                                    ))
                                    : '-' ?>
                            </td>


                            <!-- TIPE SESI -->
                            <td>
                                <?= esc($item['tipe_sesi'] ?? '-') ?>
                            </td>


                            <!-- STATUS -->
                            <td>
                                <span class="psychologist-status psychologist-status--<?= esc($statusClass) ?>">
                                    <?= esc($status) ?>
                                </span>
                            </td>


                            <!-- ==================================
                                 KOLOM AKSI
                            ================================== -->
                            <td>

                                <div class="psy-actions">

                                    <!-- DETAIL SELALU MUNCUL -->
                                    <a href="<?= site_url(
                                        'psychologist/consultations/' .
                                        $consultationId
                                    ) ?>" class="psy-btn psy-btn--secondary">
                                        Detail
                                    </a>


                                    <?php if ($canRespond): ?>

                                        <!-- TERIMA KONSULTASI -->
                                        <form action="<?= site_url(
                                            'psychologist/consultations/' .
                                            $consultationId .
                                            '/confirm'
                                        ) ?>" method="post">
                                            <?= csrf_field() ?>

                                            <button type="submit" class="psy-btn psy-btn--accept">
                                                Terima
                                            </button>

                                        </form>


                                        <!-- TOLAK: BUKA CUSTOM MODAL -->
                                        <button type="button" class="psy-btn psy-btn--reject js-open-reject-modal" data-action="<?= esc(
                                            site_url(
                                                'psychologist/consultations/' .
                                                $consultationId .
                                                '/reject'
                                            ),
                                            'attr'
                                        ) ?>" data-client="<?= esc(
                                             $clientName,
                                             'attr'
                                         ) ?>">
                                            Tolak
                                        </button>

                                    <?php endif; ?>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>


<!-- ==========================================
     MODAL KONFIRMASI TOLAK KONSULTASI
========================================== -->

<div class="psy-modal" id="psyRejectModal" role="dialog" aria-modal="true" aria-labelledby="psyRejectTitle"
    aria-describedby="psyRejectDescription" aria-hidden="true">
    <!-- BACKDROP -->
    <div class="psy-modal__backdrop" data-close-reject-modal></div>

    <!-- KONTEN MODAL -->
    <div class="psy-modal__dialog">

        <!-- IKON PERINGATAN -->
        <div class="psy-modal__icon" aria-hidden="true">
            !
        </div>

        <!-- JUDUL -->
        <h2 id="psyRejectTitle">
            Konfirmasi Tolak Konsultasi
        </h2>

        <!-- DESKRIPSI -->
        <p id="psyRejectDescription">
            Apakah Anda yakin ingin menolak permintaan
            konsultasi dari
            <strong id="psyRejectClientName">
                klien ini
            </strong>?
        </p>

        <!-- FORM PENOLAKAN -->
        <form id="psyRejectForm" method="post" action="">
            <?= csrf_field() ?>

            <div class="psy-modal__actions">

                <!-- BATAL -->
                <button type="button" class="psy-modal__cancel" data-close-reject-modal>
                    Batal
                </button>

                <!-- KONFIRMASI TOLAK -->
                <button type="submit" class="psy-modal__confirm">
                    Ya, Tolak
                </button>

            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>