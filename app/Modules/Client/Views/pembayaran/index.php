
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array $konsultasi
 * @var array|null $pembayaran
 * @var int|float $jumlah
 */

$idKonsultasi = $konsultasi['id_konsultasi'];

$statusBayar = $pembayaran['status_bayar'] ?? '';
$metodeBayar = $pembayaran['metode'] ?? '';

$jumlahBayar = (float) $jumlah;

$successMessage = session()->getFlashdata('success');
$errorMessage = session()->getFlashdata('error');

$statusClass = 'pending';
$statusIcon = 'bi-hourglass-split';

if ($statusBayar === 'Sukses') {
    $statusClass = 'success';
    $statusIcon = 'bi-check-circle';
} elseif ($statusBayar === 'Gagal') {
    $statusClass = 'failed';
    $statusIcon = 'bi-x-circle';
} elseif ($statusBayar === 'Refund') {
    $statusClass = 'refund';
    $statusIcon = 'bi-arrow-counterclockwise';
}
?>

<style>
    /* ==========================================
       CLIENT PAYMENT
       CONSISTENT MEDIUM DESIGN
    ========================================== */

    .payment-page {
        --pay-primary: #6d28d9;
        --pay-dark: #5b21b6;
        --pay-text: #292436;
        --pay-muted: #746e80;
        --pay-border: #eae5f1;

        width: 100%;
        max-width: 1050px;
        margin: 0 auto;
        color: var(--pay-text);
        font-size: 14px;
        line-height: 1.6;
    }

    .payment-page *,
    .payment-page *::before,
    .payment-page *::after {
        box-sizing: border-box;
    }

    /* BACK */

    .payment-page .payment-back {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 22px;
        color: #5b21b6;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .payment-page .payment-back:hover {
        color: #7c3aed;
        text-decoration: none;
    }

    .payment-page .payment-back .bi {
        font-size: 18px;
    }

    /* HEADER */

    .payment-page .payment-header {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        margin-bottom: 24px;
        border-radius: 18px;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
        color: #fff;
    }

    .payment-page .payment-header::after {
        content: "";
        position: absolute;
        top: -90px;
        right: -65px;
        width: 240px;
        height: 240px;
        border: 38px solid rgba(255,255,255,.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .payment-page .payment-header > * {
        position: relative;
        z-index: 1;
    }

    .payment-page .payment-header-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding: 7px 13px;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 30px;
        background: rgba(255,255,255,.12);
        color: #fff;
        font-size: 13px;
    }

    .payment-page .payment-header h1 {
        margin: 0 0 12px;
        color: #fff;
        font-size: clamp(27px, 3vw, 32px);
        font-weight: 700;
        line-height: 1.3;
    }

    .payment-page .payment-header p {
        max-width: 650px;
        margin: 0;
        color: #f0e7ff;
        font-size: 15px;
        line-height: 1.75;
    }

    /* ALERT */

    .payment-page .payment-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 18px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 11px;
        font-size: 14px;
        line-height: 1.7;
    }

    .payment-page .payment-notice .bi {
        flex-shrink: 0;
        margin-top: 2px;
        font-size: 19px;
    }

    .payment-page .payment-notice.error {
        border-color: #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }

    .payment-page .payment-notice.success {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    /* LAYOUT */

    .payment-page .payment-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 310px;
        align-items: start;
        gap: 20px;
    }

    .payment-page .payment-main,
    .payment-page .payment-sidebar {
        min-width: 0;
    }

    .payment-page .payment-main {
        display: grid;
        gap: 20px;
    }

    .payment-page .payment-sidebar {
        display: grid;
        gap: 18px;
    }

    /* CARD */

    .payment-page .payment-card {
        min-width: 0;
        padding: 25px;
        border: 1px solid var(--pay-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61,31,100,.025);
    }

    .payment-page .payment-card-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        padding-bottom: 20px;
        margin-bottom: 22px;
        border-bottom: 1px solid #f0edf4;
    }

    .payment-page .payment-heading-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 21px;
    }

    .payment-page .payment-card-heading h2 {
        margin: 0 0 4px;
        color: var(--pay-text);
        font-size: 19px;
        font-weight: 700;
    }

    .payment-page .payment-card-heading p {
        margin: 0;
        color: var(--pay-muted);
        font-size: 13px;
    }

    /* DETAIL INFO */

    .payment-page .payment-info {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px;
    }

    .payment-page .payment-info-item {
        min-width: 0;
        padding: 15px;
        border: 1px solid #eeeaf3;
        border-radius: 11px;
        background: #faf9fd;
    }

    .payment-page .payment-info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 7px;
        color: var(--pay-muted);
        font-size: 12px;
    }

    .payment-page .payment-info-label .bi {
        color: #7c3aed;
        font-size: 16px;
    }

    .payment-page .payment-info-value {
        color: #3c3449;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    /* PRICE */

    .payment-page .payment-price {
        padding: 22px;
        border: 1px solid #ddd0ff;
        border-radius: 13px;
        background: #f5f0ff;
    }

    .payment-page .payment-price-label {
        color: #615a6d;
        font-size: 13px;
    }

    .payment-page .payment-price strong {
        display: block;
        margin: 8px 0;
        color: #5b21b6;
        font-size: clamp(25px, 3vw, 29px);
        font-weight: 700;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .payment-page .payment-price small {
        display: block;
        color: #746e80;
        font-size: 12px;
        line-height: 1.7;
    }

    /* FORM */

    .payment-page .payment-field {
        margin-bottom: 21px;
    }

    .payment-page .payment-field label {
        display: block;
        margin-bottom: 9px;
        color: #3c3449;
        font-size: 14px;
        font-weight: 600;
    }

    .payment-page .payment-field select,
    .payment-page .payment-field input[type="file"] {
        display: block;
        width: 100%;
        min-height: 49px;
        padding: 12px 14px;
        border: 1px solid #ded8e8;
        border-radius: 10px;
        background: #fff;
        color: #3c3449;
        font-family: inherit;
        font-size: 14px;
        outline: none;
    }

    .payment-page .payment-field select:focus,
    .payment-page .payment-field input:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(124,58,237,.1);
    }

    .payment-page .payment-field small {
        display: block;
        margin-top: 9px;
        color: var(--pay-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .payment-page .payment-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 49px;
        padding: 13px 18px;
        border: 0;
        border-radius: 10px;
        background: #6d28d9;
        color: #fff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s;
    }

    .payment-page .payment-button:hover {
        background: #5b21b6;
    }

    .payment-page .payment-button .bi {
        font-size: 18px;
    }

    /* PAYMENT STATUS */

    .payment-page .payment-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 25px;
        background: #fffbeb;
        color: #92400e;
        font-size: 13px;
        font-weight: 600;
    }

    .payment-page .payment-status.success {
        background: #dcfce7;
        color: #166534;
    }

    .payment-page .payment-status.failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .payment-page .payment-status.refund {
        background: #f3f4f6;
        color: #4b5563;
    }

    /* INSTRUCTION */

    .payment-page .payment-instruction {
        padding: 20px;
        margin-top: 22px;
        border: 1px solid #ddd0ff;
        border-radius: 12px;
        background: #faf7ff;
    }

    .payment-page .payment-instruction h3 {
        margin: 0 0 17px;
        color: #5b21b6;
        font-size: 17px;
        font-weight: 700;
    }

    .payment-page .payment-instruction-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 8px;
        padding: 11px 0;
        border-bottom: 1px solid #e9ddff;
        font-size: 14px;
    }

    .payment-page .payment-instruction-row span {
        color: #746e80;
    }

    .payment-page .payment-instruction-row strong {
        color: #3c3449;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .payment-page .payment-instruction-row:last-of-type {
        border-bottom: 0;
    }

    .payment-page .payment-simulation {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 15px;
        margin-top: 19px;
        border: 1px solid #fde68a;
        border-radius: 10px;
        background: #fffbeb;
        color: #92400e;
        font-size: 13px;
        line-height: 1.7;
    }

    .payment-page .payment-simulation .bi {
        flex-shrink: 0;
        font-size: 20px;
    }

    .payment-page .payment-simulation strong {
        display: block;
        margin-bottom: 4px;
    }

    .payment-page .payment-simulation p {
        margin: 0;
    }

    .payment-page .payment-method-note {
        margin: 17px 0 0;
        color: #615a6d;
        font-size: 13px;
        line-height: 1.7;
    }

    /* UPLOAD */

    .payment-page .payment-upload {
        padding-top: 21px;
        margin-top: 21px;
        border-top: 1px solid #e9ddff;
    }

    .payment-page .payment-upload h4 {
        margin: 0 0 14px;
        color: #3c3449;
        font-size: 16px;
        font-weight: 700;
    }

    .payment-page .payment-feedback {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 15px;
        margin-top: 18px;
        border-radius: 10px;
        background: #fffbeb;
        color: #92400e;
        font-size: 13px;
        line-height: 1.7;
    }

    .payment-page .payment-feedback.success {
        background: #f0fdf4;
        color: #166534;
    }

    .payment-page .payment-feedback.failed {
        background: #fef2f2;
        color: #991b1b;
    }

    .payment-page .payment-feedback .bi {
        flex-shrink: 0;
        margin-top: 1px;
        font-size: 19px;
    }

    /* SIDEBAR */

    .payment-page .payment-sidebar-card {
        padding: 21px;
        border: 1px solid var(--pay-border);
        border-radius: 14px;
        background: #fff;
    }

    .payment-page .payment-sidebar-card h3 {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: #292436;
        font-size: 16px;
        font-weight: 700;
    }

    .payment-page .payment-sidebar-card h3 .bi {
        color: #6d28d9;
        font-size: 19px;
    }

    .payment-page .payment-sidebar-card p {
        margin: 0;
        color: #746e80;
        font-size: 13px;
        line-height: 1.8;
    }

    .payment-page .payment-steps {
        display: grid;
        gap: 16px;
    }

    .payment-page .payment-step {
        display: flex;
        align-items: flex-start;
        gap: 11px;
    }

    .payment-page .payment-step-number {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 27px;
        height: 27px;
        border-radius: 8px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 12px;
        font-weight: 700;
    }

    .payment-page .payment-step p {
        margin: 1px 0 0;
        color: #615a6d;
        font-size: 13px;
        line-height: 1.7;
    }

    .payment-page .payment-security {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 16px;
        border: 1px solid #ddd0ff;
        border-radius: 12px;
        background: #f5f0ff;
        color: #615a6d;
        font-size: 13px;
        line-height: 1.7;
    }

    .payment-page .payment-security .bi {
        flex-shrink: 0;
        color: #6d28d9;
        font-size: 19px;
    }

    /* RESPONSIVE */

    @media (max-width: 900px) {
        .payment-page .payment-layout {
            grid-template-columns: 1fr;
        }

        .payment-page .payment-sidebar {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .payment-page .payment-security {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 600px) {
        .payment-page .payment-header {
            padding: 26px 22px;
        }

        .payment-page .payment-header h1 {
            font-size: 27px;
        }

        .payment-page .payment-header p {
            font-size: 14px;
        }

        .payment-page .payment-card {
            padding: 19px;
        }

        .payment-page .payment-info,
        .payment-page .payment-sidebar {
            grid-template-columns: 1fr;
        }

        .payment-page .payment-security {
            grid-column: auto;
        }

        .payment-page .payment-instruction {
            padding: 16px;
        }
    }
</style>

<div class="payment-page">

    <!-- BACK -->

    <a
        href="<?= site_url('client/jadwal') ?>"
        class="payment-back"
    >
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        Kembali ke Jadwal Saya
    </a>

    <!-- HEADER -->

    <section class="payment-header">

        <div class="payment-header-label">
            <i class="bi bi-credit-card" aria-hidden="true"></i>
            Transaksi Konseling
        </div>

        <h1>Pembayaran Konseling</h1>

        <p>
            Periksa informasi sesi konseling dan selesaikan
            proses pembayaran sesuai metode yang kamu pilih.
        </p>

    </section>

    <!-- FLASH MESSAGES -->

    <?php if ($errorMessage): ?>

        <div class="payment-notice error" role="alert">
            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
            <span><?= esc($errorMessage) ?></span>
        </div>

    <?php endif; ?>

    <?php if ($successMessage): ?>

        <div class="payment-notice success" role="status">
            <i class="bi bi-check-circle" aria-hidden="true"></i>
            <span><?= esc($successMessage) ?></span>
        </div>

    <?php endif; ?>

    <div class="payment-layout">

        <!-- ======================================
             MAIN CONTENT
        ====================================== -->

        <main class="payment-main">

            <!-- DETAIL CONSULTATION -->

            <section class="payment-card">

                <div class="payment-card-heading">

                    <div class="payment-heading-icon">
                        <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h2>Detail Konsultasi</h2>
                        <p>Informasi sesi yang akan dibayarkan.</p>
                    </div>

                </div>

                <div class="payment-info">

                    <div class="payment-info-item">

                        <div class="payment-info-label">
                            <i class="bi bi-hash" aria-hidden="true"></i>
                            ID Konsultasi
                        </div>

                        <div class="payment-info-value">
                            #<?= esc($idKonsultasi) ?>
                        </div>

                    </div>

                    <div class="payment-info-item">

                        <div class="payment-info-label">
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                            Tanggal Konseling
                        </div>

                        <div class="payment-info-value">
                            <?= esc(date(
                                'd M Y',
                                strtotime($konsultasi['tanggal'])
                            )) ?>
                        </div>

                    </div>

                    <div class="payment-info-item">

                        <div class="payment-info-label">
                            <i class="bi bi-clock" aria-hidden="true"></i>
                            Waktu Konseling
                        </div>

                        <div class="payment-info-value">
                            <?= esc(substr($konsultasi['waktu_mulai'], 0, 5)) ?>
                            –
                            <?= esc(substr($konsultasi['waktu_selesai'], 0, 5)) ?>
                        </div>

                    </div>

                    <div class="payment-info-item">

                        <div class="payment-info-label">
                            <i class="bi bi-chat-dots" aria-hidden="true"></i>
                            Tipe Sesi
                        </div>

                        <div class="payment-info-value">
                            <?= esc($konsultasi['tipe_sesi']) ?>
                        </div>

                    </div>

                    <div class="payment-info-item">

                        <div class="payment-info-label">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            Status Booking
                        </div>

                        <div class="payment-info-value">
                            <?= esc($konsultasi['status']) ?>
                        </div>

                    </div>

                </div>

            </section>

            <!-- PAYMENT METHOD -->

            <section class="payment-card">

                <div class="payment-card-heading">

                    <div class="payment-heading-icon">
                        <i class="bi bi-wallet2" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h2>Metode Pembayaran</h2>
                        <p>Pilih metode dan ikuti proses pembayaran.</p>
                    </div>

                </div>

                <?php if (!empty($pembayaran)): ?>

                    <!-- STATUS -->

                    <div>
                        <span class="payment-status <?= esc($statusClass, 'attr') ?>">
                            <i
                                class="bi <?= esc($statusIcon, 'attr') ?>"
                                aria-hidden="true"
                            ></i>

                            <?= esc($statusBayar) ?>
                        </span>
                    </div>

                    <?php if ($statusBayar === 'Pending'): ?>

                        <!-- PAYMENT INSTRUCTION -->

                        <div class="payment-instruction">

                            <h3>Instruksi Pembayaran</h3>

                            <div class="payment-instruction-row">
                                <span>Metode Pembayaran</span>
                                <strong><?= esc($metodeBayar) ?></strong>
                            </div>

                            <div class="payment-instruction-row">
                                <span>Kode Transaksi</span>
                                <strong>
                                    <?= esc($pembayaran['kode_transaksi'] ?? '-') ?>
                                </strong>
                            </div>

                            <div class="payment-instruction-row">
                                <span>Nominal</span>
                                <strong>
                                    Rp<?= number_format(
                                        (float) ($pembayaran['jumlah'] ?? $jumlah),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </strong>
                            </div>

                            <!-- SIMULATION WARNING -->

                            <div class="payment-simulation">

                                <i
                                    class="bi bi-exclamation-triangle"
                                    aria-hidden="true"
                                ></i>

                                <div>

                                    <strong>Simulasi Pembayaran</strong>

                                    <p>
                                        Ini merupakan simulasi pembayaran
                                        untuk demonstrasi aplikasi.
                                        Jangan mengirim uang sungguhan
                                        berdasarkan informasi ini.
                                    </p>

                                </div>

                            </div>

                            <!-- METHOD INFORMATION -->

                            <?php if ($metodeBayar === 'GoPay'): ?>

                                <p class="payment-method-note">
                                    Metode yang dipilih adalah GoPay.
                                    Lanjutkan sesuai alur simulasi pembayaran.
                                </p>

                            <?php elseif ($metodeBayar === 'OVO'): ?>

                                <p class="payment-method-note">
                                    Metode yang dipilih adalah OVO.
                                    Lanjutkan sesuai alur simulasi pembayaran.
                                </p>

                            <?php elseif ($metodeBayar === 'Transfer Bank'): ?>

                                <p class="payment-method-note">
                                    Metode yang dipilih adalah Transfer Bank.
                                    Lanjutkan sesuai alur simulasi pembayaran.
                                </p>

                            <?php elseif ($metodeBayar === 'Virtual Account'): ?>

                                <p class="payment-method-note">
                                    Metode yang dipilih adalah Virtual Account.
                                    Lanjutkan sesuai alur simulasi pembayaran.
                                </p>

                            <?php endif; ?>

                            <p class="payment-method-note">
                                Status pembayaran tetap Pending sampai
                                diverifikasi oleh admin.
                            </p>

                            <!-- UPLOAD PROOF -->

                            <?php if (empty($pembayaran['bukti_bayar'])): ?>

                                <div class="payment-upload">

                                    <h4>Unggah Bukti Pembayaran</h4>

                                    <form
                                        action="<?= site_url(
                                            'client/pembayaran/upload-bukti/' .
                                            $idKonsultasi
                                        ) ?>"
                                        method="post"
                                        enctype="multipart/form-data"
                                    >

                                        <?= csrf_field() ?>

                                        <div class="payment-field">

                                            <label for="bukti_bayar">
                                                Pilih File Bukti Pembayaran
                                            </label>

                                            <input
                                                type="file"
                                                id="bukti_bayar"
                                                name="bukti_bayar"
                                                accept="image/jpeg,image/png,image/webp"
                                                required
                                            >

                                            <small>
                                                Format JPG, PNG, atau WebP.
                                                Maksimal 2 MB.
                                            </small>

                                        </div>

                                        <button
                                            type="submit"
                                            class="payment-button"
                                        >
                                            <i
                                                class="bi bi-cloud-arrow-up"
                                                aria-hidden="true"
                                            ></i>

                                            Unggah Bukti Pembayaran
                                        </button>

                                    </form>

                                </div>

                            <?php else: ?>

                                <div class="payment-feedback">

                                    <i
                                        class="bi bi-hourglass-split"
                                        aria-hidden="true"
                                    ></i>

                                    <span>
                                        Bukti pembayaran sudah diunggah
                                        dan sedang menunggu verifikasi admin.
                                    </span>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php elseif ($statusBayar === 'Sukses'): ?>

                        <div class="payment-feedback success">

                            <i class="bi bi-check-circle" aria-hidden="true"></i>

                            <span>
                                Pembayaran sudah diverifikasi dan
                                dinyatakan berhasil.
                            </span>

                        </div>

                    <?php elseif ($statusBayar === 'Gagal'): ?>

                        <div class="payment-feedback failed">

                            <i class="bi bi-x-circle" aria-hidden="true"></i>

                            <span>
                                Pembayaran ditolak atau gagal.
                                Silakan hubungi admin untuk informasi
                                lebih lanjut.
                            </span>

                        </div>

                    <?php elseif ($statusBayar === 'Refund'): ?>

                        <div class="payment-feedback">

                            <i
                                class="bi bi-arrow-counterclockwise"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Pembayaran ini memiliki status Refund.
                            </span>

                        </div>

                    <?php endif; ?>

                <?php else: ?>

                    <!-- SELECT PAYMENT METHOD -->

                    <form
                        action="<?= site_url(
                            'client/pembayaran/proses/' .
                            $idKonsultasi
                        ) ?>"
                        method="post"
                    >

                        <?= csrf_field() ?>

                        <div class="payment-field">

                            <label for="metode">
                                Pilih Metode Pembayaran
                            </label>

                            <select
                                name="metode"
                                id="metode"
                                required
                            >

                                <option value="">
                                    Pilih metode pembayaran
                                </option>

                                <option value="GoPay">
                                    GoPay
                                </option>

                                <option value="OVO">
                                    OVO
                                </option>

                                <option value="Transfer Bank">
                                    Transfer Bank
                                </option>

                                <option value="Virtual Account">
                                    Virtual Account
                                </option>

                            </select>

                            <small>
                                Pilih salah satu metode untuk
                                membuat transaksi simulasi pembayaran.
                            </small>

                        </div>

                        <button
                            type="submit"
                            class="payment-button"
                        >
                            <i
                                class="bi bi-arrow-right-circle"
                                aria-hidden="true"
                            ></i>

                            Lanjutkan Pembayaran
                        </button>

                    </form>

                <?php endif; ?>

            </section>

        </main>

        <!-- ======================================
             SIDEBAR
        ====================================== -->

        <aside class="payment-sidebar">

            <!-- TOTAL PRICE -->

            <section class="payment-price">

                <div class="payment-price-label">
                    Total Pembayaran
                </div>

                <strong>
                    Rp<?= number_format(
                        $jumlahBayar,
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>

                <small>
                    Untuk satu sesi konseling selama 60 menit.
                </small>

            </section>

            <!-- PAYMENT STEPS -->

            <section class="payment-sidebar-card">

                <h3>
                    <i class="bi bi-list-check" aria-hidden="true"></i>
                    Alur Pembayaran
                </h3>

                <div class="payment-steps">

                    <div class="payment-step">

                        <div class="payment-step-number">1</div>

                        <p>
                            Periksa detail konsultasi
                            dan total pembayaran.
                        </p>

                    </div>

                    <div class="payment-step">

                        <div class="payment-step-number">2</div>

                        <p>
                            Pilih metode pembayaran
                            yang ingin digunakan.
                        </p>

                    </div>

                    <div class="payment-step">

                        <div class="payment-step-number">3</div>

                        <p>
                            Unggah bukti pembayaran
                            sesuai alur simulasi.
                        </p>

                    </div>

                    <div class="payment-step">

                        <div class="payment-step-number">4</div>

                        <p>
                            Tunggu verifikasi pembayaran
                            dari admin.
                        </p>

                    </div>

                </div>

            </section>

            <!-- INFORMATION -->

            <div class="payment-security">

                <i
                    class="bi bi-shield-check"
                    aria-hidden="true"
                ></i>

                <span>
                    Halaman ini menggunakan simulasi pembayaran
                    untuk kebutuhan pengembangan dan pengujian
                    Counseling Platform.
                </span>

            </div>

        </aside>

    </div>

</div>

<?= $this->endSection() ?>
