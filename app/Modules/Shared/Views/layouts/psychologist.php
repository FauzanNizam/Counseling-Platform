
<?php

$currentPath = trim(uri_string(), '/');

$isActive = static function (string $path) use ($currentPath): bool {
    return $currentPath === $path
        || str_starts_with($currentPath, $path . '/');
};

/* ==========================================
   MENU SIDEBAR PSIKOLOG
========================================== */

$menuItems = [
    [
        'label' => 'Dashboard',
        'icon' => 'bi bi-grid-1x2-fill',
        'url' => site_url('psychologist'),
        'active' => $currentPath === 'psychologist',
    ],
    [
        'label' => 'Jadwal Konsultasi',
        'icon' => 'bi bi-calendar2-check',
        'url' => site_url('psychologist/consultations'),
        'active' => $isActive('psychologist/consultations'),
    ],
    [
        'label' => 'Jadwal Praktik',
        'icon' => 'bi bi-calendar-week',
        'url' => site_url('psychologist/practice-schedule'),
        'active' => $isActive('psychologist/practice-schedule'),
    ],
    [
        'label' => 'Klien',
        'icon' => 'bi bi-people',
        'url' => site_url('psychologist/clients'),
        'active' => $isActive('psychologist/clients'),
    ],
    [
        'label' => 'Profil',
        'icon' => 'bi bi-person-circle',
        'url' => site_url('psychologist/profile'),
        'active' => $isActive('psychologist/profile'),
    ],
];

/* ==========================================
   MOBILE NAVIGATION
========================================== */

$mobileItems = [
    [
        'label' => 'Dashboard',
        'icon' => 'bi bi-grid-1x2-fill',
        'url' => site_url('psychologist'),
        'active' => $currentPath === 'psychologist',
    ],
    [
        'label' => 'Konsultasi',
        'icon' => 'bi bi-calendar2-check',
        'url' => site_url('psychologist/consultations'),
        'active' => $isActive('psychologist/consultations'),
    ],
    [
        'label' => 'Praktik',
        'icon' => 'bi bi-calendar-week',
        'url' => site_url('psychologist/practice-schedule'),
        'active' => $isActive('psychologist/practice-schedule'),
    ],
    [
        'label' => 'Klien',
        'icon' => 'bi bi-people',
        'url' => site_url('psychologist/clients'),
        'active' => $isActive('psychologist/clients'),
    ],
    [
        'label' => 'Profil',
        'icon' => 'bi bi-person-circle',
        'url' => site_url('psychologist/profile'),
        'active' => $isActive('psychologist/profile'),
    ],
];

/* ==========================================
   SHARED LAYOUT DATA
========================================== */

$shared = [
    'workspaceCaption' => 'Psychologist Workspace',
    'navLabel' => 'Navigasi psikolog',
    'menuItems' => $menuItems,
    'mobileItems' => $mobileItems,
    'topbarTitle' => 'Ruang Kerja Psikolog',
    'fallbackName' => 'Psikolog',
    'roleLabel' => 'Psikolog',
];

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= esc($title ?? 'Dashboard Psikolog - Counseling Platform') ?>
    </title>

    <!-- CSS UTAMA -->
    <link
        rel="stylesheet"
        href="<?= base_url('assets/css/style.css') ?>"
    >

    <!-- CSS SHARED DASHBOARD -->
    <link
        rel="stylesheet"
        href="<?= base_url('assets/css/dashboard.css') ?>"
    >

    <!-- CSS KHUSUS PSIKOLOG -->
    <link
        rel="stylesheet"
        href="<?= base_url('assets/css/psychologist.css') ?>"
    >

    <!-- BOOTSTRAP ICONS OFFLINE -->
    <link
        rel="stylesheet"
        href="<?= base_url('assets/bootstrap-icons/bootstrap-icons.css') ?>"
    >

</head>

<body>

    <!-- ==========================================
         SHARED SIDEBAR
    ========================================== -->

    <?= view(
        'Modules\Shared\Views\partials\workspace\sidebar',
        $shared
    ) ?>

    <!-- MOBILE SIDEBAR BACKDROP -->
    <div
        class="nav-backdrop"
        id="navBackdrop"
    ></div>

    <!-- ==========================================
         MAIN CONTENT
    ========================================== -->

    <main class="main">

        <!-- SHARED TOPBAR -->
        <?= view(
            'Modules\Shared\Views\partials\workspace\topbar',
            $shared
        ) ?>

        <section class="content">

            <?php
            $accessError = session()->getFlashdata('access_error');
            ?>

            <?php if ($accessError): ?>

                <div
                    class="alert alert-warning"
                    role="alert"
                >
                    <?= esc($accessError) ?>
                </div>

            <?php endif; ?>

            <!-- HALAMAN PSIKOLOG -->
            <?= $this->renderSection('content') ?>

        </section>

        <!-- SHARED FOOTER -->
        <?= view(
            'Modules\Shared\Views\partials\workspace\footer'
        ) ?>

    </main>

    <!-- ==========================================
         SHARED MOBILE NAVIGATION
    ========================================== -->

    <?= view(
        'Modules\Shared\Views\partials\workspace\mobile_nav',
        $shared
    ) ?>

    <!-- ==========================================
         JAVASCRIPT
    ========================================== -->

    <!-- SHARED DASHBOARD -->
    <script
        src="<?= base_url('assets/js/dashboard-layout.js') ?>"
        defer
    ></script>

    <!-- JADWAL KONSULTASI -->
    <script
        src="<?= base_url('assets/js/psychologist-consultations.js') ?>"
        defer
    ></script>

    <!-- MULAI / SELESAIKAN SESI -->
    <script
        src="<?= base_url('assets/js/psychologist-session.js') ?>"
        defer
    ></script>

    <!-- KELOLA JADWAL PRAKTIK -->
    <script
        src="<?= base_url('assets/js/psychologist-practice.js') ?>"
        defer
    ></script>

    <!-- LINK GOOGLE MEET / ZOOM -->
    <script
        src="<?= base_url('assets/js/psychologist-meeting.js') ?>"
        defer
    ></script>

</body>
</html>
