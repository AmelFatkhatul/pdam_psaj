<!-- manajemen-tagihan.php -->

<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../login/login_admin.php');
    exit;
}

// Koneksi ke database
include '../koneksi.php';
$query = "SELECT id_tagihan, anggota.nama_anggota, tambah_tagihan.no_pelanggan, tambah_tagihan.bulan_pem, tambah_tagihan.total_pem, tambah_tagihan.status FROM tambah_tagihan JOIN anggota ON tambah_tagihan.id_anggota = anggota.id_anggota";
$result = $conn->query($query);

// Ambil daftar anggota
$anggotaList = [];
$anggotaQuery = $conn->query("SELECT id_anggota, nama_anggota, no_pelanggan FROM anggota");
while ($anggota = $anggotaQuery->fetch_assoc()) {
    $anggotaList[] = $anggota;
}

// Proses tambah tagihan jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $id_anggota = $_POST['id_anggota'];
    $bulan_pem = $_POST['bulan_pem'];
    $total_pem = $_POST['total_pem'];

    // Ambil nomor pelanggan berdasarkan id_anggota
    $query = $conn->prepare("SELECT no_pelanggan FROM anggota WHERE id_anggota = ?");
    $query->bind_param("i", $id_anggota);
    $query->execute();
    $result = $query->get_result();
    $data = $result->fetch_assoc();
    $no_pelanggan = $data['no_pelanggan'] ?? '';

    // Validasi input tidak boleh kosong
    if (!empty($id_anggota) && !empty($bulan_pem) && !empty($total_pem)) {
        // Insert ke database menggunakan prepared statement
        $queryInsert = $conn->prepare("INSERT INTO tambah_tagihan (id_anggota, no_pelanggan, bulan_pem, total_pem) VALUES (?, ?, ?, ?)");
        $queryInsert->bind_param("isss", $id_anggota, $no_pelanggan, $bulan_pem, $total_pem);
        $queryInsert->execute();
    }
}

// Proses hapus tagihan
if (isset($_POST['hapus'])) {
    $id_hapus = $_POST['id_hapus'];
    $stmt = $conn->prepare("DELETE FROM tambah_tagihan WHERE id_tagihan = ?");
    $stmt->bind_param("i", $id_hapus);
    $stmt->execute();
    $stmt->close();
    header("Location: manajemen-tagihan.php?status=deleted");
    exit();
}

// Proses edit tagihan
if (isset($_POST['update'])) {
    $id_edit = $_POST['id_edit'];
    $bulan_pem = $_POST['bulan_pem'];
    $total_pem = $_POST['total_pem'];

    $stmt = $conn->prepare("UPDATE tambah_tagihan SET bulan_pem = ?, total_pem = ? WHERE id_tagihan = ?");
    $stmt->bind_param("ssi", $bulan_pem, $total_pem, $id_edit);
    $stmt->execute();
    $stmt->close();
    header("Location: manajemen-tagihan.php?status=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tagihan</title>
    <link rel="stylesheet" href="../styless.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Styling Modal */
        .title{
            color: #007bff;
            justify-content: center;
            display: flex;
        }
        
        .modal-content select,
.modal-content input {
    width: calc(100% - 16px); /* Sesuaikan lebar agar sama */
    padding: 10px; /* Padding agar konsisten */
    border: 1px solid #ccc;
    border-radius: 5px;

    box-sizing: border-box; /* Hindari perubahan ukuran akibat padding */
}


        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-content h3 {
            margin-bottom: 15px;
        }

        .modal-content form div {
            margin-bottom: 10px;
            text-align: left;
        }


        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-content button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }

        
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo">Admin <span>AquaPay</span></div>
        <ul class="nav-links">
            <li><a href="../dashboard-admin/daftar-anggota.php">Daftar Pelanggan</a></li>
            <li><a href="manajemen-tagihan.php" class="active">Manajemen Tagihan</a></li>
            <li><a href="../dashboard-admin/manajemen-keluhan.php">Manajemen Keluhan</a></li>
            <li><a href="../login/logout_admin.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i>Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2 class="title">Manajemen Tagihan</h2>
        <div class="search-container">
            <input class="search-box" type="text" id="searchInput" placeholder="Cari..." onkeyup="searchTable()">
            <button onclick="openModal()" class="btn-add">Tambah Tagihan</button>
        </div>

        <table>
            <thead>
                <tr>
                  
                    <th>No. Pelanggan</th>
                    <th>Bulan Pembayaran</th>
                    <th>Total Pembayaran</th>
                    <th>Status</th>
                    <th>Aksi</th>


                    
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = "SELECT id_tagihan, anggota.nama_anggota, tambah_tagihan.no_pelanggan, tambah_tagihan.bulan_pem, tambah_tagihan.total_pem FROM tambah_tagihan JOIN anggota ON tambah_tagihan.id_anggota = anggota.id_anggota";
                $result = $conn->query("SELECT * FROM tambah_tagihan");
                while 
                               
                ($row = $result->fetch_assoc()): ?>
                    <tr>
                        
                        <td><?= $row['no_pelanggan']; ?></td>
                        <td><?= $row['bulan_pem']; ?></td>
                        <td>Rp <?= number_format($row['total_pem'], 2, ',', '.'); ?></td>
                        <td style="color: <?= ($row['status'] == 'sudah bayar') ? '#0BA16F' : '#D84040'; ?>; font-weight: bold;">
                            <?= $row['status']; ?>
                        </td>
                        <td class="aksi">
                            <button onclick="openEditModal('<?= $row['id_tagihan']; ?>', '<?= $row['bulan_pem']; ?>', '<?= $row['total_pem']; ?>')"><i class="fa-solid fa-pencil"></i></button>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id_hapus' value='<?= $row['id_tagihan']; ?>'>
                                <button type='submit' name='hapus'><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Popup -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span onclick="closeModal()" class="close">✖</span>
            <h3>Tambah Tagihan</h3>
            <form method="POST">
                <div>
                    <select class="option" name="id_anggota" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($anggotaList as $anggota) : ?>
                        <option value="<?= $anggota['id_anggota']; ?>">
                            <?= $anggota['nama_anggota']; ?> -
                            <?= $anggota['no_pelanggan']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>

                    <input type="date" name="bulan_pem" required placeholder="Bulan Pembayaran">
                </div>
                <div>

                    <input type="number" name="total_pem" required placeholder="Total Pembayaran">
                </div>
                <button type="submit" name="submit">Tambah</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#tableBody tr");

    rows.forEach(row => {
        let nama = row.cells[0].textContent.toLowerCase(); // Mengambil teks dari kolom pertama
        row.style.display = nama.includes(input) ? "" : "none";
    });
}

    </script>

<div id="editModal" class="modal">
        <div class="modal-content">
            <span onclick="closeEditModal()" class="close">✖</span>
            <h3>Edit Tagihan</h3>
            <form method="POST">
                <input type="hidden" name="id_edit" id="edit_id">
                <div>
                    
                    <input type="date" name="bulan_pem" id="edit_bulan_pem" required>
                </div>
                <div>
                   
                    <input type="number" name="total_pem" id="edit_total_pem" required>
                </div>
                <button type="submit" name="update">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
        function openEditModal(id, bulan, total) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_bulan_pem').value = bulan;
            document.getElementById('edit_total_pem').value = total;
            document.getElementById('editModal').style.display = 'flex';
        }
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>

</html>






