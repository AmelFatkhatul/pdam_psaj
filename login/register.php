<!-- register.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AquaPay</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }

        .left {
            width: 50%;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .left img {
            width: 200px;
        }

        .left p {
            font-weight: bold;
            color: #0057b8;
            text-align: center;
        }

        .right {
            width: 50%;
            background: #0057b8;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 350px;
            text-align: center;
        }

        .login-box h2 {
            color: #0057b8;
        }

        .input-box {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background: #0057b8;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background: #004494;
        }

        .register-link {
            margin-top: 10px;
            display: block;
        }
    </style>
</head>

<body>
    <div class="left">
        <img src="../img/air.png" alt="Maskot AquaPay">
        <p>Bersama Mengalirkan Kemudahan, <br> Transaksi Aman, Hidup Nyaman</p>
    </div>
    <div class="right">
        <div class="login-box">
            <h2>AquaPay</h2>
            <form action="../login/register_process.php" method="post">
                <input type="text" name="email_user" class="input-box" placeholder="Email" required>
                <input type="text" name="username_user" class="input-box" placeholder="Username" required>
                <input type="password" name="password_user" class="input-box" placeholder="Password" required>
                <button type="submit" class="btn">Daftar</button>
            </form>
            <p class="register-link">Sudah punya akun? <a href="../login/login_user.php">Masuk sekarang</a></p>
        </div>
    </div>
    </div>
</body>

</html>