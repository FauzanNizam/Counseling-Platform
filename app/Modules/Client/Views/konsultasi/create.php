
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
$timezone = new \DateTimeZone('Asia/Makassar');

$today = (new \DateTimeImmutable(
    'now',
    $timezone
))->format('Y-m-d');

$selectedType = old('tipe_sesi') ?? '';
?>

<link
    rel="stylesheet"
    href="<?= base_url('assets/css/client-booking.css') ?>"
>

<div class="booking-page">

    <!-- KEMBALI -->
    <a
        class="booking-back"
        href="<?= site_url('client/konsultan') ?>"
    >
        ← Kembali ke Cari Psikolog
    </a>

    <!-- HEADER -->
    <h1 class="booking-title">
        Buat Janji Konseling
    </h1>

    <p class="booking-subtitle">
        Pilih jadwal praktik dan tipe sesi yang sesuai
        dengan kebutuhanmu.
    </p>

    <!-- PSIKOLOG YANG DIPILIH -->
    <?php if (!empty($konsultan)): ?>

        <div class="booking-selected">

            <strong>Psikolog pilihanmu</strong>

            <div class="booking-selected__name">
                <?= esc($konsultan['nama_lengkap']) ?>
            </div>

            <div class="booking-selected__specialty">
                <?= esc($konsultan['spesialisasi'] ?? '-') ?>
            </div>

        </div>

    <?php endif; ?>

    <!-- PESAN ERROR -->
    <?php if (session()->getFlashdata('error')): ?>

        <div
            class="booking-notice booking-error"
            role="alert"
        >
            <?= esc(session()->getFlashdata('error')) ?>
        </div>

    <?php endif; ?>

    <!-- JADWAL TIDAK TERSEDIA -->
    <?php if (empty($jadwal)): ?>

        <div class="booking-empty">
            Belum ada jadwal praktik yang tersedia saat ini.
        </div>

    <?php else: ?>

        <!-- FORM BOOKING -->
        <form
            class="booking-card"
            id="bookingForm"
            action="<?= site_url('client/konsultasi/simpan') ?>"
            method="post"
        >

            <?= csrf_field() ?>

            <!-- PILIH JADWAL -->
            <div class="booking-field">

                <label for="id_jadwal">
                    Pilih Jadwal Praktik
                </label>

                <select
                    id="id_jadwal"
                    name="id_jadwal"
                    required
                >

                    <option value="">
                        -- Pilih jadwal praktik --
                    </option>

                    <?php foreach ($jadwal as $item): ?>

                        <option
                            value="<?= esc($item['id_jadwal'], 'attr') ?>"
                            <?= old('id_jadwal') == $item['id_jadwal']
                                ? 'selected'
                                : '' ?>
                        >
                            <?= esc($item['hari']) ?>
                            (<?= esc(substr($item['jam_mulai'], 0, 5)) ?>
                            –<?= esc(substr($item['jam_selesai'], 0, 5)) ?>)
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- TANGGAL KONSELING -->
            <div class="booking-field">

                <label for="tanggal">
                    Tanggal Konseling
                </label>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    min="<?= esc($today, 'attr') ?>"
                    value="<?= esc(old('tanggal') ?? '', 'attr') ?>"
                    required
                >

                <small>
                    Pilih tanggal sesuai hari praktik psikolog.
                    Waktu konsultasi menggunakan WITA.
                </small>

            </div>

            <!-- TIPE SESI -->
            <div class="booking-field">

                <label for="tipe_sesi">
                    Tipe Sesi
                </label>

                <select
                    id="tipe_sesi"
                    name="tipe_sesi"
                    required
                >

                    <option value="">
                        -- Pilih tipe sesi --
                    </option>

                    <option
                        value="Online"
                        <?= $selectedType === 'Online'
                            ? 'selected'
                            : '' ?>
                    >
                        Online
                    </option>

                    <option
                        value="Pick-up"
                        <?= $selectedType === 'Pick-up'
                            ? 'selected'
                            : '' ?>
                    >
                        Pick-up Counseling
                    </option>

                </select>

                <small>
                    Online dilakukan melalui video call.
                    Pick-up Counseling dilakukan dalam
                    perjalanan bersama psikolog.
                </small>

            </div>

            <!-- LOKASI PICK-UP -->
            <section
                id="pickupSection"
                class="booking-pickup"
                <?= $selectedType === 'Pick-up' ? '' : 'hidden' ?>
            >

                <div class="booking-pickup__header">

                    <h2>
                        Lokasi Pick-up Counseling
                    </h2>

                    <p>
                        Masukkan alamat lengkap penjemputan
                        dan tujuan perjalanan. Psikolog akan
                        menggunakan informasi ini untuk
                        koordinasi penjemputan.
                    </p>

                </div>

                <!-- TITIK JEMPUT -->
                <div class="booking-field">

                    <label for="titik_jemput">
                        Alamat Lengkap Titik Jemput
                    </label>

                    <textarea
                        id="titik_jemput"
                        name="titik_jemput"
                        rows="3"
                        maxlength="500"
                        placeholder="Contoh: Jl. Ahmad Yani KM 35, Banjarbaru, Kalimantan Selatan, dekat gerbang utama"
                        <?= $selectedType === 'Pick-up'
                            ? 'required'
                            : '' ?>
                    ><?= esc(old('titik_jemput') ?? '') ?></textarea>

                    <small>
                        Cantumkan nama jalan, nomor bangunan,
                        kecamatan, kota, dan patokan lokasi.
                    </small>

                </div>

                <!-- TITIK TUJUAN -->
                <div class="booking-field">

                    <label for="titik_tujuan">
                        Alamat Lengkap Titik Tujuan
                    </label>

                    <textarea
                        id="titik_tujuan"
                        name="titik_tujuan"
                        rows="3"
                        maxlength="500"
                        placeholder="Contoh: Jl. Brigjen H. Hasan Basri, Banjarmasin Utara, Kota Banjarmasin"
                        <?= $selectedType === 'Pick-up'
                            ? 'required'
                            : '' ?>
                    ><?= esc(old('titik_tujuan') ?? '') ?></textarea>

                    <small>
                        Masukkan tujuan perjalanan yang
                        diinginkan untuk sesi konseling.
                    </small>

                </div>

                <!-- INFORMASI TAMBAHAN -->
                <p class="booking-privacy-note">
                    Informasi lokasi digunakan untuk
                    keperluan layanan Pick-up Counseling.
                    Pastikan alamat benar dan mudah ditemukan.
                    Koordinasi lebih lanjut dapat dilakukan
                    melalui WhatsApp setelah konsultasi
                    dikonfirmasi.
                </p>

            </section>

            <!-- TOMBOL SIMPAN -->
            <button
                class="booking-button"
                type="submit"
            >
                Ajukan Janji Konseling
            </button>

        </form>

    <?php endif; ?>

</div>

<script
    src="<?= base_url('assets/js/client-booking.js') ?>"
    defer
></script>

<?= $this->endSection() ?>
