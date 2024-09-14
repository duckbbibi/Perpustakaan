<?php
session_start();
require '../../../private/function.php';
$dataadmin = connect("SELECT * FROM t_admin");

$dataperhalaman = 5;
$totaldata = count(connect("SELECT * FROM t_kategori"));
$jumlahhalaman = ceil($totaldata / $dataperhalaman);

if (isset($_GET['halaman'])) {
  $halamanberapa = $_GET['halaman'];
} else {
  $halamanberapa = 1;
}

$awaldata = ($dataperhalaman * $halamanberapa) - $dataperhalaman;
$dataadmin = connect("SELECT * FROM t_admin ORDER BY f_id ASC LIMIT $awaldata, $dataperhalaman");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM t_admin WHERE f_id=$id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

if (isset($_POST['editAdmin'])) {

    $nama = $_POST['f_nama'];
    $username = $_POST['f_username'];
    $new_password = $_POST['f_password'];
    $level = $_POST['f_level'];
    $status = $_POST['f_status'];
    $tglupdate = $_POST['f_update'];

    // CEK KALO ADA KESAMAAN NAMA DIT_ADMIN
    $checkUsernameQuery = "SELECT f_id FROM t_admin WHERE f_username = '$username' AND f_id != $id";
    $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);

    if (mysqli_num_rows($checkUsernameResult) > 0) {
        echo "<script>
                alert('Username sudah digunakan oleh admin lain');
                document.location.href ='admin.php';
            </script>";
        exit;
    }

    // CEK PERUBAHAN YANG DI LAKUKAN ADMIN
    $perubahan = "";
    if ($row['f_nama'] != $nama) {
        $perubahan .= "nama dari '{$row['f_nama']}' menjadi '$nama', ";
    }
    if ($row['f_username'] != $username) {
        $perubahan .= "username dari '{$row['f_username']}' menjadi '$username', ";
    }
    
    $originalPassword = $row['f_password'];
    $newPasswordHash = md5($new_password);
    if (!empty($new_password) && $newPasswordHash != $originalPassword) {
        $password = $newPasswordHash;
        $perubahan .= "password berubah, ";
    } else {
        $password = $originalPassword;
    }
    
    if ($row['f_level'] != $level) {
        $perubahan .= "level dari '{$row['f_level']}' menjadi '$level', ";
    }
    if ($row['f_status'] != $status) {
        $perubahan .= "status dari '{$row['f_status']}' menjadi '$status', ";
    }

    $perubahan = rtrim($perubahan, ', ');

    $admin_id = $_SESSION['id'];
    //admin yg diubah
    $resultChangedAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$id'");
    $updateAdminUsername = mysqli_fetch_assoc($resultChangedAdmin)['f_username'];
    //admin yg ngubah
    $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
    $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];
    $tanggal = date("Y-m-d H:i:s");
    $catatan = mysqli_real_escape_string($conn, "Admin $adminUsername berhasil melakukan perubahan $perubahan di-akun admin dengan username '$updateAdminUsername'");

    $sql = "UPDATE t_admin SET f_nama='$nama', f_username='$username', f_password='$password', f_level='$level', f_status='$status', f_update='$tglupdate' WHERE f_id=$id";

    
    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        // MASUKIN DATA RIWAYAT KE T_RIWAYAT
        $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                         VALUES ('$admin_id', '$tanggal', '$catatan')";
        mysqli_query($conn, $queryRiwayat);

        echo "<script>
                alert('Berhasil mengupdate admin');
                document.location.href ='admin.php';
            </script>";
    } else {
        echo "<script>
                alert('Gagal mengupdate admin');
                document.location.href ='admin.php';
            </script>";
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
            padding: 20px;
        }
        nav{
            justify-content: center;
            margin-top: 10px;
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
</div>


<main>
<div class="gap-2">
<div class="p-4">

  <div class="p-2 ">
    <div class="card mt-5" id="card">
    <h5 class="card-header" style=" background-color: #072448; color:#ffffff;">Daftar Petugas Perpustakaan</h5>
    <div class="card-body" id="card1">

    <form method="post">
        <div class="form-group mb-2">
            <label for="kategori">Nama Lengkap</label>
            <input type="text" class="form-control" name="f_nama" id="" placeholder="Enter your name"  value="<?php echo $row['f_nama'] ?>" >
        </div>
        <div class="form-group mb-2">
            <label for="kategori">Ussername</label>
            <input type="text" class="form-control" name="f_username" id="" placeholder="Enter your username"  value="<?php echo $row['f_username'] ?>" >
        </div>
        <div class="form-group mb-2">
            <label for="kategori">Password</label>
            <input type="hidden" name="original_password" value="<?php echo $row['f_password']; ?>">
            <input type="password" class="form-control" name="f_password" id="" placeholder="Kosongkan jika tidak ingin mengubah password">
        </div>
        <div class="form-box">
            <span class="details">Level</span>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="f_level" id="Admin" <?php if ($row["f_level"] == "Admin") echo "checked";?> value="Admin">
                <label class="form-check-label" for="Admin">
                    Admin
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="f_level" id="Petugas" <?php if ($row["f_level"] == "Pustakawan") echo "checked";?> value="Pustakawan">
                <label class="form-check-label" for="Petugas">
                    Pustakawan
                </label>
            </div>
        </div>


        <div class="form-box">
                <span class="details">Status</span>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="f_status" id="Aktif" <?php if ($row["f_status"] == "Aktif") echo "checked";?> value="Aktif">
                    <label class="form-check-label" for="Aktif">
                        Aktif
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="f_status" id="tdkAktif" <?php if ($row["f_status"] == "Tidak Aktif") echo "checked";?> value="Tidak Aktif">
                    <label class="form-check-label" for="tdkAktif">
                        Tidak Aktif
                    </label>
                </div>
            </div>
        </div>

        <input type="hidden" name="f_update" value="<?= date("Y-m-d H:i:s"); ?>">


        <center>
        <button type="submit" name="editAdmin" class="btn btn-primary mt-2 mb-2">Submit</button>
        </center>
    </form>

    </div>
    </div>
    </div>
  </div>
</div>
</body>
</html>