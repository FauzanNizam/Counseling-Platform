
<?= $this->extend('Modules\Shared\Views\layouts\client') ?>
<?= $this->section('content') ?>

<?php
/** @var array $konsultan */

$konsultanData = $konsultan ?? [];

$daftarSpesialisasi = array_unique(
    array_filter(array_column($konsultanData, 'spesialisasi'))
);

sort($daftarSpesialisasi);
?>

<style>
    /* ==========================================
       CARI PSIKOLOG - CLIENT
       MEDIUM & CONSISTENT DESIGN
    ========================================== */

    .konsultan-page {
        --k-primary: #6d28d9;
        --k-dark: #5b21b6;
        --k-text: #292436;
        --k-muted: #746e80;
        --k-border: #eae5f1;

        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        color: var(--k-text);
        font-size: 14px;
        line-height: 1.6;
    }

    .konsultan-page *,
    .konsultan-page *::before,
    .konsultan-page *::after {
        box-sizing: border-box;
    }

    /* ==========================================
       HEADER
    ========================================== */

    .konsultan-page .konsultan-header {
        position: relative;
        overflow: hidden;
        padding: 34px 36px;
        margin-bottom: 24px;
        border-radius: 18px;
        background: linear-gradient(120deg, #5b21b6, #7c3aed);
        color: #fff;
    }

    .konsultan-page .konsultan-header::after {
        content: "";
        position: absolute;
        top: -90px;
        right: -65px;
        width: 240px;
        height: 240px;
        border: 38px solid rgba(255,255,255,.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .konsultan-page .konsultan-header > * {
        position: relative;
        z-index: 1;
    }

    .konsultan-page .konsultan-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding: 7px 13px;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 30px;
        background: rgba(255,255,255,.12);
        color: #fff;
        font-size: 13px;
        font-weight: 500;
    }

    .konsultan-page .konsultan-label .bi {
        font-size: 16px;
    }

    .konsultan-page .konsultan-header h1 {
        margin: 0 0 12px;
        color: #fff;
        font-size: clamp(27px, 3vw, 32px);
        font-weight: 700;
        line-height: 1.3;
        letter-spacing: -.4px;
    }

    .konsultan-page .konsultan-header p {
        max-width: 680px;
        margin: 0;
        color: #f0e7ff;
        font-size: 15px;
        line-height: 1.75;
    }

    /* ==========================================
       SEARCH AND FILTER
    ========================================== */

    .konsultan-page .konsultan-filter-card {
        padding: 23px 24px;
        margin-bottom: 24px;
        border: 1px solid var(--k-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61,31,100,.025);
    }

    .konsultan-page .konsultan-filter-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .konsultan-page .konsultan-filter-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #ede0ff;
        color: #5b21b6;
        font-size: 21px;
    }

    .konsultan-page .konsultan-filter-heading h2 {
        margin: 0;
        color: var(--k-text);
        font-size: 19px;
        font-weight: 700;
    }

    .konsultan-page .konsultan-filter-heading p {
        margin: 4px 0 0;
        color: var(--k-muted);
        font-size: 14px;
    }

    .konsultan-page .konsultan-filters {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 16px;
    }

    .konsultan-page .konsultan-field {
        min-width: 0;
    }

    .konsultan-page .konsultan-field label {
        display: block;
        margin-bottom: 8px;
        color: #3c3449;
        font-size: 14px;
        font-weight: 600;
    }

    .konsultan-page .konsultan-input-wrap {
        position: relative;
    }

    .konsultan-page .konsultan-input-icon {
        position: absolute;
        top: 50%;
        left: 16px;
        color: #8c8499;
        font-size: 18px;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .konsultan-page .konsultan-filters input,
    .konsultan-page .konsultan-filters select {
        display: block;
        width: 100%;
        height: 48px;
        padding: 0 15px;
        border: 1px solid #e5e0ed;
        border-radius: 10px;
        background: #fff;
        color: var(--k-text);
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .konsultan-page .konsultan-filters input {
        padding-left: 45px;
    }

    .konsultan-page .konsultan-filters input::placeholder {
        color: #9891a3;
    }

    .konsultan-page .konsultan-filters select {
        cursor: pointer;
    }

    .konsultan-page .konsultan-filters input:focus,
    .konsultan-page .konsultan-filters select:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(124,58,237,.1);
    }

    /* ==========================================
       SECTION HEADING
    ========================================== */

    .konsultan-page .konsultan-section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .konsultan-page .konsultan-section-heading h2 {
        margin: 0;
        color: var(--k-text);
        font-size: 20px;
        font-weight: 700;
    }

    .konsultan-page .jumlah-konsultan {
        margin: 0;
        padding: 7px 13px;
        border: 1px solid #ddd0ff;
        border-radius: 30px;
        background: #f5f0ff;
        color: #5b21b6;
        font-size: 13px;
        font-weight: 600;
    }

    /* ==========================================
       PSYCHOLOGIST CARDS
    ========================================== */

    .konsultan-page .konsultan-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .konsultan-page .konsultan-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        padding: 23px;
        border: 1px solid var(--k-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 14px rgba(61,31,100,.025);
        transition: transform .2s, border-color .2s, box-shadow .2s;
    }

    .konsultan-page .konsultan-card:hover {
        transform: translateY(-3px);
        border-color: #d8bfff;
        box-shadow: 0 10px 26px rgba(91,33,182,.065);
    }

    .konsultan-page .konsultan-card-top {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
        margin-bottom: 18px;
    }

    .konsultan-page .konsultan-avatar {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 62px;
        height: 62px;
        border-radius: 16px;
        background: linear-gradient(135deg, #ede0ff, #f5f0ff);
        color: #5b21b6;
        font-size: 25px;
        font-weight: 700;
    }

    .konsultan-page .konsultan-info {
        flex: 1;
        min-width: 0;
    }

    .konsultan-page .konsultan-info h3 {
        margin: 0 0 6px;
        color: var(--k-text);
        font-size: 17px;
        font-weight: 700;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .konsultan-page .konsultan-role {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--k-muted);
        font-size: 13px;
    }

    .konsultan-page .konsultan-role .bi {
        color: #8b5cf6;
        font-size: 15px;
    }

    /* CARD INFORMATION */

    .konsultan-page .konsultan-meta {
        display: flex;
        flex-direction: column;
        gap: 13px;
        margin-bottom: 20px;
        padding: 17px;
        border: 1px solid #f0edf4;
        border-radius: 11px;
        background: #faf9fd;
    }

    .konsultan-page .konsultan-meta-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .konsultan-page .konsultan-meta-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 35px;
        height: 35px;
        border-radius: 9px;
        background: #ede0ff;
        color: #6d28d9;
        font-size: 17px;
    }

    .konsultan-page .konsultan-meta-label {
        margin-bottom: 3px;
        color: var(--k-muted);
        font-size: 12px;
    }

    .konsultan-page .konsultan-meta-value {
        color: #3c3449;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    /* CARD BUTTON */

    .konsultan-page .konsultan-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        min-height: 46px;
        margin-top: auto;
        padding: 12px 18px;
        border: 1px solid #ddd0ff;
        border-radius: 10px;
        background: #f5f0ff;
        color: #5b21b6;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        text-decoration: none;
        transition: background .2s, color .2s;
    }

    .konsultan-page .konsultan-link:hover {
        background: #6d28d9;
        color: #fff;
        text-decoration: none;
    }

    .konsultan-page .konsultan-link .bi {
        font-size: 17px;
    }

    /* ==========================================
       EMPTY STATES
    ========================================== */

    .konsultan-page .konsultan-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 44px 22px;
        border: 1px dashed #c4b5fd;
        border-radius: 15px;
        background: #fff;
        text-align: center;
    }

    .konsultan-page .konsultan-empty-icon {
        display: grid;
        place-items: center;
        width: 68px;
        height: 68px;
        margin-bottom: 5px;
        border-radius: 19px;
        background: #f5f0ff;
        color: #6d28d9;
        font-size: 30px;
    }

    .konsultan-page .konsultan-empty h3 {
        margin: 0;
        color: var(--k-text);
        font-size: 19px;
        font-weight: 700;
    }

    .konsultan-page .konsultan-empty p {
        max-width: 450px;
        margin: 0;
        color: var(--k-muted);
        font-size: 14px;
        line-height: 1.75;
    }

    .konsultan-page .konsultan-no-results {
        display: none;
        margin-top: 18px;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 900px) {
        .konsultan-page .konsultan-list {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .konsultan-page .konsultan-header {
            padding: 26px 22px;
            border-radius: 15px;
        }

        .konsultan-page .konsultan-header h1 {
            font-size: 27px;
        }

        .konsultan-page .konsultan-header p {
            font-size: 14px;
        }

        .konsultan-page .konsultan-filter-card {
            padding: 19px;
        }

        .konsultan-page .konsultan-filters {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .konsultan-page .konsultan-section-heading h2 {
            font-size: 19px;
        }

        .konsultan-page .konsultan-card {
            padding: 19px;
        }
    }

    @media (max-width: 420px) {
        .konsultan-page .konsultan-card-top {
            gap: 12px;
        }

        .konsultan-page .konsultan-avatar {
            width: 53px;
            height: 53px;
            font-size: 22px;
        }

        .konsultan-page .konsultan-info h3 {
            font-size: 16px;
        }

        .konsultan-page .konsultan-meta {
            padding: 14px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .konsultan-page *,
        .konsultan-page *::before,
        .konsultan-page *::after {
            transition: none !important;
        }
    }
</style>

<div class="konsultan-page">

    <!-- ==========================================
         HEADER
    ========================================== -->

    <section class="konsultan-header">

        <div class="konsultan-label">
            <i class="bi bi-person-heart" aria-hidden="true"></i>
            Temukan pendamping yang tepat
        </div>

        <h1>Cari Psikolog</h1>

        <p>
            Temukan psikolog yang sesuai dengan kebutuhan konselingmu.
            Jelajahi spesialisasi dan pengalaman untuk mengenal
            psikolog sebelum melakukan konsultasi.
        </p>

    </section>

    <!-- ==========================================
         SEARCH AND FILTER
    ========================================== -->

    <section class="konsultan-filter-card">

        <div class="konsultan-filter-heading">

            <div class="konsultan-filter-icon">
                <i class="bi bi-search" aria-hidden="true"></i>
            </div>

            <div>
                <h2>Cari Psikolog</h2>
                <p>Gunakan pencarian dan filter untuk menemukan psikolog.</p>
            </div>

        </div>

        <div class="konsultan-filters">

            <div class="konsultan-field">

                <label for="cariKonsultan">
                    Nama Psikolog
                </label>

                <div class="konsultan-input-wrap">

                    <i
                        class="bi bi-search konsultan-input-icon"
                        aria-hidden="true"
                    ></i>

                    <input
                        type="search"
                        id="cariKonsultan"
                        placeholder="Cari nama psikolog..."
                        autocomplete="off"
                    >

                </div>

            </div>

            <div class="konsultan-field">

                <label for="filterSpesialisasi">
                    Spesialisasi
                </label>

                <select id="filterSpesialisasi">

                    <option value="">
                        Semua Spesialisasi
                    </option>

                    <?php foreach ($daftarSpesialisasi as $spesialisasi): ?>

                        <option value="<?= esc($spesialisasi, 'attr') ?>">
                            <?= esc($spesialisasi) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

    </section>

    <!-- ==========================================
         SECTION HEADING
    ========================================== -->

    <div class="konsultan-section-heading">

        <h2>Psikolog Tersedia</h2>

        <p
            id="jumlahKonsultan"
            class="jumlah-konsultan"
            aria-live="polite"
        >
            Menampilkan <?= count($konsultanData) ?> psikolog
        </p>

    </div>

    <!-- ==========================================
         EMPTY DATABASE
    ========================================== -->

    <?php if (empty($konsultanData)): ?>

        <div class="konsultan-empty">

            <div class="konsultan-empty-icon">
                <i class="bi bi-person-x" aria-hidden="true"></i>
            </div>

            <h3>Belum Ada Psikolog Tersedia</h3>

            <p>
                Saat ini belum ada psikolog yang tersedia.
                Silakan kembali lagi untuk melihat
                daftar psikolog terbaru.
            </p>

        </div>

    <?php else: ?>

        <!-- ======================================
             PSYCHOLOGIST LIST
        ====================================== -->

        <div class="konsultan-list" id="konsultanList">

            <?php foreach ($konsultanData as $item): ?>

                <?php
                    $nama = $item['nama_lengkap'] ?? 'Psikolog';
                    $spesialisasi = $item['spesialisasi'] ?? '';
                    $pengalaman = $item['pengalaman_tahun'] ?? 0;

                    $inisial = function_exists('mb_substr')
                        ? mb_substr($nama, 0, 1, 'UTF-8')
                        : substr($nama, 0, 1);

                    $inisial = function_exists('mb_strtoupper')
                        ? mb_strtoupper($inisial, 'UTF-8')
                        : strtoupper($inisial);
                ?>

                <article
                    class="konsultan-card"
                    data-nama="<?= esc($nama, 'attr') ?>"
                    data-spesialisasi="<?= esc($spesialisasi, 'attr') ?>"
                >

                    <!-- CARD TOP -->

                    <div class="konsultan-card-top">

                        <div class="konsultan-avatar" aria-hidden="true">
                            <?= esc($inisial) ?>
                        </div>

                        <div class="konsultan-info">

                            <h3><?= esc($nama) ?></h3>

                            <div class="konsultan-role">
                                <i
                                    class="bi bi-person-badge"
                                    aria-hidden="true"
                                ></i>
                                Konsultan
                            </div>

                        </div>

                    </div>

                    <!-- CARD INFORMATION -->

                    <div class="konsultan-meta">

                        <div class="konsultan-meta-row">

                            <div class="konsultan-meta-icon">
                                <i
                                    class="bi bi-heart-pulse"
                                    aria-hidden="true"
                                ></i>
                            </div>

                            <div>

                                <div class="konsultan-meta-label">
                                    Spesialisasi
                                </div>

                                <div class="konsultan-meta-value">
                                    <?= esc($spesialisasi ?: 'Belum dicantumkan') ?>
                                </div>

                            </div>

                        </div>

                        <div class="konsultan-meta-row">

                            <div class="konsultan-meta-icon">
                                <i
                                    class="bi bi-award"
                                    aria-hidden="true"
                                ></i>
                            </div>

                            <div>

                                <div class="konsultan-meta-label">
                                    Pengalaman
                                </div>

                                <div class="konsultan-meta-value">
                                    <?= esc((string) $pengalaman) ?> tahun
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- CARD BUTTON -->

                    <a
                        href="<?= site_url(
                            'client/konsultan/' . $item['id_profil']
                        ) ?>"
                        class="konsultan-link"
                    >
                        Lihat Profil
                        <i
                            class="bi bi-arrow-right"
                            aria-hidden="true"
                        ></i>
                    </a>

                </article>

            <?php endforeach; ?>

        </div>

        <!-- ======================================
             NO SEARCH RESULTS
        ====================================== -->

        <div
            class="konsultan-empty konsultan-no-results"
            id="konsultanNoResults"
            role="status"
        >

            <div class="konsultan-empty-icon">
                <i class="bi bi-search" aria-hidden="true"></i>
            </div>

            <h3>Psikolog Tidak Ditemukan</h3>

            <p>
                Tidak ada psikolog yang sesuai dengan pencarianmu.
                Coba gunakan nama atau spesialisasi yang berbeda.
            </p>

        </div>

    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const page = document.querySelector('.konsultan-page');

    if (!page) {
        return;
    }

    const inputCari = page.querySelector('#cariKonsultan');
    const filterSpesialisasi = page.querySelector('#filterSpesialisasi');
    const kartuKonsultan = page.querySelectorAll('.konsultan-card');
    const jumlahKonsultan = page.querySelector('#jumlahKonsultan');
    const noResults = page.querySelector('#konsultanNoResults');

    if (!inputCari || !filterSpesialisasi || !jumlahKonsultan) {
        return;
    }

    function filterKonsultan() {

        const kataKunci = inputCari.value.toLocaleLowerCase().trim();
        const spesialisasiDipilih =
            filterSpesialisasi.value.toLocaleLowerCase().trim();

        let jumlahTampil = 0;

        kartuKonsultan.forEach(function (kartu) {

            const nama = (kartu.dataset.nama || '')
                .toLocaleLowerCase();

            const spesialisasi = (kartu.dataset.spesialisasi || '')
                .toLocaleLowerCase()
                .trim();

            const cocokNama = nama.includes(kataKunci);

            const cocokSpesialisasi =
                spesialisasiDipilih === '' ||
                spesialisasi === spesialisasiDipilih;

            const tampil = cocokNama && cocokSpesialisasi;

            kartu.hidden = !tampil;

            if (tampil) {
                jumlahTampil++;
            }

        });

        jumlahKonsultan.textContent =
            'Menampilkan ' + jumlahTampil + ' psikolog';

        if (noResults) {
            noResults.style.display =
                jumlahTampil === 0 ? 'flex' : 'none';
        }

    }

    inputCari.addEventListener('input', filterKonsultan);
    filterSpesialisasi.addEventListener('change', filterKonsultan);

    filterKonsultan();

});
</script>

<style>
    /* Pastikan kartu yang disembunyikan filter tidak tampil */
    .konsultan-page .konsultan-card[hidden] {
        display: none !important;
    }
</style>

<?= $this->endSection() ?>
