
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>

<?= $this->section('content') ?>

<?php

// ==========================================
// DATA FILTER
// ==========================================

$keyword = (string) ($keyword ?? '');
$statusFilter = (string) ($selectedStatus ?? '');
$verificationFilter = (string) ($selectedVerification ?? '');


// ==========================================
// LABEL STATUS
// ==========================================

$statusLabels = [
    'aktif' => 'Aktif',
    'nonaktif' => 'Nonaktif',
    'ditangguhkan' => 'Ditangguhkan',
];

$verificationLabels = [
    'pending' => 'Pending',
    'verified' => 'Verified',
    'rejected' => 'Rejected',
];


// ==========================================
// FLASH MESSAGE
// ==========================================

$flashType = null;
$flashMessage = null;

if ($message = session()->getFlashdata('success')) {
    $flashType = 'success';
    $flashMessage = $message;
} elseif ($message = session()->getFlashdata('error')) {
    $flashType = 'error';
    $flashMessage = $message;
}

?>


<!-- ======================================
     HEADER HALAMAN
====================================== -->

<div class="dashboard-header">

    <h1>Manajemen Konsultan</h1>

    <p>
        Kelola akun dan profil konsultan yang telah
        direkrut oleh Counseling Platform.
    </p>

</div>


<!-- ======================================
     POPUP SUKSES / ERROR
====================================== -->

<?php if ($flashMessage !== null): ?>

    <div
        id="consultantFlashAlert"
        data-type="<?= esc($flashType, 'attr') ?>"
        data-message="<?= esc($flashMessage, 'attr') ?>"
        hidden
    ></div>

<?php endif; ?>


<!-- ======================================
     KARTU UTAMA
====================================== -->

<div class="management-card consultant-page-card">

    <!-- HEADER DAFTAR KONSULTAN -->

    <div class="consultant-list-header">

        <div>

            <h2>Daftar Konsultan</h2>

            <p>
                Kelola informasi akun, profil profesional,
                dan status verifikasi konsultan.
            </p>

        </div>


        <!-- TOMBOL TAMBAH KONSULTAN -->

        <a
            href="<?= site_url('admin/consultants/create') ?>"
            class="consultant-btn consultant-add-btn"
        >
            <i
                class="bi bi-person-plus"
                aria-hidden="true"
            ></i>

            Tambah Konsultan
        </a>

    </div>


    <!-- ======================================
         FORM PENCARIAN DAN FILTER
    ====================================== -->

    <form
        action="<?= site_url('admin/consultants') ?>"
        method="get"
        class="consultant-filter-form"
    >

        <!-- PENCARIAN -->

        <div class="consultant-search">

            <label
                for="keyword"
                class="consultant-sr-only"
            >
                Cari Konsultan
            </label>

            <input
                type="search"
                id="keyword"
                name="keyword"
                placeholder="Cari nama atau email konsultan..."
                value="<?= esc($keyword, 'attr') ?>"
            >

        </div>


        <!-- FILTER STATUS AKUN -->

        <div class="consultant-filter-select">

            <label
                for="status_akun"
                class="consultant-sr-only"
            >
                Filter Status Akun
            </label>

            <select
                id="status_akun"
                name="status"
            >

                <option value="">
                    Semua status akun
                </option>

                <?php foreach ($statusLabels as $value => $label): ?>

                    <option
                        value="<?= esc($value, 'attr') ?>"
                        <?= $statusFilter === $value ? 'selected' : '' ?>
                    >
                        <?= esc($label) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- FILTER VERIFIKASI -->

        <div class="consultant-filter-select">

            <label
                for="status_verifikasi"
                class="consultant-sr-only"
            >
                Filter Status Verifikasi
            </label>

            <select
                id="status_verifikasi"
                name="verification"
            >

                <option value="">
                    Semua verifikasi
                </option>

                <?php foreach ($verificationLabels as $value => $label): ?>

                    <option
                        value="<?= esc($value, 'attr') ?>"
                        <?= $verificationFilter === $value ? 'selected' : '' ?>
                    >
                        <?= esc($label) ?>
                    </option>

                <?php endforeach; ?>


                <option
                    value="missing"
                    <?= $verificationFilter === 'missing' ? 'selected' : '' ?>
                >
                    Belum ada profil
                </option>

            </select>

        </div>


        <!-- TOMBOL FILTER -->

        <button
            type="submit"
            class="consultant-btn consultant-filter-btn"
        >
            <i
                class="bi bi-funnel"
                aria-hidden="true"
            ></i>

            Filter
        </button>


        <!-- TOMBOL RESET -->

        <a
            href="<?= site_url('admin/consultants') ?>"
            class="consultant-btn-secondary consultant-reset-btn"
        >
            <i
                class="bi bi-arrow-counterclockwise"
                aria-hidden="true"
            ></i>

            Reset
        </a>

    </form>


    <!-- ======================================
         TABEL DAFTAR KONSULTAN
    ====================================== -->

    <div class="consultant-table-wrapper">

        <table class="consultant-table">

            <thead>

                <tr>
                    <th>Nama Konsultan</th>
                    <th>Email</th>
                    <th>Spesialisasi</th>
                    <th>Status Akun</th>
                    <th>Verifikasi</th>
                    <th class="consultant-actions-heading">Aksi</th>
                </tr>

            </thead>


            <tbody>

                <?php if (!empty($consultants)): ?>

                    <?php foreach ($consultants as $consultant): ?>

                        <?php

                        $id = (int) $consultant['id_user'];

                        $name = (string) (
                            $consultant['nama_lengkap'] ?? ''
                        );

                        $email = (string) (
                            $consultant['email'] ?? ''
                        );

                        $specialization = (string) (
                            $consultant['spesialisasi'] ?? ''
                        );

                        $accountStatus = (string) (
                            $consultant['status_akun'] ?? ''
                        );

                        $verificationStatus = (string) (
                            $consultant['status_verifikasi'] ?? ''
                        );

                        $hasProfile = !empty(
                            $consultant['id_profil']
                        );

                        ?>


                        <tr>

                            <!-- NAMA KONSULTAN -->

                            <td>

                                <span class="consultant-name">
                                    <?= esc($name) ?>
                                </span>

                            </td>


                            <!-- EMAIL -->

                            <td>
                                <?= esc($email) ?>
                            </td>


                            <!-- SPESIALISASI -->

                            <td>
                                <?= esc(
                                    $specialization !== ''
                                        ? $specialization
                                        : '-'
                                ) ?>
                            </td>


                            <!-- STATUS AKUN -->

                            <td>

                                <span
                                    class="consultant-status consultant-status-<?= esc($accountStatus, 'attr') ?>"
                                >
                                    <?= esc(
                                        $statusLabels[$accountStatus]
                                        ?? $accountStatus
                                    ) ?>
                                </span>

                            </td>


                            <!-- STATUS VERIFIKASI -->

                            <td>

                                <?php if (!$hasProfile): ?>

                                    <span
                                        class="consultant-status consultant-status-missing"
                                    >
                                        Belum ada profil
                                    </span>

                                <?php else: ?>

                                    <span
                                        class="consultant-status consultant-status-<?= esc($verificationStatus, 'attr') ?>"
                                    >
                                        <?= esc(
                                            $verificationLabels[$verificationStatus]
                                            ?? 'Pending'
                                        ) ?>
                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- TOMBOL AKSI -->

                            <td>

                                <div class="consultant-table-actions">

                                    <!-- DETAIL -->

                                    <a
                                        href="<?= site_url('admin/consultants/' . $id) ?>"
                                        class="consultant-action consultant-action-detail"
                                        title="Lihat detail konsultan"
                                    >
                                        <i
                                            class="bi bi-eye"
                                            aria-hidden="true"
                                        ></i>

                                        Detail
                                    </a>


                                    <!-- EDIT -->

                                    <a
                                        href="<?= site_url('admin/consultants/' . $id . '/edit') ?>"
                                        class="consultant-action consultant-action-edit"
                                        title="Edit konsultan"
                                    >
                                        <i
                                            class="bi bi-pencil-square"
                                            aria-hidden="true"
                                        ></i>

                                        Edit
                                    </a>


                                    <!-- HAPUS -->

                                    <form
                                        action="<?= site_url('admin/consultants/' . $id . '/delete') ?>"
                                        method="post"
                                        class="consultant-delete-form"
                                        data-consultant-name="<?= esc($name, 'attr') ?>"
                                        onsubmit="return false;"
                                    >

                                        <?= csrf_field() ?>

                                        <button
                                            type="submit"
                                            class="consultant-action consultant-action-delete"
                                            title="Hapus konsultan"
                                        >
                                            <i
                                                class="bi bi-trash"
                                                aria-hidden="true"
                                            ></i>

                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <!-- DATA KOSONG -->

                    <tr>

                        <td
                            colspan="6"
                            class="consultant-empty"
                        >
                            Belum ada data konsultan yang sesuai
                            dengan pencarian atau filter.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>


    <!-- ======================================
         PAGINATION
    ====================================== -->

    <?php if (isset($pager) && $pager): ?>

        <div class="consultant-pagination">

            <?= $pager->links() ?>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>
