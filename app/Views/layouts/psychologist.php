
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard Psikolog - Counseling Platform') ?></title>

    <style>
        :root {
            --purple-900: #3b1678;
            --purple-700: #5b21b6;
            --purple-600: #6d28d9;
            --purple-100: #ede0ff;
            --background: #f7f5fb;
            --text: #292436;
            --muted: #817b8d;
            --border: #eeeaf3;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
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

        .menu a,
        .logout-button {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 46px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            color: #e9ddff;
            background: transparent;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
        }

        .menu a:hover,
        .menu a.active,
        .logout-button:hover {
            color: white;
            background: var(--purple-700);
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

        .sidebar-bottom form {
            margin: 0;
        }

        .logout-button {
            width: 100%;
        }

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
            background: white;
            border-bottom: 1px solid var(--border);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
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

        .mobile-menu-button,
        .nav-backdrop,
        .mobile-nav {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: min(280px, 82vw);
                padding: 24px 16px;
                transform: translateX(-100%);
                visibility: hidden;
                transition: transform 0.25s ease,
                            visibility 0.25s ease;
            }

            body.menu-open .sidebar {
                transform: translateX(0);
                visibility: visible;
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

            .mobile-menu-button {
                display: grid;
                place-items: center;
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
                grid-template-columns: repeat(4, minmax(0, 1fr));
                padding: 8px 4px max(8px, env(safe-area-inset-bottom));
                background: white;
                border-top: 1px solid var(--border);
            }

            .mobile-nav a {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                padding: 5px 0;
                color: var(--muted);
                font-size: 10px;
            }

            .mobile-nav a.active {
                color: var(--purple-700);
                font-weight: 700;
            }

            .nav-icon {
                font-size: 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition: none !important;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-name">Counseling</div>
            <div class="brand-caption">Psychologist Workspace</div>
        </div>

        <nav aria-label="Navigasi psikolog">
            <ul class="menu">
                <li>
                    <a href="<?= site_url('psychologist') ?>" class="active">
                        <span class="menu-icon">⌂</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon">▣</span>
                        <span>Jadwal Konsultasi</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="menu-icon">♧</span>
                        <span>Klien</span>
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

                <div class="topbar-title">Ruang Kerja Psikolog</div>
            </div>

            <?php
                $name = session()->get('user_name') ?? 'Psikolog';
                $initial = strtoupper(substr($name, 0, 1));
            ?>

            <div class="profile">
                <div class="profile-info">
                    <div class="profile-name"><?= esc($name) ?></div>
                    <div class="profile-role">Psikolog</div>
                </div>

                <div class="avatar"><?= esc($initial) ?></div>
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
        <a href="<?= site_url('psychologist') ?>" class="active">
            <span class="nav-icon">⌂</span>
            <span>Dashboard</span>
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
