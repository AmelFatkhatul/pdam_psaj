<!-- tambah_saldo.php -->

<?php
include "../koneksi.php";
session_start();

if (!isset($_SESSION['username_user'])) {
    exit("Silakan login terlebih dahulu.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_user = $_SESSION['username_user'];
    $amount = intval($_POST['amount']);
    $payment_method = $_POST['payment_method'];

    // Validasi jumlah top-up tidak boleh negatif atau nol
    if ($amount <= 0) {
        $_SESSION['referral_code'] = "Jumlah top-up tidak valid!";
        header("Location: ../dashboard-user/user.php");
        exit();
    }

    // Ambil id_user dari username_user
    $query_user = "SELECT username_user, saldo FROM login_users WHERE username_user = '$username_user'";
    $result_user = $conn->query($query_user);

    if ($result_user->num_rows > 0) {
        $row = $result_user->fetch_assoc();
        $username_user = $row['username_user'];
        $saldo_sekarang = $row['saldo'];

        // Generate kode referral unik (8 karakter)
        $referral_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        // Simpan transaksi top-up ke database
        $sql = "INSERT INTO topup (username_user, amount, payment_method, referral_code) 
                VALUES ('$username_user', '$amount', '$payment_method', '$referral_code')";

        if ($conn->query($sql) === TRUE) {
            // Update saldo pengguna
            $saldo_baru = $saldo_sekarang + $amount;
            $update_saldo = "UPDATE login_users SET saldo = '$saldo_baru' WHERE username_user = '$username_user'";
            $conn->query($update_saldo);

            $_SESSION['referral_code'] = $referral_code; // Simpan kode referral di session
        } else {
            $_SESSION['referral_code'] = "Error: " . $conn->error; // Simpan error di session
        }
    } else {
        $_SESSION['referral_code'] = "User tidak ditemukan!";
    }

    // Redirect kembali ke halaman form
    header("Location: ../dashboard-user/user.php");
    exit();
}
?>
