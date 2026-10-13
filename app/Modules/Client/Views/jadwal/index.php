<?= $this->extend('Modules\Shared\Views\layouts\client') ?>

<?= $this->section('content') ?>

<?php
/** @var array $konsultasi */
?>

<style>
    .jadwal-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 28px;
    }

    .jadwal-header {
        margin-bottom: 24px;
    }

    .jadwal-header h1 {
        margin: 0 0 8px;
        color: #5b21b6;
        font-size: 30px;
    }

    .jadwal-status.status-dibatalkan {
        background: #fee2e2;
        color: #991b1b;
    }

    .jadwal-status.status-dikonfirmasi {
        background: #dcfce7;
        color: #166534;
    }


    .jadwal-header p {
        margin: 0;
        color: #6b7280;
    }

    .jadwal-list {
        display: grid;
        gap: 16px;
    }

    .jadwal-card {
        padding: 22px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
    }

    .jadwal-card h3 {
        margin: 0 0 14px;
        color: #1f2937;
        font-size: 18px;
    }

    .jadwal-info {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        color: #4b5563;
        font-size: 14px;
    }

    .jadwal-info p {
        margin: 0;
        overflow-wrap: anywhere;
    }

    .jadwal-status {
        display: inline-block;
        margin-top: 16px;
        padding: 7px 12px;
        border-radius: 20px;
        background: #fef3c7;
        color: #92400e;
        font-size: 12px;
        font-weight: 600;
    }

    .jadwal-empty {
        padding: 32px 20px;
        border: 1px dashed #d1d5db;
        border-radius: 14px;
        text-align: center;
        color: #6b7280;
        background: #fff;
    }

    .jadwal-action {
        display: inline-block;
        margin-top: 20px;
        padding: 11px 16px;
        background: #5b21b6;
        border-radius: 9px;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 600px) {
        .jadwal-page {
            padding: 20px 14px;
        }

        .jadwal-header h1 {
            font-size: 25px;
        }

        .jadwal-info {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="jadwal-page">
    <div class="jadwal-header">
        <h1>Jadwal Saya</h1>
        <p>Lihat pengajuan dan jadwal konselingmu di sini.</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="
            margin-bottom: 20px;
            padding: 14px;
            border-radius: 10px;
            background: #dcfce7;
            color: #166534;
        ">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="
        margin-bottom: 20px;
        padding: 14px;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    ">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($konsultasi)): ?>
        <div class="jadwal-empty">
            <h3>Belum Ada Jadwal Konseling</h3>
            <p>Kamu belum memiliki pengajuan konseling.</p>

            <a class="jadwal-action"
                href="<?= site_url('client/konsultan') ?>">
                Cari Psikolog
            </a>
        </div>
    <?php else: ?>
        <div class="jadwal-list">
            <?php foreach ($konsultasi as $item): ?>
                <article class="jadwal-card">
                    <h3><?= esc($item['nama_konsultan']) ?></h3>

                    <div class="jadwal-info">
                        <p>
                            <strong>Tanggal:</strong>
                            <?= esc(date('d-m-Y', strtotime($item['tanggal']))) ?>
                        </p>

                        <p>
                            <strong>Hari praktik:</strong>
                            <?= esc($item['hari']) ?>
                        </p>

                        <p>
                            <strong>Waktu:</strong>
                            <?= esc(substr($item['waktu_mulai'], 0, 5)) ?>
                            –
                            <?= esc(substr($item['waktu_selesai'], 0, 5)) ?>
                        </p>

                        <p>
                            <strong>Tipe sesi:</strong>
                            <?= esc($item['tipe_sesi']) ?>
                        </p>
                    </div>


                    <span class="jadwal-status <?= $item['status'] === 'Dibatalkan' ? 'status-dibatalkan' : ($item['status'] === 'Dikonfirmasi' ? 'status-dikonfirmasi' : '') ?>">
                        <?= esc($item['status']) ?>
                    </span>

                    <?php
                    $nomorWhatsApp = preg_replace(
                        '/\D/',
                        '',
                        (string) ($item['telepon_konsultan'] ?? '')
                    );

                    // Ubah awalan 0 menjadi kode negara Indonesia 62.
                    if (str_starts_with($nomorWhatsApp, '0')) {
                        $nomorWhatsApp = '62' . substr($nomorWhatsApp, 1);
                    }
                    ?>

                    <?php if (
                        $item['status'] === 'Dikonfirmasi'
                        && $nomorWhatsApp !== ''
                    ): ?>

                        <?php if ($item['status'] === 'Dikonfirmasi'): ?>
                            <a
                                href="<?= site_url('client/pembayaran/' . $item['id_konsultasi']) ?>"
                                class="jadwal-action"
                                style="margin-right: 8px;">
                                Bayar Sekarang
                            </a>
                        <?php endif; ?>

                        <?php
                        $pesanAwal = 'Halo ' . $item['nama_konsultan']
                            . ', saya klien Anda dari Counseling Platform.'
                            . ' Saya ingin membahas jadwal konsultasi tanggal '
                            . date('d-m-Y', strtotime($item['tanggal'])) . '.';

                        $linkWhatsApp = 'https://wa.me/' . $nomorWhatsApp
                            . '?text=' . rawurlencode($pesanAwal);
                        ?>

                        <a
                            href="<?= esc($linkWhatsApp) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="
            display: inline-block;
            margin-top: 16px;
            margin-right: 8px;
            padding: 11px 16px;
            background: #25D366;
            border-radius: 9px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        ">
                            Chat via WhatsApp
                        </a>
                    <?php endif; ?>

                    <?php if ($item['status'] === 'Menunggu Konfirmasi'): ?>
                        <form
                            action="<?= site_url('client/jadwal/batalkan/' . $item['id_konsultasi']) ?>"
                            method="post"
                            style="margin-top: 16px;"
                            onsubmit="return confirm('Yakin ingin membatalkan pengajuan konseling ini?');">
                            <?= csrf_field() ?>

                            <button
                                type="submit"
                                style="
                padding: 10px 16px;
                border: 1px solid #dc2626;
                border-radius: 9px;
                background: #fff;
                color: #dc2626;
                font-weight: 600;
                cursor: pointer;
            ">
                                Batalkan Janji
                            </button>
                        </form>
                    <?php endif; ?>

                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>