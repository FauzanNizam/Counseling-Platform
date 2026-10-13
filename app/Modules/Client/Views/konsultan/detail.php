<?= $this->extend('Modules\Shared\Views\layouts\client') ?>

<?= $this->section('content') ?>

<?php
/** @var array $item */
?>
<div style="padding: 24px; max-width: 900px; margin: auto;">
    <a href="<?= site_url('client/konsultan') ?>"
        style="color: #5b21b6; text-decoration: none;">
        ← Kembali ke Cari Psikolog
    </a>

    <div style="
        margin-top: 24px;
        padding: 32px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    ">
        <div style="
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #ede9fe;
            color: #5b21b6;
            font-size: 32px;
            margin-bottom: 20px;
        ">
            <?= esc(strtoupper(substr($item['nama_lengkap'], 0, 1))) ?>
        </div>

        <h1 style="color: #5b21b6; margin-bottom: 8px;">
            <?= esc($item['nama_lengkap']) ?>
        </h1>

        <p style="color: #6b7280; font-size: 17px; margin-bottom: 24px;">
            <?= esc($item['spesialisasi']) ?>
        </p>

        <hr style="border: 0; border-top: 1px solid #e5e7eb;">

        <h3 style="margin-top: 24px; color: #1f2937;">
            Informasi Konsultan
        </h3>

        <p style="margin-top: 16px; color: #4b5563;">
            <strong>Spesialisasi:</strong>
            <?= esc($item['spesialisasi']) ?>
        </p>

        <p style="margin-top: 12px; color: #4b5563;">
            <strong>Pengalaman:</strong>
            <?= esc($item['pengalaman_tahun']) ?> tahun
        </p>

        <p style="margin-top: 12px; color: #4b5563;">
            <strong>Email:</strong>
            <?= esc($item['email']) ?>
        </p>

        <p style="margin-top: 12px; color: #4b5563;">
            <strong>Nomor telepon:</strong>
            <?= esc($item['no_telepon'] ?: 'Belum tersedia') ?>
        </p>

        <div style="
            margin-top: 28px;
            padding: 14px;
            background: #f5f3ff;
            border-radius: 10px;
            color: #5b21b6;
        ">
            Informasi profil ditampilkan berdasarkan data yang tersedia.
        </div>


        <a href="<?= site_url('client/konsultasi/buat/' . $item['id_profil']) ?>"
            style="
       display: inline-flex;
       align-items: center;
       justify-content: center;
       gap: 8px;
       margin-top: 24px;
       padding: 14px 24px;
       background: #5b21b6;
       color: white;
       text-decoration: none;
       font-weight: 600;
       border-radius: 10px;
   ">
            <i class="fas fa-calendar-plus"></i>
            Buat Janji Konseling
        </a>


    </div>
</div>

<?= $this->endSection() ?>