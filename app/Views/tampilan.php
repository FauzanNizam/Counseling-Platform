<!doctype html>
<html>

<head>
  <title>Tani Makmur</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <link
    href="https://fonts.googleapis.com/css2?family=Saira+Stencil:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Mogra&display=swap"
    rel="stylesheet" />
  <style>
    nav {
      background-color: #222;
      display: flex;
      align-items: center;
      padding: 15px 20px;
    }

    .logo {
      flex: 1;
      font-family: "Saira Stencil", cursive;
      color: #fff;
      font-size: 24px;
      text-decoration: none;
    }

    .menu {
      flex: 1;
      display: flex;
      justify-content: center;
      /* ini bikin ke tengah */
      gap: 20px;
    }


    .menu a {
      color: #fff;
      text-decoration: none;
      font-size: 18px;
    }

    .menu a:hover {
      color: #ddd;
    }


    .profile {
      flex: 1;
      display: flex;
      justify-content: flex-end;
      /* dorong ke kanan */
    }

    body {
      margin: 0;
    }

    .hero {
      position: relative;
      height: 100vh;
      /* tinggi tetap */
      background-image: url("https://images.unsplash.com/photo-1560493676-04071c5f467b");
      background-size: cover;
      background-position: center;

      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .hero::before {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
    }

    .hero-text {
      margin-bottom: 1px;
      position: relative;
      font-size: 50px;
      font-family: "Saira Stencil", cursive;
      color: rgb(255, 255, 255);
      text-align: center;
    }

    .slogan {
      margin-top: 0;
      position: relative;
      font-family: "Mogra", system-ui;
      font-size: 30px;
      font-style: normal;
      color: rgb(255, 255, 255);
      text-align: center;
    }

    .button {
      margin-top: 10px;
      position: relative;
      font-family: "Saira Stencil", cursive;
      font-size: 30px;
      color: rgb(255, 255, 255);
      text-align: center;
      padding: 10px 20px;
      background-color: #059ff2;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .footer {
      background-color: #222;
      color: #fff;
      text-align: center;
      padding: 20px;
    }

    .footer-content {
      font-family: "Saira Stencil", cursive;
      font-size: 18px;
    }
  </style>


</head>

<body>


  <nav>
    <a href="<?= base_url('/') ?>" class="logo">Tani Makmur</a>

    <div class="menu">
      <a href="<?= base_url('/') ?>">Home</a>
      <a href="<?= base_url('catalog') ?>">Katalog</a>
      <a href="<?= base_url('about') ?>">About</a>
      <a href="<?= base_url('contact') ?>">Contact</a>
    </div>

    <div class="profile">
      <a href="<?= base_url('/') ?>">
        <img src="https://raw.githubusercontent.com/FauzanNizam/image/main/user.png" alt="user" width="50px" />
      </a>
    </div>
  </nav>

  <section class="hero">
    <p class="hero-text">Welcome to Tani Makmur!</p>
    <br />
    <p class="slogan">“Tumbuh Bersama Alam, Makmur Bersama Kami”</p>
    <br />
    <a href="<?= base_url('catalog') ?>">
      <input type="button" value="Jelajahi" class="button" />
    </a>
  </section>

  <footer class="footer">
    <div class="footer-content">
      <h3>Tani Makmur</h3>
      <p>“Tumbuh Bersama Alam, Makmur Bersama Kami”</p>

      <div class="social">
        <p>contact</p>
        <a href="#">WhatsApp</a>
      </div>

      <p>© 2026 Tani Makmur</p>
    </div>
  </footer>
</body>

</html>