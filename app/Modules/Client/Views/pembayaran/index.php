<?= $this->extend('Modules\Shared\Views\layouts\client') ?>

<?php
/**
 * @var array $konsultasi
 * @var array|null $pembayaran
 * @var int|float $jumlah
 */
?>

<?= $this->section('content') ?>

<style>
    .payment-page {
        max-width: 850px;
        margin: 0 auto;
        padding: 28px;
    }

    .payment-title {
        color: #5b21b6;
        font-size: 30px;
        margin-bottom: 8px;
    }

    .payment-subtitle {
        color: #6b7280;
        margin-bottom: 24px;
    }

    .payment-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 26px;
        margin-bottom: 20px;
    }

    .payment-info {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .payment-info p {
        margin: 0;
        color: #4b5563;
    }

    .payment-price {
        background: #f5f3ff;
        border-radius: 12px;
        padding: 20px;
        margin-top: 22px;
    }

    .payment-price strong {
        display: block;
        color: #5b21b6;
        font-size: 28px;
        margin-top: 6px;
    }

    .payment-field {
        margin-bottom: 20px;
    }

    .payment-field label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
    }

    .payment-field select {
        box-sizing: border-box;
        width: 100%;
        padding: 13px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: white;
        font: inherit;
    }

    .payment-button {
        display: block;
        width: 100%;
        padding: 14px;
        border: 0;
        border-radius: 10px;
        background: #5b21b6;
        color: white;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
    }

    .payment-back {
        display: inline-block;
        margin-bottom: 20px;
        color: #6d28d9;
        text-decoration: none;
    }

    .payment-notice {
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .payment-error {
        background: #fef2f2;
        color: #b91c1c;
    }

    .payment-status {
        color: #92400e;
        background: #fef3c7;
        display: inline-block;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    @media (max-width: 600px) {
        .payment-page {
            padding: 20px 14px;
        }

        .payment-info {
            grid-template-columns: 1fr;
        }

        .payment-title {
            font-size: 25px;
        }

        .payment-card {
            padding: 20px;
        }
    }
</style>

<div class="payment-page">
    <a class="payment-back" href="<?= site_url('client/jadwal') ?>">
        ← Kembali ke Jadwal Saya
    </a>

    <h1 class="payment-title">Pembayaran Konseling</h1>

    <p class="payment-subtitle">
        Periksa detail konsultasi sebelum melanjutkan pembayaran.
    </p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="payment-notice payment-error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>


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


    <div class="payment-card">
        <h3>Detail Konsultasi</h3>

        <div class="payment-info">
            <p>
                <strong>ID Konsultasi</strong><br>
                #<?= esc($konsultasi['id_konsultasi']) ?>
            </p>

            <p>
                <strong>Tanggal</strong><br>
                <?= esc(date('d-m-Y', strtotime($konsultasi['tanggal']))) ?>
            </p>

            <p>
                <strong>Waktu</strong><br>
                <?= esc(substr($konsultasi['waktu_mulai'], 0, 5)) ?>
                –
                <?= esc(substr($konsultasi['waktu_selesai'], 0, 5)) ?>
            </p>

            <p>
                <strong>Tipe Sesi</strong><br>
                <?= esc($konsultasi['tipe_sesi']) ?>
            </p>

            <p>
                <strong>Status Booking</strong><br>
                <?= esc($konsultasi['status']) ?>
            </p>
        </div>

        <div class="payment-price">
            Total pembayaran
            <strong>
                Rp<?= number_format((float) $jumlah, 0, ',', '.') ?>
            </strong>
            <small>Untuk satu sesi konseling selama 60 menit.</small>
        </div>
    </div>

    <div class="payment-card">
        <h3>Metode Pembayaran</h3>


        <?php if (!empty($pembayaran)): ?>
            <p>Status pembayaran:</p>

            <span class="payment-status">
                <?= esc($pembayaran['status_bayar']) ?>
            </span>

            <?php if ($pembayaran['status_bayar'] === 'Pending'): ?>
                <?php
                $metode = $pembayaran['metode'] ?? '';
                ?>

                <div style="
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd6fe;
            border-radius: 12px;
            background: #f5f3ff;
        ">
                    <h3 style="color: #5b21b6; margin-top: 0;">
                        Instruksi Pembayaran
                    </h3>

                    <p>
                        Metode:
                        <strong><?= esc($metode) ?></strong>
                    </p>

                    <p>
                        Kode transaksi:
                        <strong><?= esc($pembayaran['kode_transaksi']) ?></strong>
                    </p>

                    <p>
                        Nominal:
                        <strong>
                            Rp<?= number_format((float) $pembayaran['jumlah'], 0, ',', '.') ?>
                        </strong>
                    </p>

                    <div style="
                padding: 12px;
                border-radius: 8px;
                background: #fff;
                color: #92400e;
                margin-top: 16px;
            ">
                        <strong>SIMULASI PEMBAYARAN</strong>
                        <p style="margin-bottom: 0;">
                            Ini adalah instruksi untuk demonstrasi aplikasi.
                            Jangan mengirim uang sungguhan berdasarkan informasi ini.
                        </p>
                    </div>

                    <?php if ($metode === 'GoPay'): ?>
                        <p>Silakan pilih GoPay pada simulasi pembayaran.</p>
                    <?php elseif ($metode === 'OVO'): ?>
                        <p>Silakan pilih OVO pada simulasi pembayaran.</p>
                    <?php elseif ($metode === 'Transfer Bank'): ?>
                        <p>Silakan pilih Transfer Bank pada simulasi pembayaran.</p>
                    <?php elseif ($metode === 'Virtual Account'): ?>
                        <p>Silakan pilih Virtual Account pada simulasi pembayaran.</p>
                    <?php endif; ?>

                    <p style="color: #6b7280; font-size: 14px;">
                        Status tetap Pending sampai pembayaran diverifikasi oleh admin.
                    </p>

                    <?php if (empty($pembayaran['bukti_bayar'])): ?>
                        <form
                            action="<?= site_url('client/pembayaran/upload-bukti/' . $konsultasi['id_konsultasi']) ?>"
                            method="post"
                            enctype="multipart/form-data"
                            style="margin-top: 20px;">
                            <?= csrf_field() ?>

                            <div class="payment-field">
                                <label for="bukti_bayar">Unggah Bukti Pembayaran</label>

                                <input
                                    type="file"
                                    id="bukti_bayar"
                                    name="bukti_bayar"
                                    accept="image/jpeg,image/png,image/webp"
                                    required
                                    style="
                    box-sizing: border-box;
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #d1d5db;
                    border-radius: 10px;
                    background: white;
                ">

                                <small style="color: #6b7280;">
                                    Format JPG, PNG, atau WebP. Maksimal 2 MB.
                                </small>
                            </div>

                            <button type="submit" class="payment-button">
                                Unggah Bukti Pembayaran
                            </button>
                        </form>
                    <?php else: ?>
                        <div style="
        margin-top: 20px;
        padding: 14px;
        background: #fef3c7;
        color: #92400e;
        border-radius: 10px;
    ">
                            Bukti pembayaran sudah diunggah dan menunggu verifikasi admin.
                        </div>
                    <?php endif; ?>

                </div>
            <?php elseif ($pembayaran['status_bayar'] === 'Sukses'): ?>
                <p style="color: #166534; margin-top: 16px;">
                    Pembayaran sudah diverifikasi berhasil.
                </p>
            <?php elseif ($pembayaran['status_bayar'] === 'Gagal'): ?>
                <p style="color: #b91c1c; margin-top: 16px;">
                    Pembayaran ditolak atau gagal. Silakan hubungi admin.
                </p>
            <?php elseif ($pembayaran['status_bayar'] === 'Refund'): ?>
                <p style="color: #6b7280; margin-top: 16px;">
                    Pembayaran ini berstatus refund.
                </p>
            <?php endif; ?>

        <?php else: ?>
            <form
                action="<?= site_url('client/pembayaran/proses/' . $konsultasi['id_konsultasi']) ?>"
                method="post">

                <?= csrf_field() ?>

                <div class="payment-field">
                    <label for="metode">Pilih Metode Pembayaran</label>

                    <select name="metode" id="metode" required>
                        <option value="">-- Pilih metode pembayaran --</option>
                        <option value="GoPay">GoPay</option>
                        <option value="OVO">OVO</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Virtual Account">Virtual Account</option>
                    </select>
                </div>

                <button type="submit" class="payment-button">
                    Lanjutkan Pembayaran
                </button>
            </form>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>