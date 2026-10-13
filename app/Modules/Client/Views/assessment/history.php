<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>
<?php
/** @var array $assessments */
?>

<style>
    .history-page {
        max-width: 1000px;
        margin: 0 auto;
        color: #28213f;
    }

    .history-header {
        padding: 28px;
        border-radius: 20px;
        background: linear-gradient(135deg, #6d28d9, #8b5cf6);
        color: white;
        margin-bottom: 24px;
    }

    .history-header h1 {
        font-size: 26px;
        margin: 0 0 10px;
    }

    .history-header p {
        margin: 0;
        line-height: 1.7;
        color: #f3e8ff;
    }

    .history-summary {
        background: white;
        border: 1px solid #eee8f8;
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 20px;
    }

    .history-summary h2 {
        margin: 0 0 6px;
        font-size: 18px;
    }

    .history-count {
        font-size: 28px;
        font-weight: 700;
        color: #7c3aed;
    }

    .history-list {
        display: grid;
        gap: 16px;
    }

    .history-card {
        background: white;
        border: 1px solid #eee8f8;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(61, 31, 100, 0.04);
    }

    .history-card h3 {
        margin: 0 0 10px;
        font-size: 17px;
    }

    .history-date {
        font-size: 13px;
        color: #756b86;
        margin-bottom: 14px;
    }

    .history-score {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 9px;
        background: #ede9fe;
        color: #6d28d9;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 14px;
    }

    .history-detail {
        line-height: 1.7;
        margin: 5px 0;
    }

    .history-empty {
        background: white;
        border: 1px dashed #c4b5fd;
        border-radius: 16px;
        padding: 35px 20px;
        text-align: center;
        color: #756b86;
    }

    .history-empty h3 {
        color: #28213f;
        margin-top: 0;
    }

    .history-button {
        display: inline-block;
        margin-top: 12px;
        padding: 11px 17px;
        border-radius: 10px;
        color: white;
        background: #7c3aed;
        text-decoration: none;
        font-weight: 600;
    }

    .history-button:hover {
        background: #6d28d9;
    }

    @media (max-width: 600px) {
        .history-header {
            padding: 22px;
        }

        .history-header h1 {
            font-size: 22px;
        }

        .history-card {
            padding: 17px;
        }
    }
</style>

<div class="history-page">

    <div class="history-header">
        <h1>Riwayat Self-Assessment</h1>
        <p>
            Lihat kembali hasil refleksi diri yang pernah kamu selesaikan.
            Gunakan riwayat ini untuk mengenali perubahan kondisi dirimu.
        </p>
    </div>

    <div class="history-summary">
        <h2>Total Assessment</h2>
        <div class="history-count">
            <?= count($assessments) ?>
        </div>
        <p>Jumlah assessment yang pernah kamu selesaikan.</p>
    </div>

    <?php if (empty($assessments)): ?>

        <div class="history-empty">
            <h3>Belum Ada Riwayat</h3>
            <p>
                Kamu belum menyelesaikan self-assessment.
                Mulai dengan mengisi kuesioner terlebih dahulu.
            </p>

            <a href="<?= site_url('client/assessment/new') ?>"
                class="history-button">
                Mulai Assessment
            </a>
        </div>

    <?php else: ?>

        <div class="history-list">

            <?php foreach ($assessments as $assessment): ?>

                <article class="history-card">

                    <h3>Refleksi Kesejahteraan Diri</h3>

                    <div class="history-date">
                        <?= esc(date(
                            'd M Y, H:i',
                            strtotime($assessment['tanggal_assessment'])
                        )) ?>
                    </div>

                    <div class="history-score">
                        Skor: <?= esc($assessment['skor']) ?> / 24
                    </div>

                    <p class="history-detail">
                        <strong>Ringkasan:</strong>
                        <?= esc($assessment['ringkasan'] ?? '-') ?>
                    </p>

                    <p class="history-detail">
                        <strong>Rekomendasi:</strong>
                        <?= esc($assessment['rekomendasi'] ?? '-') ?>
                    </p>
                    <a
                        href="<?= site_url('client/assessment/history/' . $assessment['id_assessment']) ?>"
                        class="history-button">
                        Lihat Detail
                    </a>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <a href="<?= site_url('client/assessment/new') ?>"
        class="history-button">
        Isi Assessment Baru
    </a>

</div>

<?= $this->endSection() ?>