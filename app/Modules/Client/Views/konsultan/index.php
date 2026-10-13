<?= $this->extend('Modules\Shared\Views\layouts\client') ?>

<?= $this->section('content') ?>

<?php
/** @var array $konsultan */
?>

<style>
    /* Search dan filter */
    .konsultan-filters {
        display: flex;
        gap: 12px;
        margin: 28px 0 14px;
        max-width: 760px;
    }

    .konsultan-filters input,
    .konsultan-filters select {
        box-sizing: border-box;
        height: 48px;
        padding: 0 16px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        color: #1f2937;
        font: inherit;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .konsultan-filters input {
        flex: 1;
        min-width: 0;
    }

    .konsultan-filters select {
        width: 230px;
        cursor: pointer;
    }

    .konsultan-filters input:focus,
    .konsultan-filters select:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
    }

    .jumlah-konsultan {
        color: #6b7280;
        font-size: 14px;
        margin: 0 0 16px;
    }

    .konsultan-card {
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }

    .konsultan-card:hover {
        transform: translateY(-3px);
        border-color: #ddd6fe;
        box-shadow: 0 10px 24px rgba(91, 33, 182, .08);
    }

    .konsultan-link {
        padding: 9px 14px;
        border-radius: 9px;
        background: #f5f3ff;
        transition: background .2s, color .2s;
    }

    .konsultan-link:hover {
        background: #5b21b6;
        color: #fff;
        text-decoration: none;
    }

    @media (max-width: 600px) {
        .konsultan-filters {
            flex-direction: column;
        }

        .konsultan-filters select {
            width: 100%;
        }

        .konsultan-filters input {
            width: 100%;
            min-height: 48px;
        }
    }

    .konsultan-page {
        padding: 28px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .konsultan-heading {
        margin-bottom: 24px;
    }

    .konsultan-heading h1 {
        color: #5b21b6;
        font-size: 30px;
        margin: 0 0 8px;
    }

    .konsultan-heading p {
        color: #6b7280;
        margin: 0;
    }

    .konsultan-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .konsultan-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
        min-width: 0;
    }

    .konsultan-avatar {
        width: 64px;
        height: 64px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #ede9fe;
        color: #5b21b6;
        font-size: 26px;
    }

    .konsultan-info {
        flex: 1;
        min-width: 0;
    }

    .konsultan-info h3 {
        margin: 0 0 6px;
        color: #1f2937;
        font-size: 17px;
        overflow-wrap: anywhere;
    }

    .konsultan-info p {
        margin: 4px 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.5;
    }

    .konsultan-link {
        display: inline-block;
        margin-top: 10px;
        color: #6d28d9;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .konsultan-link:hover {
        text-decoration: underline;
    }

    .konsultan-empty {
        padding: 24px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        color: #6b7280;
    }

    @media (max-width: 900px) {
        .konsultan-list {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .konsultan-page {
            padding: 20px 14px;
        }

        .konsultan-heading h1 {
            font-size: 25px;
        }

        .konsultan-card {
            padding: 16px;
            gap: 12px;
        }

        .konsultan-avatar {
            width: 52px;
            height: 52px;
            font-size: 22px;
        }

    }
</style>

<div class="konsultan-page">

    <div class="konsultan-heading">
        <h1>Cari Psikolog</h1>
        <p>Temukan konsultan yang sesuai dengan kebutuhan konselingmu.</p>
    </div>

    <div class="konsultan-filters">
        <input
            type="text"
            id="cariKonsultan"
            placeholder="Cari nama konsultan..."
            aria-label="Cari nama konsultan">

        <select id="filterSpesialisasi" aria-label="Filter spesialisasi">
            <option value="">Semua spesialisasi</option>

            <?php
            $daftarSpesialisasi = array_unique(
                array_filter(array_column($konsultan, 'spesialisasi'))
            );
            sort($daftarSpesialisasi);
            ?>

            <?php foreach ($daftarSpesialisasi as $spesialisasi): ?>
                <option value="<?= esc($spesialisasi) ?>">
                    <?= esc($spesialisasi) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <p id="jumlahKonsultan" class="jumlah-konsultan"></p>


    <?php if (empty($konsultan)): ?>
        <div class="konsultan-empty">
            Belum ada konsultan yang tersedia saat ini.
        </div>
    <?php else: ?>
        <div class="konsultan-list">
            <?php foreach ($konsultan as $item): ?>
                <article class="konsultan-card">
                    <div class="konsultan-avatar">
                        <?= esc(strtoupper(substr($item['nama_lengkap'], 0, 1))) ?>
                    </div>

                    <div class="konsultan-info">
                        <h3><?= esc($item['nama_lengkap']) ?></h3>

                        <p><?= esc($item['spesialisasi']) ?></p>

                        <p>
                            Pengalaman:
                            <?= esc($item['pengalaman_tahun']) ?> tahun
                        </p>

                        <a
                            class="konsultan-link"
                            href="<?= site_url('client/konsultan/' . $item['id_profil']) ?>">
                            Lihat Profil →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputCari = document.getElementById('cariKonsultan');
        const filterSpesialisasi = document.getElementById('filterSpesialisasi');
        const kartuKonsultan = document.querySelectorAll('.konsultan-card');
        const jumlahKonsultan = document.getElementById('jumlahKonsultan');

        function filterKonsultan() {
            const kataKunci = inputCari.value.toLowerCase().trim();
            const spesialisasiDipilih = filterSpesialisasi.value.toLowerCase();
            let jumlahTampil = 0;

            kartuKonsultan.forEach(function(kartu) {
                const nama = kartu.querySelector('h3').textContent.toLowerCase();
                const spesialisasi = kartu.querySelector('.konsultan-info p').textContent.toLowerCase();

                const cocokNama = nama.includes(kataKunci);
                const cocokSpesialisasi =
                    spesialisasiDipilih === '' ||
                    spesialisasi === spesialisasiDipilih;

                const tampil = cocokNama && cocokSpesialisasi;

                kartu.style.display = tampil ? 'flex' : 'none';

                if (tampil) {
                    jumlahTampil++;
                }
            });

            jumlahKonsultan.textContent =
                'Menampilkan ' + jumlahTampil + ' konsultan';
        }

        inputCari.addEventListener('input', filterKonsultan);
        filterSpesialisasi.addEventListener('change', filterKonsultan);

        filterKonsultan();
    });
</script>


<?= $this->endSection() ?>