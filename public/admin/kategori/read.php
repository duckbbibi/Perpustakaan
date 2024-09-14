<?php
session_start();
require '../../../private/function.php';
$datakategori = connect("SELECT * FROM t_kategori");

$dataperhalaman = 5;
    $totaldata = count(connect("SELECT * FROM t_kategori"));
    $jumlahhalaman = ceil($totaldata / $dataperhalaman);
  
    if (isset($_GET['halaman'])) {
      $halamanberapa = $_GET['halaman'];
    } else {
      $halamanberapa = 1;
    }
  
    $awaldata = ($dataperhalaman * $halamanberapa) - $dataperhalaman;
    $datakategori = connect("SELECT * FROM t_kategori ORDER BY f_id ASC LIMIT $awaldata, $dataperhalaman");

    if (isset($_POST['addkategori'])) {
        $admin_id = $_SESSION['id'];
        tambahKategori($admin_id);
    }
    if (isset($_GET['key'])) {
        $search_term = $_GET['key'];
        $datakategori = srckategori($_GET['key']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                <li class="nav-item"><a class="nav-link" href="./read.php"><i class="fas fa-book"></i> Kategori Buku</a></li>
                <li class="nav-item ">
                    <a class="nav-link" href="../buku/read.php" aria-expanded="false">
                        <i class="fas fa-book-open"></i> Koleksi Buku
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="../peminjaman/read.php"><i class="fas fa-shopping-cart"></i> Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="../pengembalian/read.php"><i class="fas fa-undo"></i> Pengembalian</a></li>
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
<nav class="navbar">
  <div class="container-fluid">
  <a class="navbar-brand"></a>    
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" name="key" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
</nav>
</div>

<main>
    <div class="d-md-flex flex-md-row gap-5 mb-5">
        <div class="p-4 col-md-4 mt-auto mb-auto">
            <div class="card mt-5 m-lg-4" id="card">
                <h5 class="card-header" style="background-color: #ffcb00; color:#ffffff;">Form Kategori Buku</h5>
                <div class="card-body" id="card1">
                    <div id="form-container position-absolute top-0 start-50 translate-middle">
                        <form method="post">
                            <div class="form-group">
                                <label for="kategori">Kategori Buku</label>
                                <input type="text" class="form-control" name="f_kategori" id="kategori" placeholder="Masukan Kategori Baru" required>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="f_tglinpt" value="<?= date("Y-m-d H:i:s"); ?>">
                                <input type="hidden" name="f_tglupdt" value="<?= date("Y-m-d H:i:s"); ?>">
                            </div>
                            <button type="submit" name="addkategori" class="btn btn-primary mt-2 float-end">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-2 col-md-7">
            <div class="card mt-5" id="card">
                <h5 class="card-header" style="background-color: #072448; color:#ffffff;">Daftar Kategori Buku</h5>
                <div class="card-body" id="card1">
                    <table class="table table-striped w-auto">
                        <tr class="text-center" id="th">
                            <th>No</th>
                            <th>Id Kategori</th>
                            <th style="width: 30%;">Kategori Buku</th>
                            <th style="width: 30%;">Tanggal Input</th>
                            <th style="width: 30%;">Tanggal Update</th>
                            <th style="width: 10%;" colspan="2">Aksi</th>
                        </tr>

                        <?php
                        $row_number = ($halamanberapa - 1) * $dataperhalaman + 1;
                        foreach($datakategori as $row):
                        ?>
                        <tr class="text-center">
                            <td><?= $row_number; ?></td>
                            <td><?= $row["f_id"]; ?></td>
                            <td><?= $row["f_kategori"]; ?></td>
                            <td><?= $row["f_tglinpt"]; ?></td>
                            <td><?= $row["f_tglupdt"]; ?></td>
                            <td>
                                <button type="button" class="btn btn-warning">
                                    <a href="update.php?id=<?= $row['f_id']; ?>">
                                        <i class="fa-solid fa-wand-magic-sparkles" style="color: #ffffff;"></i>
                                    </a>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger">
                                    <a href="./delete.php?id=<?= $row['f_id']; ?>">
                                        <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                    </a>
                                </button>
                            </td>
                        </tr>
                        <?php
                        $row_number++;
                        endforeach;
                        ?>
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
</main>


<footer>
    <center>
    <p class="footer">&copy; 2024 Perpustakaan Digital. All rights reserved.</p>
    </center>
</footer>
</body>
</html>