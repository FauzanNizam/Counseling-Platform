
<?= $this->extend('Modules\Shared\Views\layouts\psychologist') ?>

<?= $this->section('content') ?>

<?php
$schedules = $schedules ?? [];
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
$success = session()->getFlashdata('success');
$error = session()->getFlashdata('error');
?>

<div class="dashboard-header">
    <h1>Jadwal Praktik</h1>
    <p>Atur hari, jam, dan kuota konsultasi yang tersedia untuk klien.</p>
</div>

<div class="psy-practice">

    <?php if ($success): ?>
        <div class="psy-alert psy-alert--success" role="alert">
            <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="psy-alert psy-alert--error" role="alert">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <!-- FORM TAMBAH JADWAL -->
    <section class="psy-practice__card">

        <div class="psy-practice__heading">
            <div>
                <h2>Tambah Jadwal Praktik</h2>
                <p>Jadwal aktif dapat dipilih klien ketika mengajukan konsultasi.</p>
            </div>
        </div>

        <form
            action="<?= site_url('psychologist/practice-schedule/store') ?>"
            method="post"
            class="psy-practice__form"
        >
            <?= csrf_field() ?>

            <div class="psy-practice__fields">

                <div class="psy-practice__field">
                    <label for="practiceHari">Hari Praktik</label>
                    <select id="practiceHari" name="hari" required>
                        <option value="">-- Pilih Hari --</option>

                        <?php foreach ($days as $day): ?>
                            <option
                                value="<?= esc($day, 'attr') ?>"
                                <?= old('hari') === $day ? 'selected' : '' ?>
                            >
                                <?= esc($day) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="psy-practice__field">
                    <label for="practiceStart">Jam Mulai</label>
                    <input
                        id="practiceStart"
                        type="time"
                        name="jam_mulai"
                        value="<?= esc(old('jam_mulai', '09:00'), 'attr') ?>"
                        required
                    >
                </div>

                <div class="psy-practice__field">
                    <label for="practiceEnd">Jam Selesai</label>
                    <input
                        id="practiceEnd"
                        type="time"
                        name="jam_selesai"
                        value="<?= esc(old('jam_selesai', '17:00'), 'attr') ?>"
                        required
                    >
                </div>

                <div class="psy-practice__field">
                    <label for="practiceQuota">Kuota Konsultasi</label>
                    <input
                        id="practiceQuota"
                        type="number"
                        name="kuota"
                        min="1"
                        max="100"
                        value="<?= esc(old('kuota', '4'), 'attr') ?>"
                        required
                    >
                </div>

            </div>

            <div class="psy-practice__form-footer">
                <p>
                    Pastikan jam praktik tidak bertabrakan dengan jadwal lain.
                </p>

                <button type="submit" class="psy-btn psy-btn--primary">
                    + Tambah Jadwal
                </button>
            </div>

        </form>

    </section>


    <!-- DAFTAR JADWAL -->
    <section class="psy-practice__card">

        <div class="psy-practice__heading">
            <div>
                <h2>Daftar Jadwal Praktik</h2>
                <p>Kelola ketersediaan jadwal yang telah dibuat.</p>
            </div>

            <span class="psychologist-schedule__count">
                <?= count($schedules) ?> Jadwal
            </span>
        </div>

        <?php if (empty($schedules)): ?>

            <div class="psychologist-schedule__empty">
                Belum ada jadwal praktik.
                Tambahkan jadwal melalui formulir di atas.
            </div>

        <?php else: ?>

            <div class="psy-practice__table-wrap">
                <table class="psy-practice__table">

                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam Praktik</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($schedules as $schedule): ?>

                            <?php
                            $id = (int) $schedule['id_jadwal'];
                            $active = (int) $schedule['is_aktif'] === 1;

                            $start = substr(
                                $schedule['jam_mulai'],
                                0,
                                5
                            );

                            $end = substr(
                                $schedule['jam_selesai'],
                                0,
                                5
                            );
                            ?>

                            <tr>

                                <td class="psy-practice__day">
                                    <?= esc($schedule['hari']) ?>
                                </td>

                                <td>
                                    <?= esc($start) ?>–<?= esc($end) ?>
                                </td>

                                <td>
                                    <?= esc($schedule['kuota']) ?>
                                </td>

                                <td>
                                    <span class="psy-practice__status <?= $active ? 'is-active' : 'is-inactive' ?>">
                                        <?= $active ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="psy-actions">

                                        <button
                                            type="button"
                                            class="psy-btn psy-btn--secondary js-edit-practice"
                                            data-action="<?= esc(
                                                site_url(
                                                    'psychologist/practice-schedule/' .
                                                    $id . '/update'
                                                ),
                                                'attr'
                                            ) ?>"
                                            data-hari="<?= esc($schedule['hari'], 'attr') ?>"
                                            data-start="<?= esc($start, 'attr') ?>"
                                            data-end="<?= esc($end, 'attr') ?>"
                                            data-quota="<?= esc($schedule['kuota'], 'attr') ?>"
                                        >
                                            Edit
                                        </button>

                                        <form
                                            action="<?= site_url(
                                                'psychologist/practice-schedule/' .
                                                $id . '/toggle'
                                            ) ?>"
                                            method="post"
                                        >
                                            <?= csrf_field() ?>

                                            <button
                                                type="button"
                                                class="psy-btn <?= $active ? 'psy-btn--reject' : 'psy-btn--accept' ?> js-toggle-practice"
                                                data-label="<?= $active ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                            >
                                                <?= $active ? 'Nonaktifkan' : 'Aktifkan' ?>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>
            </div>

        <?php endif; ?>

    </section>

</div>


<!-- MODAL EDIT JADWAL -->
<div
    class="psy-practice-modal"
    id="practiceEditModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="practiceEditTitle"
    aria-hidden="true"
>
    <div class="psy-practice-modal__backdrop" data-close-practice-edit></div>

    <div class="psy-practice-modal__dialog">

        <h2 id="practiceEditTitle">Edit Jadwal Praktik</h2>

        <p>Perbarui hari, jam praktik, atau kuota konsultasi.</p>

        <form id="practiceEditForm" method="post">
            <?= csrf_field() ?>

            <div class="psy-practice__field">
                <label for="editHari">Hari Praktik</label>

                <select id="editHari" name="hari" required>
                    <?php foreach ($days as $day): ?>
                        <option value="<?= esc($day, 'attr') ?>">
                            <?= esc($day) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="psy-practice__field">
                <label for="editStart">Jam Mulai</label>
                <input
                    id="editStart"
                    name="jam_mulai"
                    type="time"
                    required
                >
            </div>

            <div class="psy-practice__field">
                <label for="editEnd">Jam Selesai</label>
                <input
                    id="editEnd"
                    name="jam_selesai"
                    type="time"
                    required
                >
            </div>

            <div class="psy-practice__field">
                <label for="editQuota">Kuota Konsultasi</label>
                <input
                    id="editQuota"
                    name="kuota"
                    type="number"
                    min="1"
                    max="100"
                    required
                >
            </div>

            <div class="psy-practice-modal__actions">
                <button
                    type="button"
                    class="psy-btn psy-btn--secondary"
                    data-close-practice-edit
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="psy-btn psy-btn--primary"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>


<!-- MODAL KONFIRMASI AKTIF / NONAKTIF -->
<div
    class="psy-modal"
    id="practiceToggleModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="practiceToggleTitle"
    aria-describedby="practiceToggleMessage"
    aria-hidden="true"
>
    <div class="psy-modal__backdrop" data-close-practice-toggle></div>

    <div class="psy-modal__dialog">

        <div class="psy-modal__icon" aria-hidden="true">!</div>

        <h2 id="practiceToggleTitle">Konfirmasi</h2>

        <p id="practiceToggleMessage"></p>

        <div class="psy-modal__actions">

            <button
                type="button"
                class="psy-modal__cancel"
                data-close-practice-toggle
            >
                Batal
            </button>

            <button
                type="button"
                class="psy-modal__confirm"
                id="practiceToggleConfirm"
            >
                Ya, Lanjutkan
            </button>

        </div>

    </div>
</div>

<?= $this->endSection() ?>
