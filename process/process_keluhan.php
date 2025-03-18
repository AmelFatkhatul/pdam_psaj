<?php
session_start();
require '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = htmlspecialchars($_POST['nama']);
    $nomor_pelanggan = htmlspecialchars($_POST['nomor_pelanggan']);
    $keluhan = htmlspecialchars($_POST['keluhan']);

    // Periksa apakah nomor pelanggan ada di database
    $query = "SELECT id_anggota FROM anggota WHERE no_pelanggan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nomor_pelanggan);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $id_anggota = $row['id_anggota'];

        // Simpan keluhan ke database
        $insert = "INSERT INTO keluhan (id_anggota, keluhan) VALUES (?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("is", $id_anggota, $keluhan);

        if ($stmt->execute()) {
            $_SESSION['pesan'] = "Keluhan berhasil dikirim!";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['pesan'] = "Gagal mengirim keluhan.";
            $_SESSION['status'] = "error";
        }
    } else {
        $_SESSION['pesan'] = "Nomor pelanggan tidak ditemukan.";
        $_SESSION['status'] = "error";
    }

    $stmt->close();
    $conn->close();
}

header("Location: ../dashboard-user/user.php"); // Kembali ke halaman user tanpa pindah halaman langsung
exit();
?>
