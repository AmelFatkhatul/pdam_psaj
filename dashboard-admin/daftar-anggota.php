<!-- daftar_anggota.php -->

<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../login/login_admin.php');
    exit;
}

// Koneksi ke database
include '../koneksi.php';

// Proses Tambah Anggota
if (isset($_POST['submit'])) {
    $nama = $_POST['nama_anggota'];
    $no_pelanggan = $_POST['no_pelanggan'];
    $telepon = $_POST['telepon'];

    $stmt = $conn->prepare("INSERT INTO anggota (nama_anggota, no_pelanggan, telepon) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $no_pelanggan, $telepon);
    $stmt->execute();
    $stmt->close();
    header("Location: daftar-anggota.php?status=success");
    exit();
}

// Proses Hapus Anggota
if (isset($_POST['hapus'])) {
    $id_hapus = $_POST['id_hapus'];
    $stmt = $conn->prepare("DELETE FROM anggota WHERE id_anggota = ?");
    $stmt->bind_param("i", $id_hapus);
    $stmt->execute();
    $stmt->close();
    header("Location: daftar-anggota.php?status=deleted");
    exit();
}

// Proses Edit Anggota
if (isset($_POST['update'])) {
    $id_edit = $_POST['id_edit'];
    $nama = $_POST['nama_anggota'];
    $no_pelanggan = $_POST['no_pelanggan'];
    $telepon = $_POST['telepon'];

    $stmt = $conn->prepare("UPDATE anggota SET nama_anggota = ?, no_pelanggan = ?, telepon = ? WHERE id_anggota = ?");
    $stmt->bind_param("sssi", $nama, $no_pelanggan, $telepon, $id_edit);
    $stmt->execute();
    $stmt->close();
    header("Location: daftar-anggota.php?status=updated");
    exit();
}

// Ambil daftar anggota dari database
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM anggota WHERE nama_anggota LIKE ? OR no_pelanggan LIKE ? OR telepon LIKE ?";
$stmt = $conn->prepare($query);
$searchParam = "%" . $search . "%";
$stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
$stmt->execute();
$anggotaList = $stmt->get_result();
$stmt->close();

// Ambil data anggota untuk edit jika ada `edit_id` di URL
$editData = null;
if (isset($_GET['edit_id'])) {
    $id_edit = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM anggota WHERE id_anggota = ?");
    $stmt->bind_param("i", $id_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan</title>
    <link rel="stylesheet" href="../styless.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Modal Styling */
        .title{
    color: #007bff;
    justify-content: center;
    display: flex;

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
            display: block;
            text-decoration: none;
            font-weight: bold;
            color: rgb(0, 0, 0);
        }

        .modal input {
            width: 380px;
            padding: 10px;
            display: flex;
            justify-content: center;
            margin-right: 40px;
            gap: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .modal button{
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            align-items: center;
            border-radius: 5px;
            gap: 10px;
        }
        /* Menampilkan modal jika URL memiliki parameter */
        <?php if (isset($_GET['add']) || isset($_GET['edit_id'])): ?>.modal {
            display: flex;
        }

        <?php endif;
        ?>
    </style>
</head>

<script>
function searchData() {
    let searchValue = document.getElementById("search").value;
    window.location.href = "daftar-anggota.php?search=" + encodeURIComponent(searchValue);
}
</script>

<body>
    <nav class="navbar">
        <div class="logo">Admin <span>AquaPay</span></div>
        <ul class="nav-links">
            <li><a href="daftar-anggota.php" class="active">Daftar Pelanggan</a></li>
            <li><a href="manajemen-tagihan.php">Manajemen Tagihan</a></li>
            <li><a href="manajemen-keluhan.php">Manajemen Keluhan</a></li>
            <li class="logout"><a href="../login/logout_admin.php" ><i class="fa-solid fa-right-from-bracket"></i>Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2 class="title">Daftar Pelanggan</h2>

        <div class="search-container">
            <form method="GET" style="display:inline;">
                <input type="text" id="search" placeholder="Cari..." class="search-box"
                value="<?php echo htmlspecialchars($search); ?>" onkeyup="searchData()">
            </form>
            <form method="GET" style="display:inline;">
                <button type="submit" name="add" value="true" class="btn-add">Tambah Pelanggan</button>
            </form>
        </div>



        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>No. Pelanggan</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $anggotaList->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($row['nama_anggota']); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['no_pelanggan']); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['telepon']); ?>
                    </td>
                    <td class="aksi">
                        <form method="GET" style="display:inline;">
                            <input type="hidden" name="edit_id" value="<?php echo $row['id_anggota']; ?>">
                            <button type="submit"><i class="fa-solid fa-pencil"></i></button>
                        </form>

                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_hapus" value="<?php echo $row['id_anggota']; ?>">
                            <button type="submit" name="hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Anggota -->
    <?php if (isset($_GET['add'])): ?>
    <div class="modal">
        <div class="modal-content">
            <a href="daftar-anggota.php" class="close">✖</a>
            <h3>Tambah Pelanggan</h3>
            <form method="POST">
                <input type="text" name="nama_anggota" placeholder="Nama" required>
                <input type="text" name="no_pelanggan" placeholder="No. Pelanggan" required>
                <input type="text" name="telepon" placeholder="Telepon" required>
                <button type="submit" name="submit">Tambah</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal Edit Anggota -->
    <?php if ($editData): ?>
    <div class="modal">
        <div class="modal-content">
            <a href="daftar-anggota.php" class="close">✖</a>
            <h3>Edit Pelanggan</h3>
            <form method="POST">
                <input type="hidden" name="id_edit" value="<?php echo $editData['id_anggota']; ?>">
                <input type="text" name="nama_anggota"
                    value="<?php echo htmlspecialchars($editData['nama_anggota']); ?>" required>
                <input type="text" name="no_pelanggan"
                    value="<?php echo htmlspecialchars($editData['no_pelanggan']); ?>" required>
                <input type="text" name="telepon" value="<?php echo htmlspecialchars($editData['telepon']); ?>"
                    required>
                <button type="submit" name="update">Update</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</body>

</html>