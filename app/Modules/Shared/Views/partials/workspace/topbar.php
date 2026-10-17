
<?php
$fallbackName = 'Pengguna';

$sessionName = session()->get('user_name');
$name = is_string($sessionName) && trim($sessionName) !== ''
    ? trim($sessionName)
    : $fallbackName;

$role = session()->get('role');

$roleLabel = match ($role) {
    'admin' => 'Administrator',
    'client' => 'Klien',
    'psychologist' => 'Psikolog',
    default => 'Pengguna',
};

$initial = mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8');
$topbarTitle = $topbarTitle ?? 'Dashboard';
?>

<header class="topbar">
    <div class="topbar-left">
        <button class="mobile-menu-button" id="menuButton" type="button" aria-label="Buka menu" aria-controls="sidebar"
            aria-expanded="false">☰</button>
        <div class="topbar-title"><?= esc($topbarTitle) ?></div>
    </div>
    <div class="profile">
        <div class="profile-info">
            <div class="profile-name"><?= esc($name) ?></div>
            <div class="profile-role"><?= esc($roleLabel) ?></div>
        </div>
        <div class="avatar" aria-label="<?= esc('Avatar ' . $name, 'attr') ?>"><?= esc($initial) ?></div>
    </div>
</header>