<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['username_user'])) {
    exit();
}

$username_user = $_SESSION['username_user'];
$no_pelanggan = $_POST['no_pelanggan'];
$bulan_pem = $_POST['bulan_pem'];
$total_pem = (int) $_POST['total_pem'];

// Ambil saldo user dari database
$query_saldo = "SELECT saldo FROM login_users WHERE username_user = '$username_user'";
$result_saldo = $conn->query($query_saldo);

if ($result_saldo->num_rows > 0) {
    $row = $result_saldo->fetch_assoc();
    $saldo_sekarang = (int) $row['saldo'];
} else {
    exit(); // Jika saldo tidak ditemukan, hentikan eksekusi
}

// Cek apakah saldo mencukupi
if ($saldo_sekarang >= $total_pem) {
    $saldo_baru = $saldo_sekarang - $total_pem;

    // Update saldo di database
    $update_saldo = "UPDATE login_users SET saldo = '$saldo_baru' WHERE username_user = '$username_user'";
    $conn->query($update_saldo);

    // Update status tagihan jadi lunas
    $update_status = "UPDATE tambah_tagihan SET status = 'sudah bayar' WHERE no_pelanggan = '$no_pelanggan' AND bulan_pem = '$bulan_pem'";
    $conn->query($update_status);
}

header("Location: ../dashboard-user/user.php");
?>