<!-- user.php -->
<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['username_user'])) {
    header("Location: ../login/login_user.php");
    exit();
}

$username_user = $_SESSION['username_user'];

// Ambil saldo terbaru dari database
$query_saldo = "SELECT saldo FROM login_users WHERE username_user = '$username_user'";
$result_saldo = $conn->query($query_saldo);
$saldo = 0;
if ($result_saldo->num_rows > 0) {
    $row = $result_saldo->fetch_assoc();
    $saldo = $row['saldo'];
}

$referral_message = "";
if (isset($_SESSION['referral_code'])) {
    $referral_message = "Top-Up Berhasil! Kode Referral Anda: <b>" . $_SESSION['referral_code'] . "</b>";
    unset($_SESSION['referral_code']); // Hapus setelah ditampilkan
}



$username_user = $_SESSION['username_user'];

if (isset($_SESSION['pesan'])) {
    $status = $_SESSION['status'] == "success" ? "background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;" : "background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;";
    echo '<div style="padding: 10px; margin: 10px; '.$status.'">'.$_SESSION['pesan'].'</div>';

    // Hapus session pesan setelah ditampilkan
    unset($_SESSION['pesan']);
    unset($_SESSION['status']);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>AquaPay</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0px;
            padding: 0px;
            background-color: #f4f4f4;
            height: 100%;
            overflow: hidden;
        }

        .header {
            background-color: #0057b8;
            color: white;
            padding: 18px;
            text-align: center;
            font-size: 20px;
            position: relative;
        }

        .h3{
            text-align: center;
        }

        .logout-btn {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            color: #0057b8;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            border: 1px solid black;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #0057b8;
            color: white;
            border-color: white;
        }

        .container {
            padding: 20px;
            text-align: center;
            
        }

        .left-section {
            flex: 1;
            text-align: center;
        }

        .saldo-box {
            background-image: url('../img/pdam.png');
            background-size: cover;
            background-position: center;
            background-color: #0057b8;
            color: white;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            height: 150px;
        }

        .btnn-container {
            display: flex;
            gap: 5px;
            margin: 0px;
            justify-content: center;
        }

        .btnn {
            background-color: #0057b8;
            color: white;
            padding: 15px;
            margin: 10px;
            border-radius: 10px;
            display: inline-block;
            cursor: pointer;
            width: 200px;
            text-decoration: none;
            text-align: center;
        }

        .btn {
            width: 300px;
            height: 150px;
            background-color: #0057b8;
            color: white;
            border-radius: 10px;
            display: inline-block;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px;
        }

        .tanda {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px; /* Sesuaikan ukuran */
    height: 40px;
    background-color: #0057b8; /* Warna latar */
    color: white; /* Warna ikon */
    border-radius: 50%; /* Membuatnya bulat */
    text-decoration: none;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
    margin-right: 60px;
}

        .mascot {
            width: 350px;
            height: 350px;
        }

        /* Popup */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            display: none;
        }

        /* Menampilkan popup jika anchor dengan ID dituju */
        #popupTopup:target,
        #popupBayarTagihan:target,
        #popupEdukasi:target,
        #popupKeluhan:target,
        #popupInfoTagihan:target {
            display: block;
        }

        /* Tombol tutup */
        .close {
            display: block;
            text-align: right;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            color: rgb(0, 0, 0);
        }

        .popup h3 {
            margin-top: 0;
        }

        .popup input,
        .popup select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .popup button {
            width: 100%;
            padding: 10px;
            background-color: #0057b8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>


<script>
    document.querySelector("#popupBayarTagihan form").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah pengiriman form default

        let formData = new FormData(this);
        let hasilPesan = document.createElement("div");

        fetch("../process/update_status.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hasilPesan.style.padding = "10px";
            hasilPesan.style.marginTop = "10px";

            if (data.status === "success") {
                hasilPesan.style.backgroundColor = "#d4edda";
                hasilPesan.style.color = "#155724";
                hasilPesan.style.border = "1px solid #c3e6cb";
                
                // Perbarui saldo di saldo-box tanpa refresh halaman
                document.getElementById("saldo-box").innerText = data.saldo;
            } else {
                hasilPesan.style.backgroundColor = "#f8d7da";
                hasilPesan.style.color = "#721c24";
                hasilPesan.style.border = "1px solid #f5c6cb";
            }

            hasilPesan.innerText = data.message;
            this.appendChild(hasilPesan);
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
</script>

<body>
    <div class="header">
        AquaPay - Bersama Mengalirkan Kemudahan
        <a href="../login/logout_user.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
    </div>

    <div class="container">
    <div class="saldo-box">
    <span style="font-weight:bold; margin-left: 50px;">Hai <?php echo htmlspecialchars($username_user); ?>, saldo anda sekarang Rp <span id="saldo-box"><?php echo number_format($saldo, 0, ',', '.'); ?></span></span>
    <a href="#popupTopup" class="tanda"><i class="fa-solid fa-plus"></i></a>
</div>

        <div class="btnn-container">
            <div>
                <a href="#popupInfoTagihan" class="btn">Info Tagihan</a>
                <a href="#popupKeluhan" class="btn">Keluhan</a><br><br>
            </div>
            <div>
                <a href="#popupBayarTagihan" class="btn">Bayar Tagihan</a>
                <a href="#popupEdukasi" class="btn">Edukasi</a><br><br>
            </div>
            <div>
                <img src="../img/mascot.png" alt="Maskot AquaPay" class="mascot">
            </div>
        </div>
    </div>
    </div>

        <!-- Popup Tambah Saldo -->
        <div id="popupTopup" class="popup">
        <a href="#" class="close">✖</a>
        <h3 class="h3">Tambah Saldo</h3>
        <form action="../process/tambah_saldo.php" method="post">
            
            <label>Jumlah TopUp</label>
            <input type="number" name="amount" required>

            <label>Metode Pembayaran</label>
            <select name="payment_method">
                <option>Dana</option>
                <option>BRI</option>
                <option>Gopay</option>
                <option>OVO</option>
            </select>
            <button type="submit">Tambah</button>
        </form>
        <?php if ($referral_message) echo "<p style='margin-top:10px; color:green;'>$referral_message</p>"; ?>
    </div>
    
    <!-- Popup Bayar Tagihan -->
    <div id="popupBayarTagihan" class="popup">
        <a href="#" class="close">✖</a>
        <h3 class="h3">Bayar Tagihan</h3>
        <form action="../process/update_status.php" method="post">
            <label>Nomor Pelanggan</label>
            <input type="text" name="no_pelanggan" required>
            <label>Bulan Pembayaran</label>
            <input type="date" name="bulan_pem" required>
            <label>Nominal Pembayaran</label>
            <input type="number" name="total_pem" required>
            <button type="submit">Kirim</button>
        </form>
    </div>

    <!-- Pupup Keluhan -->
    <div id="popupKeluhan" class="popup">
        <a href="#" class="close">✖</a>
        <h3 class="h3">Keluhan</h3>
        <form action="../process/process_keluhan.php" method="post">

            <label>Nama</label>
            <input type="text" name="nama" required>

            <label>Nomor Pelanggan</label>
            <input type="text" name="nomor_pelanggan" required>

            <label>Keluhan Anda</label>
            <textarea name="keluhan" required></textarea>

            <button type="submit">Kirim</button>
        </form>
    </div>

    
    <!-- Popup Info Tagihan -->
    <div id="popupInfoTagihan" class="popup">
        <a href="#" class="close">✖</a>
        <h3 class="h3">Info Tagihan</h3>
        <form action="../process/info_tagihan.php" method="POST">
            <label for="no_pelanggan">Nomor Pelanggan:</label>
            <input type="text" id="no_pelanggan" name="no_pelanggan" required>
            <button type="button" onclick="cekTagihan()">Cek Tagihan</button>
        </form>
        <div id="hasilTagihan" style="margin-top: 15px; font-weight: bold;"></div>
    </div>

    <script>
        function cekTagihan() {
            let noPelanggan = document.getElementById("no_pelanggan").value;
            let hasilTagihan = document.getElementById("hasilTagihan");

            if (noPelanggan.trim() === "") {
                hasilTagihan.innerHTML = "Nomor pelanggan tidak boleh kosong!";
                return;
            }

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../process/info_tagihan.php", true); // Sesuaikan path
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        hasilTagihan.innerHTML = xhr.responseText;
                    } else {
                        hasilTagihan.innerHTML = "Terjadi kesalahan dalam memproses data.";
                    }
                }
            };
            xhr.send("no_pelanggan=" + encodeURIComponent(noPelanggan));
        }
    </script>
    </div>

    <!-- Popup Edukasi -->
    <div id="popupEdukasi" class="popup">
        <a href="#" class="close">✖</a>
        <h3 class="h3">Edukasi Pelanggan</h3>
        <p>1. Hemat Air💧<br>
	•	Matikan keran saat tidak digunakan.<br>
	•	Gunakan air bekas cucian sayur untuk menyiram tanaman.<br>
	•	Periksa kebocoran pipa di rumah secara rutin.<br><br>

2. Penyebab & Solusi Air Keruh🥤<br>
	•	Penyebab: Sedimen pipa tua, perbaikan jaringan, atau pencemaran sumber air.<br>
	•	Solusi: Diamkan air beberapa saat, gunakan filter, atau lapor ke PDAM jika berlanjut.<br><br>

3. Jika Air Mati, Apa yang Harus Dilakukan?🚰<br>
	•	Cek pengumuman PDAM untuk info gangguan.<br>
	•	Pastikan tidak ada kebocoran atau kran tertutup.<br>
	•	Gunakan air cadangan dengan bijak.<br><br>

4. Pentingnya Merawat Instalasi Air di Rumah🔧<br>
	•	Bersihkan toren air minimal sebulan sekali.<br>
	•	Gunakan pipa berkualitas untuk menghindari kebocoran.<br>
	•	Jangan buang minyak atau sampah ke saluran air.<br>
</p>
    </div>
</body>
</html>