
<?= $this->extend('Modules\Shared\Views\layouts\admin') ?>
<?= $this->section('content') ?>

<?php
$consultation = $consultation ?? [];

$id = (int) ($consultation['id_konsultasi'] ?? 0);

/**
 * Menampilkan nilai kosong sebagai tanda "-".
 */
$display = static function ($value): string {
    if ($value === null || trim((string) $value) === '') {
        return '-';
    }

    return (string) $value;
};

/**
 * Mengambil nilai dari kolom yang tersedia.
 *
 * Beberapa nama kolom tanggal/waktu masih perlu
 * dicocokkan dengan struktur database asli.
 */
$getValue = static function (
    array $data,
    array $columns
) use ($display): string {
    foreach ($columns as $column) {
        if (
            array_key_exists($column, $data)
            && $data[$column] !== null
            && trim((string) $data[$column]) !== ''
        ) {
            return $display($data[$column]);
        }
    }

    return '-';
};

/**
 * Mengubah tanggal menjadi format Indonesia.
 */
$formatDate = static function ($value) use ($display): string {
    if ($value === null || $value === '' || $value === '-') {
        return '-';
    }

    $timestamp = strtotime((string) $value);

    if ($timestamp === false) {
        return $display($value);
    }

    return date('d/m/Y', $timestamp);
};

/**
 * Mengubah waktu menjadi HH:mm.
 */
$formatTime = static function ($value) use ($display): string {
    if ($value === null || $value === '' || $value === '-') {
        return '-';
    }

    $timestamp = strtotime((string) $value);

    if ($timestamp === false) {
        return $display($value);
    }

    return date('H:i', $timestamp);
};

/**
 * Menentukan warna badge berdasarkan status.
 * Memakai class CSS yang sudah digunakan
 * pada Manajemen Konsultan.
 */
$getStatusClass = static function ($value): string {
    $status = strtolower(trim((string) $value));

    if (in_array($status, [
        'selesai',
        'completed',
        'berhasil',
    ], true)) {
        return 'consultant-status-aktif';
    }

    if (in_array($status, [
        'dibatalkan',
        'batal',
        'cancelled',
        'ditolak',
        'rejected',
    ], true)) {
        return 'consultant-status-rejected';
    }

    if (in_array($status, [
        'berlangsung',
        'dikonfirmasi',
        'confirmed',
    ], true)) {
        return 'consultant-status-verified';
    }

    return 'consultant-status-pending';
};

// Data utama konsultasi
$clientName = $display(
    $consultation['nama_klien'] ?? null
);

$psychologistName = $display(
    $consultation['nama_psikolog'] ?? null
);

$sessionType = $display(
    $consultation['tipe_sesi'] ?? null
);

$status = $display(
    $consultation['status'] ?? null
);

$statusClass = $getStatusClass($status);

// Tanggal dan waktu
$consultationDate = $getValue(
    $consultation,
    [
        'tanggal_konsultasi',
        'tanggal',
        'tanggal_sesi',
    ]
);

$startTime = $getValue(
    $consultation,
    [
        'waktu_mulai',
        'jam_mulai',
    ]
);

$endTime = $getValue(
    $consultation,
    [
        'waktu_selesai',
        'jam_selesai',
    ]
);

// Waktu pencatatan
$createdAt = $getValue(
    $consultation,
    ['created_at']
);

$updatedAt = $getValue(
    $consultation,
    ['updated_at']
);

// Pesan flash
$flashType = '';
$flashMessage = '';

if ($message = session()->getFlashdata('success')) {
    $flashType = 'success';
    $flashMessage = $message;
} elseif ($message = session()->getFlashdata('error')) {
    $flashType = 'error';
    $flashMessage = $message;
}
?>

<!-- ========================================
     HEADER HALAMAN
======================================== -->

<div class="dashboard-header">
    <h1>Detail Konsultasi</h1>

    <p>
        Informasi administratif sesi konsultasi
        antara klien dan psikolog.
    </p>
</div>

<!-- ========================================
     NOTIFIKASI POPUP
======================================== -->

<?php if ($flashMessage !== ''): ?>
    <div
        id="appFlashAlert"
        data-type="<?= esc($flashType, 'attr') ?>"
        data-message="<?= esc($flashMessage, 'attr') ?>"
        hidden
    ></div>
<?php endif; ?>

<!-- ========================================
     INFORMASI UTAMA
======================================== -->

<div class="management-card consultant-page-card">

    <div class="consultant-detail-header">

        <div>
            <h2>Informasi Konsultasi</h2>

            <p>
                Detail sesi konsultasi
                #<?= esc((string) $id) ?>
            </p>
        </div>

        <div class="consultant-detail-actions">

            <a
                href="<?= site_url('admin/consultations') ?>"
                class="consultant-btn-secondary"
            >
                Kembali ke Daftar
            </a>

        </div>

    </div>

    <!-- INFORMASI PESERTA -->
    <div class="consultant-detail-section">

        <h3>Informasi Peserta</h3>

        <div class="consultant-detail-grid">

            <div class="consultant-detail">
                <span>Nama Klien</span>

                <strong>
                    <?= esc($clientName) ?>
                </strong>
            </div>

            <div class="consultant-detail">
                <span>Nama Psikolog</span>

                <strong>
                    <?= esc($psychologistName) ?>
                </strong>
            </div>

        </div>

    </div>

    <!-- INFORMASI SESI -->
    <div class="consultant-detail-section">

        <h3>Informasi Sesi</h3>

        <div class="consultant-detail-grid">

            <div class="consultant-detail">
                <span>ID Konsultasi</span>

                <strong>
                    #<?= esc((string) $id) ?>
                </strong>
            </div>

            <div class="consultant-detail">
                <span>Jenis Sesi</span>

                <strong>
                    <?= esc($sessionType) ?>
                </strong>
            </div>

            <div class="consultant-detail">
                <span>Tanggal Konsultasi</span>

                <strong>
                    <?= esc($formatDate($consultationDate)) ?>
                </strong>
            </div>

            <div class="consultant-detail">
                <span>Status Konsultasi</span>

                <div>
                    <span
                        class="consultant-status <?= esc($statusClass, 'attr') ?>"
                    >
                        <?= esc($status) ?>
                    </span>
                </div>
            </div>

            <div class="consultant-detail">
                <span>Waktu Mulai</span>

                <strong>
                    <?= esc($formatTime($startTime)) ?>
                </strong>
            </div>

            <div class="consultant-detail">
                <span>Waktu Selesai</span>

                <strong>
                    <?= esc($formatTime($endTime)) ?>
                </strong>
            </div>

        </div>

    </div>

    <!-- INFORMASI PENCATATAN -->
    <div class="consultant-detail-section">

        <h3>Informasi Pencatatan</h3>

        <div class="consultant-detail-grid">

            <div class="consultant-detail">
                <span>Tanggal Dibuat</span>

                <strong>
                    <?= esc($createdAt) ?>
                </strong>
            </div>

            <div class="consultant-detail">
                <span>Terakhir Diperbarui</span>

                <strong>
                    <?= esc($updatedAt) ?>
                </strong>
            </div>

        </div>

    </div>

</div>

<!-- ========================================
     INFORMASI HAK AKSES ADMIN
======================================== -->

<div class="management-card consultant-page-card mt-4">

    <div class="consultant-form-header">

        <h2>Informasi Pemantauan</h2>

        <p>
            Ketentuan akses Administrator terhadap
            data konsultasi.
        </p>

    </div>

    <div class="consultant-note">

        <strong>Privasi Konsultasi</strong>

        <p>
            Administrator hanya dapat memantau
            informasi administratif konsultasi,
            seperti peserta, jenis sesi, jadwal,
            dan status konsultasi.
        </p>

        <p>
            Isi percakapan, catatan konseling,
            dan informasi pribadi yang bersifat
            rahasia tidak ditampilkan pada halaman ini.
        </p>

    </div>

</div>

<?= $this->endSection() ?>
