
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - CounselPlatform</title>

    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #fff;
            color: #241b35;
        }

        .register-left {
            width: 45%;
            min-height: 100vh;
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
            background: linear-gradient(145deg, #7c3aed, #3b1678);
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 70px;
        }

        .register-left h1 {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(38px, 4vw, 58px);
            font-weight: 400;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .register-left p {
            font-size: 14px;
            line-height: 1.8;
            color: #ede0ff;
            max-width: 400px;
        }

        .benefits {
            margin-top: 36px;
            display: grid;
            gap: 18px;
        }

        .benefit {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .check {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255,255,255,.16);
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .register-right {
            width: 55%;
            min-height: 100vh;
            padding: 40px 24px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-form {
            width: 100%;
            max-width: 420px;
        }

        .register-form h2 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #81778f;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #e2dbea;
            border-radius: 10px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            background: #fff;
        }

        input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px #f5f0ff;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 75px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #7c3aed;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .terms {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 18px 0;
            font-size: 12px;
            line-height: 1.6;
            color: #716780;
        }

        .terms input {
            margin-top: 3px;
            accent-color: #7c3aed;
            flex-shrink: 0;
        }

        .terms label {
            margin: 0;
            font-weight: 400;
        }

        .terms a,
        .login-link a {
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #7c3aed;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
        }

        .submit-btn:hover {
            background: #5b21b6;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #81778f;
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 11px;
            color: #a39aaf;
            line-height: 1.7;
        }

        .error-box {
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: 10px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13px;
            line-height: 1.7;
        }

        @media (max-width: 800px) {
            .register-left {
                display: none;
            }

            .register-right {
                width: 100%;
                padding: 32px 24px;
            }

            .register-form {
                max-width: 460px;
            }
        }

        @media (max-width: 420px) {
            .register-right {
                padding: 28px 18px;
            }

            .register-form h2 {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>

    <section class="register-left">
        <div class="brand">CounselPlatform</div>

        <h1>Mulai perjalananmu menuju diri yang lebih baik.</h1>

        <p>
            Buat akun untuk menemukan ruang aman, mengenali perasaan,
            dan mendapatkan dukungan dari tenaga profesional.
        </p>

        <div class="benefits">
            <div class="benefit">
                <span class="check">✓</span>
                <span>Konseling bersama psikolog profesional</span>
            </div>

            <div class="benefit">
                <span class="check">✓</span>
                <span>Pilih sesi online atau tatap muka</span>
            </div>

            <div class="benefit">
                <span class="check">✓</span>
                <span>Kelola jadwal konseling dengan mudah</span>
            </div>
        </div>
    </section>

    <section class="register-right">
        <div class="register-form">
            <h2>Buat Akun</h2>

            <p class="subtitle">
                Daftar sekarang dan mulai langkah pertamamu.
            </p>

            <?php if (session()->has('errors')): ?>
                <div class="error-box">
                    <?php foreach (session('errors') as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form
                action="<?= site_url('register') ?>"
                method="post"
                id="registerForm"
            >
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Masukkan nama lengkap"
                        value="<?= esc(old('name') ?? '') ?>"
                        autocomplete="name"
                        minlength="3"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="nama@email.com"
                        value="<?= esc(old('email') ?? '') ?>"
                        autocomplete="email"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                        <button
                            type="button"
                            class="toggle-password"
                            data-target="password"
                        >Lihat</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">
                        Konfirmasi Kata Sandi
                    </label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="confirmPassword"
                            name="confirmPassword"
                            placeholder="Ulangi kata sandi"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                        <button
                            type="button"
                            class="toggle-password"
                            data-target="confirmPassword"
                        >Lihat</button>
                    </div>
                </div>

                <div class="terms">
                    <input
                        type="checkbox"
                        id="agree"
                        required
                    >

                    <label for="agree">
                        Saya menyetujui
                        <a href="#">Syarat dan Ketentuan</a>
                        serta
                        <a href="#">Kebijakan Privasi</a>.
                    </label>
                </div>

                <button type="submit" class="submit-btn">
                    Buat Akun
                </button>
            </form>

            <p class="login-link">
                Sudah punya akun?
                <a href="<?= site_url('login') ?>">Masuk</a>
            </p>

            <div class="footer">
                © <?= date('Y') ?> CounselPlatform.
                Semua hak dilindungi.
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(
                    button.dataset.target
                );

                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                button.textContent = isHidden ? 'Sembunyi' : 'Lihat';
            });
        });

        document.getElementById('registerForm')
            .addEventListener('submit', event => {
                const password =
                    document.getElementById('password').value;

                const confirmPassword =
                    document.getElementById('confirmPassword').value;

                if (password !== confirmPassword) {
                    event.preventDefault();
                    alert('Konfirmasi kata sandi tidak cocok.');
                }
            });
    </script>

</body>
</html>
