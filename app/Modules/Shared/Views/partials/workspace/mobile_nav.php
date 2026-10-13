<nav class="mobile-nav" aria-label="Navigasi mobile">
    <?php foreach ($mobileItems as $item): ?>
        <a href="<?= esc($item['url'], 'attr') ?>" <?= !empty($item['active']) ? 'class="active" aria-current="page"' : '' ?>>
            <span class="nav-icon"><?= esc($item['icon']) ?></span>
            <span><?= esc($item['label']) ?></span>
        </a>
    <?php endforeach; ?>
</nav>
