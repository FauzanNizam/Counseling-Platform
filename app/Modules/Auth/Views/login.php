
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Counseling Platform</title>

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
            --border: #e8e2f0;
            --background: #f7f5fb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: var(--text);
            background: var(--background);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            font: inherit;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* BAGIAN KIRI */

        .login-left {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: 50px 65px;
            color: white;
            background: linear-gradient(
                145deg,
                #3b1678 0%,
                #5b21b6 55%,
                #7c3aed 100%
            );
        }

        .login-left::before {
            content: "";
            position: absolute;
            width: 450px;
            height: 450px;
            right: -180px;
            top: -150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .login-left::after {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            left: -150px;
            bottom: -160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .brand,
        .welcome-content,
        .left-footer {
            position: relative;
            z-index: 1;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            display: grid;
            place-items: center;
            width: 45px;
            height: 45px;
            border-radius: 13px;
            background: rgba(255, 255, 255, 0.16);
            font-size: 24px;
        }

        .brand-name {
            font-size: 23px;
            font-weight: 700;
        }

        .welcome-content {
            max-width: 480px;
        }

        .welcome-badge {
            display: inline-block;
            margin-bottom: 25px;
            padding: 10px 17px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            font-size: 13px;
        }

        .welcome-content h1 {
            margin-bottom: 22px;
            font-size: clamp(32px, 3.5vw, 49px);
            line-height: 1.2;
        }

        .welcome-content p {
            max-width: 420px;
            color: #e4d7ff;
            font-size: 16px;
            line-height: 1.8;
        }

        .feature-list {
            display: grid;
            gap: 17px;
            margin-top: 38px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #f1eaff;
            font-size: 14px;
        }

        .feature-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.14);
        }

        .left-footer {
            color: #d6c4f8;
            font-size: 12px;
        }

        /* BAGIAN KANAN */

        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 45px 35px;
            background: white;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 470px;
        }

        .form-heading {
            margin-bottom: 30px;
        }

        .form-heading h2 {
            margin-bottom: 10px;
            color: var(--purple-900);
            font-size: 30px;
        }

        .form-heading p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .alert {
            margin-bottom: 20px;
            padding: 13px 15px;
            border-radius: 10px;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert-error {
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .alert-success {
            color: #166534;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .form-group {
            margin-bottom: 21px;
        }

        .form-label {
            display: block;
            margin-bottom: 9px;
            font-size: 13px;
            font-weight: 700;
            color: #3d3450;
        }

        .form-input {
            width: 100%;
            height: 49px;
            padding: 0 15px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: #fff;
            color: var(--text);
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            border-color: var(--purple-600);
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.1);
        }

        .form-input::placeholder {
            color: #aaa3b5;
        }

        /* PILIHAN ROLE */

        .role-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .role-option {
            position: relative;
            cursor: pointer;
        }

        .role-option input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .role-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 94px;
            padding: 14px 8px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            background: white;
            transition: 0.2s;
            text-align: center;
        }

        .role-icon {
            font-size: 25px;
        }

        .role-name {
            font-size: 12px;
            font-weight: 700;
            color: #51465f;
        }

        .role-option:hover .role-card {
            border-color: #b69be8;
            background: #faf7ff;
        }

        .role-option input:checked + .role-card {
            border-color: var(--purple-600);
            background: var(--purple-50);
            box-shadow: 0 0 0 2px rgba(109, 40, 217, 0.08);
        }

        .role-option input:checked + .role-card .role-name {
            color: var(--purple-700);
        }

        .role-option input:focus-visible + .role-card {
            outline: 3px solid #c4b5fd;
            outline-offset: 2px;
        }

        /* PASSWORD */

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-input {
            padding-right: 75px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--purple-700);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 25px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 12px;
        }

        .remember-label input {
            accent-color: var(--purple-700);
        }

        .forgot-link {
            color: var(--purple-700);
            font-size: 12px;
            font-weight: 700;
        }

        .login-button {
            width: 100%;
            height: 49px;
            border: none;
            border-radius: 11px;
            background: linear-gradient(
                110deg,
                var(--purple-900),
                var(--purple-600)
            );
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .login-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(91, 33, 182, 0.22);
        }

        .register-text {
            margin-top: 26px;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        .register-text a {
            color: var(--purple-700);
            font-weight: 700;
        }

        .security-note {
            margin-top: 20px;
            color: #9992a4;
            font-size: 11px;
            line-height: 1.6;
            text-align: center;
        }

        /* RESPONSIVE */

        @media (max-width: 1000px) {
            .login-left {
                padding: 40px;
            }

            .login-right {
                padding: 40px 25px;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-left {
                min-height: 220px;
                padding: 30px 25px;
            }

            .welcome-content {
                margin-top: 30px;
            }

            .welcome-content h1 {
                margin-bottom: 12px;
                font-size: 27px;
            }

            .welcome-content p {
                font-size: 13px;
            }

            .welcome-badge,
            .feature-list,
            .left-footer {
                display: none;
            }

            .login-right {
                padding: 35px 20px;
            }

            .form-heading h2 {
                font-size: 26px;
            }
        }

        @media (max-width: 380px) {
            .role-grid {
                gap: 6px;
            }

            .role-card {
                padding: 12px 4px;
            }

            .role-name {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <!-- BAGIAN KIRI -->
        <section class="login-left">

            <div class="brand">
                <div class="brand-logo">♡</div>
                <div class="brand-name">Counseling</div>
            </div>

            <div class="welcome-content">

                <div class="welcome-badge">
                    Mental Health & Counseling Platform
                </div>

                <h1>
                    Selamat Datang
                    Kembali!
                </h1>

                <p>
                    Ruang yang aman untuk berbagi cerita,
                    mendapatkan dukungan, dan menjaga
                    kesehatan mental bersama.
                </p>

                <div class="feature-list">

                    <div class="feature-item">
                        <div class="feature-icon">♡</div>
                        <span>Dukungan kesehatan mental</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">✦</div>
                        <span>Konsultasi bersama psikolog</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Platform yang aman dan nyaman</span>
                    </div>

                </div>

            </div>

            <div class="left-footer">
                © <?= date('Y') ?> Counseling Platform.
                All rights reserved.
            </div>

        </section>

        <!-- BAGIAN KANAN -->
        <section class="login-right">

            <div class="login-form-wrapper">

                <div class="form-heading">
                    <h2>Masuk ke Akun</h2>
                    <p>
                        Pilih peran Anda, lalu masukkan email
                        dan password untuk melanjutkan.
                    </p>
                </div>

                <!-- PESAN ERROR -->
                <?php $error = session()->getFlashdata('error'); ?>

                <?php if ($error): ?>
                    <div class="alert alert-error" role="alert">
                        <?= esc($error) ?>
                    </div>
                <?php endif; ?>

                <!-- PESAN SUKSES -->
                <?php $success = session()->getFlashdata('success'); ?>

                <?php if ($success): ?>
                    <div class="alert alert-success" role="status">
                        <?= esc($success) ?>
                    </div>
                <?php endif; ?>

                <!-- FORM LOGIN -->
                <form
                    action="<?= site_url('login') ?>"
                    method="post"
                >

                    <?= csrf_field() ?>

                    <!-- PILIH ROLE -->
                    <div class="form-group">

                        <label class="form-label" id="role-label">
                            Masuk Sebagai
                        </label>

                        <div class="role-grid" role="group" aria-labelledby="role-label">

                            <label class="role-option">
                                <input
                                    type="radio"
                                    name="role"
                                    value="client"
                                    required
                                    <?= old('role', 'client') === 'client' ? 'checked' : '' ?>
                                >

                                <span class="role-card">
                                    <span class="role-icon">♡</span>
                                    <span class="role-name">Klien</span>
                                </span>
                            </label>

                            <label class="role-option">
                                <input
                                    type="radio"
                                    name="role"
                                    value="psychologist"
                                    required
                                    <?= old('role') === 'psychologist' ? 'checked' : '' ?>
                                >

                                <span class="role-card">
                                    <span class="role-icon">✚</span>
                                    <span class="role-name">Psikolog</span>
                                </span>
                            </label>

                            <label class="role-option">
                                <input
                                    type="radio"
                                    name="role"
                                    value="admin"
                                    required
                                    <?= old('role') === 'admin' ? 'checked' : '' ?>
                                >

                                <span class="role-card">
                                    <span class="role-icon">⚙</span>
                                    <span class="role-name">Administrator</span>
                                </span>
                            </label>

                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="form-group">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-input"
                            placeholder="Masukkan alamat email"
                            value="<?= esc(old('email') ?? '', 'attr') ?>"
                            autocomplete="username"
                            required
                        >

                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group">

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-input"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="toggle-password"
                                id="togglePassword"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                            >
                                Lihat
                            </button>

                        </div>

                    </div>

                    <!-- TOMBOL LOGIN -->
                    <button type="submit" class="login-button">
                        Masuk Sekarang
                    </button>

                </form>

                <!-- REGISTER -->
                <div class="register-text">
                    Belum punya akun?
                    <a href="<?= site_url('register') ?>">
                        Daftar sebagai Klien
                    </a>
                </div>

                <div class="security-note">
                    Hak akses akun ditentukan berdasarkan
                    role yang terdaftar di sistem.
                </div>

            </div>

        </section>

    </div>

    <!-- JAVASCRIPT -->
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';

            togglePassword.textContent = isHidden
                ? 'Sembunyikan'
                : 'Lihat';

            togglePassword.setAttribute(
                'aria-label',
                isHidden ? 'Sembunyikan password' : 'Tampilkan password'
            );

            togglePassword.setAttribute(
                'aria-pressed',
                String(isHidden)
            );
        });
    </script>

</body>
</html>
