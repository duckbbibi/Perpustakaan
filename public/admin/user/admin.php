<?php
session_start();
require '../../../private/function.php';

$dataadmin = connect("SELECT * FROM t_admin");

$dataperhalaman = 5;
$totaldata = count(connect("SELECT * FROM t_admin"));
$jumlahhalaman = ceil($totaldata / $dataperhalaman);

if (isset($_GET['halaman'])) {
    $halamanberapa = $_GET['halaman'];
} else {
    $halamanberapa = 1;
}

$awaldata = ($dataperhalaman * $halamanberapa) - $dataperhalaman;
$dataadmin = connect("SELECT * FROM t_admin ORDER BY f_id ASC LIMIT $awaldata, $dataperhalaman");

if (isset($_POST['addAnggota'])) {
    $admin_id = $_SESSION['id'];
    if (registrasi($_POST, $admin_id)) {
        echo "<script>
                alert('user baru telah didaftarkan');
                document.location.href = './admin.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menambahkan user baru');</script>";
    }
}


function registrasi($POST, $admin_id){
    global $conn;

    $username = strtolower($POST["f_username"]);
    $password = md5(mysqli_real_escape_string($conn, $POST["f_password"]));
    $level = $_POST['f_level'];
    $status = $_POST['f_status'];
    $nama = $_POST['f_nama'];
    $tglinput = $_POST['f_input'];
    $tglupdate = $_POST['f_update'];

    $result = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_username = '$username'");
    if (mysqli_fetch_assoc($result)){
        echo "<script>
                alert('username telah didaftarkan sebelumnya');
              </script>";
        return false;
    } else {
        $query = "INSERT INTO t_admin (f_nama, f_username, f_password, f_level, f_status, f_input, f_update) 
                  VALUES ('$nama', '$username', '$password', '$level', '$status', '$tglinput', '$tglupdate')";
        mysqli_query($conn, $query);

        $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
        if ($resultAdmin && mysqli_num_rows($resultAdmin) > 0) {
            $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];
            $tanggal = date("Y-m-d H:i:s");
            $catatan = "Admin $adminUsername telah menambahkan admin baru dengan Username $username";

            $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                             VALUES ('$admin_id', '$tanggal', '$catatan')";
            mysqli_query($conn, $queryRiwayat);
        } else {
            echo "<script>alert('Admin ID not found');</script>";
        }
        return true;
    }
}
if (isset($_GET['key'])) {
    $search_term = $_GET['key'];
    $query = srcadmin($_GET['key']);
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
            overflow-x: hidden;
        }
        .nav{
            justify-content: center;
            margin: 10px;
        }
        .card {
            border-radius: 8px;
        }
        .table-container {
            overflow-x: auto;
        }
        .card table {
            width: max-content;
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
                <li class="nav-item"><a class="nav-link" href="../riwayat/read.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</a></li>

                <li class="nav-item dropdown" id="active">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="far fa-user"></i> Pengguna
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="anggotaDropdown">
                        <li><a class="dropdown-item" href="./admin.php">Admin</a></li>
                        <li><a class="dropdown-item" href="./anggota.php">Anggota</a></li>
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
<div class="d-md-flex flex-md-row gap-5 mb-5">
<div class="p-4 col-md-4 mt-auto mb-auto">
  <div class="card mt-5 m-lg-2">
  <h5 class="card-header" style=" background-color: #ffcb00; color:#ffffff;">Form Tambah Petugas</h5>
  <div class="card-body" id="card1">

  <div id="form-container position-absolute top-0 start-50 translate-middle">
  <form method="post">
        <div class="form-group mb-2">
            <label for="kategori">Nama Lengkap</label>
            <input type="text" class="form-control" name="f_nama" id="" placeholder="Enter your name" required>
        </div>
        <div class="form-group mb-2">
            <label for="kategori">Ussername</label>
            <input type="text" class="form-control" name="f_username" id="" placeholder="Enter your username" required>
        </div>
        <div class="form-group mb-2">
            <label for="kategori">Password</label>
            <input type="text" class="form-control" name="f_password" id="" placeholder="Enter your password" required>
        </div>
        <div class="form-box">
                <span class="details">Level</span>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="f_level" id="Admin" value="Admin">
                    <label class="form-check-label" for="Admin">
                        Admin
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="f_level" id="Pustakawan" value="Pustakawan">
                    <label class="form-check-label" for="Pustakawan">
                        Pustakawan
                    </label>
                </div>
            </div>

            <div class="form-box">
                    <span class="details">Status</span>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="f_status" id="Aktif" value="Aktif">
                        <label class="form-check-label" for="Aktif">
                            Aktif
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="f_status" id="tdkAktif" value="Tidak Aktif">
                        <label class="form-check-label" for="tdkAktif">
                            Tidak Aktif
                        </label>
                    </div>
                </div>

            <input type="hidden" name="f_input" value="<?= date("Y-m-d H:i:s"); ?>">
            <input type="hidden" name="f_update" value="<?= date("Y-m-d H:i:s"); ?>">

        <center>
        <button type="submit" name="addAnggota" class="btn btn-primary mt-2">Submit</button>
        </center>
    </form>
  </div>
  </div>
</div>
</div>

    <div class="p-2 col-md-7">
      <div class="card mt-5" id="card">
        <h5 class="card-header" style="background-color: #072448; color: #ffffff;">Daftar Anggota Petugas</h5>
        <div class="card-body" id="card1">
          <!-- Table container with horizontal scrolling -->
        <div class="table-container">
         <table class="table table-striped">
          <tr class="text-center" id="th" >
              <th>Id Admin</th>
              <th>Nama Admin</th>
              <th>Username</th>
              <th style="width: 20%;">Password</th>
              <th>Level</th>
              <th>Status</th>
              <th>Tanggal Input</th>
              <th>Tanggal Update</th>
              <th colspan="2">Aksi</th>
          </tr>
          <?php
        $row_number = 1;
        foreach($dataadmin as $row):
        ?>
        <tr class="text-center">
          <td><?= $row_number; ?></td>
          <td><?= $row["f_nama"]; ?></td>
          <td><?= $row["f_username"]; ?></td>
          <td><?= $row["f_password"]; ?></td>
          <td><?= $row["f_level"]; ?></td>
          <td><?= $row["f_status"]; ?></td>
          <td><?= $row["f_input"]; ?></td>
          <td><?= $row["f_update"]; ?></td>
          <td>
            <button type="button" class="btn btn-warning">
              <a href="./adminUpdate.php?id=<?= $row['f_id']; ?>">
              <i class="fa-solid fa-wand-magic-sparkles" style="color: #ffffff;"></i>
              </a>
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-danger">
              <a href="./adminDelete.php?id=<?= $row['f_id']; ?>">
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