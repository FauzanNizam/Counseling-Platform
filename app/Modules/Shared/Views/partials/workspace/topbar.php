<?php
$name = session()->get('user_name') ?: $fallbackName;
$initial = mb_strtoupper(mb_substr($name, 0, 1));
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