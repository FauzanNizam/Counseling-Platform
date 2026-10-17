<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/** @var array $assessment */
/** @var array $answers */

$answerLabels = [
    0 => 'Tidak pernah',
    1 => 'Beberapa hari',
    2 => 'Lebih dari separuh hari',
    3 => 'Hampir setiap hari',
];
?>

<style>
    /* ==========================================
       DETAIL SELF-ASSESSMENT
       MEDIUM & CONSISTENT DESIGN
    ========================================== */

    .detail-page {
        --detail-primary: #6d28d9;
        --detail-dark: #5b21b6;
        --detail-text: #292436;
        --detail-muted: #746e80;
        --detail-border: #eae5f1;

        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        color: var(--detail-text);
        font-size: 14px;
        line-height: 1.6;
    }

    .detail-page *,
    .detail-page *::before,
    .detail-page *::after {
        box-sizing: border-box;
    }

    /* ==========================================
       HEADER
    ========================================== */

    .detail-page .detail-header {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        margin-bottom: 24px;
        border-radius: 18px;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
        color: #fff;
    }

    .detail-page .detail-header::after {
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

    .detail-page .detail-header>* {
        position: relative;
        z-index: 1;
    }

    .detail-page .detail-label {
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

    .detail-page .detail-label .bi {
        font-size: 16px;
    }

    .detail-page .detail-header h1 {
        margin: 0 0 12px;
        color: #fff;
        font-size: clamp(27px, 3vw, 32px);
        font-weight: 700;
        line-height: 1.3;
        letter-spacing: -.4px;
    }

    .detail-page .detail-header p {
        max-width: 680px;
        margin: 0;
        color: #f0e7ff;
        font-size: 15px;
        line-height: 1.75;
    }

    /* ==========================================
       GENERAL CARD
    ========================================== */

    .detail-page .detail-card {
        padding: 24px;
        margin-bottom: 20px;
        border: 1px solid var(--detail-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61, 31, 100, .025);
    }

    .detail-page .detail-card-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 22px;
    }

    .detail-page .detail-heading-icon {
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

    .detail-page .detail-card h2 {
        margin: 0;
        color: #292436;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.4;
    }

    .detail-page .detail-card-subtitle {
        margin-top: 5px;
        color: var(--detail-muted);
        font-size: 14px;
        line-height: 1.6;
    }

    /* ==========================================
       ASSESSMENT INFORMATION
    ========================================== */

    .detail-page .detail-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .detail-page .detail-info-item {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        min-width: 0;
        padding: 18px;
        border: 1px solid #eeeaf3;
        border-radius: 12px;
        background: #faf9fd;
    }

    .detail-page .detail-info-icon {
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

    .detail-page .detail-info-label {
        margin-bottom: 5px;
        color: var(--detail-muted);
        font-size: 13px;
    }

    .detail-page .detail-info-value {
        color: #292436;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    /* ==========================================
       SCORE
    ========================================== */

    .detail-page .detail-score-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 22px 24px;
        margin-bottom: 22px;
        border: 1px solid #ddd0ff;
        border-radius: 14px;
        background: #f5f0ff;
    }

    .detail-page .detail-score-label {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 7px;
        color: #5b21b6;
        font-size: 16px;
        font-weight: 700;
    }

    .detail-page .detail-score-label .bi {
        font-size: 19px;
    }

    .detail-page .detail-score-description {
        color: #746e80;
        font-size: 14px;
        line-height: 1.6;
    }

    .detail-page .detail-score-value {
        flex-shrink: 0;
        color: #5b21b6;
        font-size: 32px;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
    }

    .detail-page .detail-score-value small {
        color: #817b8d;
        font-size: 16px;
        font-weight: 500;
    }

    /* ==========================================
       RESULT SECTIONS
    ========================================== */

    .detail-page .detail-result-section {
        padding: 20px 0;
        border-top: 1px solid #f0edf4;
    }

    .detail-page .detail-result-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        color: #3b1678;
        font-size: 16px;
        font-weight: 700;
    }

    .detail-page .detail-result-title .bi {
        color: #6d28d9;
        font-size: 19px;
    }

    .detail-page .detail-text {
        margin: 0;
        color: #615a6d;
        font-size: 14px;
        line-height: 1.85;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    /* ==========================================
       DISCLAIMER
    ========================================== */


    /* DISCLAIMER - ICON DAN TEKS RATA TENGAH */

    .detail-page .detail-disclaimer {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        margin-top: 5px;
        border: 1px solid #ddd0ff;
        border-radius: 12px;
        background: #f5f0ff;
        color: #615a6d;
        font-size: 14px;
        line-height: 1.7;
    }

    .detail-page .detail-disclaimer .bi {
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

    .detail-page .detail-disclaimer span {
        flex: 1;
        min-width: 0;
        margin: 0;
        line-height: 1.7;
    }


    /* ==========================================
       QUESTION ANSWERS
    ========================================== */

    .detail-page .detail-item {
        padding: 20px 0;
        border-bottom: 1px solid #f0edf4;
    }

    .detail-page .detail-item:first-child {
        padding-top: 0;
    }

    .detail-page .detail-item:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .detail-page .detail-question-row {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 14px;
    }

    .detail-page .detail-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 15px;
        font-weight: 700;
    }

    .detail-page .detail-question {
        flex: 1;
        min-width: 0;
        margin-top: 5px;
        color: #292436;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.65;
    }

    .detail-page .detail-answer {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-left: 50px;
        padding: 10px 14px;
        border: 1px solid #e8ddff;
        border-radius: 9px;
        background: #f5f0ff;
        color: #5b21b6;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
    }

    .detail-page .detail-answer .bi {
        font-size: 17px;
    }

    /* ==========================================
       EMPTY STATE
    ========================================== */

    .detail-page .detail-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 34px 20px;
        color: var(--detail-muted);
        text-align: center;
        font-size: 14px;
    }

    .detail-page .detail-empty .bi {
        color: #a78bfa;
        font-size: 34px;
    }

    /* ==========================================
       ACTION BUTTONS
    ========================================== */

    .detail-page .detail-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
        margin-bottom: 16px;
    }

    .detail-page .detail-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 46px;
        padding: 12px 19px;
        border: 1px solid transparent;
        border-radius: 10px;
        background: #6d28d9;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        text-decoration: none;
        transition: background .2s, transform .2s;
    }

    .detail-page .detail-button:hover {
        background: #5b21b6;
        color: #fff;
        transform: translateY(-2px);
    }

    .detail-page .detail-button.secondary {
        border-color: #ddd0ff;
        background: #fff;
        color: #5b21b6;
    }

    .detail-page .detail-button.secondary:hover {
        background: #f5f0ff;
        color: #5b21b6;
    }

    .detail-page .detail-button .bi {
        font-size: 18px;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 700px) {
        .detail-page .detail-info-grid {
            grid-template-columns: 1fr;
        }

        .detail-page .detail-header {
            padding: 26px 22px;
            border-radius: 15px;
        }

        .detail-page .detail-header h1 {
            font-size: 27px;
        }

        .detail-page .detail-header p {
            font-size: 14px;
        }

        .detail-page .detail-card {
            padding: 19px;
        }

        .detail-page .detail-card h2 {
            font-size: 19px;
        }

        .detail-page .detail-score-box {
            padding: 19px;
        }

        .detail-page .detail-score-value {
            font-size: 29px;
        }

        .detail-page .detail-question {
            font-size: 15px;
        }

        .detail-page .detail-actions {
            flex-direction: column;
        }

        .detail-page .detail-button {
            width: 100%;
        }
    }

    @media (max-width: 420px) {
        .detail-page .detail-score-box {
            align-items: flex-start;
            flex-direction: column;
        }

        .detail-page .detail-answer {
            margin-left: 0;
        }

        .detail-page .detail-info-item {
            padding: 15px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .detail-page *,
        .detail-page *::before,
        .detail-page *::after {
            transition: none !important;
        }
    }
</style>

<div class="detail-page">

    <!-- ==========================================
         HEADER
    ========================================== -->

    <section class="detail-header">

        <div class="detail-label">
            <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
            Hasil refleksi diri
        </div>

        <h1>Detail Self-Assessment</h1>

        <p>
            Lihat kembali hasil dan jawaban dari refleksi diri
            yang telah kamu selesaikan.
        </p>

    </section>

    <!-- ==========================================
         RINGKASAN HASIL
    ========================================== -->

    <section class="detail-card">

        <div class="detail-card-heading">

            <div class="detail-heading-icon">
                <i class="bi bi-clipboard-data" aria-hidden="true"></i>
            </div>

            <div>
                <h2>Ringkasan Hasil</h2>

                <div class="detail-card-subtitle">
                    Informasi hasil assessment yang telah kamu kerjakan.
                </div>
            </div>

        </div>

        <!-- INFORMASI ASSESSMENT -->

        <div class="detail-info-grid">

            <div class="detail-info-item">

                <div class="detail-info-icon">
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                </div>

                <div>
                    <div class="detail-info-label">
                        Tanggal Pengerjaan
                    </div>

                    <div class="detail-info-value">
                        <?= esc(date(
                            'd M Y, H:i',
                            strtotime($assessment['tanggal_assessment'])
                        )) ?>
                    </div>
                </div>

            </div>

            <div class="detail-info-item">

                <div class="detail-info-icon">
                    <i class="bi bi-journal-text" aria-hidden="true"></i>
                </div>

                <div>
                    <div class="detail-info-label">
                        Jenis Assessment
                    </div>

                    <div class="detail-info-value">
                        Refleksi Kesejahteraan Diri
                    </div>
                </div>

            </div>

        </div>

        <!-- SKOR -->

        <div class="detail-score-box">

            <div>

                <div class="detail-score-label">
                    <i class="bi bi-bar-chart" aria-hidden="true"></i>
                    Skor Assessment
                </div>

                <div class="detail-score-description">
                    Total skor berdasarkan jawaban yang diberikan.
                </div>

            </div>

            <div class="detail-score-value">
                <?= esc($assessment['skor']) ?>
                <small>/ 24</small>
            </div>

        </div>

        <!-- RINGKASAN -->

        <div class="detail-result-section">

            <div class="detail-result-title">
                <i class="bi bi-file-text" aria-hidden="true"></i>
                Ringkasan
            </div>

            <p class="detail-text"><?= esc($assessment['ringkasan'] ?? '-') ?></p>

        </div>

        <!-- REKOMENDASI -->

        <div class="detail-result-section">

            <div class="detail-result-title">
                <i class="bi bi-lightbulb" aria-hidden="true"></i>
                Rekomendasi
            </div>

            <p class="detail-text"><?= esc($assessment['rekomendasi'] ?? '-') ?></p>

        </div>

        <!-- DISCLAIMER -->

        <div class="detail-disclaimer">

            <i class="bi bi-info-circle" aria-hidden="true"></i>

            <span>
                Hasil ini merupakan refleksi diri sederhana,
                bukan diagnosis atau pengganti konsultasi
                dengan psikolog.
            </span>

        </div>

    </section>

    <!-- ==========================================
         JAWABAN KUESIONER
    ========================================== -->

    <section class="detail-card">

        <div class="detail-card-heading">

            <div class="detail-heading-icon">
                <i class="bi bi-list-check" aria-hidden="true"></i>
            </div>

            <div>
                <h2>Jawaban Kuesioner</h2>

                <div class="detail-card-subtitle">
                    Daftar jawaban yang kamu pilih saat mengisi assessment.
                </div>
            </div>

        </div>

        <?php if (empty($answers)): ?>

            <div class="detail-empty">

                <i class="bi bi-inbox" aria-hidden="true"></i>

                <span>
                    Data jawaban untuk assessment ini tidak ditemukan.
                </span>

            </div>

        <?php else: ?>

            <div class="detail-answer-list">

                <?php foreach ($answers as $answer): ?>

                    <div class="detail-item">

                        <div class="detail-question-row">

                            <span class="detail-number">
                                <?= esc($answer['nomor_pertanyaan']) ?>
                            </span>

                            <div class="detail-question">
                                <?= esc($answer['pertanyaan']) ?>
                            </div>

                        </div>

                        <span class="detail-answer">

                            <i class="bi bi-check-circle" aria-hidden="true"></i>

                            <?= esc(
                                $answerLabels[(int) $answer['jawaban']]
                                ?? 'Jawaban tidak dikenal'
                            ) ?>

                        </span>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

    <!-- ==========================================
         ACTION BUTTONS
    ========================================== -->

    <div class="detail-actions">

        <a href="<?= site_url('client/assessment/history') ?>" class="detail-button secondary">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            Kembali ke Riwayat
        </a>

        <a href="<?= site_url('client/assessment/new') ?>" class="detail-button">
            <i class="bi bi-clipboard2-plus" aria-hidden="true"></i>
            Isi Assessment Baru
        </a>

        <a href="<?= site_url('client/konsultan') ?>" class="detail-button">
            <i class="bi bi-person-heart" aria-hidden="true"></i>
            Cari Psikolog
        </a>

    </div>

</div>

<?= $this->endSection() ?>