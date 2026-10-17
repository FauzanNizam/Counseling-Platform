
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
        'icon' => '⌂',
        'url' => site_url('psychologist'),
        'active' => $currentPath === 'psychologist',
    ],
    [
        'label' => 'Jadwal Konsultasi',
        'icon' => '▣',
        'url' => site_url('psychologist/consultations'),
        'active' => $isActive('psychologist/consultations'),
    ],
    [
        'label' => 'Jadwal Praktik',
        'icon' => '▦',
        'url' => site_url('psychologist/practice-schedule'),
        'active' => $isActive('psychologist/practice-schedule'),
    ],
    [
        'label' => 'Klien',
        'icon' => '♧',
        'url' => '#',
        'active' => $isActive('psychologist/clients'),
    ],
    [
        'label' => 'Pesan',
        'icon' => '✉',
        'url' => '#',
        'active' => $isActive('psychologist/messages'),
    ],
    [
        'label' => 'Profil',
        'icon' => '○',
        'url' => site_url('psychologist/profile'),
        'active' => $isActive('psychologist/profile'),
    ],
];

/* ==========================================
   MOBILE NAVIGATION
========================================== */

$mobileItems = [
    $menuItems[0],
    [
        'label' => 'Konsultasi',
        'icon' => '▣',
        'url' => $menuItems[1]['url'],
        'active' => $menuItems[1]['active'],
    ],
    [
        'label' => 'Praktik',
        'icon' => '▦',
        'url' => $menuItems[2]['url'],
        'active' => $menuItems[2]['active'],
    ],
    $menuItems[4],
    $menuItems[5],
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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <div class="nav-backdrop" id="navBackdrop"></div>


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

                <div class="alert alert-warning" role="alert">
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

    <script
    src="<?= base_url('assets/js/psychologist-meeting.js') ?>"
    defer
></script>

</body>

</html>
