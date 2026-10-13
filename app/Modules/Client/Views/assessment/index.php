<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<style>
    .assessment-page {
        max-width: 1000px;
        margin: 0 auto;
        color: #28213f;
    }

    .assessment-header {
        padding: 28px;
        border-radius: 20px;
        background: linear-gradient(135deg, #6d28d9, #8b5cf6);
        color: white;
        margin-bottom: 24px;
    }

    .assessment-header h1 {
        font-size: 26px;
        margin: 0 0 10px;
    }

    .assessment-header p {
        margin: 0;
        line-height: 1.7;
        color: #f3e8ff;
    }

    .assessment-note {
        padding: 14px 16px;
        border-radius: 12px;
        background: #f5f3ff;
        color: #5b21b6;
        margin-bottom: 22px;
        line-height: 1.6;
        font-size: 14px;
    }

    .assessment-question {
        padding: 22px;
        background: white;
        border: 1px solid #eee8f8;
        border-radius: 16px;
        margin-bottom: 16px;
        box-shadow: 0 4px 15px rgba(61, 31, 100, 0.04);
    }

    .assessment-question h3 {
        font-size: 16px;
        line-height: 1.6;
        margin: 0 0 18px;
    }

    .assessment-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: #ede9fe;
        color: #6d28d9;
        margin-right: 9px;
        font-size: 14px;
    }

    .assessment-options {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
    }

    .assessment-option {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 12px;
        border: 1px solid #e5e0ed;
        border-radius: 10px;
        cursor: pointer;
        font-size: 13px;
        line-height: 1.5;
        transition: 0.2s;
    }

    .assessment-option:hover {
        border-color: #8b5cf6;
        background: #faf5ff;
    }

    .assessment-option input {
        accent-color: #7c3aed;
        flex-shrink: 0;
    }

    .assessment-submit {
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        color: white;
        background: #7c3aed;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    .assessment-submit:hover {
        background: #6d28d9;
    }

    .assessment-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    @media (max-width: 700px) {
        .assessment-header {
            padding: 22px;
        }

        .assessment-header h1 {
            font-size: 22px;
        }

        .assessment-options {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .assessment-question {
            padding: 17px;
        }
    }

    @media (max-width: 380px) {
        .assessment-options {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="assessment-page">

    <div class="assessment-header">
        <h1>Self-Assessment</h1>
        <p>
            Luangkan beberapa menit untuk mengenali kondisi emosi dan
            kesejahteraan dirimu. Jawablah sesuai dengan pengalamanmu
            selama 7 hari terakhir.
        </p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="padding:14px 16px; margin-bottom:18px; border-radius:12px; background:#dcfce7; color:#166534;">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="padding:14px 16px; margin-bottom:18px; border-radius:12px; background:#fee2e2; color:#991b1b;">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>


    <div class="assessment-note">
        <strong>Perlu diketahui:</strong> Kuesioner ini merupakan sarana
        refleksi diri sederhana, bukan alat diagnosis atau pengganti
        konsultasi dengan psikolog.
    </div>

    <form action="<?= site_url('client/assessment/submit') ?>" method="post"
        id="assessmentForm">

        <?= csrf_field() ?>

        <?php
        $options = [
            0 => 'Tidak pernah',
            1 => 'Beberapa hari',
            2 => 'Lebih dari separuh hari',
            3 => 'Hampir setiap hari',
        ];
        ?>


        <?php
        /** @var string[] $questions */
        ?>

        <?php foreach ($questions as $index => $question): ?>

            <div class="assessment-question">
                <h3>
                    <span class="assessment-number">
                        <?= $index + 1 ?>
                    </span>
                    <?= esc($question) ?>
                </h3>

                <div class="assessment-options">
                    <?php foreach ($options as $value => $label): ?>
                        <label class="assessment-option">
                            <input
                                type="radio"
                                name="answers[<?= $index + 1 ?>]"
                                value="<?= $value ?>"
                                required>
                            <span><?= esc($label) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="assessment-submit">
            Simpan dan Lihat Hasil
        </button>

    </form>

</div>

<?= $this->endSection() ?>