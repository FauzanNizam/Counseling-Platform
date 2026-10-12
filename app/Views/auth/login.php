
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — CounselPlatform</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --p900: #3b1678;
            --p800: #4c1d95;
            --p700: #5b21b6;
            --p600: #6d28d9;
            --p500: #7c3aed;
            --p100: #ede0ff;
            --g900: #201b2c;
            --g600: #655d72;
            --g400: #968fa3;
            --g200: #e5e0eb;
            --g50: #faf9fc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--g900);
            background: white;
        }

        .login-page {
            display: flex;
            min-height: 100vh;
        }

        /* PANEL KIRI */
        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: white;
            background: linear-gradient(
                160deg,
                var(--p900),
                var(--p700) 50%,
                var(--p500)
            );
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: #ffffff26;
            font-size: 20px;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 700;
        }

        .login-left h1 {
            margin-bottom: 14px;
            font-family: 'Instrument Serif', serif;
            font-size: clamp(32px, 3vw, 42px);
            font-weight: 400;
            font-style: italic;
            line-height: 1.2;
        }

        .intro {
            max-width: 450px;
            color: #ffffffa6;
            font-size: 14px;
            line-height: 1.8;
        }

        .benefits {
            display: grid;
            gap: 14px;
            margin-top: 34px;
        }

        .benefit {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ffffffd9;
            font-size: 13px;
        }

        .benefit-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #ffffff1a;
            font-size: 17px;
        }

        /* PANEL KANAN */
        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .login-box h2 {
            margin-bottom: 5px;
            font-size: 24px;
            font-weight: 700;
        }

        .subtitle {
            margin-bottom: 28px;
            color: var(--g400);
            font-size: 13px;
            line-height: 1.7;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: var(--g400);
            font-size: 16px;
            pointer-events: none;
        }

        .field input {
            width: 100%;
            min-height: 46px;
            padding: 12px 70px 12px 40px;
            border: 1px solid var(--g200);
            border-radius: 9px;
            outline: none;
            background: white;
            color: var(--g900);
            font: inherit;
            font-size: 13px;
            transition: border-color .2s, box-shadow .2s;
        }

        .field input:focus {
            border-color: var(--p500);
            box-shadow: 0 0 0 3px #7c3aed18;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: var(--p500);
            cursor: pointer;
            font-family: inherit;
            font-size: 12px;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin: 4px 0 22px;
            font-size: 12px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--g600);
            cursor: pointer;
        }

        .remember input {
            accent-color: var(--p500);
        }

        .text-link {
            color: var(--p500);
            text-decoration: none;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 46px;
            padding: 12px;
            border: 1px solid transparent;
            border-radius: 9px;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-primary {
            background: var(--p500);
            color: white;
        }

        .btn-primary:hover {
            background: var(--p600);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: #b2acbd;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--g200);
        }

        .btn-secondary {
            gap: 8px;
            border-color: var(--g200);
            background: white;
            color: var(--g600);
        }

        .btn-secondary:hover {
            background: var(--g50);
        }

        .terms {
            margin-top: 22px;
            color: var(--g400);
            font-size: 11px;
            line-height: 1.7;
            text-align: center;
        }

        .message {
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: 9px;
            font-size: 12px;
            line-height: 1.7;
        }

        .message-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .message-success {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        /* RESPONSIVE */
        @media (max-width: 800px) {
            .login-left {
                flex: 0 0 40%;
                padding: 32px 24px;
            }

            .login-right {
                padding: 28px 24px;
            }

            .login-left h1 {
                font-size: 30px;
            }
        }

        @media (max-width: 600px) {
            .login-page {
                flex-direction: column;
                min-height: 100vh;
            }

            .login-left {
                flex: none;
                padding: 28px 24px;
            }

            .brand {
                margin-bottom: 24px;
            }

            .login-left h1 {
                font-size: 32px;
            }

            .intro {
                font-size: 13px;
            }

            .benefits {
                gap: 10px;
                margin-top: 22px;
            }

            .benefit {
                font-size: 12px;
            }

            .benefit-icon {
                width: 30px;
                height: 30px;
            }

            .login-right {
                flex: 1;
                align-items: flex-start;
                padding: 32px 24px;
            }

            .login-box h2 {
                font-size: 22px;
            }
        }

        @media (max-width: 360px) {
            .login-left,
            .login-right {
                padding-right: 18px;
                padding-left: 18px;
            }
        }
    </style>
</head>

<body>

<div class="login-page">

    <section class="login-left">
        <div class="brand">
            <div class="brand-icon">🧠</div>
            <span class="brand-name">CounselPlatform</span>
        </div>

        <div>
            <h1>Ruang aman untuk<br>cerita &amp; pemulihan.</h1>

            <p class="intro">
                Platform konseling mental health hybrid — online maupun
                pick-up session — bersama psikolog berpengalaman
                dan terverifikasi.
            </p>
        </div>

        <div class="benefits">
            <div class="benefit">
                <span class="benefit-icon">▣</span>
                <span>Konseling online via video call</span>
            </div>

            <div class="benefit">
                <span class="benefit-icon">📍</span>
                <span>Pick-up session langsung ke lokasi kamu</span>
            </div>

            <div class="benefit">
                <span class="benefit-icon">✓</span>
                <span>Psikolog terverifikasi &amp; berlisensi STR</span>
            </div>
        </div>
    </section>

    <section class="login-right">
        <div class="login-box">

            <h2>Masuk</h2>
            <p class="subtitle">Lanjutkan sesi pemulihan kamu</p>

            <?php if (session()->has('login_error')): ?>
                <div class="message message-error" role="alert">
                    <?= esc(session('login_error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('errors')): ?>
                <div class="message message-error" role="alert">
                    <?php foreach (session('errors') as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('success')): ?>
                <div class="message message-success" role="status">
                    <?= esc(session('success')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="post" id="loginForm">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="email">Email</label>

                    <div class="input-wrap">
                        <span class="input-icon">✉</span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="email@kamu.com"
                            value="<?= esc(old('email') ?? '') ?>"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>

                    <div class="input-wrap">
                        <span class="input-icon">▣</span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Tampilkan password"
                        >Lihat</button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        Ingat saya
                    </label>

                    <a class="text-link" href="#">
                        Lupa password?
                    </a>
                </div>

                <button type="submit" class="btn btn-primary">
                    Masuk
                </button>
            </form>

            <div class="divider">atau</div>

            <a href="<?= site_url('register') ?>" class="btn btn-secondary">
                ＋ Daftar Akun Baru
            </a>

            <p class="terms">
                Dengan masuk, kamu setuju dengan
                <a href="#" class="text-link">Syarat &amp; Ketentuan</a>.
            </p>

        </div>
    </section>

</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';
        togglePassword.textContent = isPassword ? 'Sembunyi' : 'Lihat';

        togglePassword.setAttribute(
            'aria-label',
            isPassword ? 'Sembunyikan password' : 'Tampilkan password'
        );
    });
</script>

</body>
</html>
