
<?php
/** @var string $workspaceCaption */
/** @var string $navLabel */
/** @var array $menuItems */
?>

<aside class="sidebar" id="sidebar">

    <!-- BRAND -->
    <div class="brand">
        <div class="brand-name">Counseling</div>

        <div class="brand-caption">
            <?= esc($workspaceCaption) ?>
        </div>
    </div>

    <!-- NAVIGASI SIDEBAR -->
    <nav aria-label="<?= esc($navLabel, 'attr') ?>">

        <ul class="menu">

            <?php foreach ($menuItems as $item): ?>

                <li>
                    <a
                        href="<?= esc($item['url'], 'attr') ?>"
                        <?= !empty($item['active'])
                            ? 'class="active" aria-current="page"'
                            : '' ?>
                    >

                        <!-- BOOTSTRAP ICON -->
                        <span class="menu-icon">
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
                </li>

            <?php endforeach; ?>

        </ul>

    </nav>

    <!-- LOGOUT -->
    <div class="sidebar-bottom">

        <form
            action="<?= site_url('logout') ?>"
            method="post"
        >

            <?= csrf_field() ?>

            <button
                type="submit"
                class="logout-button"
            >

                <span class="menu-icon">
                    <i
                        class="bi bi-box-arrow-right"
                        aria-hidden="true"
                    ></i>
                </span>

                <span>Keluar</span>

            </button>

        </form>

    </div>

</aside>
