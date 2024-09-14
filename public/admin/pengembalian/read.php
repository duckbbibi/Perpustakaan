<?php
session_start();
require '../../../private/function.php';

$dataperhalaman = 5;
$totaldata = count(connect("SELECT f_id FROM t_detailpeminjaman"));
$jumlahhalaman = ceil($totaldata / $dataperhalaman);

if (isset($_GET['halaman'])) {
    $halamanberapa = $_GET['halaman'];
} else {
    $halamanberapa = 1;
}

$awaldata = ($dataperhalaman * $halamanberapa) - $dataperhalaman;

$sql = "SELECT t_anggota.f_nama AS namaanggota, t_peminjaman.f_id, t_detailpeminjaman.f_tanggalkembali, t_buku.f_judul, t_admin.f_nama AS namaadmin,t_detailpeminjaman.f_id as idpengembalian, t_detailbuku.f_id as iddetailbuku,t_peminjaman.f_tanggalpeminjaman
    FROM t_peminjaman
    INNER JOIN t_admin ON t_peminjaman.f_idadmin=t_admin.f_id
    INNER JOIN t_anggota ON t_peminjaman.f_idanggota=t_anggota.f_id
    INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id=t_detailpeminjaman.f_idpeminjaman
    INNER JOIN t_detailbuku ON t_detailpeminjaman.f_iddetailbuku=t_detailbuku.f_id
    INNER JOIN t_buku ON t_detailbuku.f_idbuku=t_buku.f_id
    ORDER BY idpengembalian DESC LIMIT $awaldata, $dataperhalaman";

$result = mysqli_query($conn, $sql);
$no = 1 + $awaldata;
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
                <li class="nav-item"><a class="nav-link" href="./read.php"><i class="fas fa-undo"></i> Pengembalian</a></li>
                <li class="nav-item"><a class="nav-link" href="../riwayat/read.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</a></li>

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
    <h5 class="card-header" style=" background-color: #072448; color:#ffffff;">Daftar Peminjaman Buku</h5>
    <div class="card-body" id="card1">

    <div class="table-container">
    <table class="table table-striped ms-auto ">
          <tr class="text-center" id="th" >
              <th>No</th>
              <th>Id Peminjaman</th>
              <th>user</th>
              <th>Buku</th>
              <th>Tanggal Pengembalian</th>
              <th>Tanggal Peminjaman</th>
              <th>Admin</th>
              <th>Status</th>
              <!-- <th>Delete</th> -->
          </tr>

          <?php
        if (!empty($result)) { ?>
            <?php foreach ($result as $r) : ?>
                <tr>
                    <td><center> <?php echo $no++ ?> </center></td>
                    <td><center> <?php echo $r['f_id'] ?> </center></td>
                    <td><center> <?php echo $r['namaanggota'] ?> </center></td>
                    <td><center> <?php echo $r['f_judul'] ?> </center></td>
                    <td><center> <?php
                            if ($r['f_tanggalkembali'] == '0000-00-00') {
                                echo "Belum Kembali";
                            } else {
                                echo $r['f_tanggalkembali'];
                            } ?> </td></center>
                    <td><center> <?php echo $r['f_tanggalpeminjaman'] ?> </center></td>
                    <td><center> <?php echo $r['namaadmin'] ?> </center></td>
                    <td><center>
                    <?php
                    if ($r['f_tanggalkembali'] == '0000-00-00') {
                      echo "<a href='./update.php?f=pengembalian&m=update&id=" . $r['idpengembalian'] . 
                      "&iddetailbuku=" . $r['iddetailbuku'] . "'>
                      <button type='button' class= 'btn btn-outline-danger'>Belum DiKembalikan</button></a>";
                    } else {
                      echo "<button type='button' class='btn btn-outline-success'>Sudah DiKembalikan</button>";
                    }
                    ?>
                    </center>
                  </td>
                  <td>
                  <!-- <center>
                    <input type="hidden" name="f_id">
                    <button type="submit" class="btn btn-danger">
                    <a href="./delete.php?id=<?= $r['idpengembalian']; ?>">
                    <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                    </button>
                    </center> -->
                  </td>
            <?php endforeach ?>
        <?php } ?>
      </table>

    </div>
    </div>
        <nav class="nav" aria-label="Page navigation example">
                <ul class="pagination">
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