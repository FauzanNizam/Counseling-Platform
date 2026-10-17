
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/** @var string[] $questions */

$options = [
    0 => 'Tidak pernah',
    1 => 'Beberapa hari',
    2 => 'Lebih dari separuh hari',
    3 => 'Hampir setiap hari',
];

$totalQuestions = count($questions);
?>

<style>
    /* ==========================================
       SELF-ASSESSMENT CLIENT
       MEDIUM & CONSISTENT DESIGN
    ========================================== */

    .assessment-page {
        --assessment-primary: #6d28d9;
        --assessment-dark: #5b21b6;
        --assessment-text: #292436;
        --assessment-muted: #746e80;
        --assessment-border: #eae5f1;

        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        color: var(--assessment-text);
        font-size: 14px;
        line-height: 1.6;
    }

    .assessment-page *,
    .assessment-page *::before,
    .assessment-page *::after {
        box-sizing: border-box;
    }

    /* ==========================================
       HEADER
    ========================================== */

    .assessment-page .assessment-header {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        margin-bottom: 24px;
        border-radius: 18px;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
        color: #fff;
    }

    .assessment-page .assessment-header::after {
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

    .assessment-page .assessment-header > * {
        position: relative;
        z-index: 1;
    }

    .assessment-page .assessment-label {
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

    .assessment-page .assessment-label .bi {
        font-size: 16px;
    }

    .assessment-page .assessment-header h1 {
        margin: 0 0 12px;
        color: #fff;
        font-size: clamp(27px, 3vw, 32px);
        font-weight: 700;
        line-height: 1.3;
        letter-spacing: -.4px;
    }

    .assessment-page .assessment-header p {
        max-width: 680px;
        margin: 0;
        color: #f0e7ff;
        font-size: 15px;
        line-height: 1.75;
    }

    /* ==========================================
       FLASH ALERT
    ========================================== */

    .assessment-page .assessment-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 18px;
        margin-bottom: 20px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.65;
    }

    .assessment-page .assessment-alert .bi {
        flex-shrink: 0;
        margin-top: 1px;
        font-size: 19px;
    }

    .assessment-page .assessment-alert.success {
        border: 1px solid #bbf7d0;
        background: #dcfce7;
        color: #166534;
    }

    .assessment-page .assessment-alert.error {
        border: 1px solid #fecaca;
        background: #fee2e2;
        color: #991b1b;
    }

    /* ==========================================
       INFORMATION NOTE
    ========================================== */

    .assessment-page .assessment-note {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px 22px;
        margin-bottom: 24px;
        border: 1px solid #ddd0ff;
        border-radius: 15px;
        background: #f5f0ff;
        color: #615a6d;
        font-size: 14px;
        line-height: 1.75;
    }

    .assessment-page .assessment-note-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #e4d5ff;
        color: #5b21b6;
        font-size: 21px;
    }

    .assessment-page .assessment-note-content {
        min-width: 0;
        flex: 1;
    }

    .assessment-page .assessment-note strong {
        display: block;
        margin-bottom: 5px;
        color: #3b1678;
        font-size: 15px;
        font-weight: 700;
    }

    /* ==========================================
       PROGRESS CARD
    ========================================== */

    .assessment-page .assessment-progress {
        padding: 22px 24px;
        margin-bottom: 22px;
        border: 1px solid var(--assessment-border);
        border-radius: 15px;
        background: #fff;
    }

    .assessment-page .assessment-progress-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 15px;
    }

    .assessment-page .assessment-progress-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #292436;
        font-size: 16px;
        font-weight: 700;
    }

    .assessment-page .assessment-progress-title .bi {
        color: #6d28d9;
        font-size: 19px;
    }

    .assessment-page .assessment-progress-count {
        color: #6d28d9;
        font-size: 14px;
        font-weight: 600;
    }

    .assessment-page .assessment-progress-track {
        overflow: hidden;
        width: 100%;
        height: 9px;
        border-radius: 20px;
        background: #eee8f8;
    }

    .assessment-page .assessment-progress-fill {
        width: 0%;
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #6d28d9, #8b5cf6);
        transition: width .25s ease;
    }

    .assessment-page .assessment-progress-help {
        margin-top: 12px;
        color: var(--assessment-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    /* ==========================================
       QUESTION SECTION
    ========================================== */

    .assessment-page .assessment-section-heading {
        margin: 0 0 18px;
    }

    .assessment-page .assessment-section-heading h2 {
        margin: 0 0 5px;
        color: #292436;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.4;
    }

    .assessment-page .assessment-section-heading p {
        margin: 0;
        color: var(--assessment-muted);
        font-size: 14px;
        line-height: 1.65;
    }

    /* ==========================================
       QUESTION CARDS
    ========================================== */

    .assessment-page .assessment-question {
        padding: 24px;
        margin-bottom: 18px;
        border: 1px solid var(--assessment-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61, 31, 100, .025);
        transition: border-color .2s, box-shadow .2s;
    }

    .assessment-page .assessment-question:focus-within {
        border-color: #c4b5fd;
        box-shadow: 0 5px 18px rgba(109, 40, 217, .06);
    }

    .assessment-page .assessment-question-heading {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 20px;
    }

    .assessment-page .assessment-number {
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

    .assessment-page .assessment-question h3 {
        flex: 1;
        min-width: 0;
        margin: 5px 0 0;
        color: #292436;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.6;
    }

    /* ==========================================
       ANSWER OPTIONS
    ========================================== */

    .assessment-page .assessment-options {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .assessment-page .assessment-option {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        min-height: 56px;
        padding: 13px 14px;
        border: 1px solid #e8e3ef;
        border-radius: 10px;
        background: #fff;
        color: #615a6d;
        cursor: pointer;
        font-size: 14px;
        line-height: 1.5;
        transition:
            border-color .2s,
            background .2s,
            color .2s;
    }

    .assessment-page .assessment-option:hover {
        border-color: #c4b5fd;
        background: #faf7ff;
    }

    .assessment-page .assessment-option input {
        flex-shrink: 0;
        width: 17px;
        height: 17px;
        margin: 0;
        accent-color: #6d28d9;
        cursor: pointer;
    }

    .assessment-page .assessment-option span {
        min-width: 0;
    }

    .assessment-page .assessment-option:has(input:checked) {
        border-color: #8b5cf6;
        background: #f5f0ff;
        color: #5b21b6;
        font-weight: 600;
    }

    .assessment-page .assessment-option:focus-within {
        outline: 2px solid #c4b5fd;
        outline-offset: 2px;
    }

    /* ==========================================
       SUBMIT AREA
    ========================================== */

    .assessment-page .assessment-submit-area {
        padding: 24px;
        margin-top: 24px;
        border: 1px solid var(--assessment-border);
        border-radius: 15px;
        background: #fff;
    }

    .assessment-page .assessment-submit-info {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 18px;
        color: var(--assessment-muted);
        font-size: 14px;
        line-height: 1.7;
    }

    .assessment-page .assessment-submit-info .bi {
        flex-shrink: 0;
        margin-top: 1px;
        color: #6d28d9;
        font-size: 19px;
    }

    .assessment-page .assessment-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 48px;
        padding: 13px 20px;
        border: none;
        border-radius: 10px;
        background: #6d28d9;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .assessment-page .assessment-submit:hover {
        background: #5b21b6;
    }

    .assessment-page .assessment-submit:active {
        transform: translateY(1px);
    }

    .assessment-page .assessment-submit:disabled {
        opacity: .65;
        cursor: not-allowed;
    }

    .assessment-page .assessment-submit .bi {
        font-size: 18px;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 950px) {
        .assessment-page .assessment-options {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .assessment-page .assessment-header {
            padding: 26px 22px;
            border-radius: 15px;
        }

        .assessment-page .assessment-header h1 {
            font-size: 27px;
        }

        .assessment-page .assessment-header p {
            font-size: 14px;
        }

        .assessment-page .assessment-note {
            padding: 17px;
            gap: 12px;
        }

        .assessment-page .assessment-progress {
            padding: 19px;
        }

        .assessment-page .assessment-question {
            padding: 19px;
        }

        .assessment-page .assessment-question h3 {
            font-size: 15px;
        }

        .assessment-page .assessment-option {
            min-height: 52px;
            padding: 12px;
            font-size: 13px;
        }

        .assessment-page .assessment-submit-area {
            padding: 19px;
        }

        .assessment-page .assessment-section-heading h2 {
            font-size: 19px;
        }
    }

    @media (max-width: 420px) {
        .assessment-page .assessment-progress-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .assessment-page .assessment-question-heading {
            gap: 11px;
        }

        .assessment-page .assessment-number {
            width: 33px;
            height: 33px;
        }

        .assessment-page .assessment-options {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .assessment-page *,
        .assessment-page *::before,
        .assessment-page *::after {
            transition: none !important;
        }
    }
</style>

<div class="assessment-page">

    <!-- ==========================================
         HEADER
    ========================================== -->

    <section class="assessment-header">

        <div class="assessment-label">
            <i class="bi bi-heart-pulse" aria-hidden="true"></i>
            Kenali dirimu lebih baik
        </div>

        <h1>Self-Assessment</h1>

        <p>
            Luangkan beberapa menit untuk mengenali kondisi emosi
            dan kesejahteraan dirimu. Jawablah sesuai dengan
            pengalamanmu selama 7 hari terakhir.
        </p>

    </section>

    <!-- ==========================================
         FLASH MESSAGES
    ========================================== -->

    <?php $successMessage = session()->getFlashdata('success'); ?>

    <?php if ($successMessage): ?>

        <div class="assessment-alert success" role="status">
            <i class="bi bi-check-circle" aria-hidden="true"></i>
            <span><?= esc($successMessage) ?></span>
        </div>

    <?php endif; ?>

    <?php $errorMessage = session()->getFlashdata('error'); ?>

    <?php if ($errorMessage): ?>

        <div class="assessment-alert error" role="alert">
            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
            <span><?= esc($errorMessage) ?></span>
        </div>

    <?php endif; ?>

    <!-- ==========================================
         INFORMATION NOTE
    ========================================== -->

    <div class="assessment-note">

        <div class="assessment-note-icon">
            <i class="bi bi-info-circle" aria-hidden="true"></i>
        </div>

        <div class="assessment-note-content">

            <strong>Perlu diketahui</strong>

            Kuesioner ini merupakan sarana refleksi diri sederhana,
            bukan alat diagnosis atau pengganti konsultasi
            dengan psikolog.

        </div>

    </div>

    <!-- ==========================================
         FORM
    ========================================== -->

    <form
        action="<?= site_url('client/assessment/submit') ?>"
        method="post"
        id="assessmentForm"
    >

        <?= csrf_field() ?>

        <!-- ======================================
             PROGRESS
        ====================================== -->

        <div class="assessment-progress">

            <div class="assessment-progress-header">

                <div class="assessment-progress-title">
                    <i class="bi bi-list-check" aria-hidden="true"></i>
                    Progres Pengisian
                </div>

                <div
                    class="assessment-progress-count"
                    id="assessmentProgressCount"
                    aria-live="polite"
                >
                    0 dari <?= $totalQuestions ?> pertanyaan
                </div>

            </div>

            <div
                class="assessment-progress-track"
                id="assessmentProgressTrack"
                role="progressbar"
                aria-label="Progres pengisian assessment"
                aria-valuemin="0"
                aria-valuemax="<?= $totalQuestions ?>"
                aria-valuenow="0"
            >
                <div
                    class="assessment-progress-fill"
                    id="assessmentProgressFill"
                ></div>
            </div>

            <div class="assessment-progress-help">
                Pilih satu jawaban pada setiap pertanyaan.
            </div>

        </div>

        <!-- ======================================
             QUESTION HEADING
        ====================================== -->

        <div class="assessment-section-heading">
            <h2>Daftar Pertanyaan</h2>
            <p>
                Pilih jawaban yang paling sesuai dengan
                pengalamanmu selama 7 hari terakhir.
            </p>
        </div>

        <!-- ======================================
             QUESTIONS
        ====================================== -->

        <?php foreach ($questions as $index => $question): ?>

            <div class="assessment-question">

                <div class="assessment-question-heading">

                    <span class="assessment-number">
                        <?= $index + 1 ?>
                    </span>

                    <h3 id="assessment-question-<?= $index + 1 ?>">
                        <?= esc($question) ?>
                    </h3>

                </div>

                <div
                    class="assessment-options"
                    role="group"
                    aria-labelledby="assessment-question-<?= $index + 1 ?>"
                >

                    <?php foreach ($options as $value => $label): ?>

                        <label class="assessment-option">

                            <input
                                type="radio"
                                name="answers[<?= $index + 1 ?>]"
                                value="<?= $value ?>"
                                required
                            >

                            <span><?= esc($label) ?></span>

                        </label>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php endforeach; ?>

        <!-- ======================================
             SUBMIT
        ====================================== -->

        <div class="assessment-submit-area">

            <div class="assessment-submit-info">

                <i class="bi bi-shield-check" aria-hidden="true"></i>

                <span>
                    Pastikan semua pertanyaan sudah dijawab
                    sesuai dengan kondisi yang kamu alami.
                </span>

            </div>

            <button
                type="submit"
                class="assessment-submit"
                id="assessmentSubmit"
            >
                <i class="bi bi-check2-circle" aria-hidden="true"></i>
                Simpan dan Lihat Hasil
            </button>

        </div>

    </form>

</div>

<!-- ==========================================
     PROGRESS JAVASCRIPT
========================================== -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('assessmentForm');
    const progressCount = document.getElementById('assessmentProgressCount');
    const progressFill = document.getElementById('assessmentProgressFill');
    const progressTrack = document.getElementById('assessmentProgressTrack');
    const submitButton = document.getElementById('assessmentSubmit');

    if (!form) {
        return;
    }

    const totalQuestions = <?= (int) $totalQuestions ?>;

    function updateProgress() {

        const checkedAnswers = form.querySelectorAll(
            'input[type="radio"]:checked'
        );

        const answered = checkedAnswers.length;

        const percentage = totalQuestions > 0
            ? (answered / totalQuestions) * 100
            : 0;

        progressCount.textContent =
            answered + ' dari ' + totalQuestions + ' pertanyaan';

        progressFill.style.width = percentage + '%';

        progressTrack.setAttribute(
            'aria-valuenow',
            String(answered)
        );
    }

    form.addEventListener('change', function (event) {

        if (event.target.matches('input[type="radio"]')) {
            updateProgress();
        }

    });

    form.addEventListener('submit', function (event) {

        if (!form.checkValidity()) {
            return;
        }

        if (submitButton.disabled) {
            event.preventDefault();
            return;
        }

        submitButton.disabled = true;

        submitButton.innerHTML =
            '<i class="bi bi-hourglass-split" aria-hidden="true"></i> Menyimpan...';

    });

    updateProgress();

});
</script>

<?= $this->endSection() ?>
