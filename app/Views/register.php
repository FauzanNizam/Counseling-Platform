<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'tanimakmur_db');

if($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$nama = $_POST['nama'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(strlen($password) < 6) {
    $_SESSION['error'] = "Password harus minimal 6 karakter";
    header("Location: tampilan.php");
    exit();
}

if(!preg_match('/[A-Z]/', $password)) {
    $_SESSION['error'] = "Password harus mengandung setidaknya satu huruf kapital";
    header("Location: tampilan.php");
    exit();
}

IF(!preg_match('/[0-9]/', $password)) {
    $_SESSION['error'] = "Password harus mengandung setidaknya satu angka";
    header("Location: tampilan.php");
    exit();
}

$cek = $conn->query("SELECT * FROM users WHERE email = '$email' OR nama='$nama'");
if(!$cek) {
    die("Query gagal: " . $conn->error);
}

if($cek->num_rows > 0){
    $_SESSION['error_register']="Nama atau email sudah terdaftar";
    header("Location: tampilan.php");
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$insert =  $conn->query("INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$hash')");
if(!$insert) {
    die("Query gagal: " . $conn->error);
}
$_SESSION['success_register'] = "Registrasi berhasil!";
header("Location: tampilan.php");
exit();
