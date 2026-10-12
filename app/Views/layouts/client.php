<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title ?? 'Counseling Platform') ?></title>

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

        /* DESKTOP LAYOUT */

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

        .menu-icon {
            display: inline-flex;
            width: 22px;
            justify-content: center;
            font-size: 18px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid #ffffff24;
        }

        .sidebar-bottom a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            color: #e9ddff;
            font-size: 14px;
        }

        /* MAIN AND TOPBAR */

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

        .content {
            width: 100%;
            max-width: 1440px;
            padding: 30px;
            margin: 0 auto;
        }

        .sidebar-bottom form {
            margin: 0;
        }

        .sidebar-bottom .logout-button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            background: transparent;
            color: inherit;
            font: inherit;
            text-align: left;
            cursor: pointer;
        }

        .sidebar-bottom .logout-button:hover {
            background: rgba(124, 58, 237, 0.1);
        }

        /* MOBILE-ONLY CONTROLS */

        .mobile-menu-button,
        .mobile-nav,
        .nav-backdrop {
            display: none;
        }

        /* MOBILE */

        @media (max-width: 768px) {
            .sidebar {
                width: min(280px, 82vw);
                padding: 24px 16px;
                transform: translateX(-100%);
                visibility: hidden;
                transition: transform 0.25s ease,
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

            .topbar-left {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
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
            }

            .mobile-nav .nav-icon {
                font-size: 20px;
                line-height: 1.2;
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

    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-name">Counseling</div>
            <div class="brand-caption">Mental Health Platform</div>
        </div>

        <nav aria-label="Navigasi utama">
            <ul class="menu">
                <li>
                    <a href="/client" class="active" aria-current="page">
                        <span class="menu-icon">⌂</span>
                        <span>Beranda</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon">✓</span>
                        <span>Self-Assessment</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon">♧</span>
                        <span>Cari Psikolog</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon">▣</span>
                        <span>Jadwal Saya</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon">✉</span>
                        <span>Pesan</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon">○</span>
                        <span>Profil</span>
                    </a>
                </li>
            </ul>
        </nav>


        <div class="sidebar-bottom">
            <form action="<?= site_url('logout') ?>" method="post">
                <?= csrf_field() ?>

                <button type="submit" class="logout-button">
                    <span class="menu-icon">↪</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>

    </aside>

    <div class="nav-backdrop" id="navBackdrop"></div>


    <?php if (session()->getFlashdata('access_error')): ?>
        <div class="alert alert-warning" role="alert">
            <?= esc(session()->getFlashdata('access_error')) ?>
        </div>
    <?php endif; ?>


    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button
                    class="mobile-menu-button"
                    id="menuButton"
                    type="button"
                    aria-label="Buka menu"
                    aria-controls="sidebar"
                    aria-expanded="false">☰</button>

                <div class="topbar-title">Counseling Platform</div>
            </div>

            <div class="profile">
                <div class="profile-info">
                    <div class="profile-name">Andi</div>
                    <div class="profile-role">Client</div>
                </div>
                <div class="avatar" aria-label="Avatar Andi">A</div>
            </div>
        </header>

        <section class="content">
            <?php if (session()->getFlashdata('access_error')): ?>
                <div class="alert alert-warning" role="alert">
                    <?= esc(session()->getFlashdata('access_error')) ?>
                </div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </section>
    </main>

    <nav class="mobile-nav" aria-label="Navigasi mobile">
        <a href="/client" class="active" aria-current="page">
            <span class="nav-icon">⌂</span>
            <span>Beranda</span>
        </a>
        <a href="#">
            <span class="nav-icon">✓</span>
            <span>Assessment</span>
        </a>
        <a href="#">
            <span class="nav-icon">▣</span>
            <span>Jadwal</span>
        </a>
        <a href="#">
            <span class="nav-icon">✉</span>
            <span>Pesan</span>
        </a>
        <a href="#">
            <span class="nav-icon">○</span>
            <span>Profil</span>
        </a>
    </nav>

    <script>
        const menuButton = document.getElementById('menuButton');
        const backdrop = document.getElementById('navBackdrop');
        const sidebarLinks = document.querySelectorAll(
            '#sidebar a'
        );

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