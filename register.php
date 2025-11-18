<?php
include "koneksi.php";

$success = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($fullname) || empty($email) || empty($password)) {
        $error = "Semua field wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter.";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error = "Email sudah digunakan. Silakan login.";
        } else {
            // Simpan user baru
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$fullname, $email, $hashedPassword])) {
                $success = "Registrasi berhasil! Silakan <a href='login.php'>Login</a>.";
            } else {
                $error = "Terjadi kesalahan saat registrasi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-center">Registrasi ✨</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Daftar</button>
                    </form>

                    <div class="mt-3 text-center">
                        Sudah punya akun? <a href="login.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
