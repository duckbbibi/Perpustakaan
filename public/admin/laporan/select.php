<?php
session_start();
require '../function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>

    <!-- link -->
    <script src="https://kit.fontawesome.com/7d803913db.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --background: linear-gradient(rgba(4,9,30,0.5), rgba(4,9,30,0.5)),url('../image/background-3.jpg');
            --navbar-width: 256px;
            --navbar-width-min: 80px;
            --navbar-dark-primary: #f2f0f0; /* bg nav */
            --navbar-dark-secondary: #18283b; /* bg footer */
            --navbar-light-primary: #f5f6fa; /* color footer */
            --navbar-light-secondary: #18283b; /* color text */
        }

        body {
            margin: 0;
            background: var(--background);
        }

        .title h1 {
            font-family: 'Poppins', sans-serif;
            text-align: left;
            align-items: center;
            margin-top: 10px;
            margin-left: 300px;
            color: #fff;
        }

        .title h4 {
            color: #fff;
            margin-left: 300px;
            margin-bottom: 30px;
        }

        #nav-bar {
            position: absolute;
            left: 1vw;
            top: 1vw;
            height: calc(100% - 2vw);
            background: var(--navbar-dark-primary);
            border-radius: 16px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            display: flex;
            flex-direction: column;
            color: var(--navbar-light-primary);
            overflow: hidden;
            user-select: none;
            box-shadow: 0 .4rem .8rem #0005;
        }

        #nav-bar hr {
            margin: 0;
            position: relative;
            left: 16px;
            width: calc(100% - 32px);
            border: none;
            border-top: solid 1px var(--navbar-dark-secondary);
        }

        #nav-bar a {
            color: inherit;
            text-decoration: inherit;
        }

        #nav-bar input[type=checkbox] {
            display: none;
        }

        #nav-header {
            position: relative;
            width: var(--navbar-width);
            left: 16px;
            width: calc(var(--navbar-width) - 16px);
            min-height: 80px;
            background: var(--navbar-dark-primary);
            border-radius: 16px;
            z-index: 2;
            display: flex;
            align-items: center;
            transition: width 0.2s;
        }

        #nav-header hr {
            position: absolute;
            bottom: 0;
        }

        #nav-header img {
            width: 200px;
            height: auto;
            margin-left: 15px;
        }

        #nav-title {
            font-size: 1.5rem;
            transition: opacity 1s;
        }

        #nav-content {
            margin: -16px 0;
            padding: 16px 0;
            position: relative;
            flex: 1;
            overflow-x: hidden;
            transition: width 0.2s;
        }

        #nav-content a {
            width: 100%;
        }

        #nav-content-highlight {
            position: absolute;
            left: 16px;
            top: -70px;
            width: 90%;
            height: 54px;
            background: var(--background);
            background-attachment: fixed;
            border-radius: 16px;
            transition: top 0.2s;
        }

        .nav-button {
            position: relative;
            margin-left: 16px;
            height: 54px;
            display: flex;
            align-items: center;
            color: var(--navbar-light-secondary);
            direction: ltr;
            cursor: pointer;
            z-index: 1;
            transition: color 0.2s;
        }

        .nav-button span {
            transition: opacity 1s;
        }

        .nav-button .fas {
            transition: min-width 0.2s;
        }

        .nav-button:hover {
            color: var(--navbar-light-primary);
        }

        .nav-button:nth-of-type(1):hover ~ #nav-content-highlight {
            top: 16px;
        }
        
        .nav-button:nth-of-type(2):hover ~ #nav-content-highlight {
            top: 70px;
        }

        .nav-button:nth-of-type(3):hover ~ #nav-content-highlight {
            top: 124px;
        }

        .nav-button:nth-of-type(4):hover ~ #nav-content-highlight {
            top: 178px;
        }

        .nav-button:nth-of-type(5):hover ~ #nav-content-highlight {
            top: 232px;
        }

        .nav-button:nth-of-type(6):hover ~ #nav-content-highlight {
            top: 286px;
        }

        .nav-button:nth-of-type(7):hover ~ #nav-content-highlight {
            top: 340px;
        }

        .nav-button:nth-of-type(8):hover ~ #nav-content-highlight {
            top: 394px;
        }

        #nav-bar .fas {
            min-width: 3rem;
            text-align: center;
        }

        #nav-footer {
            position: relative;
            width: var(--navbar-width);
            height: 54px;
            background: var(--navbar-dark-secondary);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            z-index: 2;
            transition: width 0.2s, height 0.2s;
        }

        #nav-footer-heading {
            position: relative;
            width: 100%;
            height: 54px;
            display: flex;
            align-items: center;
        }

        #nav-footer-avatar {
            position: relative;
            margin: 11px 0 11px 16px;
            left: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            transform: translate(0);
            transition: 0.2s;
        }

        #nav-footer-avatar img {
            height: 100%;
        }

        #nav-footer-titlebox {
            position: relative;
            margin-left: 10px;
            width: 10px;
            display: flex;
            flex-direction: column;
            transition: opacity 1s;
        }

        #nav-footer-subtitle {
            color: var(--navbar-light-primary);
            font-size: 0.6rem;
        } 

        .tambah {
            text-align: center;
            margin-top: 2px;
            margin-right: 30px;
        }

        .tambah a{
            background-color: #18283b;
            color: var(--navbar-light-primary);
            padding: 5px 15px;
            text-decoration: none;
            border-radius: 7px;
        }

        .tambah a:hover {
            background-color: #253d59;
            color: #d5d1defe;
        }

        /* kotak 1 */
        main.table {
            width: 26vw;
            height: 25vh;
            background-color: #fff5;
            backdrop-filter: blur(7px);
            box-shadow: 0 .4rem .8rem #0005;
            border-radius: .8rem;
            overflow: hidden;
            margin-left: 300px;
            margin-top: 15px;
        }

        .tbl-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff4;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .tbl-head h1 {
            margin: 0;
            padding: 2rem;
            font-size: 20px;
            margin-left: 20px;
        }

        /* .show1 {
            margin-left: 20px;
        }

        .show1 .fa-eye {
            background-color: #fff5;
        } */

        /* kotak 2 */
        main.table2 {
            width: 26vw;
            height: 25vh;
            background-color: #fff5;
            backdrop-filter: blur(7px);
            box-shadow: 0 .4rem .8rem #0005;
            border-radius: .8rem;
            overflow: hidden;
            margin-left: 685px;
            margin-top: -175px;
            position: absolute;
        }

        .tbl-head2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff4;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .tbl-head2 h1 {
            margin: 0;
            padding: 2rem;
            font-size: 20px;
            margin-left: 20px;
        }

        /* kotak 3 */
        main.table3 {
            width: 26vw;
            height: 21vh;
            background-color: #fff5;
            backdrop-filter: blur(7px);
            box-shadow: 0 .4rem .8rem #0005;
            border-radius: .8rem;
            overflow: hidden;
            margin-left: 300px;
            margin-top: 10px;
            position: absolute;
        }

        .tbl-head3 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff4;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .tbl-head3 h1 {
            margin: 0;
            padding: 2rem;
            font-size: 20px;
            margin-left: 20px;
        }

        /* kotak 4 */
        main.table4 {
            width: 26vw;
            height: 21vh;
            background-color: #fff5;
            backdrop-filter: blur(7px);
            box-shadow: 0 .4rem .8rem #0005;
            border-radius: .8rem;
            overflow: hidden;
            margin-left: 685px;
            margin-top: 10px;
            position: absolute;
        }

        .tbl-head4 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff4;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .tbl-head4 h1 {
            margin: 0;
            padding: 2rem;
            font-size: 20px;
            margin-left: 20px;
        }
    </style>
</head>
<body>
<div id="nav-bar">
        <input id="nav-toggle" type="checkbox"/>
        <div id="nav-header"><a id="nav-title" href="../hal_admin.php"><img src="../image/logo1.png" alt=""></a>
            <hr/>
        </div>
        <div id="nav-content">
            <div class="nav-button"><a href="../kategori/select.php"><i class="fas fa-list-ul"></i><span>Kategori</span></a></div>
            <div class="nav-button"><a href="../buku/select.php"><i class="fas fa-book"></i><span>Buku</span></a></div>
            <div class="nav-button"><a href="../peminjaman/select.php"><i class="fas fa-history"></i><span>Peminjaman</span></a></div>
            <div class="nav-button"><a href="../pengembalian/select.php"><i class="fas fa-exchange-alt"></i><span>Pengembalian</span></a></div>
            <hr/>
            <div class="nav-button"><a href="../pengguna/select.php"><i class="fas fa-user"></i><span>Pengguna</span></a></div>
            <div class="nav-button"><a href="../anggota/anggota.php"><i class="fas fa-users"></i><span>Anggota</span></a></div>
            <hr/>
            <div class="nav-button"><a href="../laporan/select.php"><i class="fas fa-file-pdf"></i><span>Laporan</span></a></div>
            <div class="nav-button"><a href="../logout.php"><i class="fas fa-right-from-bracket"></i><span>Keluar</span></a></div>
            <div id="nav-content-highlight"></div>
        </div>
        <input id="nav-footer-toggle" type="checkbox"/>
        <div id="nav-footer">
            <div id="nav-footer-heading">
            <div id="nav-footer-avatar"><img src="../image/profil.png"/></div>
            <div id="nav-footer-titlebox"><a id="nav-footer-title"><?php echo $_SESSION['admin']; ?></b></a><span id="nav-footer-subtitle">Admin</span></div>
            </div>
        </div>
    </div>

    <div class="title">
        <h1>Halo, <?php echo $_SESSION['admin']; ?>!</h1>
        <h4>Lihat laporan terkait J'Perpustakaan berikut.</h4>
    </div>

    <!-- kotak 1 -->
    <main class="table">
        <div class="tbl-head">
            <h1>Peminjaman Buku Terbanyak</h1>
        </div>
        <div class="show1">
            <a href="BukuTerbanyak.php"><i class="fa-solid fa-eye" style="background: none; font-size: 20px; margin-left: 160px; margin-right: 160px; margin-top: 5px;"></i></a>
        </div>
    </main>

    <!-- kotak 2 -->
    <main class="table2">
        <div class="tbl-head2">
            <h1>Anggota Belum Kembalikan Buku</h1>
        </div>
        <div class="show1">
            <a href="AnggotaNot.php"><i class="fa-solid fa-eye" style="color: #000; background: none; font-size: 20px; margin-left: 168px; margin-right: 168px; margin-top: 14px;"></i></a>
        </div>
    </main>

    <!-- kotak 3 -->
    <main class="table3">
        <div class="tbl-head3">
            <h1>Buku Belum Dikembalikan</h1>
        </div>
        <div class="show3">
            <a href="BukuNot.php"><i class="fa-solid fa-eye" style="color: #000; background: none; font-size: 20px; margin-left: 168px; margin-right: 168px; margin-top: 14px;"></i></a>
        </div>
    </main>

    <!-- kotak 4 -->
    <main class="table4">
        <div class="tbl-head4">
            <h1>Anggota Sering Meminjam</h1>
        </div>
        <div class="show">
            <a href="AnggotaTerbanyak.php"><i class="fa-solid fa-eye" style="color: #000; background: none; font-size: 20px; margin-left: 168px; margin-right: 168px; margin-top: 14px;"></i></a>
        </div>
    </main>

    <!-- link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>