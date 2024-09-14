<?php
session_start();
require '../../private/function.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../src/css/indexAdmin.css">
    <style>
        .jumbotron {
            text-align: center;
            padding: 2rem;
            margin-top:2rem;
            margin-bottom: -2rem;
        }

        .jumbotron h1 {
            font-size: 3.5rem; 
            font-weight: bold;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }

        .jumbotron .btn-primary {
            margin-top: 1rem; 
            font-size: 1.5rem; 
            margin-right: 1rem;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="./peminjaman/read.php"><i class="fas fa-shopping-cart"></i> Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="./pengembalian/read.php"><i class="fas fa-undo"></i> Pengembalian</a></li>
            
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
    <b><?php echo isset($_SESSION['pustakawan']) ? $_SESSION['pustakawan'] : ''; ?></b>
    telah login sebagai
    <b><?php echo isset($_SESSION['level']) ? $_SESSION['level'] : ''; ?></b>
</div>
</div>

<main>
<div class="jumbotron">
        <h1 class="display-4">Hello, <?php echo $_SESSION['pustakawan']?>!</h1>
        <p class="lead">Cetak Laporan Terkait Peminjaman Di Perpustakaan Nasional</p>
</div>
<center>
    <a href="./laporan/bukuNot.php">
    <button type="button" class="btn btn-outline-danger mt-5 me-4" id="buttonlaporan">
        <i class="fa-solid fa-file-pdf fa-6x"></i>
        <h5 class="mt-2">Laporan Buku Yang Belum Dikembalikan</h5></button>
    </a>
</center>
</main>
<footer>
    <center>
    <p class="footer mt-3">&copy; 2024 Perpustakaan Digital. All rights reserved.</p>
    </center>
</footer>
</body>
</html>