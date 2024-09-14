<?php
session_start();
require '../../private/function.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="shadow p-3 mb-5 rounded">
    <nav class="navbar navbar-expand-lg" id="navbar">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <a href="../index.php">
            <img src="../../src/images/logo.png" alt="Logo" class="logo_login" width="200px">
            </a>
            <ul class="navbar-nav ms-auto mb-2 me-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="./kategori/read.php"><i class="fas fa-book"></i> Kategori Buku</a></li>
                <li class="nav-item ">
                    <a class="nav-link" href="./buku/read.php" aria-expanded="false">
                        <i class="fas fa-book-open"></i> Koleksi Buku
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="./peminjaman/read.php"><i class="fas fa-shopping-cart"></i> Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="./pengembalian/read.php"><i class="fas fa-undo"></i> Pengembalian</a></li>
                <li class="nav-item"><a class="nav-link" href="./riwayat/read.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</a></li>

                <li class="nav-item dropdown" id="active">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="far fa-user"></i> Pengguna
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="anggotaDropdown">
                        <li><a class="dropdown-item" href="./user/admin.php">Admin</a></li>
                        <li><a class="dropdown-item" href="./user/anggota.php">Anggota</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" aria-current="page" href="../../private/logout.php">
                        <i class="fa-solid fa-right-from-bracket" style="color: #000000;"></i>
                        Log-Out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<hr>
<div class="alert alert-info" role="alert">
<i class="fa-solid fa-user-tie"></i>
    <b><?php echo isset($_SESSION['admin']) ? $_SESSION['admin'] : ''; ?></b>
    telah login sebagai
    <b><?php echo isset($_SESSION['level']) ? $_SESSION['level'] : ''; ?></b>
</div>
</div>

<main>
    <h2>Lihat Laporan Terkait Peminjaman Buku Sekarang</h2>
    <center>
        <a href="./laporan/bukuNot.php">
        <button type="button" class="btn btn-outline-danger mt-5 me-4" id="buttonlaporan">
            <i class="fa-solid fa-file-pdf fa-6x"></i>
            <h5 class="mt-2">Laporan Buku Yang Belum Dikembalikan</h5></button>
        </a>
        <a href="./laporan/AnggotaNot.php">
        <button type="button" class="btn btn-outline-warning mt-5 me-4" id="buttonlaporan">
        <i class="fa-solid fa-file-pdf fa-6x"></i>
        <h5 class="mt-2"> Laporan Anggota Yang Belum Mengembalikan Buku</h5></button>
        </a>
        <a href="./laporan/BukuTerbanyak.php">
        <button type="button" class="btn btn-outline-success mt-5 me-4" id="buttonlaporan">
        <i class="fa-solid fa-file-pdf fa-6x"></i>
        <h5 class="mt-2">Laporan Buku Yang Paling Banyak Dipinjam</h5></button>
        </a>
        <a href="./laporan/AnggotaTerbanyak.php">
        <button type="button" class="btn btn-outline-info mt-5 me-4" id="buttonlaporan">
        <i class="fa-solid fa-file-pdf fa-6x"></i>
        <h5 class="mt-2">Laporan Anggota Yang Paling Banyak Meminjam</h5></button>
        </a>
    </center>
</main>

<footer>
    <center>
    <p class="footer fixed-bottom">&copy; 2024 Perpustakaan Digital. All rights reserved.</p>
    </center>
</footer>
</body>
</html>