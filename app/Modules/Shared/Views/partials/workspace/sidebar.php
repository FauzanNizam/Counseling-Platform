<?php
/** @var string $workspaceCaption */
/** @var string $navLabel */
/** @var array $menuItems */
?>
<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-name">Counseling</div>
        <div class="brand-caption"><?= esc($workspaceCaption) ?></div>
    </div>
    <nav aria-label="<?= esc($navLabel, 'attr') ?>">
        <ul class="menu">
            <?php foreach ($menuItems as $item): ?>
                <li>
                    <a href="<?= esc($item['url'], 'attr') ?>" <?= !empty($item['active']) ? 'class="active" aria-current="page"' : '' ?>>
                        <span class="menu-icon"><?= esc($item['icon']) ?></span>
                        <span><?= esc($item['label']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="sidebar-bottom">
        <form action="<?= site_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="logout-button">
                <span class="menu-icon">↪</span><span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
