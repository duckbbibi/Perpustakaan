<?php
session_start();
require '../../../private/function.php';

$datakategori = connect("SELECT * FROM t_kategori");

$dataperhalaman = 5;
$totaldata = count(connect("SELECT * FROM t_buku"));
$jumlahhalaman = ceil($totaldata / $dataperhalaman);

if (isset($_GET['halaman'])) {
    $halamanberapa = $_GET['halaman'];
} else {
    $halamanberapa = 1;
}

$awaldata = ($dataperhalaman * $halamanberapa) - $dataperhalaman;

$sql = "SELECT DISTINCT t_buku.f_id as f_id, t_kategori.f_kategori as f_kategori, f_judul, f_pengarang, f_gambar, f_penerbit, f_deskripsi, f_tglinput, f_tglupdate
        FROM t_buku 
        INNER JOIN t_kategori ON t_buku.f_idkategori = t_kategori.f_id 
        LEFT JOIN t_detailbuku ON t_buku.f_id = t_detailbuku.f_idbuku 
        ORDER BY t_buku.f_id ASC
        LIMIT $awaldata, $dataperhalaman";


$result = mysqli_query($conn, $sql);

$no = 1 + $awaldata;
if (isset($_POST['addBuku'])) {
  $admin_id = $_SESSION['id'];
    tambahBuku($admin_id);
    if (mysqli_affected_rows($conn) > 0) {
        echo "<script>
                alert('Berhasil menambahkan buku');
                document.location.href ='./read.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan buku');
                document.location.href ='./read.php';
              </script>";
    };
}
if (isset($_GET['key'])) {
    $search_term = $_GET['key'];
    $result = srcbuku($_GET['key']);
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
<div class="shadow p-3 mb-5 rounded">
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="../buku/read.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-book-open"></i> Koleksi Buku
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="anggotaDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#tambahBukuModal">Tambah Buku</a></li>
                    </ul>
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

<table class="table table-striped table-hover table-bordered mt-5 w-auto">
          <tr class="text-center" id="th" >
              <th style="background-color: #ff6150">No</th>
              <th style="background-color: #ff6150">Id Buku</th>
              <th style="background-color: #ff6150">Kategori Buku</th>
              <th style="background-color: #ff6150">Judul Buku</th>
              <th style="background-color: #ff6150">Cover</th>
              <th style="background-color: #ff6150">Pengarang</th>
              <th style="background-color: #ff6150">Penerbit</th>
              <th style="background-color: #ff6150">Deskripsi Buku</th>
              <th style="background-color: #ff6150">Tanggal Input</th>
              <th style="background-color: #ff6150">Tanggal Update</th>
              <th style="background-color: #ff6150">Stock Buku</th>
              <th style="background-color: #ff6150" colspan="2">Aksi</th>
          </tr>

          <?php foreach ($result as $row) : ?>
    <tr class="text-center">
        <td><?php echo $no++ ?></td>
        <td><?= $row["f_id"]; ?></td>
        <td><?= $row["f_kategori"]; ?></td>
        <td><?= $row["f_judul"]; ?></td>
        <td><img src="../gambar/<?= $row["f_gambar"]; ?>" style="width: 14rem;" alt="Cover Image"></td>
        <td><?= $row["f_pengarang"]; ?></td>
        <td><?= $row["f_penerbit"]; ?></td>
        <td  style="width: 20rem;"><?= $row["f_deskripsi"]; ?></td>
        <td><?= $row["f_tglinput"]; ?></td>
        <td><?= $row["f_tglupdate"]; ?></td>
        <td>
            <?php
            $eksemplar = connect("SELECT * FROM t_detailbuku WHERE f_status='Tersedia' AND f_idbuku = " . $row['f_id'] . "");
            ?>
            <nav aria-label="stock" class="stock">
                <ul class="pagination">
                    <!-- <li class="page-item"><a class="page-link" href="?f=buku&m=select&idbuku=<?php echo $row['f_id'] ?>&updatestock=kurang">-</a></li> -->
                    <li class="page-item"><a class="page-link" href="#"><?php echo count($eksemplar); ?></a></li>
                    <!-- <li class="page-item"><a class="page-link" href="?f=buku&m=select&idbuku=<?php echo $row['f_id'] ?>&updatestock=tambah">+</a></li> -->
                  </ul>
            </nav>
        </td>
        <td>
            <button type="button" class="btn btn-warning">
                <a href="./update.php?id=<?= $row['f_id']; ?>">
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
<?php endforeach ?>

    </table>
</div>

<nav class="nav" aria-label="Page navigation example">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $jumlahhalaman; $i++): ?>
            <li class="page-item"><a class="page-link" href="?halaman=<?=$i?>&f=buku&m=select" ><?=$i?></a></li>
        <?php endfor; ?>
    </ul>
</nav>
</main>

<div class="modal fade" id="tambahBukuModal" tabindex="-1" aria-labelledby="tambahBukuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahBukuModalLabel">
          <i class="bi bi-book-plus"></i> Form Tambah Buku
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="kategori" class="form-label">
              <i class="bi bi-folder"></i> Kategori
            </label>
            <select class="form-control" name="f_kategori">
              <option value="" disabled selected>Pilih Kategori Buku</option>
              <?php foreach ($datakategori as $kategori) : ?>
                <option value="<?php echo $kategori['f_id'] ?>"><?php echo $kategori['f_kategori'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="judul" class="form-label">
              <i class="bi bi-file-text"></i> Judul Buku
            </label>
            <input type="text" class="form-control" id="judul" name="f_judul" required>
          </div>
          <div class="mb-3">
            <label for="gambar" class="form-label">
              <i class="bi bi-file-earmark-image"></i> Cover Buku
            </label>
            <input type="file" class="form-control" id="gambar" name="f_gambar" required>
          </div>
          <div class="mb-3">
            <label for="penerbit" class="form-label">
              <i class="bi bi-building"></i> Penerbit
            </label>
            <input type="text" class="form-control" id="penerbit" name="f_penerbit" required>
          </div>
          <div class="mb-3">
            <label for="pengarang" class="form-label">
              <i class="bi bi-person"></i> Pengarang
            </label>
            <input type="text" class="form-control" id="pengarang" name="f_pengarang" required>
          </div>
          <div class="mb-3">
            <label for="deskripsi" class="form-label">
              <i class="bi bi-file-earmark-text"></i> Deskripsi Buku
            </label>
            <input type="text" class="form-control" id="deskripsi" name="f_deskripsi" required>
          </div>
          <div class="mb-3">
            <label for="eksemplar" class="form-label">
              <i class="bi bi-book"></i> Jumlah Eksemplar Buku
            </label>
            <input type="number" class="form-control" id="eksemplar" name="eksemplar" required>
          </div>
          <input type="hidden" name="f_tglinput" value="<?= date("Y-m-d H:i:s"); ?>">
          <input type="hidden" name="f_tglupdate" value="<?= date("Y-m-d H:i:s"); ?>">

          <center>
            <button type="submit" name="addBuku" class="btn btn-primary">
              <i class="bi bi-plus"></i> Tambah Buku
            </button>
          </center>
        </form>
      </div>
    </div>
  </div>
</div>


<footer>
    <center>
    <p class="footer">&copy; 2024 Perpustakaan Digital. All rights reserved.</p>
    </center>
</footer>
</body>
</html>