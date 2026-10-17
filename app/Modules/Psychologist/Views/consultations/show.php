
<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>
<?= $this->section('content') ?>

<?php

// ==========================================
// DATA KONSULTASI
// ==========================================

$consultationId = (int) ($consultation['id_konsultasi'] ?? 0);
$status = $consultation['status'] ?? '-';
$tipeSesi = $consultation['tipe_sesi'] ?? '-';
$clientName = $consultation['nama_klien'] ?? 'Klien';

// ==========================================
// NAVIGASI KEMBALI
// ==========================================

$backUrl = site_url('psychologist/consultations');

$from = (string) service('request')->getGet('from');
$clientIdFromUrl = (int) service('request')->getGet('client_id');

$fromClient = (
    $from === 'client'
    && $clientIdFromUrl > 0
    && $clientIdFromUrl === (int) ($consultation['id_klien'] ?? 0)
);

if ($fromClient) {
    $backUrl = site_url(
        'psychologist/clients/' . $clientIdFromUrl
    );
}

$statusClass = match ($status) {
    'Menunggu Konfirmasi' => 'pending',
    'Dikonfirmasi' => 'confirmed',
    'Berlangsung' => 'ongoing',
    'Selesai' => 'completed',
    'Dibatalkan' => 'cancelled',
    default => 'default',
};

// ==========================================
// INFORMASI PICK-UP
// ==========================================

$titikJemput = trim(
    (string) ($consultation['titik_jemput'] ?? '')
);

$titikTujuan = trim(
    (string) ($consultation['titik_tujuan'] ?? '')
);

$alamatLengkap = $titikJemput !== '' && $titikTujuan !== '';

// ==========================================
// TIMEZONE WITA
// ==========================================

$timezone = new DateTimeZone('Asia/Makassar');
$now = new DateTimeImmutable('now', $timezone);

$sessionStart = false;

if (
    !empty($consultation['tanggal']) &&
    !empty($consultation['waktu_mulai'])
) {
    $time = substr(
        (string) $consultation['waktu_mulai'],
        0,
        8
    );

    if (strlen($time) === 5) {
        $time .= ':00';
    }

    $sessionStart = DateTimeImmutable::createFromFormat(
        '!Y-m-d H:i:s',
        $consultation['tanggal'] . ' ' . $time,
        $timezone
    );
}

// Psikolog dapat merespons permintaan
// selama status masih Menunggu Konfirmasi.

$canRespond = $status === 'Menunggu Konfirmasi';

$canStart = $status === 'Dikonfirmasi'
    && $sessionStart !== false
    && $now >= $sessionStart->modify('-15 minutes');

$canFinish = $status === 'Berlangsung';

$canManageMeeting = $tipeSesi === 'Online'
    && in_array(
        $status,
        ['Dikonfirmasi', 'Berlangsung'],
        true
    );

// ==========================================
// LINK GOOGLE MEET / ZOOM
// ==========================================

$platform = $consultation['platform'] ?? '';

$meetingUrl = trim(
    (string) ($consultation['link_meeting'] ?? '')
);

$meetingParts = parse_url($meetingUrl);
$meetingParts = is_array($meetingParts)
    ? $meetingParts
    : [];

$meetingHost = strtolower(
    $meetingParts['host'] ?? ''
);

$meetingScheme = strtolower(
    $meetingParts['scheme'] ?? ''
);

$validMeeting = filter_var(
    $meetingUrl,
    FILTER_VALIDATE_URL
)
    && $meetingScheme === 'https'
    && (
        $meetingHost === 'meet.google.com'
        || $meetingHost === 'zoom.us'
        || str_ends_with($meetingHost, '.zoom.us')
    )
    && !isset($meetingParts['user'])
    && !isset($meetingParts['pass']);

// ==========================================
// WHATSAPP KLIEN
// ==========================================

$rawPhone = (string) (
    $consultation['no_telepon_klien'] ?? ''
);

$phone = preg_replace(
    '/\D+/',
    '',
    $rawPhone
);

if (str_starts_with($phone, '0')) {
    $phone = '62' . substr($phone, 1);
} elseif (str_starts_with($phone, '8')) {
    $phone = '62' . $phone;
}

$validPhone = preg_match(
    '/^62[0-9]{8,13}$/',
    $phone
) === 1;

$whatsappMessage = sprintf(
    'Halo %s, saya menghubungi terkait jadwal konsultasi Anda di Counseling Platform.',
    $clientName
);

$whatsappUrl = $validPhone
    ? 'https://wa.me/' . $phone
        . '?text=' . rawurlencode($whatsappMessage)
    : '';

$canContactClient = in_array(
    $status,
    ['Dikonfirmasi', 'Berlangsung'],
    true
);

// ==========================================
// NOTIFIKASI
// ==========================================

$success = session()->getFlashdata('success');
$error = session()->getFlashdata('error');

?>

<!-- ==========================================
     HEADER
========================================== -->

<div class="dashboard-header">
    <h1>Detail Konsultasi</h1>

    <p>
        Kelola jadwal, akses komunikasi,
        dan pelaksanaan sesi konsultasi.
    </p>
</div>

<!-- ==========================================
     DETAIL KONSULTASI
========================================== -->

<div class="psy-detail">

    <div class="psy-detail__top">

        <div>
            <h2>
                Konsultasi #<?= esc($consultationId) ?>
            </h2>

            <p>
                Informasi konsultasi bersama klien.
            </p>
        </div>

        <span
            class="psychologist-status psychologist-status--<?= esc($statusClass, 'attr') ?>"
        >
            <?= esc($status) ?>
        </span>

    </div>

    <!-- NOTIFIKASI -->

    <?php if ($success): ?>

        <div
            class="psy-alert psy-alert--success"
            role="alert"
        >
            <?= esc($success) ?>
        </div>

    <?php endif; ?>

    <?php if ($error): ?>

        <div
            class="psy-alert psy-alert--error"
            role="alert"
        >
            <?= esc($error) ?>
        </div>

    <?php endif; ?>

    <!-- ======================================
         INFORMASI KONSULTASI
    ======================================= -->

    <div class="psy-detail__grid">

        <div class="psy-detail__item">
            <span>Nama Klien</span>
            <strong><?= esc($clientName) ?></strong>
        </div>

        <div class="psy-detail__item">
            <span>Tanggal Konsultasi</span>

            <strong>
                <?= !empty($consultation['tanggal'])
                    ? esc(date(
                        'd/m/Y',
                        strtotime($consultation['tanggal'])
                    ))
                    : '-' ?>
            </strong>
        </div>

        <div class="psy-detail__item">
            <span>Waktu Mulai</span>

            <strong>
                <?= esc(substr(
                    (string) ($consultation['waktu_mulai'] ?? '-'),
                    0,
                    5
                )) ?>
                WITA
            </strong>
        </div>

        <div class="psy-detail__item">
            <span>Waktu Selesai</span>

            <strong>
                <?= esc(substr(
                    (string) ($consultation['waktu_selesai'] ?? '-'),
                    0,
                    5
                )) ?>
                WITA
            </strong>
        </div>

        <div class="psy-detail__item">
            <span>Tipe Sesi</span>
            <strong><?= esc($tipeSesi) ?></strong>
        </div>

        <div class="psy-detail__item">
            <span>Durasi Konsultasi</span>

            <strong>
                <?= esc(
                    $consultation['durasi_menit'] ?? '-'
                ) ?>
                menit
            </strong>
        </div>

    </div>

    <!-- ======================================
         KONSULTASI ONLINE
    ======================================= -->

    <?php if ($tipeSesi === 'Online'): ?>

        <div class="psy-detail__meeting">

            <h3>Akses Konsultasi Online</h3>

            <?php if ($canManageMeeting): ?>

                <p>
                    Siapkan link Google Meet atau Zoom
                    agar klien dapat bergabung ke sesi
                    konsultasi.
                </p>

                <!-- FORM LINK MEETING -->

                <form
                    action="<?= site_url(
                        'psychologist/consultations/'
                        . $consultationId
                        . '/meeting'
                    ) ?>"
                    method="post"
                    class="psy-meeting-form"
                >

                    <?= csrf_field() ?>

                    <div class="psy-practice__field">

                        <label for="meetingPlatform">
                            Platform Konsultasi
                        </label>

                        <select
                            id="meetingPlatform"
                            name="platform"
                            required
                        >
                            <option value="">
                                -- Pilih Platform --
                            </option>

                            <option
                                value="Google Meet"
                                <?= $platform === 'Google Meet'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Google Meet
                            </option>

                            <option
                                value="Zoom"
                                <?= $platform === 'Zoom'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Zoom
                            </option>

                        </select>

                    </div>

                    <div class="psy-practice__field">

                        <label for="meetingLink">
                            Link Meeting
                        </label>

                        <input
                            id="meetingLink"
                            type="url"
                            name="link_meeting"
                            value="<?= esc(
                                $meetingUrl,
                                'attr'
                            ) ?>"
                            placeholder="https://meet.google.com/xxx-xxxx-xxx"
                            maxlength="2048"
                            required
                        >

                    </div>

                    <div class="psy-meeting-form__actions">

                        <button
                            type="submit"
                            class="psy-btn psy-btn--primary"
                        >
                            Simpan Link Meeting
                        </button>

                    </div>

                </form>

                <!-- LINK SUDAH TERSIMPAN -->

                <?php if ($validMeeting): ?>

                    <div class="psy-meeting-saved">

                        <strong>
                            Link Meeting Tersimpan
                        </strong>

                        <p>
                            <?= esc(
                                $platform ?: 'Video Call'
                            ) ?>
                        </p>

                        <div class="psy-meeting-saved__actions">

                            <a
                                href="<?= esc(
                                    $meetingUrl,
                                    'attr'
                                ) ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="psy-btn psy-btn--primary"
                            >
                                Gabung Video Call
                            </a>

                            <button
                                type="button"
                                class="psy-btn psy-btn--secondary js-copy-meeting"
                                data-link="<?= esc(
                                    $meetingUrl,
                                    'attr'
                                ) ?>"
                            >
                                Salin Link
                            </button>

                        </div>

                        <span
                            class="psy-meeting-copy-feedback"
                            role="status"
                            aria-live="polite"
                        ></span>

                    </div>

                <?php endif; ?>

            <?php else: ?>

                <p>
                    <?php if (
                        $status === 'Menunggu Konfirmasi'
                    ): ?>

                        Link meeting dapat disiapkan
                        setelah permintaan konsultasi
                        dikonfirmasi.

                    <?php elseif (
                        $status === 'Selesai'
                    ): ?>

                        Sesi konsultasi ini sudah selesai.

                    <?php else: ?>

                        Konsultasi ini tidak aktif.

                    <?php endif; ?>
                </p>

            <?php endif; ?>

        </div>

    <?php endif; ?>

    <!-- ======================================
         WHATSAPP KLIEN
    ======================================= -->

    <?php if ($canContactClient): ?>

        <div class="psy-detail__meeting psy-contact">

            <h3>Komunikasi dengan Klien</h3>

            <p>
                Hubungi klien melalui WhatsApp untuk
                koordinasi pelaksanaan konsultasi.
            </p>

            <?php if ($validPhone): ?>

                <a
                    href="<?= esc(
                        $whatsappUrl,
                        'attr'
                    ) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="psy-btn psy-btn--accept"
                >
                    Chat WhatsApp Klien
                </a>

            <?php else: ?>

                <div class="psy-alert psy-alert--error">
                    Nomor telepon klien belum tersedia
                    atau formatnya tidak valid.
                </div>

            <?php endif; ?>

        </div>

    <?php endif; ?>

    <!-- ======================================
         INFORMASI PICK-UP COUNSELING
    ======================================= -->

    <?php if ($tipeSesi === 'Pick-up'): ?>

        <div class="psy-detail__meeting psy-pickup">

            <h3>Informasi Pick-up Counseling</h3>

            <p class="psy-pickup__description">
                Layanan konseling dilakukan selama
                perjalanan bersama psikolog.
                Berikut informasi lokasi penjemputan
                dan tujuan yang diberikan klien.
            </p>

            <div class="psy-detail__grid">

                <!-- TITIK JEMPUT -->

                <div class="psy-detail__item">

                    <span>Titik Jemput</span>

                    <?php if ($titikJemput !== ''): ?>

                        <strong class="psy-pickup__address">
                            <?= nl2br(esc($titikJemput)) ?>
                        </strong>

                    <?php else: ?>

                        <p class="psy-pickup__empty">
                            Alamat penjemputan
                            belum tersedia.
                        </p>

                    <?php endif; ?>

                </div>

                <!-- TITIK TUJUAN -->

                <div class="psy-detail__item">

                    <span>Titik Tujuan</span>

                    <?php if ($titikTujuan !== ''): ?>

                        <strong class="psy-pickup__address">
                            <?= nl2br(esc($titikTujuan)) ?>
                        </strong>

                    <?php else: ?>

                        <p class="psy-pickup__empty">
                            Alamat tujuan
                            belum tersedia.
                        </p>

                    <?php endif; ?>

                </div>

            </div>

            <!-- STATUS INFORMASI LOKASI -->

            <?php if (!$alamatLengkap): ?>

                <div class="psy-pickup__notice">

                    <strong>
                        Informasi lokasi belum lengkap.
                    </strong>

                    <p>
                        Silakan hubungi klien melalui
                        WhatsApp untuk mengonfirmasi
                        alamat penjemputan dan tujuan
                        sebelum perjalanan dimulai.
                    </p>

                </div>

            <?php else: ?>

                <div class="psy-pickup__ready">

                    <strong>
                        Informasi lokasi tersedia.
                    </strong>

                    <p>
                        Pastikan kembali alamat
                        penjemputan, titik pertemuan,
                        dan tujuan perjalanan bersama
                        klien sebelum memulai sesi.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    <?php endif; ?>

    <!-- ======================================
         INFORMASI STATUS KONSULTASI
    ======================================= -->

    <?php if (
        $status === 'Dikonfirmasi' && !$canStart
    ): ?>

        <div class="psy-detail__info">
            <p>
                Konsultasi telah dikonfirmasi.
                Tombol Mulai Sesi tersedia mulai
                15 menit sebelum jadwal konsultasi.
            </p>
        </div>

    <?php elseif ($status === 'Berlangsung'): ?>

        <div class="psy-detail__info">
            <p>
                Sesi konsultasi sedang berlangsung.
                Klik Selesaikan Sesi setelah konsultasi
                benar-benar berakhir.
            </p>
        </div>

    <?php elseif ($status === 'Selesai'): ?>

        <div class="psy-detail__info">
            <p>
                Konsultasi telah selesai dan tersimpan
                dalam riwayat konsultasi.
            </p>
        </div>

    <?php elseif ($status === 'Dibatalkan'): ?>

        <div class="psy-detail__info">
            <p>
                Konsultasi ini telah dibatalkan
                atau ditolak.
            </p>
        </div>

    <?php endif; ?>

    <!-- ======================================
         TOMBOL AKSI SESI
    ======================================= -->

    <div class="psy-detail__actions">

        <!-- TOMBOL KEMBALI DINAMIS -->

        <a
            href="<?= esc($backUrl, 'attr') ?>"
            class="psy-btn psy-btn--secondary"
        >
            Kembali
        </a>

        <!-- TERIMA DAN TOLAK -->

        <?php if ($canRespond): ?>

            <form
                action="<?= site_url(
                    'psychologist/consultations/'
                    . $consultationId
                    . '/confirm'
                ) ?>"
                method="post"
            >

                <?= csrf_field() ?>

                <button
                    type="submit"
                    class="psy-btn psy-btn--accept"
                >
                    Terima Konsultasi
                </button>

            </form>

            <button
                type="button"
                class="psy-btn psy-btn--reject js-session-modal"
                data-action="<?= esc(
                    site_url(
                        'psychologist/consultations/'
                        . $consultationId
                        . '/reject'
                    ),
                    'attr'
                ) ?>"
                data-title="Konfirmasi Tolak Konsultasi"
                data-message="Apakah Anda yakin ingin menolak permintaan konsultasi ini?"
                data-confirm="Ya, Tolak"
            >
                Tolak Konsultasi
            </button>

        <!-- MULAI SESI -->

        <?php elseif ($canStart): ?>

            <button
                type="button"
                class="psy-btn psy-btn--primary js-session-modal"
                data-action="<?= esc(
                    site_url(
                        'psychologist/consultations/'
                        . $consultationId
                        . '/start'
                    ),
                    'attr'
                ) ?>"
                data-title="Konfirmasi Mulai Sesi"
                data-message="Apakah Anda yakin ingin memulai sesi konsultasi ini?"
                data-confirm="Ya, Mulai Sesi"
            >
                Mulai Sesi
            </button>

        <!-- SELESAIKAN SESI -->

        <?php elseif ($canFinish): ?>

            <button
                type="button"
                class="psy-btn psy-btn--accept js-session-modal"
                data-action="<?= esc(
                    site_url(
                        'psychologist/consultations/'
                        . $consultationId
                        . '/finish'
                    ),
                    'attr'
                ) ?>"
                data-title="Konfirmasi Selesaikan Sesi"
                data-message="Pastikan sesi konsultasi benar-benar telah selesai."
                data-confirm="Ya, Selesaikan"
            >
                Selesaikan Sesi
            </button>

        <?php endif; ?>

    </div>

</div>

<!-- ==========================================
     MODAL KONFIRMASI SESI
========================================== -->

<div
    class="psy-modal"
    id="psySessionModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="psySessionModalTitle"
    aria-describedby="psySessionModalMessage"
    aria-hidden="true"
>

    <div
        class="psy-modal__backdrop"
        data-close-session-modal
    ></div>

    <div class="psy-modal__dialog">

        <div
            class="psy-modal__icon"
            aria-hidden="true"
        >
            !
        </div>

        <h2 id="psySessionModalTitle">
            Konfirmasi
        </h2>

        <p id="psySessionModalMessage"></p>

        <form
            id="psySessionForm"
            method="post"
        >

            <?= csrf_field() ?>

            <div class="psy-modal__actions">

                <button
                    type="button"
                    class="psy-modal__cancel"
                    data-close-session-modal
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="psy-modal__confirm"
                    id="psySessionConfirm"
                >
                    Ya, Lanjutkan
                </button>

            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>
