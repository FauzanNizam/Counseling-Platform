
<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>

<?= $this->section('content') ?>

<?php
$clientId = (int) ($client['id_user'] ?? 0);
?>

<!-- ==========================================
     HEADER
========================================== -->

<div class="dashboard-header">
    <h1>Detail Klien</h1>
    <p>Informasi dan riwayat konsultasi klien.</p>
</div>

<!-- ==========================================
     DETAIL KLIEN
========================================== -->

<div class="psy-detail">

    <div class="psy-detail__top">
        <div>
            <h2>
                <?= esc($client['nama_lengkap'] ?? '-') ?>
            </h2>

            <p>
                <?= esc($client['email'] ?? '-') ?>
            </p>
        </div>
    </div>

    <!-- ======================================
         RIWAYAT KONSULTASI
    ======================================= -->

    <div class="psy-detail__meeting">

        <h3>Riwayat Konsultasi</h3>

        <div class="psy-clients__table-wrap">

            <table class="psy-clients__table">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Tipe Sesi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($consultations)): ?>

                        <?php foreach ($consultations as $item): ?>

                            <?php
                            $consultationId = (int) (
                                $item['id_konsultasi'] ?? 0
                            );

                            // Memberitahu halaman Detail Sesi
                            // bahwa pengguna datang dari menu Klien.
                            $detailUrl = site_url(
                                'psychologist/consultations/'
                                . $consultationId
                            ) . '?' . http_build_query([
                                'from' => 'client',
                                'client_id' => $clientId,
                            ]);
                            ?>

                            <tr>

                                <td>
                                    <?= !empty($item['tanggal'])
                                        ? esc(date(
                                            'd/m/Y',
                                            strtotime($item['tanggal'])
                                        ))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= esc(substr(
                                        (string) (
                                            $item['waktu_mulai'] ?? '-'
                                        ),
                                        0,
                                        5
                                    )) ?>
                                    WITA
                                </td>

                                <td>
                                    <?= esc(
                                        $item['tipe_sesi'] ?? '-'
                                    ) ?>
                                </td>

                                <td>
                                    <?= esc(
                                        $item['status'] ?? '-'
                                    ) ?>
                                </td>

                                <td>
                                    <a
                                        class="psy-btn psy-btn--secondary"
                                        href="<?= esc(
                                            $detailUrl,
                                            'attr'
                                        ) ?>"
                                    >
                                        Detail Sesi
                                    </a>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td
                                colspan="5"
                                class="psy-clients__empty"
                            >
                                Belum ada riwayat konsultasi.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- ======================================
         TOMBOL KEMBALI
    ======================================= -->

    <div class="psy-detail__actions">

        <a
            href="<?= site_url(
                'psychologist/clients'
            ) ?>"
            class="psy-btn psy-btn--secondary"
        >
            Kembali ke Daftar Klien
        </a>

    </div>

</div>

<?= $this->endSection() ?>
