
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/** @var array $assessments */

$historyData = $assessments ?? [];

usort($historyData, static function ($a, $b) {
    return strtotime($b['tanggal_assessment'] ?? '')
        <=> strtotime($a['tanggal_assessment'] ?? '');
});

$totalAssessment = count($historyData);
$latestAssessment = $historyData[0] ?? null;
?>

<style>
    /* ==========================================
       HISTORY SELF-ASSESSMENT
       MEDIUM & CONSISTENT DESIGN
    ========================================== */

    .history-page {
        --history-primary: #6d28d9;
        --history-dark: #5b21b6;
        --history-text: #292436;
        --history-muted: #746e80;
        --history-border: #eae5f1;

        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        color: var(--history-text);
        font-size: 14px;
        line-height: 1.6;
    }

    .history-page *,
    .history-page *::before,
    .history-page *::after {
        box-sizing: border-box;
    }

    .history-page a {
        text-decoration: none;
    }

    /* ==========================================
       HEADER
    ========================================== */

    .history-page .history-header {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        margin-bottom: 24px;
        border-radius: 18px;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
        color: #fff;
    }

    .history-page .history-header::after {
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

    .history-page .history-header > * {
        position: relative;
        z-index: 1;
    }

    .history-page .history-label {
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
        font-weight: 500;
    }

    .history-page .history-label .bi {
        font-size: 16px;
    }

    .history-page .history-header h1 {
        margin: 0 0 12px;
        color: #fff;
        font-size: clamp(27px, 3vw, 32px);
        font-weight: 700;
        line-height: 1.3;
        letter-spacing: -.4px;
    }

    .history-page .history-header p {
        max-width: 680px;
        margin: 0;
        color: #f0e7ff;
        font-size: 15px;
        line-height: 1.75;
    }

    /* ==========================================
       STATISTICS
    ========================================== */

    .history-page .history-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .history-page .history-stat-card {
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
        padding: 22px 24px;
        border: 1px solid var(--history-border);
        border-radius: 15px;
        background: #fff;
    }

    .history-page .history-stat-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 25px;
    }

    .history-page .history-stat-icon.green {
        background: #e2f6eb;
        color: #168451;
    }

    .history-page .history-stat-content {
        flex: 1;
        min-width: 0;
    }

    .history-page .history-stat-label {
        margin-bottom: 5px;
        color: var(--history-muted);
        font-size: 14px;
    }

    .history-page .history-stat-value {
        color: var(--history-text);
        font-size: 26px;
        font-weight: 700;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .history-page .history-stat-value.date {
        font-size: 19px;
    }

    .history-page .history-stat-description {
        margin-top: 5px;
        color: var(--history-muted);
        font-size: 13px;
        line-height: 1.5;
    }

    /* ==========================================
       SECTION HEADING
    ========================================== */

    .history-page .history-section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 18px;
    }

    .history-page .history-section-heading h2 {
        margin: 0;
        color: var(--history-text);
        font-size: 20px;
        font-weight: 700;
        line-height: 1.4;
    }

    .history-page .history-section-heading p {
        margin: 5px 0 0;
        color: var(--history-muted);
        font-size: 14px;
        line-height: 1.6;
    }

    /* ==========================================
       BUTTONS
    ========================================== */

    .history-page .history-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 46px;
        padding: 12px 19px;
        border: 1px solid transparent;
        border-radius: 10px;
        background: var(--history-primary);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        white-space: nowrap;
        transition: background .2s, transform .2s;
    }

    .history-page .history-button:hover {
        background: var(--history-dark);
        color: #fff;
        transform: translateY(-2px);
    }

    .history-page .history-button .bi {
        font-size: 17px;
        line-height: 1;
    }

    .history-page .history-button.outline {
        border-color: #ddd0ff;
        background: #fff;
        color: var(--history-dark);
    }

    .history-page .history-button.outline:hover {
        background: #f5f0ff;
        color: var(--history-dark);
    }

    /* ==========================================
       HISTORY LIST
    ========================================== */

    .history-page .history-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .history-page .history-card {
        overflow: hidden;
        border: 1px solid var(--history-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61,31,100,.025);
        transition: border-color .2s, box-shadow .2s;
    }

    .history-page .history-card:hover {
        border-color: #d8bfff;
        box-shadow: 0 8px 24px rgba(61,31,100,.055);
    }

    .history-page .history-card-main {
        padding: 23px 24px;
    }

    .history-page .history-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 18px;
    }

    .history-page .history-card-identity {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        flex: 1;
        min-width: 0;
    }

    .history-page .history-card-icon {
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

    .history-page .history-card-title {
        flex: 1;
        min-width: 0;
    }

    .history-page .history-card-title h3 {
        margin: 0 0 7px;
        color: var(--history-text);
        font-size: 16px;
        font-weight: 700;
        line-height: 1.5;
    }

    .history-page .history-date {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        color: var(--history-muted);
        font-size: 13px;
        line-height: 1.5;
    }

    .history-page .history-date .bi {
        color: #8b5cf6;
        font-size: 15px;
    }

    /* SCORE */

    .history-page .history-score-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        min-width: 98px;
        padding: 12px 16px;
        border: 1px solid #ddd0ff;
        border-radius: 12px;
        background: #f5f0ff;
    }

    .history-page .history-score-value {
        color: #5b21b6;
        font-size: 26px;
        font-weight: 700;
        line-height: 1.3;
        white-space: nowrap;
    }

    .history-page .history-score-value small {
        color: #817b8d;
        font-size: 13px;
        font-weight: 500;
    }

    .history-page .history-score-label {
        margin-top: 3px;
        color: #746e80;
        font-size: 12px;
    }

    /* RESULT PREVIEW */

    .history-page .history-card-body {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .history-page .history-preview {
        min-width: 0;
        padding: 16px 18px;
        border: 1px solid #f0edf4;
        border-radius: 11px;
        background: #faf9fd;
    }

    .history-page .history-preview-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 9px;
        color: #3b1678;
        font-size: 14px;
        font-weight: 700;
    }

    .history-page .history-preview-title .bi {
        color: #6d28d9;
        font-size: 17px;
    }

    .history-page .history-preview p {
        display: -webkit-box;
        overflow: hidden;
        margin: 0;
        color: #615a6d;
        font-size: 14px;
        line-height: 1.75;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
        line-clamp: 3;
    }

    /* CARD FOOTER */

    .history-page .history-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 24px;
        border-top: 1px solid #f0edf4;
        background: #fff;
    }

    .history-page .history-card-footer-note {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--history-muted);
        font-size: 13px;
        line-height: 1.5;
    }

    .history-page .history-card-footer-note .bi {
        color: #8b5cf6;
        font-size: 16px;
    }

    .history-page .history-detail-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 40px;
        padding: 9px 15px;
        border: 1px solid #ddd0ff;
        border-radius: 9px;
        background: #f5f0ff;
        color: #5b21b6;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        transition: background .2s;
    }

    .history-page .history-detail-link:hover {
        background: #e9ddff;
        color: #5b21b6;
    }

    .history-page .history-detail-link .bi {
        font-size: 17px;
    }

    /* ==========================================
       EMPTY STATE
    ========================================== */

    .history-page .history-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 46px 24px;
        border: 1px dashed #c4b5fd;
        border-radius: 15px;
        background: #fff;
        text-align: center;
    }

    .history-page .history-empty-icon {
        display: grid;
        place-items: center;
        width: 70px;
        height: 70px;
        margin-bottom: 20px;
        border-radius: 20px;
        background: #f5f0ff;
        color: #6d28d9;
        font-size: 32px;
    }

    .history-page .history-empty h3 {
        margin: 0 0 10px;
        color: var(--history-text);
        font-size: 19px;
        font-weight: 700;
    }

    .history-page .history-empty p {
        max-width: 460px;
        margin: 0 0 22px;
        color: var(--history-muted);
        font-size: 14px;
        line-height: 1.75;
    }

    /* ==========================================
       DISCLAIMER
    ========================================== */

    .history-page .history-note {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        margin-top: 24px;
        border: 1px solid #ddd0ff;
        border-radius: 12px;
        background: #f5f0ff;
        color: #615a6d;
        font-size: 14px;
        line-height: 1.7;
    }

    .history-page .history-note .bi {
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

    .history-page .history-note span {
        flex: 1;
        min-width: 0;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 850px) {
        .history-page .history-card-body {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .history-page .history-header {
            padding: 26px 22px;
            border-radius: 15px;
        }

        .history-page .history-header h1 {
            font-size: 27px;
        }

        .history-page .history-header p {
            font-size: 14px;
        }

        .history-page .history-stats {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .history-page .history-stat-card {
            padding: 19px;
        }

        .history-page .history-section-heading {
            align-items: stretch;
            flex-direction: column;
        }

        .history-page .history-section-heading h2 {
            font-size: 19px;
        }

        .history-page .history-section-heading .history-button {
            align-self: flex-start;
        }

        .history-page .history-card-main {
            padding: 19px;
        }

        .history-page .history-card-footer {
            padding: 15px 19px;
        }
    }

    @media (max-width: 480px) {
        .history-page .history-card-top {
            gap: 12px;
        }

        .history-page .history-card-icon {
            width: 40px;
            height: 40px;
            font-size: 19px;
        }

        .history-page .history-card-identity {
            gap: 11px;
        }

        .history-page .history-score-box {
            min-width: 76px;
            padding: 10px;
        }

        .history-page .history-score-value {
            font-size: 22px;
        }

        .history-page .history-card-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .history-page .history-detail-link {
            width: 100%;
        }

        .history-page .history-preview {
            padding: 15px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .history-page *,
        .history-page *::before,
        .history-page *::after {
            transition: none !important;
        }
    }
</style>

<div class="history-page">

    <!-- ==========================================
         HEADER
    ========================================== -->

    <section class="history-header">

        <div class="history-label">
            <i class="bi bi-clock-history" aria-hidden="true"></i>
            Pantau perjalanan refleksi dirimu
        </div>

        <h1>Riwayat Self-Assessment</h1>

        <p>
            Lihat kembali hasil refleksi diri yang pernah kamu
            selesaikan. Gunakan riwayat ini untuk mengenali
            perubahan kondisi dirimu dari waktu ke waktu.
        </p>

    </section>

    <!-- ==========================================
         STATISTICS
    ========================================== -->

    <section class="history-stats">

        <div class="history-stat-card">

            <div class="history-stat-icon">
                <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
            </div>

            <div class="history-stat-content">

                <div class="history-stat-label">
                    Total Assessment
                </div>

                <div class="history-stat-value">
                    <?= esc((string) $totalAssessment) ?>
                </div>

                <div class="history-stat-description">
                    Assessment yang telah diselesaikan
                </div>

            </div>

        </div>

        <div class="history-stat-card">

            <div class="history-stat-icon green">
                <i class="bi bi-calendar2-check" aria-hidden="true"></i>
            </div>

            <div class="history-stat-content">

                <div class="history-stat-label">
                    Assessment Terakhir
                </div>

                <div class="history-stat-value date">

                    <?php if ($latestAssessment): ?>

                        <?= esc(date(
                            'd M Y',
                            strtotime($latestAssessment['tanggal_assessment'])
                        )) ?>

                    <?php else: ?>

                        Belum Ada

                    <?php endif; ?>

                </div>

                <div class="history-stat-description">
                    Tanggal assessment terbaru
                </div>

            </div>

        </div>

    </section>

    <!-- ==========================================
         SECTION HEADING
    ========================================== -->

    <section class="history-section-heading">

        <div>

            <h2>Daftar Riwayat</h2>

            <p>
                <?= esc((string) $totalAssessment) ?>
                hasil assessment yang tersimpan.
            </p>

        </div>

        <?php if (!empty($historyData)): ?>

            <a
                href="<?= site_url('client/assessment') ?>"
                class="history-button"
            >
                <i class="bi bi-plus-circle" aria-hidden="true"></i>
                Isi Assessment Baru
            </a>

        <?php endif; ?>

    </section>

    <!-- ==========================================
         EMPTY STATE
    ========================================== -->

    <?php if (empty($historyData)): ?>

        <div class="history-empty">

            <div class="history-empty-icon">
                <i class="bi bi-clipboard2-x" aria-hidden="true"></i>
            </div>

            <h3>Belum Ada Riwayat Assessment</h3>

            <p>
                Kamu belum menyelesaikan self-assessment.
                Mulai dengan mengisi kuesioner untuk mengenali
                kondisi emosi dan kesejahteraan dirimu.
            </p>

            <a
                href="<?= site_url('client/assessment') ?>"
                class="history-button"
            >
                <i class="bi bi-clipboard2-plus" aria-hidden="true"></i>
                Mulai Assessment
            </a>

        </div>

    <?php else: ?>

        <!-- ======================================
             HISTORY LIST
        ====================================== -->

        <div class="history-list">

            <?php foreach ($historyData as $assessment): ?>

                <article class="history-card">

                    <!-- TOP -->

                    <div class="history-card-main">

                        <div class="history-card-top">

                            <div class="history-card-identity">

                                <div class="history-card-icon">
                                    <i
                                        class="bi bi-clipboard2-pulse"
                                        aria-hidden="true"
                                    ></i>
                                </div>

                                <div class="history-card-title">

                                    <h3>
                                        Refleksi Kesejahteraan Diri
                                    </h3>

                                    <div class="history-date">

                                        <i
                                            class="bi bi-calendar3"
                                            aria-hidden="true"
                                        ></i>

                                        <span>
                                            <?= esc(date(
                                                'd M Y, H:i',
                                                strtotime(
                                                    $assessment['tanggal_assessment']
                                                )
                                            )) ?>
                                        </span>

                                    </div>

                                </div>

                            </div>

                            <!-- SCORE -->

                            <div class="history-score-box">

                                <div class="history-score-value">
                                    <?= esc($assessment['skor']) ?>
                                    <small>/24</small>
                                </div>

                                <div class="history-score-label">
                                    Skor
                                </div>

                            </div>

                        </div>

                        <!-- PREVIEW -->

                        <div class="history-card-body">

                            <div class="history-preview">

                                <div class="history-preview-title">
                                    <i
                                        class="bi bi-file-text"
                                        aria-hidden="true"
                                    ></i>
                                    Ringkasan
                                </div>

                                <p>
                                    <?= esc(
                                        $assessment['ringkasan'] ?? '-'
                                    ) ?>
                                </p>

                            </div>

                            <div class="history-preview">

                                <div class="history-preview-title">
                                    <i
                                        class="bi bi-lightbulb"
                                        aria-hidden="true"
                                    ></i>
                                    Rekomendasi
                                </div>

                                <p>
                                    <?= esc(
                                        $assessment['rekomendasi'] ?? '-'
                                    ) ?>
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- FOOTER -->

                    <div class="history-card-footer">

                        <div class="history-card-footer-note">
                            <i
                                class="bi bi-check-circle"
                                aria-hidden="true"
                            ></i>
                            Assessment selesai
                        </div>

                        <a
                            href="<?= site_url(
                                'client/assessment/history/' .
                                $assessment['id_assessment']
                            ) ?>"
                            class="history-detail-link"
                        >
                            Lihat Detail
                            <i
                                class="bi bi-arrow-right"
                                aria-hidden="true"
                            ></i>
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <!-- ==========================================
         INFORMATION NOTE
    ========================================== -->

    <div class="history-note">

        <i class="bi bi-info-circle" aria-hidden="true"></i>

        <span>
            Hasil self-assessment merupakan sarana refleksi diri,
            bukan diagnosis medis atau pengganti konsultasi
            dengan psikolog.
        </span>

    </div>

</div>

<?= $this->endSection() ?>
