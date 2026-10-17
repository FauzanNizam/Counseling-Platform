<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/** @var array $konsultasi */

$jadwalData = $konsultasi ?? [];

$totalJadwal = count($jadwalData);
$totalMenunggu = 0;
$totalDikonfirmasi = 0;

foreach ($jadwalData as $jadwal) {
    $status = $jadwal['status'] ?? '';

    if ($status === 'Menunggu Konfirmasi') {
        $totalMenunggu++;
    }

    if ($status === 'Dikonfirmasi') {
        $totalDikonfirmasi++;
    }
}

$successMessage = session()->getFlashdata('success');
$errorMessage = session()->getFlashdata('error');
?>

<style>
    /* ==========================================
       JADWAL SAYA - CLIENT
       MEDIUM & CONSISTENT DESIGN
    ========================================== */

    .jadwal-page {
        --j-primary: #6d28d9;
        --j-dark: #5b21b6;
        --j-text: #292436;
        --j-muted: #746e80;
        --j-border: #eae5f1;

        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        color: var(--j-text);
        font-size: 14px;
        line-height: 1.6;
    }

    .jadwal-page *,
    .jadwal-page *::before,
    .jadwal-page *::after {
        box-sizing: border-box;
    }

    /* ==========================================
       HEADER
    ========================================== */

    .jadwal-page .jadwal-header {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        margin-bottom: 24px;
        border-radius: 18px;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
        color: #fff;
    }

    .jadwal-page .jadwal-header::after {
        content: "";
        position: absolute;
        top: -90px;
        right: -65px;
        width: 240px;
        height: 240px;
        border: 38px solid rgba(255, 255, 255, .08);
        border-radius: 50%;
        pointer-events: none;
    }

    .jadwal-page .jadwal-header>* {
        position: relative;
        z-index: 1;
    }

    /* ==========================================
   MODAL KONFIRMASI PEMBATALAN
========================================== */

    .modal-batalkan {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-batalkan.is-active {
        display: flex;
    }

    .modal-batalkan-overlay {
        position: absolute;
        inset: 0;
        background: rgba(25, 18, 40, 0.58);
        backdrop-filter: blur(4px);
    }

    .modal-batalkan-dialog {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 420px;
        padding: 32px 28px;
        border: 1px solid #eee7f8;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 24px 70px rgba(35, 18, 65, 0.22);
        text-align: center;
        animation: modalBatalkanMasuk 0.2s ease-out;
    }

    .modal-batalkan-icon {
        display: grid;
        place-items: center;
        width: 68px;
        height: 68px;
        margin: 0 auto 18px;
        border-radius: 20px;
        background: #fef2f2;
        color: #dc2626;
        font-size: 30px;
    }

    .modal-batalkan-dialog h2 {
        margin: 0 0 10px;
        color: #292436;
        font-size: 23px;
        font-weight: 700;
    }

    .modal-batalkan-dialog p {
        margin: 0;
        color: #746e80;
        font-size: 14px;
        line-height: 1.8;
    }

    .modal-batalkan-actions {
        display: flex;
        gap: 12px;
        margin-top: 26px;
    }

    .modal-batalkan-actions button {
        flex: 1;
        min-height: 46px;
        padding: 11px 14px;
        border: 1px solid #e5e0ec;
        border-radius: 10px;
        background: #fff;
        color: #514760;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .modal-batalkan-actions button:last-child {
        border-color: #dc2626;
        background: #dc2626;
        color: #fff;
    }

    .modal-batalkan-actions button:first-child:hover {
        background: #f8f5fc;
    }

    .modal-batalkan-actions button:last-child:hover {
        border-color: #b91c1c;
        background: #b91c1c;
    }

    body.modal-batalkan-terbuka {
        overflow: hidden;
    }

    @keyframes modalBatalkanMasuk {
        from {
            opacity: 0;
            transform: translateY(10px) scale(0.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @media (max-width: 420px) {
        .modal-batalkan-dialog {
            padding: 26px 20px;
        }

        .modal-batalkan-actions {
            flex-direction: column-reverse;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .modal-batalkan-dialog {
            animation: none;
        }
    }


    .jadwal-page .jadwal-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding: 7px 13px;
        border: 1px solid rgba(255, 255, 255, .3);
        border-radius: 30px;
        background: rgba(255, 255, 255, .12);
        color: #fff;
        font-size: 13px;
        font-weight: 500;
    }

    .jadwal-page .jadwal-label .bi {
        font-size: 16px;
    }

    .jadwal-page .jadwal-header h1 {
        margin: 0 0 12px;
        color: #fff;
        font-size: clamp(27px, 3vw, 32px);
        font-weight: 700;
        line-height: 1.3;
        letter-spacing: -.4px;
    }

    .jadwal-page .jadwal-header p {
        max-width: 680px;
        margin: 0;
        color: #f0e7ff;
        font-size: 15px;
        line-height: 1.75;
    }

    /* ==========================================
       FLASH MESSAGES
    ========================================== */

    .jadwal-page .jadwal-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 18px;
        margin-bottom: 22px;
        border: 1px solid transparent;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.7;
    }

    .jadwal-page .jadwal-alert .bi {
        flex-shrink: 0;
        font-size: 20px;
    }

    .jadwal-page .jadwal-alert.success {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    .jadwal-page .jadwal-alert.error {
        border-color: #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }

    /* ==========================================
       SUMMARY
    ========================================== */

    .jadwal-page .jadwal-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .jadwal-page .jadwal-summary-card {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
        padding: 20px;
        border: 1px solid var(--j-border);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61, 31, 100, .025);
    }

    .jadwal-page .jadwal-summary-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #ede0ff;
        color: #6d28d9;
        font-size: 23px;
    }

    .jadwal-page .jadwal-summary-icon.pending {
        background: #fff4d6;
        color: #a16207;
    }

    .jadwal-page .jadwal-summary-icon.confirmed {
        background: #dcfce7;
        color: #15803d;
    }

    .jadwal-page .jadwal-summary-content {
        min-width: 0;
    }

    .jadwal-page .jadwal-summary-value {
        margin: 0 0 3px;
        color: var(--j-text);
        font-size: 26px;
        font-weight: 700;
        line-height: 1.2;
    }

    .jadwal-page .jadwal-summary-label {
        color: var(--j-muted);
        font-size: 13px;
        line-height: 1.5;
    }

    /* ==========================================
       SECTION HEADING
    ========================================== */

    .jadwal-page .jadwal-section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 18px;
    }

    .jadwal-page .jadwal-section-heading h2 {
        margin: 0;
        color: var(--j-text);
        font-size: 20px;
        font-weight: 700;
    }

    .jadwal-page .jadwal-new-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 43px;
        padding: 10px 17px;
        border: 1px solid #ddd0ff;
        border-radius: 10px;
        background: #f5f0ff;
        color: #5b21b6;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s, color .2s;
    }

    .jadwal-page .jadwal-new-link:hover {
        background: #6d28d9;
        color: #fff;
        text-decoration: none;
    }

    /* ==========================================
       SCHEDULE LIST
    ========================================== */

    .jadwal-page .jadwal-list {
        display: grid;
        gap: 18px;
    }

    .jadwal-page .jadwal-card {
        overflow: hidden;
        border: 1px solid var(--j-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61, 31, 100, .025);
        transition: border-color .2s, box-shadow .2s;
    }

    .jadwal-page .jadwal-card:hover {
        border-color: #d8bfff;
        box-shadow: 0 8px 24px rgba(91, 33, 182, .05);
    }

    .jadwal-page .jadwal-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        padding: 22px 24px;
        border-bottom: 1px solid #f0edf4;
    }

    .jadwal-page .jadwal-consultant {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .jadwal-page .jadwal-consultant-avatar {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 51px;
        height: 51px;
        border-radius: 13px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 21px;
        font-weight: 700;
    }

    .jadwal-page .jadwal-consultant-info {
        min-width: 0;
    }

    .jadwal-page .jadwal-consultant-info h3 {
        margin: 0 0 4px;
        color: var(--j-text);
        font-size: 17px;
        font-weight: 700;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .jadwal-page .jadwal-consultant-info p {
        margin: 0;
        color: var(--j-muted);
        font-size: 13px;
    }

    /* ==========================================
       STATUS
    ========================================== */

    .jadwal-page .jadwal-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        border: 1px solid #fde68a;
        border-radius: 30px;
        background: #fffbeb;
        color: #92400e;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
        white-space: nowrap;
    }

    .jadwal-page .jadwal-status .bi {
        font-size: 14px;
    }

    .jadwal-page .jadwal-status.status-dikonfirmasi {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    .jadwal-page .jadwal-status.status-dibatalkan {
        border-color: #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }

    .jadwal-page .jadwal-status.status-other {
        border-color: #e5e7eb;
        background: #f9fafb;
        color: #4b5563;
    }

    /* ==========================================
       SCHEDULE DETAILS
    ========================================== */

    .jadwal-page .jadwal-card-body {
        padding: 23px 24px;
    }

    .jadwal-page .jadwal-info {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
    }

    .jadwal-page .jadwal-info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 0;
        padding: 16px;
        border: 1px solid #eeeaf3;
        border-radius: 11px;
        background: #faf9fd;
    }

    .jadwal-page .jadwal-info-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #ede0ff;
        color: #6d28d9;
        font-size: 18px;
    }

    .jadwal-page .jadwal-info-content {
        flex: 1;
        min-width: 0;
    }

    .jadwal-page .jadwal-info-label {
        margin-bottom: 4px;
        color: var(--j-muted);
        font-size: 12px;
    }

    .jadwal-page .jadwal-info-value {
        color: #3c3449;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    /* ==========================================
       ACTION BUTTONS
    ========================================== */

    .jadwal-page .jadwal-card-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 11px;
        padding: 18px 24px;
        border-top: 1px solid #f0edf4;
        background: #fff;
    }

    .jadwal-page .jadwal-card-actions form {
        margin: 0;
    }

    .jadwal-page .jadwal-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 44px;
        padding: 11px 17px;
        border: 1px solid transparent;
        border-radius: 9px;
        background: #6d28d9;
        color: #fff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .jadwal-page .jadwal-action:hover {
        background: #5b21b6;
        color: #fff;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .jadwal-page .jadwal-action .bi {
        font-size: 17px;
    }

    .jadwal-page .jadwal-action.whatsapp {
        background: #16a34a;
    }

    .jadwal-page .jadwal-action.whatsapp:hover {
        background: #15803d;
    }

    .jadwal-page .jadwal-action.cancel {
        border-color: #fecaca;
        background: #fff;
        color: #dc2626;
    }

    .jadwal-page .jadwal-action.cancel:hover {
        background: #fef2f2;
        color: #b91c1c;
    }

    /* ==========================================
       EMPTY STATE
    ========================================== */

    .jadwal-page .jadwal-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 13px;
        padding: 48px 24px;
        border: 1px dashed #c4b5fd;
        border-radius: 15px;
        background: #fff;
        text-align: center;
    }

    .jadwal-page .jadwal-empty-icon {
        display: grid;
        place-items: center;
        width: 74px;
        height: 74px;
        margin-bottom: 5px;
        border-radius: 20px;
        background: #f5f0ff;
        color: #6d28d9;
        font-size: 34px;
    }

    .jadwal-page .jadwal-empty h3 {
        margin: 0;
        color: var(--j-text);
        font-size: 20px;
        font-weight: 700;
    }

    .jadwal-page .jadwal-empty p {
        max-width: 460px;
        margin: 0;
        color: var(--j-muted);
        font-size: 14px;
        line-height: 1.75;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 850px) {
        .jadwal-page .jadwal-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .jadwal-page .jadwal-summary-card:first-child {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 700px) {
        .jadwal-page .jadwal-header {
            padding: 26px 22px;
            border-radius: 15px;
        }

        .jadwal-page .jadwal-header h1 {
            font-size: 27px;
        }

        .jadwal-page .jadwal-header p {
            font-size: 14px;
        }

        .jadwal-page .jadwal-info {
            grid-template-columns: 1fr;
        }

        .jadwal-page .jadwal-card-header,
        .jadwal-page .jadwal-card-body,
        .jadwal-page .jadwal-card-actions {
            padding: 19px;
        }
    }

    @media (max-width: 480px) {
        .jadwal-page .jadwal-summary {
            grid-template-columns: 1fr;
        }

        .jadwal-page .jadwal-summary-card:first-child {
            grid-column: auto;
        }

        .jadwal-page .jadwal-section-heading {
            align-items: stretch;
            flex-direction: column;
        }

        .jadwal-page .jadwal-new-link {
            width: 100%;
        }

        .jadwal-page .jadwal-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .jadwal-page .jadwal-card-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .jadwal-page .jadwal-card-actions form,
        .jadwal-page .jadwal-action {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .jadwal-page *,
        .jadwal-page *::before,
        .jadwal-page *::after {
            transition: none !important;
        }
    }
</style>

<div class="jadwal-page">

    <!-- ==========================================
         HEADER
    ========================================== -->

    <section class="jadwal-header">

        <div class="jadwal-label">
            <i class="bi bi-calendar2-week" aria-hidden="true"></i>
            Kelola jadwal konselingmu
        </div>

        <h1>Jadwal Saya</h1>

        <p>
            Pantau pengajuan dan jadwal konselingmu,
            lihat status konfirmasi, serta kelola
            sesi konsultasi yang telah dijadwalkan.
        </p>

    </section>

    <!-- ==========================================
         FLASH MESSAGES
    ========================================== -->

    <?php if ($successMessage): ?>

        <div class="jadwal-alert success" role="status">

            <i class="bi bi-check-circle" aria-hidden="true"></i>

            <span>
                <?= esc($successMessage) ?>
            </span>

        </div>

    <?php endif; ?>

    <?php if ($errorMessage): ?>

        <div class="jadwal-alert error" role="alert">

            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>

            <span>
                <?= esc($errorMessage) ?>
            </span>

        </div>

    <?php endif; ?>

    <!-- ==========================================
         SUMMARY
    ========================================== -->

    <section class="jadwal-summary" aria-label="Ringkasan jadwal">

        <!-- TOTAL -->

        <div class="jadwal-summary-card">

            <div class="jadwal-summary-icon">
                <i class="bi bi-calendar2-week" aria-hidden="true"></i>
            </div>

            <div class="jadwal-summary-content">

                <div class="jadwal-summary-value">
                    <?= $totalJadwal ?>
                </div>

                <div class="jadwal-summary-label">
                    Total Jadwal
                </div>

            </div>

        </div>

        <!-- MENUNGGU -->

        <div class="jadwal-summary-card">

            <div class="jadwal-summary-icon pending">
                <i class="bi bi-hourglass-split" aria-hidden="true"></i>
            </div>

            <div class="jadwal-summary-content">

                <div class="jadwal-summary-value">
                    <?= $totalMenunggu ?>
                </div>

                <div class="jadwal-summary-label">
                    Menunggu Konfirmasi
                </div>

            </div>

        </div>

        <!-- DIKONFIRMASI -->

        <div class="jadwal-summary-card">

            <div class="jadwal-summary-icon confirmed">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
            </div>

            <div class="jadwal-summary-content">

                <div class="jadwal-summary-value">
                    <?= $totalDikonfirmasi ?>
                </div>

                <div class="jadwal-summary-label">
                    Dikonfirmasi
                </div>

            </div>

        </div>

    </section>

    <!-- ==========================================
         SECTION HEADING
    ========================================== -->

    <div class="jadwal-section-heading">

        <h2>Daftar Jadwal Konseling</h2>

        <a
            href="<?= site_url('client/konsultan') ?>"
            class="jadwal-new-link">
            <i class="bi bi-plus-circle" aria-hidden="true"></i>
            Buat Janji Baru
        </a>

    </div>

    <!-- ==========================================
         EMPTY STATE
    ========================================== -->

    <?php if (empty($jadwalData)): ?>

        <div class="jadwal-empty">

            <div class="jadwal-empty-icon">
                <i class="bi bi-calendar-x" aria-hidden="true"></i>
            </div>

            <h3>Belum Ada Jadwal Konseling</h3>

            <p>
                Kamu belum memiliki pengajuan konseling.
                Temukan psikolog yang sesuai dengan kebutuhanmu
                untuk mulai membuat janji konseling.
            </p>

            <a
                href="<?= site_url('client/konsultan') ?>"
                class="jadwal-action">
                <i class="bi bi-search" aria-hidden="true"></i>
                Cari Psikolog
            </a>

        </div>

    <?php else: ?>

        <!-- ======================================
             SCHEDULE LIST
        ====================================== -->

        <div class="jadwal-list">

            <?php foreach ($jadwalData as $item): ?>

                <?php
                $namaKonsultan = $item['nama_konsultan'] ?? 'Konsultan';
                $status = $item['status'] ?? 'Tidak diketahui';

                $tanggalRaw = $item['tanggal'] ?? '';
                $tanggalTimestamp = $tanggalRaw
                    ? strtotime($tanggalRaw)
                    : false;

                $tanggalTampil = $tanggalTimestamp !== false
                    ? date('d M Y', $tanggalTimestamp)
                    : '-';

                $tanggalPesan = $tanggalTimestamp !== false
                    ? date('d-m-Y', $tanggalTimestamp)
                    : '-';

                $waktuMulai = !empty($item['waktu_mulai'])
                    ? substr($item['waktu_mulai'], 0, 5)
                    : '-';

                $waktuSelesai = !empty($item['waktu_selesai'])
                    ? substr($item['waktu_selesai'], 0, 5)
                    : '-';

                $inisial = function_exists('mb_substr')
                    ? mb_substr($namaKonsultan, 0, 1, 'UTF-8')
                    : substr($namaKonsultan, 0, 1);

                $inisial = function_exists('mb_strtoupper')
                    ? mb_strtoupper($inisial, 'UTF-8')
                    : strtoupper($inisial);

                $statusClass = 'status-other';
                $statusIcon = 'bi-info-circle';

                if ($status === 'Dikonfirmasi') {
                    $statusClass = 'status-dikonfirmasi';
                    $statusIcon = 'bi-check-circle';
                } elseif ($status === 'Dibatalkan') {
                    $statusClass = 'status-dibatalkan';
                    $statusIcon = 'bi-x-circle';
                } elseif ($status === 'Menunggu Konfirmasi') {
                    $statusClass = '';
                    $statusIcon = 'bi-hourglass-split';
                }

                $nomorWhatsApp = preg_replace(
                    '/\D/',
                    '',
                    (string) ($item['telepon_konsultan'] ?? '')
                );

                if (substr($nomorWhatsApp, 0, 1) === '0') {
                    $nomorWhatsApp = '62' . substr($nomorWhatsApp, 1);
                }

                $pesanAwal = 'Halo ' . $namaKonsultan
                    . ', saya klien Anda dari Counseling Platform.'
                    . ' Saya ingin membahas jadwal konsultasi tanggal '
                    . $tanggalPesan . '.';

                $linkWhatsApp = 'https://wa.me/'
                    . $nomorWhatsApp
                    . '?text=' . rawurlencode($pesanAwal);
                ?>

                <article class="jadwal-card">

                    <!-- CARD HEADER -->

                    <div class="jadwal-card-header">

                        <div class="jadwal-consultant">

                            <div
                                class="jadwal-consultant-avatar"
                                aria-hidden="true">
                                <?= esc($inisial) ?>
                            </div>

                            <div class="jadwal-consultant-info">

                                <h3>
                                    <?= esc($namaKonsultan) ?>
                                </h3>

                                <p>
                                    Sesi Konseling
                                </p>

                            </div>

                        </div>

                        <span class="jadwal-status <?= esc($statusClass, 'attr') ?>">

                            <i
                                class="bi <?= esc($statusIcon, 'attr') ?>"
                                aria-hidden="true"></i>

                            <?= esc($status) ?>

                        </span>

                    </div>

                    <!-- CARD BODY -->

                    <div class="jadwal-card-body">

                        <div class="jadwal-info">

                            <!-- TANGGAL -->

                            <div class="jadwal-info-item">

                                <div class="jadwal-info-icon">
                                    <i
                                        class="bi bi-calendar3"
                                        aria-hidden="true"></i>
                                </div>

                                <div class="jadwal-info-content">

                                    <div class="jadwal-info-label">
                                        Tanggal Konseling
                                    </div>

                                    <div class="jadwal-info-value">
                                        <?= esc($tanggalTampil) ?>
                                    </div>

                                </div>

                            </div>

                            <!-- HARI PRAKTIK -->

                            <div class="jadwal-info-item">

                                <div class="jadwal-info-icon">
                                    <i
                                        class="bi bi-calendar-week"
                                        aria-hidden="true"></i>
                                </div>

                                <div class="jadwal-info-content">

                                    <div class="jadwal-info-label">
                                        Hari Praktik
                                    </div>

                                    <div class="jadwal-info-value">
                                        <?= esc($item['hari'] ?? '-') ?>
                                    </div>

                                </div>

                            </div>

                            <!-- WAKTU -->

                            <div class="jadwal-info-item">

                                <div class="jadwal-info-icon">
                                    <i
                                        class="bi bi-clock"
                                        aria-hidden="true"></i>
                                </div>

                                <div class="jadwal-info-content">

                                    <div class="jadwal-info-label">
                                        Waktu Konseling
                                    </div>

                                    <div class="jadwal-info-value">
                                        <?= esc($waktuMulai) ?>
                                        –
                                        <?= esc($waktuSelesai) ?>
                                    </div>

                                </div>

                            </div>

                            <!-- TIPE SESI -->

                            <div class="jadwal-info-item">

                                <div class="jadwal-info-icon">
                                    <i
                                        class="bi bi-chat-dots"
                                        aria-hidden="true"></i>
                                </div>

                                <div class="jadwal-info-content">

                                    <div class="jadwal-info-label">
                                        Tipe Sesi
                                    </div>

                                    <div class="jadwal-info-value">
                                        <?= esc($item['tipe_sesi'] ?? '-') ?>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ==================================
                         ACTION BUTTONS
                    ================================== -->

                    <?php if ($status === 'Dikonfirmasi'): ?>

                        <div class="jadwal-card-actions">

                            <!-- PEMBAYARAN -->

                            <a
                                href="<?= site_url(
                                            'client/pembayaran/' . $item['id_konsultasi']
                                        ) ?>"
                                class="jadwal-action">
                                <i
                                    class="bi bi-credit-card"
                                    aria-hidden="true"></i>
                                Bayar Sekarang
                            </a>

                            <!-- WHATSAPP -->

                            <?php if ($nomorWhatsApp !== ''): ?>

                                <a
                                    href="<?= esc($linkWhatsApp, 'attr') ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="jadwal-action whatsapp">
                                    <i
                                        class="bi bi-whatsapp"
                                        aria-hidden="true"></i>
                                    Chat via WhatsApp
                                </a>

                            <?php endif; ?>

                        </div>

                    <?php elseif ($status === 'Menunggu Konfirmasi'): ?>

                        <div class="jadwal-card-actions">

                            <form
                                action="<?= site_url(
                                            'client/jadwal/batalkan/' . $item['id_konsultasi']
                                        ) ?>"
                                method="post"
                                class="form-batalkan-janji">

                                <?= csrf_field() ?>

                                <button
                                    type="submit"
                                    class="jadwal-action cancel">
                                    <i
                                        class="bi bi-x-circle"
                                        aria-hidden="true"></i>
                                    Batalkan Janji
                                </button>

                            </form>

                        </div>

                    <?php endif; ?>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>


<!-- Modal Konfirmasi Pembatalan -->
<div class="modal-batalkan" id="modalBatalkan" aria-hidden="true">
    <div class="modal-batalkan-overlay"></div>

    <div
        class="modal-batalkan-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="judulModalBatalkan"
        aria-describedby="deskripsiModalBatalkan">
        <div class="modal-batalkan-icon">
            <i class="bi bi-calendar-x" aria-hidden="true"></i>
        </div>

        <h2 id="judulModalBatalkan">Batalkan Janji?</h2>

        <p id="deskripsiModalBatalkan">
            Apakah kamu yakin ingin membatalkan pengajuan konseling ini?
            Tindakan ini akan mengubah status janji konselingmu.
        </p>

        <div class="modal-batalkan-actions">
            <button type="button" id="btnKembaliBatalkan">
                Kembali
            </button>

            <button type="button" id="btnKonfirmasiBatalkan">
                Ya, Batalkan Janji
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalBatalkan');
        const tombolKembali = document.getElementById('btnKembaliBatalkan');
        const tombolKonfirmasi = document.getElementById('btnKonfirmasiBatalkan');
        const overlay = modal?.querySelector('.modal-batalkan-overlay');

        if (!modal || !tombolKembali || !tombolKonfirmasi || !overlay) {
            return;
        }

        let formAktif = null;
        let tombolSebelumnya = null;

        function bukaModal(form) {
            formAktif = form;
            tombolSebelumnya = document.activeElement;

            modal.classList.add('is-active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-batalkan-terbuka');

            tombolKembali.focus();
        }

        function tutupModal() {
            modal.classList.remove('is-active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-batalkan-terbuka');

            formAktif = null;

            if (tombolSebelumnya) {
                tombolSebelumnya.focus();
            }
        }

        document.querySelectorAll('.form-batalkan-janji').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (formAktif !== form) {
                    event.preventDefault();
                    bukaModal(form);
                }
            });
        });

        tombolKembali.addEventListener('click', tutupModal);
        overlay.addEventListener('click', tutupModal);

        tombolKonfirmasi.addEventListener('click', function() {
            if (!formAktif) {
                return;
            }

            const form = formAktif;
            tombolKonfirmasi.disabled = true;

            // Kirim form setelah klien mengonfirmasi.
            HTMLFormElement.prototype.submit.call(form);
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.classList.contains('is-active')) {
                tutupModal();
            }
        });
    });
</script>

<?= $this->endSection() ?>




