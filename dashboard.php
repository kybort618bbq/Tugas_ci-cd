<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Batas waktu idle = 5 menit (300 detik)
$timeout = 300;

// Cek apakah sudah melebihi batas waktu idle
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=session_expired");
    exit();
}

// Update waktu aktivitas terakhir
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4 text-center">
                    <h3 class="mb-3">Selamat Datang 🎉</h3>
                    <h4 class="text-primary"><?= htmlspecialchars($_SESSION['fullname']); ?></h4>
                    <p class="mt-3">Kamu berhasil login ke sistem 🚀</p>
                    
                    <div class="mt-4">
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
