
<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>
<?= $this->section('content') ?>

<div class="dashboard-header">
    <h1>Daftar Klien</h1>
    <p>Kelola informasi klien yang pernah berkonsultasi dengan Anda.</p>
</div>

<div class="psy-clients">
    <div class="psy-clients__toolbar">
        <h2>Klien Saya (<?= count($clients) ?>)</h2>

        <input
            type="search"
            id="clientSearch"
            placeholder="Cari nama klien..."
            aria-label="Cari nama klien"
        >
    </div>

    <div class="psy-clients__table-wrap">
        <table class="psy-clients__table">
            <thead>
                <tr>
                    <th>Nama Klien</th>
                    <th>Total Sesi</th>
                    <th>Konsultasi Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr class="js-client-row">
                        <td>
                            <strong><?= esc($client['nama_lengkap']) ?></strong>
                        </td>

                        <td><?= (int) $client['total_sesi'] ?> sesi</td>

                        <td>
                            <?= !empty($client['tanggal_terakhir'])
                                ? esc(date('d/m/Y', strtotime($client['tanggal_terakhir'])))
                                : '-' ?>
                        </td>

                        <td>
                            <a
                                class="psy-btn psy-btn--primary"
                                href="<?= site_url('psychologist/clients/' . (int) $client['id_user']) ?>"
                            >
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="4" class="psy-clients__empty">
                            Belum ada klien yang memiliki konsultasi terkonfirmasi.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p id="clientSearchEmpty" class="psy-clients__empty" hidden>
            Tidak ada klien yang cocok dengan pencarian.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('clientSearch');
    const rows = document.querySelectorAll('.js-client-row');
    const empty = document.getElementById('clientSearchEmpty');

    input.addEventListener('input', function () {
        const keyword = this.value.toLocaleLowerCase('id').trim();
        let visible = 0;

        rows.forEach(function (row) {
            const name = row.cells[0].textContent.toLocaleLowerCase('id');
            const match = name.includes(keyword);

            row.hidden = !match;
            if (match) visible++;
        });

        empty.hidden = visible !== 0 || rows.length === 0;
    });
});
</script>

<?= $this->endSection() ?>
