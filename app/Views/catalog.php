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

    .header {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      padding: 0 20px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 15px;
      margin-top: 20px;
    }

    .card {
      border: 1px solid #ddd;
      padding: 10px;
      cursor: pointer;
    }

    .card img {
      width: 100%;
      height: 120px;
      object-fit: cover;
    }

    .mb-3 {
      width: 100%;
    }

    .button {
      margin-top: 10px;
      position: relative;
      font-family: "Saira Stencil", cursive;
      font-size: 12px;
      color: rgb(255, 255, 255);
      text-align: center;
      padding: 10px 15px;
      background-color: #059ff2;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #search {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
  </style>
</head>

<body>
  <nav>
    <a href="#" class="logo">Tani Makmur</a>

    <div class="menu">
      <a href="<?= base_url('/') ?>">Home</a>
      <a href="<?= base_url('catalog') ?>">Katalog</a>
      <a href="about.html">About</a>
      <a href="contact.html">Contact</a>
    </div>

    <div class="profile">
      <a href="">
        <img src="https://raw.githubusercontent.com/FauzanNizam/image/main/user.png" alt="user" width="50px" />
      </a>
    </div>
  </nav>

  <div class="header">
    <h2 style="font-family: 'Saira Stencil', cursive">
      Katalog Produk
    </h2>
    <div>
      <button class="button" onclick="toggleAdmin()">
        Mode Admin
      </button>
      <button class="button" id="btnTambah" onclick="keTambah()" style="display: none">
        + Tambah
      </button>
    </div>
  </div>

  <div class="mb-3 container">
    <input
      type="text"
      id="search"
      placeholder="Cari Produk..."
      onkeyup="cari()" />
  </div>
  <div class="grid" id="produkList"></div>

  <script>
    let isAdmin = false;

    function toggleAdmin() {
      isAdmin = !isAdmin;
      document.getElementById('btnTambah').style.display = isAdmin ? 'inline-block' : 'none';
      cari(); // Refresh produk list to show/hide edit buttons
    }
  </script>
</body>

</html>