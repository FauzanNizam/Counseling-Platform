<?php
session_start();

$conn = new mysqli("lcoalhost", "root", "", "tanimakmur_db");

//cek apakah db connect
if ($conn->connect_error) {
    die("Koneksi gagal:" . $conn->connect_error);
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Email dan password harus diisi";
    header("Location: tampilan.php");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE email = '$email'");

if (!$result) {
    die("Query gagal:" . $conn->error);
}

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['foto'] = $user['foto'];

        header("Location: tampilan.php");
        exit;
    } else {
        $_SESSION['error'] = "Email atau password salah";
        header("Location: tampilan.php");
        exit();
    }
}
