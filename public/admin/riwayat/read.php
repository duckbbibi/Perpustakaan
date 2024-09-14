<?php
session_start();
require '../../../private/function.php';

$dataperhalaman = 25;
$totaldata = count(connect("SELECT * FROM t_riwayat"));
$jumlahhalaman = ceil($totaldata / $dataperhalaman);

if (isset($_GET['halaman'])) {
    $halamanberapa = $_GET['halaman'];
} else {
    $halamanberapa = 1;
}

$awaldata = ($dataperhalaman * $halamanberapa) - $dataperhalaman;
$query = "SELECT t_riwayat.*, t_admin.f_nama AS admin_name 
          FROM t_riwayat 
          JOIN t_admin ON t_riwayat.f_idadmin = t_admin.f_id 
          ORDER BY f_id ASC
          LIMIT $awaldata, $dataperhalaman";
$result = connect($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            padding: 15px;
        }
        .nav{
            justify-content: center;
        }
    </style>
</head>
<body>
<div class="shadow p-3 rounded">
<nav class="navbar navbar-expand-lg" id="navbar">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <a href="../index.php">
            <img src="../../../src/images/logo.png" alt="Logo" class="logo_login mt-2" width="200px">
            </a>
            <ul class="navbar-nav ms-auto mb-2 me-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../kategori/read.php"><i class="fas fa-book"></i> Kategori Buku</a></li>
                <li class="nav-item ">
                    <a class="nav-link" href="../buku/read.php" aria-expanded="false">
                        <i class="fas fa-book-open"></i> Koleksi Buku
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="../peminjaman/read.php"><i class="fas fa-shopping-cart"></i> Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="../pengembalian/read.php"><i class="fas fa-undo"></i> Pengembalian</a></li>
                <li class="nav-item"><a class="nav-link" href="./read.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</a></li>

                <li class="nav-item dropdown" id="active">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="far fa-user"></i> Pengguna
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="anggotaDropdown">
                        <li><a class="dropdown-item" href="../user/admin.php">Admin</a></li>
                        <li><a class="dropdown-item" href="../user/anggota.php">Anggota</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="../index.php">
                        <i class="fa-solid fa-right-from-bracket" style="color: #000000;"></i>
                        Log-Out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<hr>
<!-- <nav class="navbar">
  <div class="container-fluid">
  <a class="navbar-brand"></a>    
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" name="key" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
</nav> -->
</div>

<main>
<div class="gap-2">
<div class="p-4">

  <div class="p-2 ">
    <div class="card mt-5" id="card">
    <h5 class="card-header" style=" background-color: #072448; color:#ffffff;">Riwayat Aktivitas Perpustakaan</h5>
    <div class="card-body" id="card1">

    
    <table class="table table-striped ms-auto ">
          <tr class="text-center" id="th" >
              <th>No</th>
              <th>Tanggal</th>
              <th>Username Admin</th>
              <th>Aktivitas</th>
          </tr>

          <?php
        $row_number = 1;
        foreach($result as $r):
        ?>
                <tr>
                <td><?= $row_number; ?></td>
                <td> <?php echo $r['f_tanggalriwayat'] ?> </td>
                <td> <?php echo $r['admin_name'] ?> </td>
                <td> <?php echo $r['f_catatan'] ?> </td>
                <?php
        $row_number++;
        endforeach;
        ?>
      </table>

    </div>
    </div>
        <nav class="nav" aria-label="Page navigation example">
                <ul class="pagination mt-3">
        <?php for ($i = 1; $i <= $jumlahhalaman; $i++): ?>
            <li class="page-item"><a class="page-link" href="?halaman=<?=$i?>" ><?=$i?></a></li>
        <?php endfor; ?>
                </ul>
        </nav>
    </div>
  </div>
</div>
</main>

</body>
</html>