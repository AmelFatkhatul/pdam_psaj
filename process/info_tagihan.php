<?php
session_start();
include '../koneksi.php'; // Sesuaikan path koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['no_pelanggan'])) {
    $no_pelanggan = trim($_POST['no_pelanggan']);

    // Cek apakah input kosong
    if (empty($no_pelanggan)) {
        echo "Nomor pelanggan tidak boleh kosong!";
        exit;
    }

    // Ambil data tagihan terbaru berdasarkan nomor pelanggan
    $stmt = $conn->prepare("SELECT total_pem FROM tambah_tagihan WHERE no_pelanggan = ? ORDER BY bulan_pem DESC LIMIT 1");
    $stmt->bind_param("s", $no_pelanggan);
    $stmt->execute();
    $result = $stmt->get_result();
    $tagihan = $result->fetch_assoc();

    if ($tagihan) {
        echo "Total Tagihan: Rp " . number_format($tagihan['total_pem'], 2, ',', '.');
    } else {
        echo "Tagihan tidak ditemukan.";
    }
    exit;
}
?>
