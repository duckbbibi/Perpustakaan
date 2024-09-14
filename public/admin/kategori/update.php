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

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM t_kategori WHERE f_id=$id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (isset($_POST['editkategori'])) {
        $kategori = $_POST['f_kategori'];
        $tgl_update = $_POST['f_tglupdt'];

        // Check if a kategori with the same name already exists
        $checkKategoriQuery = "SELECT f_id FROM t_kategori WHERE f_kategori = '$kategori' AND f_id != $id";
        $checkKategoriResult = mysqli_query($conn, $checkKategoriQuery);

        if (mysqli_num_rows($checkKategoriResult) > 0) {
            echo "<script>alert('Kategori dengan nama yang sama sudah ada');</script>";
        } else {
            $perubahan = "";
            if ($row['f_kategori'] != $kategori) {
                $perubahan .= "nama kategori dari '{$row['f_kategori']}' menjadi '$kategori', ";
            }
            $perubahan = rtrim($perubahan, ', ');

            $admin_id = $_SESSION['id'];
            //admin yg diubah
            $resultChangedAdmin = mysqli_query($conn, "SELECT f_kategori FROM t_kategori WHERE f_id = '$id'");
            $updateAdminUsername = mysqli_fetch_assoc($resultChangedAdmin)['f_kategori'];
            //admin yg ngubah
            $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
            $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];
            $tanggal = date("Y-m-d H:i:s");
            $catatan = mysqli_real_escape_string($conn, "Admin $adminUsername berhasil melakukan perubahan $perubahan dengan nama kategori sebelumnya '$updateAdminUsername'");

            $sql = "UPDATE t_kategori SET f_kategori='$kategori', f_tglupdt='$tgl_update' WHERE f_id=$id";
            mysqli_query($conn, $sql);

            if (mysqli_query($conn, $sql)) {
                // MASUKIN DATA RIWAYAT KE T_RIWAYAT
                $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                                VALUES ('$admin_id', '$tanggal', '$catatan')";
                mysqli_query($conn, $queryRiwayat);
                echo "<script>alert('Berhasil mengubah data'); document.location.href ='./read.php';</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
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
    <style>
        body{
            padding: 15px;
        }
        .nav{
            justify-content: center;
            margin: 10px;
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
</div>


<main>
<div class="d-flex justify-content-center gap-5 mb-5">
<div class="p-4 col-md-8 mt-auto mb-auto">
  <div class="card mt-5 m-lg-4" id="card">
  <h5 class="card-header" style=" background-color: #072448; color:#ffffff;">Form Kategori Buku</h5>
  <div class="card-body" id="card1">

  <div id="form-container position-absolute top-0 start-50 translate-middle">
  <form method="post">
        <div class="form-group">
            <label for="kategori">Kategori Buku</label>
            <input type="text" class="form-control" name="f_kategori" id="kategori" placeholder="Enter Category" required value="<?php echo $row['f_kategori'] ?>">
        </div>
        <input type="hidden" name="f_tglupdt" value="<?= date("Y-m-d H:i:s"); ?>">
        <button type="submit" name="editkategori" class="btn btn-primary mt-2 float-end">Submit</button>
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