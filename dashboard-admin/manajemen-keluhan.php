<?php
session_start();
require '../koneksi.php'; // Gunakan koneksi dari koneksi.php

// Jika form keluhan dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_keluhan'])) {
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
            echo "<script>alert('Keluhan berhasil dikirim!'); window.location.href='user.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim keluhan.');</script>";
        }
    } else {
        echo "<script>alert('Nomor pelanggan tidak ditemukan.');</script>";
    }
    
    $stmt->close();
}

// Jika ada permintaan hapus keluhan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hapus_keluhan'])) {
    $id_keluhan = intval($_POST['id_keluhan']);
    $delete_query = "DELETE FROM keluhan WHERE id_keluhan = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id_keluhan);
    $stmt->execute();
    $stmt->close();
    header("Location: manajemen-keluhan.php");
    exit();
}
$conn->close();
?>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Keluhan</title>
    <link rel="stylesheet" href="../styless.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        function searchData() {
            let searchValue = document.getElementById("search").value;
            window.location.href = "?search=" + encodeURIComponent(searchValue);
        }
    </script>
</head>
<style>
    .box {
        width: 98%;
        height: 40px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        margin-bottom: 15px;
    }
    .btn-hapus {
        background-color: red;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

</style>
<body>
    <nav class="navbar">
        <div class="logo">Admin <span>AquaPay</span></div>
        <ul class="nav-links">
            <li><a href="../dashboard-admin/daftar-anggota.php">Daftar Pelanggan</a></li>
            <li><a href="../dashboard-admin/manajemen-tagihan.php">Manajemen Tagihan</a></li>
            <li><a href="../dashboard-admin/manajemen-keluhan.php" class="active">Manajemen Keluhan</a></li>
            <li><a href="../login/logout_admin.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i>Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2 style="color: #007bff; justify-content: center; display: flex;">Manajemen Keluhan</h2>
        <div class="search-container">
            <input class="box" type="text" id="search" placeholder="Cari..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" onkeyup="searchData()">
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Laporan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require '../koneksi.php';
                $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
                $query = "SELECT keluhan.id_keluhan, anggota.nama_anggota, keluhan.keluhan, keluhan.tanggal_keluhan 
                          FROM keluhan 
                          JOIN anggota ON keluhan.id_anggota = anggota.id_anggota 
                          WHERE anggota.nama_anggota LIKE ?
                          ORDER BY keluhan.tanggal_keluhan DESC";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $search);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_anggota']); ?></td>
                        <td><?= htmlspecialchars($row['keluhan']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_keluhan']); ?></td>
                        <td class="aksi">
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus keluhan ini?');">
                                <input type="hidden" name="id_keluhan" value="<?= $row['id_keluhan']; ?>">
                                <button type="submit" name="hapus_keluhan" ><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; 
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
