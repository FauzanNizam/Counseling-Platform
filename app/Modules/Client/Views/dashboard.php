<?= $this->extend('Modules\Shared\Views\layouts\client') ?>

<?= $this->section('content') ?>

<style>
    .dashboard {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .page-hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        border-radius: 20px;
        color: white;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
    }

    .page-hero::after {
        content: "";
        position: absolute;
        width: 210px;
        height: 210px;
        right: -55px;
        top: -80px;
        border: 35px solid #ffffff12;
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-label {
        display: inline-block;
        margin-bottom: 14px;
        padding: 6px 10px;
        border: 1px solid #ffffff40;
        border-radius: 20px;
        background: #ffffff18;
        font-size: 12px;
    }

    .page-hero h1 {
        max-width: 550px;
        margin-bottom: 12px;
        font-size: clamp(25px, 3vw, 34px);
        line-height: 1.25;
    }

    .page-hero p {
        max-width: 540px;
        color: #f0e7ff;
        font-size: 14px;
        line-height: 1.8;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .hero-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 12px 16px;
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        transition: transform .2s, background .2s;
    }

    .hero-button:hover {
        transform: translateY(-2px);
    }

    .hero-button.primary {
        background: white;
        color: #5b21b6;
    }

    .hero-button.secondary {
        border-color: #ffffff55;
        background: #ffffff18;
        color: white;
    }

    .notif-banner {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 20px;
        border: 1px solid #ddd0ff;
        border-radius: 14px;
        background: #f5f0ff;
    }

    .notif-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: #e4d5ff;
        font-size: 19px;
    }

    .notif-copy {
        font-size: 13px;
        line-height: 1.7;
    }

    .notif-copy strong {
        color: #3b1678;
    }

    .notif-copy a {
        display: inline-block;
        margin-left: 4px;
        color: #6d28d9;
        font-weight: 700;
    }

    .dashboard-section {
        min-width: 0;
    }

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .section-heading h2 {
        font-size: 19px;
        color: #292436;
    }

    .section-heading p {
        margin-top: 5px;
        color: #817b8d;
        font-size: 12px;
        line-height: 1.5;
    }

    .section-link {
        flex-shrink: 0;
        color: #6d28d9;
        font-size: 12px;
        font-weight: 700;
    }

    .service-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .service-card {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        min-width: 0;
        padding: 22px;
        border: 1px solid #eeeaf3;
        border-radius: 16px;
        background: white;
        transition: transform .2s, box-shadow .2s;
    }

    .service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px #34205b0d;
    }

    .service-card.featured {
        border: 1px solid #d8bfff;
    }

    .service-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 13px;
        background: #ede0ff;
        font-size: 23px;
    }

    .service-icon.pickup {
        background: #d9f5e9;
    }

    .service-card h3 {
        margin-bottom: 8px;
        font-size: 15px;
    }

    .service-card p {
        color: #817b8d;
        font-size: 12px;
        line-height: 1.7;
    }

    .service-price {
        margin-top: 13px;
        color: #6d28d9;
        font-size: 12px;
        font-weight: 700;
    }

    .psychologist-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .psychologist-card {
        min-width: 0;
        padding: 20px;
        border: 1px solid #eeeaf3;
        border-radius: 16px;
        background: white;
    }

    .psychologist-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .psychologist-avatar {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 16px;
        font-weight: 700;
    }

    .psychologist-card h3 {
        margin-bottom: 5px;
        font-size: 13px;
        line-height: 1.5;
    }

    .psychologist-specialty {
        color: #817b8d;
        font-size: 11px;
        line-height: 1.5;
    }

    .psychologist-rating {
        margin-bottom: 14px;
        color: #6d28d9;
        font-size: 12px;
        font-weight: 700;
    }

    .psychologist-rating span {
        color: #817b8d;
        font-weight: 400;
    }

    .psychologist-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding-top: 14px;
        border-top: 1px solid #f0edf4;
    }

    .psychologist-price {
        font-size: 12px;
        font-weight: 700;
        line-height: 1.6;
    }

    .psychologist-price small {
        display: block;
        color: #817b8d;
        font-size: 10px;
        font-weight: 400;
    }

    .small-button {
        padding: 9px 11px;
        border: 0;
        border-radius: 8px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        cursor: pointer;
    }

    .small-button:hover {
        background: #d8bfff;
    }

    @media (max-width: 1000px) {
        .psychologist-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .dashboard {
            gap: 20px;
        }

        .page-hero {
            padding: 24px 20px;
            border-radius: 16px;
        }

        .page-hero h1 {
            max-width: 320px;
            font-size: 27px;
        }

        .page-hero p {
            font-size: 13px;
        }

        .hero-actions {
            flex-direction: column;
        }

        .hero-button {
            width: 100%;
        }

        .notif-banner {
            padding: 15px;
            gap: 11px;
        }

        .section-heading h2 {
            font-size: 17px;
        }

        .service-grid,
        .psychologist-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .service-card {
            padding: 18px;
        }

        .psychologist-card {
            padding: 18px;
        }
    }
</style>

<div class="dashboard">

    <!-- HERO -->
    <section class="page-hero">
        <div class="hero-label">Ruang aman untuk dirimu</div>

        <h1>Ceritakan apa yang kamu rasakan.</h1>

        <p>
            Psikolog kami siap membantu kamu hari ini —
            online maupun langsung ke lokasimu.
        </p>

        <div class="hero-actions">

            <a
                href="<?= site_url('client/konsultan') ?>"
                class="hero-button primary">
                📅 Booking Sekarang
            </a>

            <a
                href="<?= site_url('client/assessment') ?>"
                class="hero-button secondary">
                📋 Self-Assessment
            </a>

        </div>
    </section>


    <!-- INFORMASI JADWAL -->
    <section class="notif-banner">
        <div class="notif-icon">🔔</div>

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
                    <?= esc(substr($jadwalBerikutnya['waktu_mulai'], 0, 5)) ?>.
                </div>

                <div style="margin-top: 6px;">
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
                        <a
                            href="https://wa.me/<?= esc($nomorWa) ?>?text=<?= rawurlencode('Halo, saya ingin menghubungi Anda terkait jadwal konsultasi saya.') ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="
                display: inline-block;
                margin-top: 10px;
                padding: 10px 16px;
                border-radius: 9px;
                background: #25D366;
                color: #fff;
                text-decoration: none;
                font-weight: 600;
            ">
                            Chat via WhatsApp
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                

                <a href="<?= site_url('client/jadwal') ?>">
                    Lihat Jadwal Saya →
                </a>
            <?php else: ?>
                <strong>Belum Ada Jadwal Konsultasi</strong>

                <div>
                    Kamu belum memiliki pengajuan konsultasi aktif.
                    Cari psikolog dan pilih jadwal yang sesuai dengan kebutuhanmu.
                </div>

                <a href="<?= site_url('client/konsultan') ?>">
                    Cari Psikolog →
                </a>
            <?php endif; ?>
        </div>
    </section>



    <!-- LAYANAN -->
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
            <article class="service-card featured">
                <div class="service-icon">🧑‍⚕️</div>

                <div>
                    <h3>Cari Psikolog</h3>
                    <p>
                        Temukan psikolog berdasarkan spesialisasi,
                        pengalaman, dan profil yang tersedia.
                    </p>

                    <a
                        class="service-price"
                        href="<?= site_url('client/konsultan') ?>"
                        style="text-decoration:none;">
                        Cari Psikolog →
                    </a>
                </div>
            </article>

            <article class="service-card">
                <div class="service-icon pickup">📋</div>

                <div>
                    <h3>Self-Assessment</h3>
                    <p>
                        Isi penilaian mandiri dan lihat riwayat
                        hasil assessment yang pernah kamu kerjakan.
                    </p>

                    <a
                        class="service-price"
                        href="<?= site_url('client/assessment') ?>"
                        style="text-decoration:none;">
                        Mulai Assessment →
                    </a>
                </div>
            </article>

            <article class="service-card">
                <div class="service-icon">📅</div>

                <div>
                    <h3>Jadwal Konsultasi</h3>
                    <p>
                        Pantau pengajuan konsultasi dan status
                        konfirmasi dari psikolog.
                    </p>

                    <a
                        class="service-price"
                        href="<?= site_url('client/jadwal') ?>"
                        style="text-decoration:none;">
                        Lihat Jadwal →
                    </a>
                </div>
            </article>

            <article class="service-card">
                <div class="service-icon pickup">💬</div>

                <div>
                    <h3>Konsultasi via WhatsApp</h3>
                    <p>
                        Hubungi psikolog melalui WhatsApp setelah
                        konsultasimu dikonfirmasi.
                    </p>

                    <a
                        class="service-price"
                        href="<?= site_url('client/jadwal') ?>"
                        style="text-decoration:none;">
                        Buka Jadwal Saya →
                    </a>
                </div>
            </article>
        </div>
    </section>


    <!-- PSIKOLOG -->
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
                class="section-link">
                Lihat semua →
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
                    $kataNama = preg_split('/\s+/', trim((string) $nama)) ?: [];
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

                            <div>
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
                                style="text-decoration:none;">
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