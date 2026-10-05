<!DOCTYPE html>
<html>

<head>
    <title>Users</title>
</head>

<body>
    <?= form_open('users/save'); ?>

    <p>
        Username: <?= form_input('username'); ?>
    </p>

    <p>
        Password: <?= form_password('password'); ?>
    </p>

    <p>
        Nama: <?= form_input('nama'); ?>
    </p>

    <p>
        Role: <?= form_input('role'); ?>
    </p>

    <p>
        <?= form_submit('submit', 'Simpan'); ?>
    </p>

    <?= form_close(); ?>
    
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Password</th>
            <th>Nama</th>
            <th>Role</th>
        </tr>

        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user['username']; ?></td>
                <td><?= $user['password']; ?></td>
                <td><?= $user['nama']; ?></td>
                <td><?= $user['role']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>