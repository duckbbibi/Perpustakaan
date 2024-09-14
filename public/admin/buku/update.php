<?php
session_start();
require '../../../private/function.php';

$datakategori = connect("SELECT * FROM t_kategori");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM t_buku WHERE f_id=$id";
    $result = mysqli_query($conn,$sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Error: " . mysqli_error($conn));
    }
  }

  if (isset($_GET['id'])){
    $id = $_GET['id'];

    if (isset($_POST['updateBuku'])) {
        updateBuku();
        if (mysqli_affected_rows($conn) > 0) {
            echo "<script>
                    alert('Berhasil update buku');
                    document.location.href ='./read.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal update buku');
                    document.location.href ='./read.php';
                  </script>";
        }
    }
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
</div>

<main>
<div class="d-flex justify-content-center gap-5">
<div class="p-4 col-md-8 mt-auto mb-auto">
  <div class="card mt-5 m-lg-4" id="card">
  <h5 class="card-header" style=" background-color: #072448; color:#ffffff;">Form Kategori Buku</h5>
  <div class="card-body" id="card1">

  <div id="form-container position-absolute top-0 start-50 translate-middle">
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="">Kategori</label>
        <select class="form-control" name="f_kategori">
            <?php foreach ($datakategori as $kategori) : ?>
                <option required value="<?php echo $kategori['f_id'] ?>"><?php echo $kategori['f_kategori'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="judul" class="form-label">Judul Buku</label>
        <input type="text" class="form-control" id="judul" name="f_judul" required value="<?php echo $row['f_judul'] ?>">
    </div>
    <div class="mb-3">
        <label for="judul" class="form-label">Cover Buku</label>
        <input type="file" class="form-control" id="judul" name="f_gambar" value="<?php echo $row['f_gambar'] ?>">
    </div>
    <div class="mb-3">
        <label for="judul" class="form-label">Penerbit </label>
        <input type="text" class="form-control" id="judul" name="f_penerbit" required value="<?php echo $row['f_penerbit'] ?>">
    </div>
    <div class="mb-3">
        <label for="judul" class="form-label">Pengarang </label>
        <input type="text" class="form-control" id="judul" name="f_pengarang" required value="<?php echo $row['f_pengarang'] ?>">
    </div>
    <div class="mb-3">
        <label for="judul" class="form-label">Deskripsi Buku</label>
        <input type="text" class="form-control" id="judul" name="f_deskripsi" required value="<?php echo $row['f_deskripsi'] ?>">
    </div>
    <input type="hidden" name="f_tglupdate" value="<?= date("Y-m-d H:i:s"); ?>">
    <div class="mb-3">
        <label for="eksemplar" class="form-label">Eksemplar</label>
        <input type="number" class="form-control" id="eksemplar" name="eksemplar" value="<?php echo getEksemplarForBook($row['f_id'], $conn); ?>">
    </div>

    <center>
        <button type="submit" name="updateBuku" class="btn btn-primary">Update Buku</button>
    </center>
</form>

  </div>
  </div>
</div>
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