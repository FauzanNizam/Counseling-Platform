<?php

// ==========================================
// MENENTUKAN HALAMAN AKTIF
// ==========================================

$isDashboard = url_is('admin') || url_is('admin/');

$isUsers = url_is('admin/users')
    || url_is('admin/users/*');

$isConsultants = url_is('admin/consultants')
    || url_is('admin/consultants/*');

$isConsultations = url_is('admin/consultations')
    || url_is('admin/consultations/*');

$isReports = url_is('admin/reports')
    || url_is('admin/reports/*');


// ==========================================
// MENU SIDEBAR ADMIN
// ==========================================

$menuItems = [
    [
        'label' => 'Dashboard',
        'icon' => 'bi bi-grid-1x2-fill',
        'url' => site_url('admin'),
        'active' => $isDashboard,
    ],
    [
        'label' => 'Manajemen Pengguna',
        'icon' => 'bi bi-people-fill',
        'url' => site_url('admin/users'),
        'active' => $isUsers,
    ],
    [
        'label' => 'Data Psikolog',
        'icon' => 'bi bi-person-badge-fill',
        'url' => site_url('admin/consultants'),
        'active' => $isConsultants,
    ],
    [
        'label' => 'Konsultasi',
        'icon' => 'bi bi-chat-dots-fill',
        'url' => site_url('admin/consultations'),
        'active' => $isConsultations,
    ],
    [
        'label' => 'Laporan',
        'icon' => 'bi bi-file-earmark-bar-graph-fill',
        'url' => site_url('admin/reports'),
        'active' => $isReports,
    ],
];


// ==========================================
// MENU NAVIGASI MOBILE
// ==========================================

$mobileItems = [
    $menuItems[0],
    [
        'label' => 'Pengguna',
        'icon' => 'bi bi-people-fill',
        'url' => site_url('admin/users'),
        'active' => $isUsers,
    ],
    [
        'label' => 'Psikolog',
        'icon' => 'bi bi-person-badge-fill',
        'url' => site_url('admin/consultants'),
        'active' => $isConsultants,
    ],
    $menuItems[3],
    $menuItems[4],
];


// ==========================================
// DATA UNTUK KOMPONEN LAYOUT BERSAMA
// ==========================================

$shared = [
    'workspaceCaption' => 'Administrator Workspace',
    'navLabel' => 'Navigasi admin',
    'menuItems' => $menuItems,
    'mobileItems' => $mobileItems,
    'topbarTitle' => 'Administrator Panel',
    'fallbackName' => 'Admin',
    'roleLabel' => 'Administrator',
];

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= esc($title ?? 'Admin - Counseling Platform') ?>
    </title>

    <!-- BOOTSTRAP CSS LOKAL -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">

    <!-- BOOTSTRAP ICONS LOKAL -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap-icons/bootstrap-icons.css') ?>">

    <!-- CSS UTAMA -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- CSS DASHBOARD -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">

    <!-- CSS MANAJEMEN PENGGUNA -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-users.css') ?>">

    <!-- CSS FORM PENGGUNA -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-users-form.css') ?>">

    <!-- CSS POPUP GLOBAL -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app-alerts.css') ?>">

    <!-- CSS DETAIL PENGGUNA -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-users-detail.css') ?>">

    <!-- CSS MANAJEMEN KONSULTAN -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-consultants.css') ?>">


    <link rel="stylesheet" href="<?= base_url('assets/css/admin-reports.css') ?>">


</head>

<body>

    <!-- SIDEBAR ADMIN -->
    <?= view(
        'Modules\Shared\Views\partials\workspace\sidebar',
        $shared
    ) ?>


    <!-- OVERLAY NAVIGASI MOBILE -->
    <div class="nav-backdrop" id="navBackdrop"></div>


    <!-- KONTEN UTAMA -->
    <main class="main">

        <!-- TOPBAR -->
        <?= view(
            'Modules\Shared\Views\partials\workspace\topbar',
            $shared
        ) ?>


        <!-- KONTEN HALAMAN -->
        <section class="content">

            <?php
            $accessError = session()->getFlashdata(
                'access_error'
            );
            ?>

            <?php if ($accessError): ?>

                <div id="appFlashAlert" data-type="warning" data-message="<?= esc($accessError, 'attr') ?>" hidden></div>

            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </section>


        <!-- FOOTER -->
        <?= view(
            'Modules\Shared\Views\partials\workspace\footer'
        ) ?>

    </main>


    <!-- NAVIGASI MOBILE -->
    <?= view(
        'Modules\Shared\Views\partials\workspace\mobile_nav',
        $shared
    ) ?>


    <!-- MODAL POPUP GLOBAL -->
    <?= view(
        'Modules\Shared\Views\partials\workspace\alert_modal'
    ) ?>


    <!-- BOOTSTRAP JAVASCRIPT -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- JAVASCRIPT POPUP GLOBAL -->
    <script src="<?= base_url('assets/js/app-alert.js') ?>"></script>

    <!-- JAVASCRIPT LAYOUT DASHBOARD -->
    <script src="<?= base_url('assets/js/dashboard-layout.js') ?>" defer></script>

    <!-- JAVASCRIPT MANAJEMEN KONSULTAN -->
    <script src="<?= base_url('assets/js/admin-consultants.js') ?>" defer></script>

</body>

</html>