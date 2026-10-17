
<?php
/** @var array $mobileItems */
?>

<!-- ==========================================
     MOBILE NAVIGATION - BOOTSTRAP ICONS
========================================== -->

<nav class="mobile-nav" aria-label="Navigasi mobile">

    <?php foreach ($mobileItems as $item): ?>

        <a
            href="<?= esc($item['url'], 'attr') ?>"
            <?= !empty($item['active'])
                ? 'class="active" aria-current="page"'
                : '' ?>
        >

            <!-- BOOTSTRAP ICON -->
            <span class="nav-icon">
                <i
                    class="<?= esc($item['icon'], 'attr') ?>"
                    aria-hidden="true"
                ></i>
            </span>

            <!-- NAMA MENU -->
            <span>
                <?= esc($item['label']) ?>
            </span>

        </a>

    <?php endforeach; ?>

</nav>
