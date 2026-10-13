<?= $this->extend('Modules\Shared\Views\layouts\client') ?>

<?= $this->section('content') ?>

<?php
/** @var array $jadwal */
?>

<style>
    .booking-page {
        max-width: 850px;
        margin: 0 auto;
        padding: 28px;
    }

    .booking-title {
        color: #5b21b6;
        font-size: 30px;
        margin: 0 0 8px;
    }

    .booking-subtitle {
        color: #6b7280;
        margin-bottom: 26px;
    }

    .booking-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
    }

    .booking-field {
        margin-bottom: 20px;
    }

    .booking-field label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 600;
    }

    .booking-field select,
    .booking-field input {
        box-sizing: border-box;
        width: 100%;
        min-height: 48px;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #fff;
        font: inherit;
    }

    .booking-field select:focus,
    .booking-field input:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
    }

    .booking-button {
        display: block;
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #5b21b6;
        color: white;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
    }

    .booking-button:hover {
        background: #4c1d95;
    }

    .booking-back {
        display: inline-block;
        margin-bottom: 20px;
        color: #6d28d9;
        text-decoration: none;
    }

    .booking-notice {
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .booking-error {
        background: #fef2f2;
        color: #b91c1c;
    }

    .booking-empty {
        background: #f5f3ff;
        color: #5b21b6;
        padding: 20px;
        border-radius: 12px;
    }

    @media (max-width: 600px) {
        .booking-page {
            padding: 20px 14px;
        }

        .booking-card {
            padding: 20px;
        }

        .booking-title {
            font-size: 25px;
        }
    }
</style>

<div class="booking-page">
    <a class="booking-back" href="<?= site_url('client/konsultan') ?>">
        ← Kembali ke Cari Psikolog
    </a>

    <h1 class="booking-title">Buat Janji Konseling</h1>


    <p class="booking-subtitle">
        Pilih jadwal praktik dan tipe sesi yang sesuai dengan kebutuhanmu.
    </p>

    <?php if (!empty($konsultan)): ?>
        <div style="
        padding: 16px 20px;
        margin-bottom: 20px;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 12px;
        color: #5b21b6;
    ">
            <strong>Psikolog pilihanmu</strong>
            <div style="font-size: 18px; font-weight: 600; margin-top: 6px;">
                <?= esc($konsultan['nama_lengkap']) ?>
            </div>
            <div style="font-size: 14px; margin-top: 4px;">
                <?= esc($konsultan['spesialisasi']) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="booking-notice booking-error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($jadwal)): ?>
        <div class="booking-empty">
            Belum ada jadwal praktik yang tersedia saat ini.
        </div>
    <?php else: ?>
        <form
            class="booking-card"
            action="<?= site_url('client/konsultasi/simpan') ?>"
            method="post">
            <?= csrf_field() ?>

            <div class="booking-field">
                <label for="id_jadwal">Pilih Jadwal Praktik</label>

                <select id="id_jadwal" name="id_jadwal" required>
                    <option value="">-- Pilih jadwal praktik --</option>

                    <?php foreach ($jadwal as $item): ?>
                        <option
                            value="<?= esc($item['id_jadwal']) ?>"
                            <?= old('id_jadwal') == $item['id_jadwal'] ? 'selected' : '' ?>>
                            
                            — <?= esc($item['hari']) ?>
                            (<?= esc(substr($item['jam_mulai'], 0, 5)) ?>
                            –<?= esc(substr($item['jam_selesai'], 0, 5)) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="booking-field">
                <label for="tanggal">Tanggal Konseling</label>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    min="<?= date('Y-m-d') ?>"
                    value="<?= esc(old('tanggal')) ?>"
                    required>

                <small style="color:#6b7280;">
                    Pilih tanggal yang sesuai dengan hari praktik konsultan.
                </small>
            </div>

            <div class="booking-field">
                <label for="tipe_sesi">Tipe Sesi</label>

                <select id="tipe_sesi" name="tipe_sesi" required>
                    <option value="">-- Pilih tipe sesi --</option>
                    <option value="Online" <?= old('tipe_sesi') === 'Online' ? 'selected' : '' ?>>
                        Online
                    </option>
                    <option value="Pick-up" <?= old('tipe_sesi') === 'Pick-up' ? 'selected' : '' ?>>
                        Pick-up
                    </option>
                </select>
            </div>

            <button class="booking-button" type="submit">
                Ajukan Janji Konseling
            </button>
        </form>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>