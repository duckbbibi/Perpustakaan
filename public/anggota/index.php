<?php
session_start();
require '../../private/function.php';

$ida = $_SESSION['id'];

// konfigurasi pagination
$jumlahdataperhalaman = 5;
$totaldata = count(connect("SELECT t_anggota.f_nama AS f_namaanggota, t_kategori.f_kategori, t_peminjaman.f_id, t_peminjaman.f_tanggalpeminjaman, t_buku.f_judul, t_admin.f_nama AS f_namaadmin, t_detailbuku.f_id as f_iddetb FROM t_peminjaman INNER JOIN t_admin ON t_peminjaman.f_idadmin=t_admin.f_id INNER JOIN t_anggota ON t_peminjaman.f_idanggota=t_anggota.f_id INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id=t_detailpeminjaman.f_idpeminjaman INNER JOIN t_detailbuku ON t_detailpeminjaman.f_iddetailbuku=t_detailbuku.f_id INNER JOIN t_buku ON t_detailbuku.f_idbuku=t_buku.f_id INNER JOIN t_kategori ON t_buku.f_idkategori = t_kategori.f_id WHERE t_anggota.f_id = $ida ORDER BY t_peminjaman.f_id ASC"));
$jumlahhalaman = ceil ($totaldata / $jumlahdataperhalaman);

if (isset($_GET["halaman"])) {
    $halamanberapa = $_GET["halaman"];
    $awaldata = ($halamanberapa * $jumlahdataperhalaman) - $jumlahdataperhalaman;
} else {
    $awaldata = 0;
}

$peminjaman = connect(
    "SELECT t_kategori.f_kategori as namakategori, t_anggota.f_nama AS namaanggota, t_peminjaman.f_id, t_detailpeminjaman.f_tanggalkembali, t_buku.f_judul, t_admin.f_nama AS namaadmin,t_detailpeminjaman.f_id as idpengembalian, t_detailbuku.f_id as iddetailbuku,t_peminjaman.f_tanggalpeminjaman
    FROM t_peminjaman
    INNER JOIN t_admin ON t_peminjaman.f_idadmin=t_admin.f_id
    INNER JOIN t_anggota ON t_peminjaman.f_idanggota=t_anggota.f_id
    INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id=t_detailpeminjaman.f_idpeminjaman
    INNER JOIN t_detailbuku ON t_detailpeminjaman.f_iddetailbuku=t_detailbuku.f_id
    INNER JOIN t_buku ON t_detailbuku.f_idbuku=t_buku.f_id 
    INNER JOIN t_kategori ON t_buku.f_idkategori = t_kategori.f_id 
    WHERE t_anggota.f_id = $ida
    LIMIT $awaldata, $jumlahdataperhalaman"
);

$no = 1 + $awaldata;


function hasBookToReturn($peminjaman) {
    foreach ($peminjaman as $row) {
        if ($row['f_tanggalkembali'] == '0000-00-00') {
            $tanggalPeminjaman = strtotime($row['f_tanggalpeminjaman']);
            $today = time();
            $diff = $today - $tanggalPeminjaman;
            $daysPassed = floor($diff / (60 * 60 * 24)); // Convert seconds to days

            if ($daysPassed > 3) {
                return true;
            }
        }
    }
    return false;
}  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            padding: 20px;
            text-transform: capitalize;
        }
        nav{
            justify-content: center;
            margin-top: 10px;
        }
        .table-container {
            overflow-x: auto;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg shadow-lg" id="navbar">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <a href="../index.php">
            <img src="../../src/images/logo.png" alt="Logo" class="logo_login" width="200px">
            </a>
            <ul class="navbar-nav ms-auto mb-2 me-2 mb-lg-0">
                <li class="nav-item mt-2">
                <p><b><?php echo $_SESSION['anggota']; ?></b> telah login sebagai <b>Anggota</b></p>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" aria-current="page" href="../../private/logout.php">
                        <i class="fa-solid fa-right-from-bracket" style="color: #000000;"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">Selamat Menikmati Buku Pinjamanmu!</h4>
  <p>Kamu dapat meminjam ribuan buku dari berbagai genre untuk dinikmati, Pinjam Buku Sekarang Juga
  </p>
  <hr>
  <p class="mb-0"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>
  <b>
  Semua buku memiliki tenggat selama tiga hari, pastikan kamu mengembalikan tepat waktu
  </b>
    </p>
</div>

<main>
<div class="gap-2">
<div class="p-4">

  <div class="p-2 ">
    <div class="card mt-5" id="card">
    <h5 class="card-header" style=" background-color: #072448; color:#ffffff;">Daftar Peminjaman Buku</h5>
    <div class="card-body" id="card1">

    <div class="table-container">
    <table class="table table-bordered w-80">
                <thead>
                    <tr>    
                        <th><center>No.</center></th>
                        <th><center>Judul Buku</center></th>
                        <th><center>Kategori Buku</center></th>
                        <th><center>Admin </center></th>
                        <th><center>Anggota </center></th>
                        <th><center>Tanggal Peminjaman</center></th>
                        <th><center>Tanggal Pengembalian</center></th>
                        <th><center>Status Buku</center></th>
                        <th><center>Peringatan</center></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($peminjaman as $row): ?>
                        <tr>
                            <td><center><?php echo $no++ ?></center></td>
                            <td><center><?php echo $row["f_judul"]; ?></center></td>
                            <td><center><?php echo $row["namakategori"]; ?></center></td>
                            <td><center><?php echo $row["namaadmin"]; ?></center></td>
                            <td><center><?php echo $row["namaanggota"]; ?></center></td>
                            <td><center><?php echo $row["f_tanggalpeminjaman"]; ?></center></td>
                            <td>
                                <center>
                                    <?php
                                        if ($row['f_tanggalkembali'] == '0000-00-00'){
                                            echo "";
                                        } else{
                                            echo $row['f_tanggalkembali'];
                                        }
                                    ?>
                                </center>
                            </td>
                            <td>
                                <center>
                                    <?php
                                    if ($row['f_tanggalkembali'] == '0000-00-00'){
                                        // Book hasn't been returned
                                        echo 'Belum dikembalikan';
                                    } else {
                                        // Book has been returned
                                        $tanggalPeminjaman = strtotime($row['f_tanggalpeminjaman']);
                                        $tanggalPengembalian = strtotime($row['f_tanggalkembali']);
                                        $diff = $tanggalPengembalian - $tanggalPeminjaman;
                                        $daysPassed = floor($diff / (60 * 60 * 24)); // Convert seconds to days

                                        if ($daysPassed > 3) {
                                            // Book has been returned late (more than 3 days)
                                            echo '<div>Terlambat dikembalikan (lebih dari 3 hari)</div>';
                                        } else {
                                            // Book has been returned within 3 days
                                            echo '<div>Sudah dikembalikan</div>';
                                        }
                                    }
                                    ?>
                                </center>
                            </td>

                            <td>
                                <center>
                                    <?php
                                        if ($row['f_tanggalkembali'] == '0000-00-00'){
                                            // Book hasn't been returned
                                            $tanggalPeminjaman = strtotime($row['f_tanggalpeminjaman']);
                                            $today = time();
                                            $diff = $today - $tanggalPeminjaman;
                                            $daysPassed = floor($diff / (60 * 60 * 24)); // Convert seconds to days

                                            if ($daysPassed > 3) {
                                                // Book has been borrowed for more than 3 days
                                                echo '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Buku belum dikembalikan (lebih dari 3 hari)</div>';
                                            } else {
                                                // Book has been borrowed but not returned yet
                                                echo '<div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Buku belum dikembalikan</div>';
                                            }
                                        } else {
                                            // Book has been returned
                                            echo '<div class="alert alert-success" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> Buku sudah dikembalikan </div>';
                                        }
                                    ?>
                                </center>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>
    </div>
    </div>
  </div>
  </div>
</div>
</main>
</body>
</html>

