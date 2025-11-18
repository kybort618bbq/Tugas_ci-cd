<?php
session_start();
include "koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Ambil data user berdasarkan email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Cek apakah akun terkunci
        if ($user['failed_attempts'] >= 3 && time() < $user['lock_time']) {
            $error = "Akun terkunci. Silakan coba lagi setelah 5 menit.";
        } else {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Reset percobaan gagal
                $stmt = $conn->prepare("UPDATE users SET failed_attempts=0, lock_time=0 WHERE email=?");
                $stmt->execute([$email]);

                // Buat session login
                $_SESSION['user_id']       = $user['id'];
                $_SESSION['fullname']      = $user['fullname'];
                $_SESSION['last_activity'] = time();

                header("Location: dashboard.php");
                exit();
            } else {
                // Tambah percobaan gagal
                $failed = $user['failed_attempts'] + 1;

                if ($failed >= 3) {
                    $lock_time = time() + 300; // 5 menit
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts=?, lock_time=? WHERE email=?");
                    $stmt->execute([$failed, $lock_time, $email]);
                    $error = "Akun terkunci selama 5 menit karena 3 kali gagal login.";
                } else {
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts=? WHERE email=?");
                    $stmt->execute([$failed, $email]);
                    $error = "Email atau password salah.";
                }
            }
        }
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-center">Login 🔐</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <div class="mt-3 text-center">
                        Belum punya akun? <a href="register.php">Registrasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
