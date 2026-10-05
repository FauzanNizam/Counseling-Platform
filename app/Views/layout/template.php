<!doctype html>
<html lang="en">

<head>
    <title><?= $this->renderSection('title') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">

    <style>
        body {
            background: #eef1f5;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            background: #fff;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar h4 {
            font-family: 'Saira Stencil', cursive;
            color: #333;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #333;
            text-decoration: none;
            margin-bottom: 15px;
            font-family: 'Mogra', system-ui;
        }

        .sidebar a:hover {
            color: #059ff2;
        }

        .content {
            margin-left: 240px;
            padding: 30px;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4>MY PROFILE</h4>
        <a href="#">Dashboard</a>
        <a href="#" class="fw-bold text-primary">Profile</a>
    </div>

    <div class="content">
        <?= $this->renderSection('content') ?>
    </div>

</body>

</html>