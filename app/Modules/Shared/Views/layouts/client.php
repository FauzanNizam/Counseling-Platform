
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title ?? 'Counseling Platform') ?></title>

    <!-- BOOTSTRAP ICONS OFFLINE -->
    <link
        rel="stylesheet"
        href="<?= base_url('assets/bootstrap-icons/bootstrap-icons.css') ?>"
    >

    <style>
        :root {
            --purple-900: #3b1678;
            --purple-800: #4c1d95;
            --purple-700: #5b21b6;
            --purple-600: #6d28d9;
            --purple-100: #ede0ff;
            --purple-50: #f5f0ff;
            --text: #292436;
            --muted: #817b8d;
            --border: #eeeaf3;
            --surface: #ffffff;
            --background: #f7f5fb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: var(--text);
            background: var(--background);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
        }

        /* ==========================================
           DESKTOP SIDEBAR
        ========================================== */

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 30;
            width: 240px;
            padding: 26px 16px;
            display: flex;
            flex-direction: column;
            color: white;
            background: var(--purple-900);
        }

        .brand {
            padding: 0 12px;
            margin-bottom: 38px;
        }

        .brand-name {
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand-caption {
            margin-top: 6px;
            color: #d8bfff;
            font-size: 11px;
        }

        .menu {
            display: grid;
            gap: 7px;
            list-style: none;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 46px;
            padding: 12px;
            border-radius: 10px;
            color: #e9ddff;
            font-size: 14px;
            transition: background 0.2s, color 0.2s;
        }

        .menu a:hover,
        .menu a.active {
            color: white;
            background: var(--purple-700);
        }

        .menu a.active {
            font-weight: 600;
        }

        /* BOOTSTRAP ICONS SIDEBAR */

        .menu-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            min-width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .menu-icon .bi {
            font-size: 19px;
            line-height: 1;
            color: inherit;
        }

        /* ==========================================
           LOGOUT
        ========================================== */

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid #ffffff24;
        }

        .sidebar-bottom form {
            margin: 0;
        }

        .sidebar-bottom .logout-button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 46px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: transparent;
            color: #e9ddff;
            font: inherit;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
        }

        .sidebar-bottom .logout-button:hover {
            color: white;
            background: var(--purple-700);
        }

        /* ==========================================
           MAIN AND TOPBAR
        ========================================== */

        .main {
            min-width: 0;
            min-height: 100vh;
            margin-left: 240px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
            padding: 0 30px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--purple-900);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .profile-info {
            text-align: right;
        }

        .profile-name {
            font-size: 13px;
            font-weight: 700;
        }

        .profile-role {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .avatar {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 39px;
            height: 39px;
            border-radius: 50%;
            background: var(--purple-100);
            color: var(--purple-700);
            font-weight: 700;
        }

        /* ==========================================
           CONTENT
        ========================================== */

        .content {
            width: 100%;
            max-width: 1440px;
            padding: 30px;
            margin: 0 auto;
        }

        .alert {
            padding: 14px 16px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 14px;
        }

        .alert-warning {
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
        }

        /* ==========================================
           MOBILE CONTROLS
        ========================================== */

        .mobile-menu-button,
        .mobile-nav,
        .nav-backdrop {
            display: none;
        }

        /* ==========================================
           RESPONSIVE
        ========================================== */

        @media (max-width: 768px) {

            .sidebar {
                width: min(280px, 82vw);
                padding: 24px 16px;
                transform: translateX(-100%);
                visibility: hidden;
                transition:
                    transform 0.25s ease,
                    visibility 0.25s ease;
                box-shadow: 12px 0 35px #2013331a;
            }

            body.menu-open .sidebar {
                transform: translateX(0);
                visibility: visible;
            }

            .brand {
                margin-bottom: 28px;
            }

            .main {
                margin-left: 0;
                padding-bottom: 76px;
            }

            .topbar {
                height: 62px;
                padding: 0 16px;
                gap: 12px;
            }

            .topbar-title {
                overflow: hidden;
                font-size: 14px;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .mobile-menu-button {
                display: grid;
                place-items: center;
                flex-shrink: 0;
                width: 38px;
                height: 38px;
                border: 1px solid var(--border);
                border-radius: 10px;
                background: white;
                color: var(--purple-900);
                font-size: 22px;
                cursor: pointer;
            }

            .mobile-menu-button .bi {
                font-size: 22px;
                line-height: 1;
            }

            .profile-info {
                display: none;
            }

            .avatar {
                width: 35px;
                height: 35px;
            }

            .content {
                padding: 22px 16px;
            }

            .nav-backdrop {
                position: fixed;
                inset: 0;
                z-index: 25;
                background: #170b2c70;
            }

            body.menu-open .nav-backdrop {
                display: block;
            }

            /* MOBILE BOTTOM NAVIGATION */

            .mobile-nav {
                position: fixed;
                inset: auto 0 0;
                z-index: 20;
                display: grid;
                grid-template-columns: repeat(5, minmax(0, 1fr));
                padding: 8px 4px max(8px, env(safe-area-inset-bottom));
                background: white;
                border-top: 1px solid var(--border);
            }

            .mobile-nav a {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                min-width: 0;
                padding: 5px 0;
                color: var(--muted);
                font-size: 10px;
                text-align: center;
            }

            .mobile-nav .nav-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
            }

            .mobile-nav .nav-icon .bi {
                font-size: 21px;
                line-height: 1;
                color: inherit;
            }

            .mobile-nav a.active {
                color: var(--purple-700);
                font-weight: 700;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>

    <?php

    $name = session()->get('user_name') ?: 'Client';
    $initial = mb_strtoupper(mb_substr($name, 0, 1));

    $currentPath = trim(uri_string(), '/');

    $isActive = static function (string $path) use ($currentPath): bool {
        return $currentPath === $path
            || str_starts_with($currentPath, $path . '/');
    };

    $isHome = $currentPath === 'client';

    $isAssessment = $isActive('client/assessment');
    $isPsychologist = $isActive('client/konsultan');
    $isSchedule = $isActive('client/jadwal');
    $isProfile = $isActive('client/profile');

    ?>

    <!-- ==========================================
         SIDEBAR CLIENT
    ========================================== -->

    <aside class="sidebar" id="sidebar">

        <div class="brand">
            <div class="brand-name">Counseling</div>
            <div class="brand-caption">Mental Health Platform</div>
        </div>

        <nav aria-label="Navigasi utama">

            <ul class="menu">

                <!-- BERANDA -->
                <li>
                    <a
                        href="<?= site_url('client') ?>"
                        class="<?= $isHome ? 'active' : '' ?>"
                        <?= $isHome ? 'aria-current="page"' : '' ?>
                    >
                        <span class="menu-icon">
                            <i class="bi bi-house-door-fill" aria-hidden="true"></i>
                        </span>
                        <span>Beranda</span>
                    </a>
                </li>

                <!-- SELF ASSESSMENT -->
                <li>
                    <a
                        href="<?= site_url('client/assessment') ?>"
                        class="<?= $isAssessment ? 'active' : '' ?>"
                        <?= $isAssessment ? 'aria-current="page"' : '' ?>
                    >
                        <span class="menu-icon">
                            <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                        </span>
                        <span>Self-Assessment</span>
                    </a>
                </li>

                <!-- CARI PSIKOLOG -->
                <li>
                    <a
                        href="<?= site_url('client/konsultan') ?>"
                        class="<?= $isPsychologist ? 'active' : '' ?>"
                        <?= $isPsychologist ? 'aria-current="page"' : '' ?>
                    >
                        <span class="menu-icon">
                            <i class="bi bi-person-heart" aria-hidden="true"></i>
                        </span>
                        <span>Cari Psikolog</span>
                    </a>
                </li>

                <!-- JADWAL SAYA -->
                <li>
                    <a
                        href="<?= site_url('client/jadwal') ?>"
                        class="<?= $isSchedule ? 'active' : '' ?>"
                        <?= $isSchedule ? 'aria-current="page"' : '' ?>
                    >
                        <span class="menu-icon">
                            <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                        </span>
                        <span>Jadwal Saya</span>
                    </a>
                </li>

                <!-- PROFIL -->
                <li>
                    <a
                        href="<?= site_url('client/profile') ?>"
                        class="<?= $isProfile ? 'active' : '' ?>"
                        <?= $isProfile ? 'aria-current="page"' : '' ?>
                    >
                        <span class="menu-icon">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                        </span>
                        <span>Profil</span>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- LOGOUT -->
        <div class="sidebar-bottom">

            <form action="<?= site_url('logout') ?>" method="post">
                <?= csrf_field() ?>

                <button type="submit" class="logout-button">
                    <span class="menu-icon">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    </span>
                    <span>Keluar</span>
                </button>

            </form>
        </div>

    </aside>

    <!-- MOBILE SIDEBAR BACKDROP -->
    <div class="nav-backdrop" id="navBackdrop"></div>

    <!-- ==========================================
         MAIN CONTENT
    ========================================== -->

    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="topbar-left">

                <button
                    class="mobile-menu-button"
                    id="menuButton"
                    type="button"
                    aria-label="Buka menu"
                    aria-controls="sidebar"
                    aria-expanded="false"
                >
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>

                <div class="topbar-title">
                    Counseling Platform
                </div>

            </div>

            <!-- PROFIL PENGGUNA -->
            <a
                href="<?= site_url('client/profile') ?>"
                class="profile"
                title="Buka Profil Saya"
                aria-label="Buka profil <?= esc($name, 'attr') ?>"
            >

                <div class="profile-info">
                    <div class="profile-name">
                        <?= esc($name) ?>
                    </div>

                    <div class="profile-role">
                        Client
                    </div>
                </div>

                <div class="avatar" aria-hidden="true">
                    <?= esc($initial) ?>
                </div>

            </a>

        </header>

        <!-- CONTENT -->
        <section class="content">

            <?php
            $accessError = session()->getFlashdata('access_error');
            ?>

            <?php if ($accessError): ?>

                <div class="alert alert-warning" role="alert">
                    <?= esc($accessError) ?>
                </div>

            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </section>

    </main>

    <!-- ==========================================
         RESPONSIVE BOTTOM NAVIGATION
    ========================================== -->

    <nav class="mobile-nav" aria-label="Navigasi layar kecil">

        <!-- BERANDA -->
        <a
            href="<?= site_url('client') ?>"
            class="<?= $isHome ? 'active' : '' ?>"
            <?= $isHome ? 'aria-current="page"' : '' ?>
        >
            <span class="nav-icon">
                <i class="bi bi-house-door-fill" aria-hidden="true"></i>
            </span>
            <span>Beranda</span>
        </a>

        <!-- SELF ASSESSMENT -->
        <a
            href="<?= site_url('client/assessment') ?>"
            class="<?= $isAssessment ? 'active' : '' ?>"
            <?= $isAssessment ? 'aria-current="page"' : '' ?>
        >
            <span class="nav-icon">
                <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
            </span>
            <span>Assessment</span>
        </a>

        <!-- JADWAL -->
        <a
            href="<?= site_url('client/jadwal') ?>"
            class="<?= $isSchedule ? 'active' : '' ?>"
            <?= $isSchedule ? 'aria-current="page"' : '' ?>
        >
            <span class="nav-icon">
                <i class="bi bi-calendar2-check" aria-hidden="true"></i>
            </span>
            <span>Jadwal</span>
        </a>

        <!-- PSIKOLOG -->
        <a
            href="<?= site_url('client/konsultan') ?>"
            class="<?= $isPsychologist ? 'active' : '' ?>"
            <?= $isPsychologist ? 'aria-current="page"' : '' ?>
        >
            <span class="nav-icon">
                <i class="bi bi-person-heart" aria-hidden="true"></i>
            </span>
            <span>Psikolog</span>
        </a>

        <!-- PROFIL -->
        <a
            href="<?= site_url('client/profile') ?>"
            class="<?= $isProfile ? 'active' : '' ?>"
            <?= $isProfile ? 'aria-current="page"' : '' ?>
        >
            <span class="nav-icon">
                <i class="bi bi-person-circle" aria-hidden="true"></i>
            </span>
            <span>Profil</span>
        </a>

    </nav>

    <!-- ==========================================
         JAVASCRIPT RESPONSIVE SIDEBAR
    ========================================== -->

    <script>
        const menuButton = document.getElementById('menuButton');
        const backdrop = document.getElementById('navBackdrop');
        const sidebarLinks = document.querySelectorAll('#sidebar a');

        function closeMenu() {
            document.body.classList.remove('menu-open');

            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.setAttribute('aria-label', 'Buka menu');
        }

        menuButton.addEventListener('click', () => {
            const isOpen = document.body.classList.toggle('menu-open');

            menuButton.setAttribute('aria-expanded', String(isOpen));
            menuButton.setAttribute(
                'aria-label',
                isOpen ? 'Tutup menu' : 'Buka menu'
            );
        });

        backdrop.addEventListener('click', closeMenu);

        sidebarLinks.forEach((link) => {
            link.addEventListener('click', closeMenu);
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                closeMenu();
            }
        });
    </script>

</body>
</html>
