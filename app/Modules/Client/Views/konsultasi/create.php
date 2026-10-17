
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
$timezone = new \DateTimeZone('Asia/Makassar');

$today = (new \DateTimeImmutable(
    'now',
    $timezone
))->format('Y-m-d');

$selectedType = old('tipe_sesi') ?? '';
$selectedSchedule = old('id_jadwal') ?? '';
$selectedDate = old('tanggal') ?? '';

$namaKonsultan = $konsultan['nama_lengkap'] ?? 'Psikolog';
$spesialisasi = $konsultan['spesialisasi'] ?? 'Belum dicantumkan';

$inisial = function_exists('mb_substr')
    ? mb_substr($namaKonsultan, 0, 1, 'UTF-8')
    : substr($namaKonsultan, 0, 1);

$inisial = function_exists('mb_strtoupper')
    ? mb_strtoupper($inisial, 'UTF-8')
    : strtoupper($inisial);

$errorMessage = session()->getFlashdata('error');
$successMessage = session()->getFlashdata('success');
?>

<link
    rel="stylesheet"
    href="<?= base_url('assets/css/client-booking.css') ?>"
>

<div class="booking-page">

    <!-- ==========================================
         BACK NAVIGATION
    ========================================== -->

    <a
        href="<?= site_url('client/konsultan') ?>"
        class="booking-back"
    >
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        Kembali ke Cari Psikolog
    </a>

    <!-- ==========================================
         HEADER
    ========================================== -->

    <section class="booking-header">

        <div class="booking-header-label">
            <i class="bi bi-calendar2-plus" aria-hidden="true"></i>
            Pengajuan Konseling
        </div>

        <h1>Buat Janji Konseling</h1>

        <p>
            Pilih jadwal praktik dan tipe sesi yang sesuai
            dengan kebutuhanmu. Pastikan informasi yang
            kamu masukkan sudah benar sebelum mengajukan janji.
        </p>

    </section>

    <!-- ==========================================
         FLASH MESSAGES
    ========================================== -->

    <?php if ($successMessage): ?>

        <div class="booking-notice booking-success" role="status">

            <i class="bi bi-check-circle" aria-hidden="true"></i>

            <span><?= esc($successMessage) ?></span>

        </div>

    <?php endif; ?>

    <?php if ($errorMessage): ?>

        <div class="booking-notice booking-error" role="alert">

            <i class="bi bi-exclamation-circle" aria-hidden="true"></i>

            <span><?= esc($errorMessage) ?></span>

        </div>

    <?php endif; ?>

    <!-- ==========================================
         MAIN LAYOUT
    ========================================== -->

    <div class="booking-layout">

        <!-- ======================================
             LEFT: BOOKING FORM
        ====================================== -->

        <main class="booking-main">

            <?php if (empty($jadwal)): ?>

                <div class="booking-empty">

                    <div class="booking-empty-icon">
                        <i class="bi bi-calendar-x" aria-hidden="true"></i>
                    </div>

                    <h2>Jadwal Belum Tersedia</h2>

                    <p>
                        Psikolog ini belum memiliki jadwal praktik
                        yang tersedia saat ini. Silakan kembali
                        untuk memilih psikolog lainnya.
                    </p>

                    <a
                        href="<?= site_url('client/konsultan') ?>"
                        class="booking-secondary-button"
                    >
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        Cari Psikolog Lain
                    </a>

                </div>

            <?php else: ?>

                <form
                    class="booking-card"
                    id="bookingForm"
                    action="<?= site_url('client/konsultasi/simpan') ?>"
                    method="post"
                >

                    <?= csrf_field() ?>

                    <!-- FORM HEADER -->

                    <div class="booking-card-heading">

                        <div class="booking-heading-icon">
                            <i
                                class="bi bi-calendar2-check"
                                aria-hidden="true"
                            ></i>
                        </div>

                        <div>
                            <h2>Informasi Janji Konseling</h2>

                            <p>
                                Lengkapi detail sesi konseling
                                yang ingin kamu ajukan.
                            </p>
                        </div>

                    </div>

                    <!-- ==================================
                         JADWAL & TANGGAL
                    ================================== -->

                    <div class="booking-field-grid">

                        <!-- JADWAL PRAKTIK -->

                        <div class="booking-field">

                            <label for="id_jadwal">
                                Jadwal Praktik
                                <span class="booking-required">*</span>
                            </label>

                            <div class="booking-input-wrap">

                                <i
                                    class="bi bi-calendar-week"
                                    aria-hidden="true"
                                ></i>

                                <select
                                    id="id_jadwal"
                                    name="id_jadwal"
                                    required
                                >

                                    <option value="">
                                        Pilih jadwal praktik
                                    </option>

                                    <?php foreach ($jadwal as $item): ?>

                                        <option
                                            value="<?= esc($item['id_jadwal'], 'attr') ?>"
                                            <?= (string) $selectedSchedule === (string) $item['id_jadwal']
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            <?= esc($item['hari']) ?>
                                            (<?= esc(substr($item['jam_mulai'], 0, 5)) ?>
                                            –
                                            <?= esc(substr($item['jam_selesai'], 0, 5)) ?>)
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <small>
                                Pilih hari dan jam praktik psikolog.
                            </small>

                        </div>

                        <!-- TANGGAL -->

                        <div class="booking-field">

                            <label for="tanggal">
                                Tanggal Konseling
                                <span class="booking-required">*</span>
                            </label>

                            <div class="booking-input-wrap">

                                <i
                                    class="bi bi-calendar3"
                                    aria-hidden="true"
                                ></i>

                                <input
                                    type="date"
                                    id="tanggal"
                                    name="tanggal"
                                    min="<?= esc($today, 'attr') ?>"
                                    value="<?= esc($selectedDate, 'attr') ?>"
                                    required
                                >

                            </div>

                            <small>
                                Sesuaikan tanggal dengan hari praktik.
                            </small>

                        </div>

                    </div>

                    <!-- ==================================
                         SESSION TYPE
                    ================================== -->

                    <div class="booking-field booking-session-field">

                        <label for="tipe_sesi">
                            Tipe Sesi Konseling
                            <span class="booking-required">*</span>
                        </label>

                        <div class="booking-input-wrap">

                            <i
                                class="bi bi-chat-heart"
                                aria-hidden="true"
                            ></i>

                            <select
                                id="tipe_sesi"
                                name="tipe_sesi"
                                required
                            >

                                <option value="">
                                    Pilih tipe sesi konseling
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

                        </div>

                    </div>

                    <!-- SESSION INFORMATION -->

                    <div class="booking-session-info">

                        <div class="booking-session-info-item">

                            <i
                                class="bi bi-camera-video"
                                aria-hidden="true"
                            ></i>

                            <div>
                                <strong>Online</strong>
                                <span>
                                    Konseling dilakukan melalui video call.
                                </span>
                            </div>

                        </div>

                        <div class="booking-session-info-item">

                            <i
                                class="bi bi-car-front"
                                aria-hidden="true"
                            ></i>

                            <div>
                                <strong>Pick-up Counseling</strong>
                                <span>
                                    Konseling dilakukan dalam perjalanan
                                    bersama psikolog.
                                </span>
                            </div>

                        </div>

                    </div>

                    <!-- ==================================
                         PICK-UP SECTION
                    ================================== -->

                    <section
                        id="pickupSection"
                        class="booking-pickup"
                        <?= $selectedType === 'Pick-up'
                            ? ''
                            : 'hidden' ?>
                    >

                        <div class="booking-pickup-header">

                            <div class="booking-pickup-icon">
                                <i
                                    class="bi bi-geo-alt"
                                    aria-hidden="true"
                                ></i>
                            </div>

                            <div>

                                <h2>Lokasi Pick-up Counseling</h2>

                                <p>
                                    Lengkapi lokasi penjemputan
                                    dan tujuan perjalanan untuk
                                    memudahkan koordinasi.
                                </p>

                            </div>

                        </div>

                        <!-- TITIK JEMPUT -->

                        <div class="booking-field">

                            <label for="titik_jemput">
                                Alamat Lengkap Titik Jemput
                                <span class="booking-required">*</span>
                            </label>

                            <textarea
                                id="titik_jemput"
                                name="titik_jemput"
                                rows="3"
                                maxlength="500"
                                placeholder="Masukkan alamat lengkap lokasi penjemputan beserta patokan..."
                                <?= $selectedType === 'Pick-up'
                                    ? 'required'
                                    : '' ?>
                            ><?= esc(old('titik_jemput') ?? '') ?></textarea>

                            <small>
                                Sertakan nama jalan, nomor bangunan,
                                kecamatan, kota, dan patokan lokasi.
                            </small>

                        </div>

                        <!-- TITIK TUJUAN -->

                        <div class="booking-field">

                            <label for="titik_tujuan">
                                Alamat Lengkap Titik Tujuan
                                <span class="booking-required">*</span>
                            </label>

                            <textarea
                                id="titik_tujuan"
                                name="titik_tujuan"
                                rows="3"
                                maxlength="500"
                                placeholder="Masukkan alamat lengkap tujuan perjalanan..."
                                <?= $selectedType === 'Pick-up'
                                    ? 'required'
                                    : '' ?>
                            ><?= esc(old('titik_tujuan') ?? '') ?></textarea>

                            <small>
                                Masukkan lokasi tujuan yang diinginkan
                                untuk sesi konseling.
                            </small>

                        </div>

                        <!-- PRIVACY NOTE -->

                        <div class="booking-privacy-note">

                            <i
                                class="bi bi-shield-check"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Informasi lokasi digunakan untuk
                                keperluan layanan Pick-up Counseling.
                                Koordinasi lebih lanjut dapat dilakukan
                                setelah konsultasi dikonfirmasi.
                            </span>

                        </div>

                    </section>

                    <!-- ==================================
                         SUBMIT
                    ================================== -->

                    <div class="booking-submit-area">

                        <div class="booking-submit-info">

                            <i
                                class="bi bi-info-circle"
                                aria-hidden="true"
                            ></i>

                            <span>
                                Pengajuan akan diproses oleh pihak
                                konsultan. Kamu dapat memantau
                                statusnya melalui halaman Jadwal Saya.
                            </span>

                        </div>

                        <button
                            class="booking-button"
                            id="bookingSubmit"
                            type="submit"
                        >
                            <i
                                class="bi bi-calendar2-plus"
                                aria-hidden="true"
                            ></i>

                            Ajukan Janji Konseling
                        </button>

                    </div>

                </form>

            <?php endif; ?>

        </main>

        <!-- ======================================
             RIGHT: CONSULTANT INFORMATION
        ====================================== -->

        <aside class="booking-sidebar">

            <section class="booking-selected">

                <div class="booking-sidebar-label">
                    <i
                        class="bi bi-person-check"
                        aria-hidden="true"
                    ></i>
                    Psikolog Pilihanmu
                </div>

                <div class="booking-selected-profile">

                    <div class="booking-avatar" aria-hidden="true">
                        <?= esc($inisial) ?>
                    </div>

                    <div class="booking-selected-info">

                        <h2>
                            <?= esc($namaKonsultan) ?>
                        </h2>

                        <p>
                            <?= esc($spesialisasi) ?>
                        </p>

                    </div>

                </div>

            </section>

            <!-- BOOKING GUIDE -->

            <section class="booking-guide">

                <div class="booking-guide-heading">

                    <i
                        class="bi bi-info-circle"
                        aria-hidden="true"
                    ></i>

                    <h2>Informasi Pengajuan</h2>

                </div>

                <div class="booking-guide-item">

                    <div class="booking-guide-number">1</div>

                    <p>
                        Pilih jadwal dan tanggal yang sesuai
                        dengan waktu praktik psikolog.
                    </p>

                </div>

                <div class="booking-guide-item">

                    <div class="booking-guide-number">2</div>

                    <p>
                        Tentukan tipe sesi Online atau
                        Pick-up Counseling.
                    </p>

                </div>

                <div class="booking-guide-item">

                    <div class="booking-guide-number">3</div>

                    <p>
                        Kirim pengajuan dan pantau status
                        konfirmasi melalui Jadwal Saya.
                    </p>

                </div>

            </section>

            <!-- TIMEZONE -->

            <div class="booking-timezone">

                <i class="bi bi-clock-history" aria-hidden="true"></i>

                <span>
                    Waktu konsultasi menggunakan
                    <strong>WITA (UTC+8)</strong>.
                </span>

            </div>

        </aside>

    </div>

</div>

<script
    src="<?= base_url('assets/js/client-booking.js') ?>"
    defer
></script>

<?= $this->endSection() ?>
