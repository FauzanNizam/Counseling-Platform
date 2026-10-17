
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<style>
    /* ==========================================
       DASHBOARD CLIENT - MEDIUM DESIGN
    ========================================== */

    .dashboard {
        --dash-primary: #6d28d9;
        --dash-primary-dark: #5b21b6;
        --dash-text: #292436;
        --dash-muted: #746e80;
        --dash-border: #eae5f1;

        display: flex;
        flex-direction: column;
        gap: 26px;
        width: 100%;
        color: var(--dash-text);
        font-size: 14px;
        line-height: 1.6;
    }

    .dashboard a {
        text-decoration: none;
    }

    /* ==========================================
       HERO
    ========================================== */

    .dashboard .page-hero {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        border-radius: 18px;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
        color: #fff;
    }

    .dashboard .page-hero::after {
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

    .dashboard .page-hero > * {
        position: relative;
        z-index: 1;
    }

    .dashboard .hero-label {
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

    .dashboard .hero-label .bi {
        font-size: 16px;
    }

    .dashboard .page-hero h1 {
        max-width: 650px;
        margin: 0 0 12px;
        color: #fff;
        font-size: clamp(27px, 3vw, 32px);
        font-weight: 700;
        line-height: 1.3;
        letter-spacing: -.4px;
    }

    .dashboard .page-hero p {
        max-width: 590px;
        margin: 0;
        color: #f0e7ff;
        font-size: 15px;
        line-height: 1.75;
    }

    .dashboard .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .dashboard .hero-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 45px;
        padding: 11px 19px;
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        transition: background .2s, transform .2s;
    }

    .dashboard .hero-button .bi {
        font-size: 17px;
        line-height: 1;
    }

    .dashboard .hero-button:hover {
        transform: translateY(-2px);
    }

    .dashboard .hero-button.primary {
        background: #fff;
        color: #5b21b6;
    }

    .dashboard .hero-button.primary:hover {
        background: #f3ebff;
    }

    .dashboard .hero-button.secondary {
        border-color: rgba(255, 255, 255, .4);
        background: rgba(255, 255, 255, .12);
        color: #fff;
    }

    .dashboard .hero-button.secondary:hover {
        background: rgba(255, 255, 255, .22);
    }

    /* ==========================================
       INFORMASI JADWAL
    ========================================== */

    .dashboard .notif-banner {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px 22px;
        border: 1px solid #ddd0ff;
        border-radius: 15px;
        background: #f5f0ff;
    }

    .dashboard .notif-icon {
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

    .dashboard .notif-copy {
        flex: 1;
        min-width: 0;
        color: #615a6d;
        font-size: 14px;
        line-height: 1.75;
    }

    .dashboard .notif-copy > strong {
        display: block;
        margin-bottom: 5px;
        color: #3b1678;
        font-size: 15px;
        font-weight: 700;
    }

    .dashboard .notif-copy strong {
        color: #3b1678;
    }

    .dashboard .notif-copy a {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #6d28d9;
        font-size: 14px;
        font-weight: 600;
    }

    .dashboard .notif-copy a:hover {
        text-decoration: underline;
    }

    .dashboard .notif-action {
        margin-top: 10px;
    }

    .dashboard .notif-copy .whatsapp-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        margin-top: 12px;
        padding: 11px 16px;
        border-radius: 9px;
        background: #25d366;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        transition: background .2s;
    }

    .dashboard .notif-copy .whatsapp-button:hover {
        background: #1eae54;
        color: #fff;
        text-decoration: none;
    }

    .dashboard .whatsapp-button .bi {
        font-size: 17px;
    }

    /* ==========================================
       SECTION HEADING
    ========================================== */

    .dashboard .dashboard-section {
        min-width: 0;
    }

    .dashboard .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .dashboard .section-heading h2 {
        margin: 0;
        color: #292436;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.4;
        letter-spacing: -.2px;
    }

    .dashboard .section-heading p {
        margin: 5px 0 0;
        color: var(--dash-muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .dashboard .section-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        flex-shrink: 0;
        color: #6d28d9;
        font-size: 14px;
        font-weight: 600;
    }

    .dashboard .section-link:hover {
        color: #4c1d95;
    }

    /* ==========================================
       LAYANAN KAMI
    ========================================== */

    .dashboard .service-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .dashboard .service-card {
        display: flex;
        align-items: flex-start;
        gap: 17px;
        min-width: 0;
        min-height: 165px;
        padding: 23px;
        border: 1px solid var(--dash-border);
        border-radius: 15px;
        background: #fff;
        transition: transform .2s, box-shadow .2s;
    }

    .dashboard .service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(52, 32, 91, .07);
    }

    .dashboard .service-card.featured {
        border-color: #d8bfff;
    }

    .dashboard .service-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        border-radius: 13px;
        background: #ede0ff;
        color: #5b21b6;
    }

    .dashboard .service-icon .bi {
        font-size: 24px;
        line-height: 1;
    }

    .dashboard .service-icon.pickup {
        background: #d9f5e9;
        color: #168451;
    }

    .dashboard .service-card-content {
        flex: 1;
        min-width: 0;
    }

    .dashboard .service-card h3 {
        margin: 0 0 8px;
        color: #292436;
        font-size: 16px;
        font-weight: 700;
        line-height: 1.45;
    }

    .dashboard .service-card p {
        margin: 0;
        color: var(--dash-muted);
        font-size: 14px;
        line-height: 1.7;
    }

    .dashboard .service-price {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 15px;
        color: #6d28d9;
        font-size: 14px;
        font-weight: 600;
    }

    .dashboard .service-price:hover {
        color: #4c1d95;
    }

    .dashboard .service-price .bi {
        font-size: 14px;
        transition: transform .2s;
    }

    .dashboard .service-price:hover .bi {
        transform: translateX(3px);
    }

    /* ==========================================
       PSIKOLOG TERSEDIA
    ========================================== */

    .dashboard .psychologist-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .dashboard .psychologist-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        padding: 21px;
        border: 1px solid var(--dash-border);
        border-radius: 15px;
        background: #fff;
        transition: transform .2s, box-shadow .2s;
    }

    .dashboard .psychologist-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 26px rgba(52, 32, 91, .06);
    }

    .dashboard .psychologist-profile {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        margin-bottom: 18px;
    }

    .dashboard .psychologist-avatar {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 17px;
        font-weight: 700;
    }

    .dashboard .psychologist-info {
        min-width: 0;
    }

    .dashboard .psychologist-card h3 {
        margin: 0 0 5px;
        color: #292436;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .dashboard .psychologist-specialty {
        color: var(--dash-muted);
        font-size: 13px;
        line-height: 1.55;
        overflow-wrap: anywhere;
    }

    .dashboard .psychologist-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid #f0edf4;
    }

    .dashboard .psychologist-price {
        color: #292436;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.5;
    }

    .dashboard .psychologist-price small {
        display: block;
        color: var(--dash-muted);
        font-size: 12px;
        font-weight: 400;
    }

    .dashboard .small-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 9px 12px;
        border: 0;
        border-radius: 9px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        transition: background .2s;
    }

    .dashboard .small-button:hover {
        background: #d8bfff;
        color: #5b21b6;
    }

    .dashboard .small-button .bi {
        font-size: 15px;
    }

    /* ==========================================
       EMPTY STATE
    ========================================== */

    .dashboard .jadwal-empty {
        padding: 30px 22px;
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        background: #fff;
        color: var(--dash-muted);
        font-size: 14px;
        text-align: center;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 1200px) {
        .dashboard .psychologist-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1000px) {
        .dashboard .service-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 700px) {
        .dashboard {
            gap: 22px;
        }

        .dashboard .page-hero {
            padding: 26px 22px;
        }

        .dashboard .page-hero h1 {
            font-size: 27px;
        }

        .dashboard .page-hero p {
            font-size: 14px;
        }

        .dashboard .hero-actions {
            flex-direction: column;
        }

        .dashboard .hero-button {
            width: 100%;
        }

        .dashboard .service-grid,
        .dashboard .psychologist-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .dashboard .service-card {
            min-height: auto;
            padding: 19px;
        }

        .dashboard .psychologist-card {
            padding: 19px;
        }

        .dashboard .notif-banner {
            padding: 17px;
            gap: 12px;
        }

        .dashboard .section-heading h2 {
            font-size: 19px;
        }
    }

    @media (max-width: 400px) {
        .dashboard .service-card {
            gap: 12px;
        }

        .dashboard .service-icon {
            width: 45px;
            height: 45px;
        }

        .dashboard .service-icon .bi {
            font-size: 21px;
        }

        .dashboard .psychologist-footer {
            flex-wrap: wrap;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .dashboard *,
        .dashboard *::before,
        .dashboard *::after {
            transition: none !important;
        }
    }
</style>

<div class="dashboard">

    <!-- ==========================================
         HERO
    ========================================== -->

    <section class="page-hero">

        <div class="hero-label">
            <i class="bi bi-heart-pulse" aria-hidden="true"></i>
            Ruang aman untuk dirimu
        </div>

        <h1>Ceritakan apa yang kamu rasakan.</h1>

        <p>
            Psikolog kami siap membantu kamu hari ini —
            online maupun langsung ke lokasimu.
        </p>

        <div class="hero-actions">

            <a
                href="<?= site_url('client/konsultan') ?>"
                class="hero-button primary"
            >
                <i class="bi bi-calendar2-plus" aria-hidden="true"></i>
                Booking Sekarang
            </a>

            <a
                href="<?= site_url('client/assessment') ?>"
                class="hero-button secondary"
            >
                <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                Self-Assessment
            </a>

        </div>

    </section>

    <!-- ==========================================
         INFORMASI JADWAL
    ========================================== -->

    <section class="notif-banner">

        <div class="notif-icon">
            <i class="bi bi-bell" aria-hidden="true"></i>
        </div>

        <div class="notif-copy">

            <?php if (!empty($jadwalBerikutnya)): ?>

                <strong>Informasi Konsultasi Berikutnya</strong>

                <div>
                    Konsultasi dengan
                    <strong>
                        <?= esc($jadwalBerikutnya['nama_konsultan']) ?>
                    </strong>
                    pada
                    <?= esc(date(
                        'd-m-Y',
                        strtotime($jadwalBerikutnya['tanggal'])
                    )) ?>,
                    pukul
                    <?= esc(substr(
                        $jadwalBerikutnya['waktu_mulai'],
                        0,
                        5
                    )) ?>.
                </div>

                <div style="margin-top: 7px;">
                    Status:
                    <strong>
                        <?= esc($jadwalBerikutnya['status']) ?>
                    </strong>
                </div>

                <?php if (
                    $jadwalBerikutnya['status'] === 'Dikonfirmasi'
                    && !empty($jadwalBerikutnya['telepon_konsultan'])
                ): ?>

                    <?php
                    $nomorWa = preg_replace(
                        '/\D+/',
                        '',
                        $jadwalBerikutnya['telepon_konsultan']
                    );

                    if (str_starts_with($nomorWa, '0')) {
                        $nomorWa = '62' . substr($nomorWa, 1);
                    }
                    ?>

                    <?php if ($nomorWa !== ''): ?>

                        <div>
                            <a
                                href="https://wa.me/<?= esc($nomorWa, 'attr') ?>?text=<?= rawurlencode('Halo, saya ingin menghubungi Anda terkait jadwal konsultasi saya.') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="whatsapp-button"
                            >
                                <i class="bi bi-whatsapp" aria-hidden="true"></i>
                                Chat via WhatsApp
                            </a>
                        </div>

                    <?php endif; ?>

                <?php endif; ?>

                <div class="notif-action">
                    <a href="<?= site_url('client/jadwal') ?>">
                        Lihat Jadwal Saya
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>

            <?php else: ?>

                <strong>Belum Ada Jadwal Konsultasi</strong>

                <div>
                    Kamu belum memiliki pengajuan konsultasi aktif.
                    Cari psikolog dan pilih jadwal yang sesuai
                    dengan kebutuhanmu.
                </div>

                <div class="notif-action">
                    <a href="<?= site_url('client/konsultan') ?>">
                        Cari Psikolog
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>

            <?php endif; ?>

        </div>

    </section>

    <!-- ==========================================
         LAYANAN KAMI
    ========================================== -->

    <section class="dashboard-section">

        <div class="section-heading">
            <div>
                <h2>Layanan Kami</h2>
                <p>
                    Fitur yang dapat kamu gunakan di Counseling Platform.
                </p>
            </div>
        </div>

        <div class="service-grid">

            <!-- CARI PSIKOLOG -->

            <article class="service-card featured">

                <div class="service-icon">
                    <i class="bi bi-person-heart" aria-hidden="true"></i>
                </div>

                <div class="service-card-content">

                    <h3>Cari Psikolog</h3>

                    <p>
                        Temukan psikolog berdasarkan spesialisasi,
                        pengalaman, dan profil yang tersedia.
                    </p>

                    <a
                        class="service-price"
                        href="<?= site_url('client/konsultan') ?>"
                    >
                        Cari Psikolog
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>

                </div>

            </article>

            <!-- SELF-ASSESSMENT -->

            <article class="service-card">

                <div class="service-icon pickup">
                    <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                </div>

                <div class="service-card-content">

                    <h3>Self-Assessment</h3>

                    <p>
                        Isi penilaian mandiri dan lihat riwayat
                        hasil assessment yang pernah kamu kerjakan.
                    </p>

                    <a
                        class="service-price"
                        href="<?= site_url('client/assessment') ?>"
                    >
                        Mulai Assessment
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>

                </div>

            </article>

            <!-- JADWAL KONSULTASI -->

            <article class="service-card">

                <div class="service-icon">
                    <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                </div>

                <div class="service-card-content">

                    <h3>Jadwal Konsultasi</h3>

                    <p>
                        Pantau pengajuan konsultasi dan status
                        konfirmasi dari psikolog.
                    </p>

                    <a
                        class="service-price"
                        href="<?= site_url('client/jadwal') ?>"
                    >
                        Lihat Jadwal
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>

                </div>

            </article>

            <!-- WHATSAPP -->

            <article class="service-card">

                <div class="service-icon pickup">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                </div>

                <div class="service-card-content">

                    <h3>Konsultasi via WhatsApp</h3>

                    <p>
                        Hubungi psikolog melalui WhatsApp setelah
                        konsultasimu dikonfirmasi.
                    </p>

                    <a
                        class="service-price"
                        href="<?= site_url('client/jadwal') ?>"
                    >
                        Buka Jadwal Saya
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>

                </div>

            </article>

        </div>

    </section>

    <!-- ==========================================
         PSIKOLOG TERSEDIA
    ========================================== -->

    <section class="dashboard-section">

        <div class="section-heading">

            <div>
                <h2>Psikolog Tersedia</h2>

                <p>
                    <?= esc((string) ($jumlahKonsultan ?? 0)) ?>
                    psikolog terdaftar di platform.
                </p>
            </div>

            <a
                href="<?= site_url('client/konsultan') ?>"
                class="section-link"
            >
                Lihat semua
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>

        </div>

        <?php if (empty($konsultan)): ?>

            <div class="jadwal-empty">
                Belum ada psikolog yang tersedia.
            </div>

        <?php else: ?>

            <div class="psychologist-grid">

                <?php foreach ($konsultan as $item): ?>

                    <?php
                    $nama = $item['nama_lengkap'] ?? 'Psikolog';

                    $kataNama = preg_split(
                        '/\s+/',
                        trim((string) $nama)
                    ) ?: [];

                    $inisial = '';

                    foreach (array_slice($kataNama, 0, 2) as $kata) {
                        $inisial .= mb_substr((string) $kata, 0, 1);
                    }

                    $inisial = mb_strtoupper($inisial);

                    if ($inisial === '') {
                        $inisial = 'PS';
                    }
                    ?>

                    <article class="psychologist-card">

                        <div class="psychologist-profile">

                            <div class="psychologist-avatar">
                                <?= esc($inisial) ?>
                            </div>

                            <div class="psychologist-info">

                                <h3><?= esc($nama) ?></h3>

                                <div class="psychologist-specialty">
                                    <?= esc(
                                        $item['spesialisasi']
                                            ?? 'Spesialisasi belum tersedia'
                                    ) ?>
                                </div>

                            </div>

                        </div>

                        <div class="psychologist-footer">

                            <div class="psychologist-price">
                                <?= esc(
                                    $item['pengalaman_tahun'] ?? 0
                                ) ?> tahun

                                <small>pengalaman</small>
                            </div>

                            <a
                                class="small-button"
                                href="<?= site_url(
                                    'client/konsultan/' .
                                    ($item['id_profil'] ?? '')
                                ) ?>"
                            >
                                <i class="bi bi-eye" aria-hidden="true"></i>
                                Lihat Profil
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</div>

<?= $this->endSection() ?>
