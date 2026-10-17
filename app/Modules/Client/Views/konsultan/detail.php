
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/** @var array $item */

$nama = $item['nama_lengkap'] ?? 'Konsultan';
$spesialisasi = $item['spesialisasi'] ?? '';
$pengalaman = $item['pengalaman_tahun'] ?? null;
$email = $item['email'] ?? '';
$telepon = $item['no_telepon'] ?? '';

$inisial = function_exists('mb_substr')
    ? mb_substr($nama, 0, 1, 'UTF-8')
    : substr($nama, 0, 1);

$inisial = function_exists('mb_strtoupper')
    ? mb_strtoupper($inisial, 'UTF-8')
    : strtoupper($inisial);
?>

<style>
    /* ==========================================
       DETAIL KONSULTAN - CLIENT
       MEDIUM & CONSISTENT DESIGN
    ========================================== */

    .kdetail-page {
        --kd-primary: #6d28d9;
        --kd-dark: #5b21b6;
        --kd-text: #292436;
        --kd-muted: #746e80;
        --kd-border: #eae5f1;

        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        color: var(--kd-text);
        font-size: 14px;
        line-height: 1.6;
    }

    .kdetail-page *,
    .kdetail-page *::before,
    .kdetail-page *::after {
        box-sizing: border-box;
    }

    /* ==========================================
       BACK NAVIGATION
    ========================================== */

    .kdetail-page .kdetail-back {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 22px;
        color: #5b21b6;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: gap .2s;
    }

    .kdetail-page .kdetail-back:hover {
        gap: 13px;
        color: #6d28d9;
    }

    .kdetail-page .kdetail-back .bi {
        font-size: 18px;
    }

    /* ==========================================
       PROFILE HEADER
    ========================================== */

    .kdetail-page .kdetail-header {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        margin-bottom: 24px;
        border-radius: 18px;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
        color: #fff;
    }

    .kdetail-page .kdetail-header::after {
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

    .kdetail-page .kdetail-header > * {
        position: relative;
        z-index: 1;
    }

    .kdetail-page .kdetail-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 22px;
        padding: 7px 13px;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 30px;
        background: rgba(255,255,255,.12);
        color: #fff;
        font-size: 13px;
        font-weight: 500;
    }

    .kdetail-page .kdetail-profile {
        display: flex;
        align-items: center;
        gap: 22px;
        min-width: 0;
    }

    .kdetail-page .kdetail-avatar {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 92px;
        height: 92px;
        border: 2px solid rgba(255,255,255,.3);
        border-radius: 23px;
        background: rgba(255,255,255,.2);
        color: #fff;
        font-size: 38px;
        font-weight: 700;
    }

    .kdetail-page .kdetail-profile-info {
        flex: 1;
        min-width: 0;
    }

    .kdetail-page .kdetail-header h1 {
        margin: 0 0 9px;
        color: #fff;
        font-size: clamp(25px, 3vw, 32px);
        font-weight: 700;
        line-height: 1.35;
        letter-spacing: -.4px;
        overflow-wrap: anywhere;
    }

    .kdetail-page .kdetail-specialization {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin: 0;
        color: #f0e7ff;
        font-size: 15px;
        line-height: 1.7;
    }

    .kdetail-page .kdetail-specialization .bi {
        font-size: 18px;
    }

    /* ==========================================
       SECTION CARD
    ========================================== */

    .kdetail-page .kdetail-card {
        padding: 25px;
        margin-bottom: 22px;
        border: 1px solid var(--kd-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61,31,100,.025);
    }

    .kdetail-page .kdetail-card-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 23px;
    }

    .kdetail-page .kdetail-heading-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 22px;
    }

    .kdetail-page .kdetail-card h2 {
        margin: 0;
        color: var(--kd-text);
        font-size: 20px;
        font-weight: 700;
        line-height: 1.4;
    }

    .kdetail-page .kdetail-card-subtitle {
        margin-top: 5px;
        color: var(--kd-muted);
        font-size: 14px;
        line-height: 1.6;
    }

    /* ==========================================
       INFORMATION GRID
    ========================================== */

    .kdetail-page .kdetail-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .kdetail-page .kdetail-info-item {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        min-width: 0;
        padding: 19px;
        border: 1px solid #eeeaf3;
        border-radius: 12px;
        background: #faf9fd;
    }

    .kdetail-page .kdetail-info-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 11px;
        background: #ede0ff;
        color: #6d28d9;
        font-size: 20px;
    }

    .kdetail-page .kdetail-info-content {
        flex: 1;
        min-width: 0;
    }

    .kdetail-page .kdetail-info-label {
        margin-bottom: 5px;
        color: var(--kd-muted);
        font-size: 13px;
    }

    .kdetail-page .kdetail-info-value {
        color: #3c3449;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.65;
        overflow-wrap: anywhere;
    }

    /* ==========================================
       INFORMATION NOTE
    ========================================== */

    .kdetail-page .kdetail-note {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        margin-top: 22px;
        border: 1px solid #ddd0ff;
        border-radius: 12px;
        background: #f5f0ff;
        color: #615a6d;
        font-size: 14px;
        line-height: 1.7;
    }

    .kdetail-page .kdetail-note .bi {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        margin: 0;
        color: #6d28d9;
        font-size: 21px;
        line-height: 1;
    }

    .kdetail-page .kdetail-note span {
        flex: 1;
        min-width: 0;
    }

    /* ==========================================
       BOOKING CARD
    ========================================== */

    .kdetail-page .kdetail-booking {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 25px;
        border: 1px solid #ddd0ff;
        border-radius: 15px;
        background: #f8f5ff;
    }

    .kdetail-page .kdetail-booking-content {
        flex: 1;
        min-width: 0;
    }

    .kdetail-page .kdetail-booking h2 {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 8px;
        color: #3b1678;
        font-size: 19px;
        font-weight: 700;
    }

    .kdetail-page .kdetail-booking h2 .bi {
        color: #6d28d9;
        font-size: 21px;
    }

    .kdetail-page .kdetail-booking p {
        max-width: 580px;
        margin: 0;
        color: #615a6d;
        font-size: 14px;
        line-height: 1.75;
    }

    .kdetail-page .kdetail-booking-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-shrink: 0;
        min-height: 48px;
        padding: 13px 22px;
        border: 1px solid transparent;
        border-radius: 10px;
        background: #6d28d9;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        text-decoration: none;
        white-space: nowrap;
        transition: background .2s, transform .2s;
    }

    .kdetail-page .kdetail-booking-button:hover {
        background: #5b21b6;
        color: #fff;
        transform: translateY(-2px);
    }

    .kdetail-page .kdetail-booking-button .bi {
        font-size: 19px;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 850px) {
        .kdetail-page .kdetail-booking {
            align-items: stretch;
            flex-direction: column;
        }

        .kdetail-page .kdetail-booking-button {
            align-self: flex-start;
        }
    }

    @media (max-width: 700px) {
        .kdetail-page .kdetail-header {
            padding: 26px 22px;
            border-radius: 15px;
        }

        .kdetail-page .kdetail-profile {
            gap: 16px;
        }

        .kdetail-page .kdetail-avatar {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            font-size: 30px;
        }

        .kdetail-page .kdetail-header h1 {
            font-size: 25px;
        }

        .kdetail-page .kdetail-specialization {
            font-size: 14px;
        }

        .kdetail-page .kdetail-card {
            padding: 19px;
        }

        .kdetail-page .kdetail-card h2 {
            font-size: 19px;
        }

        .kdetail-page .kdetail-info-grid {
            grid-template-columns: 1fr;
        }

        .kdetail-page .kdetail-booking {
            padding: 20px;
        }
    }

    @media (max-width: 420px) {
        .kdetail-page .kdetail-profile {
            align-items: flex-start;
            flex-direction: column;
        }

        .kdetail-page .kdetail-header h1 {
            font-size: 24px;
        }

        .kdetail-page .kdetail-info-item {
            padding: 16px;
        }

        .kdetail-page .kdetail-booking-button {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .kdetail-page *,
        .kdetail-page *::before,
        .kdetail-page *::after {
            transition: none !important;
        }
    }
</style>

<div class="kdetail-page">

    <!-- ==========================================
         BACK NAVIGATION
    ========================================== -->

    <a
        href="<?= site_url('client/konsultan') ?>"
        class="kdetail-back"
    >
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        Kembali ke Cari Psikolog
    </a>

    <!-- ==========================================
         PROFILE HEADER
    ========================================== -->

    <section class="kdetail-header">

        <div class="kdetail-label">
            <i class="bi bi-person-vcard" aria-hidden="true"></i>
            Profil Konsultan
        </div>

        <div class="kdetail-profile">

            <div class="kdetail-avatar" aria-hidden="true">
                <?= esc($inisial) ?>
            </div>

            <div class="kdetail-profile-info">

                <h1>
                    <?= esc($nama) ?>
                </h1>

                <p class="kdetail-specialization">
                    <i
                        class="bi bi-heart-pulse"
                        aria-hidden="true"
                    ></i>

                    <?= esc(
                        $spesialisasi ?: 'Spesialisasi belum dicantumkan'
                    ) ?>
                </p>

            </div>

        </div>

    </section>

    <!-- ==========================================
         CONSULTANT INFORMATION
    ========================================== -->

    <section class="kdetail-card">

        <div class="kdetail-card-heading">

            <div class="kdetail-heading-icon">
                <i class="bi bi-person-lines-fill" aria-hidden="true"></i>
            </div>

            <div>
                <h2>Informasi Konsultan</h2>

                <div class="kdetail-card-subtitle">
                    Kenali profil dan pengalaman konsultan sebelum
                    membuat janji konseling.
                </div>
            </div>

        </div>

        <div class="kdetail-info-grid">

            <!-- SPESIALISASI -->

            <div class="kdetail-info-item">

                <div class="kdetail-info-icon">
                    <i class="bi bi-heart-pulse" aria-hidden="true"></i>
                </div>

                <div class="kdetail-info-content">

                    <div class="kdetail-info-label">
                        Spesialisasi
                    </div>

                    <div class="kdetail-info-value">
                        <?= esc(
                            $spesialisasi ?: 'Belum dicantumkan'
                        ) ?>
                    </div>

                </div>

            </div>

            <!-- PENGALAMAN -->

            <div class="kdetail-info-item">

                <div class="kdetail-info-icon">
                    <i class="bi bi-award" aria-hidden="true"></i>
                </div>

                <div class="kdetail-info-content">

                    <div class="kdetail-info-label">
                        Pengalaman
                    </div>

                    <div class="kdetail-info-value">
                        <?= $pengalaman !== null && $pengalaman !== ''
                            ? esc((string) $pengalaman) . ' tahun'
                            : 'Belum dicantumkan' ?>
                    </div>

                </div>

            </div>

            <!-- EMAIL -->

            <div class="kdetail-info-item">

                <div class="kdetail-info-icon">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                </div>

                <div class="kdetail-info-content">

                    <div class="kdetail-info-label">
                        Email
                    </div>

                    <div class="kdetail-info-value">
                        <?= esc($email ?: 'Belum tersedia') ?>
                    </div>

                </div>

            </div>

            <!-- PHONE -->

            <div class="kdetail-info-item">

                <div class="kdetail-info-icon">
                    <i class="bi bi-telephone" aria-hidden="true"></i>
                </div>

                <div class="kdetail-info-content">

                    <div class="kdetail-info-label">
                        Nomor Telepon
                    </div>

                    <div class="kdetail-info-value">
                        <?= esc($telepon ?: 'Belum tersedia') ?>
                    </div>

                </div>

            </div>

        </div>

        <!-- INFORMATION NOTE -->

        <div class="kdetail-note">

            <i class="bi bi-info-circle" aria-hidden="true"></i>

            <span>
                Informasi profil ditampilkan berdasarkan data
                konsultan yang tersedia di sistem.
            </span>

        </div>

    </section>

    <!-- ==========================================
         BOOKING SECTION
    ========================================== -->

    <section class="kdetail-booking">

        <div class="kdetail-booking-content">

            <h2>
                <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                Ingin Memulai Konseling?
            </h2>

            <p>
                Buat janji konseling dengan konsultan ini
                untuk melanjutkan proses konsultasi sesuai
                kebutuhanmu.
            </p>

        </div>

        <a
            href="<?= site_url(
                'client/konsultasi/buat/' . $item['id_profil']
            ) ?>"
            class="kdetail-booking-button"
        >
            <i class="bi bi-calendar-plus" aria-hidden="true"></i>
            Buat Janji Konseling
        </a>

    </section>

</div>

<?= $this->endSection() ?>
