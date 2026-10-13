<?php
$menuItems = [
    ['label' => 'Dashboard', 'icon' => '⌂', 'url' => site_url('admin'), 'active' => true],
    ['label' => 'Manajemen Pengguna', 'icon' => '♧', 'url' => '#'],
    ['label' => 'Data Psikolog', 'icon' => '✚', 'url' => '#'],
    ['label' => 'Konsultasi', 'icon' => '▣', 'url' => '#'],
    ['label' => 'Laporan', 'icon' => '▤', 'url' => '#'],
];
$mobileItems = [
    $menuItems[0],
    ['label' => 'Pengguna', 'icon' => '♧', 'url' => '#'],
    $menuItems[3],
    $menuItems[4],
];
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
    <title><?= esc($title ?? 'Admin - Counseling Platform') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
    <?= view('Modules\Shared\Views\partials\workspace\sidebar', $shared) ?>
    <div class="nav-backdrop" id="navBackdrop"></div>
    <main class="main">
        <?= view('Modules\Shared\Views\partials\workspace\topbar', $shared) ?>
        <section class="content">
            <?php $accessError = session()->getFlashdata('access_error'); ?>
            <?php if ($accessError): ?>
                <div class="alert alert-warning" role="alert"><?= esc($accessError) ?></div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </section>
        <?= view('Modules\Shared\Views\partials\workspace\footer') ?>
    </main>
    <?= view('Modules\Shared\Views\partials\workspace\mobile_nav', $shared) ?>
    <script src="<?= base_url('assets/js/dashboard-layout.js') ?>" defer></script>
</body>
</html>
