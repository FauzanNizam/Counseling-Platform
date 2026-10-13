<?php
$menuItems = [
    ['label' => 'Dashboard', 'icon' => '⌂', 'url' => site_url('psychologist'), 'active' => true],
    ['label' => 'Jadwal Konsultasi', 'icon' => '▣', 'url' => '#'],
    ['label' => 'Klien', 'icon' => '♧', 'url' => '#'],
    ['label' => 'Pesan', 'icon' => '✉', 'url' => '#'],
    ['label' => 'Profil', 'icon' => '○', 'url' => '#'],
];
$mobileItems = [
    $menuItems[0],
    ['label' => 'Jadwal', 'icon' => '▣', 'url' => '#'],
    $menuItems[3],
    $menuItems[4],
];
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
    <title><?= esc($title ?? 'Dashboard Psikolog - Counseling Platform') ?></title>
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
