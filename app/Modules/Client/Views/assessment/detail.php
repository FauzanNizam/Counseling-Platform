<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/** @var array $assessment */
/** @var array $answers */
?>

<style>
    .detail-page {
        max-width: 1000px;
        margin: 0 auto;
        color: #28213f;
    }

    .detail-header {
        padding: 28px;
        border-radius: 20px;
        background: linear-gradient(135deg, #6d28d9, #8b5cf6);
        color: white;
        margin-bottom: 24px;
    }

    .detail-header h1 {
        font-size: 26px;
        margin: 0 0 10px;
    }

    .detail-header p {
        color: #f3e8ff;
        margin: 0;
        line-height: 1.7;
    }

    .detail-card {
        background: white;
        border: 1px solid #eee8f8;
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 18px;
        box-shadow: 0 4px 15px rgba(61, 31, 100, 0.04);
    }

    .detail-card h2 {
        font-size: 18px;
        margin: 0 0 18px;
    }

    .detail-score {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 10px;
        background: #ede9fe;
        color: #6d28d9;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .detail-item {
        padding: 16px 0;
        border-bottom: 1px solid #eee8f8;
    }

    .detail-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-question {
        font-weight: 600;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .detail-answer {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 8px;
        background: #f5f3ff;
        color: #6d28d9;
        font-size: 13px;
    }

    .detail-text {
        line-height: 1.8;
        margin: 8px 0 0;
        color: #514765;
    }

    .detail-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
    }

    .detail-button {
        display: inline-block;
        padding: 12px 18px;
        border-radius: 10px;
        background: #7c3aed;
        color: white;
        text-decoration: none;
        font-weight: 600;
    }

    .detail-button:hover {
        background: #6d28d9;
    }

    .detail-button.secondary {
        background: white;
        color: #6d28d9;
        border: 1px solid #c4b5fd;
    }

    @media (max-width: 600px) {
        .detail-header {
            padding: 22px;
        }

        .detail-header h1 {
            font-size: 22px;
        }

        .detail-card {
            padding: 17px;
        }
    }
</style>

<div class="detail-page">

    <div class="detail-header">
        <h1>Detail Self-Assessment</h1>
        <p>
            Lihat kembali hasil dan jawaban dari refleksi diri yang telah
            kamu selesaikan.
        </p>
    </div>

    <div class="detail-card">
        <h2>Ringkasan Hasil</h2>

        <div>
            <strong>Tanggal pengerjaan:</strong>
            <?= esc(date(
                'd M Y, H:i',
                strtotime($assessment['tanggal_assessment'])
            )) ?>
        </div>

        <p>
            <strong>Jenis:</strong>
            Refleksi Kesejahteraan Diri
        </p>

        <div class="detail-score">
            Skor: <?= esc($assessment['skor']) ?> / 24
        </div>

        <p class="detail-text">
            <strong>Ringkasan:</strong><br>
            <?= esc($assessment['ringkasan'] ?? '-') ?>
        </p>

        <p class="detail-text">
            <strong>Rekomendasi:</strong><br>
            <?= esc($assessment['rekomendasi'] ?? '-') ?>
        </p>

        <p class="detail-text">
            <em>
                Hasil ini merupakan refleksi diri sederhana, bukan diagnosis
                atau pengganti konsultasi dengan psikolog.
            </em>
        </p>
    </div>

    <div class="detail-card">
        <h2>Jawaban Kuesioner</h2>

        <?php if (empty($answers)): ?>

            <p>Data jawaban untuk assessment ini tidak ditemukan.</p>

        <?php else: ?>

            <?php
            $answerLabels = [
                0 => 'Tidak pernah',
                1 => 'Beberapa hari',
                2 => 'Lebih dari separuh hari',
                3 => 'Hampir setiap hari',
            ];
            ?>

            <?php foreach ($answers as $answer): ?>
                <div class="detail-item">

                    <div class="detail-question">
                        <?= esc($answer['nomor_pertanyaan']) ?>.
                        <?= esc($answer['pertanyaan']) ?>
                    </div>

                    <span class="detail-answer">
                        <?= esc(
                            $answerLabels[(int) $answer['jawaban']]
                                ?? 'Jawaban tidak dikenal'
                        ) ?>
                    </span>

                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <div class="detail-actions">
        <a
            href="<?= site_url('client/assessment/history') ?>"
            class="detail-button secondary">
            Kembali ke Riwayat
        </a>

        <a
            href="<?= site_url('client/assessment/new') ?>"
            class="detail-button">
            Isi Assessment Baru
        </a>
        
        <a href="<?= site_url('client/konsultan') ?>" class="detail-button">
            Cari Psikolog
        </a>
       
    </div>

</div>

<?= $this->endSection() ?>