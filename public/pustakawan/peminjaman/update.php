<?php
session_start();
require '../../../private/function.php';

if (isset($_GET['id']) && isset($_GET['id'])) {
    $idborrow = $_GET['id'];
    $idbuku = $_GET['id'];

    $itema = array();
    $itemaResult = mysqli_query($conn, "SELECT * FROM `t_admin` ORDER BY f_nama ASC");
    while ($row = mysqli_fetch_assoc($itemaResult)) {
        $itema[] = $row;
    }

    $itemoldResult = mysqli_query($conn, "SELECT t_peminjaman.f_id, t_peminjaman.f_idanggota, t_peminjaman.f_tanggalpeminjaman, t_admin.f_nama AS f_namaadmin, t_anggota.f_nama AS f_namaanggota
    FROM `t_peminjaman`
    INNER JOIN t_admin ON t_peminjaman.f_idadmin = t_admin.f_id
    INNER JOIN t_anggota ON t_peminjaman.f_idanggota = t_anggota.f_id
    WHERE t_peminjaman.f_id = $idborrow");
    $itemold = mysqli_fetch_assoc($itemoldResult);

    $isiAwal = mysqli_query($conn,"SELECT t_detailpeminjaman.f_iddetailbuku, t_peminjaman.f_idanggota, t_peminjaman.f_idadmin, t_peminjaman.f_tanggalpeminjaman, t_peminjaman.f_id 
    FROM t_peminjaman
    INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id = t_detailpeminjaman.f_idpeminjaman
    WHERE t_peminjaman.f_id = $idborrow");
    $isiAwal1 = mysqli_fetch_assoc($isiAwal);


    $itemm = array();  // Initialize an empty array
    $itemmResult = mysqli_query($conn, "SELECT * FROM `t_anggota` ORDER BY f_nama ASC");
    while ($row = mysqli_fetch_assoc($itemmResult)) {
        $itemm[] = $row;  // Add each row to the array
    }

    $itemb = array();
    $itembResult = mysqli_query($conn, "SELECT f_judul, t_detailbuku.f_id AS idbookdetail FROM `t_detailbuku` 
        INNER JOIN t_buku ON t_detailbuku.f_idbuku = t_buku.f_id
        WHERE f_status = 'Tersedia' OR t_detailbuku.f_id = $idbuku
        ORDER BY f_judul ASC"
    );
    while ($row = mysqli_fetch_assoc($itembResult)) {
        $itemb[] = $row;
    }

    $today = date("Y-m-d");
}

if (isset($_POST['updatePeminjaman'])) {
    $book = isset($_POST['f_judul']) ? $_POST['f_judul'] : null;
    // Assuming admin ID is retrieved from the session
    $borrowdate = $_POST['f_tanggalpeminjaman'];

    if (strtotime($borrowdate) < strtotime(date('Y-m-d'))) {
        echo "<script>
                alert('Tanggal peminjaman tidak boleh lebih kecil dari hari ini');
                window.history.back();
            </script>";
        return false;
    }
    // Constructing the SQL query for updating borrowing record
    $sql = "UPDATE `t_peminjaman` 
            SET `f_tanggalpeminjaman`='$borrowdate'
            WHERE f_id = $idborrow";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        if ($isiAwal1['f_iddetailbuku'] != $book) {

        // Redirecting with success message
        echo "<script>
                alert('Peminjaman Berhasil Di Update');
                window.location.href = './read.php';
              </script>";
    } else {
        // Redirecting with error message
        echo "<script>
                alert('Gagal Mengupdate Peminjaman');
                window.location.href = './read.php';
              </script>";
    }
    $itemoldResult = mysqli_query($conn, "SELECT f_iddetailbuku, f_idanggota, f_idadmin, f_tanggalpeminjaman, t_peminjaman.f_id 
        FROM `t_peminjaman`
        INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id = t_detailpeminjaman.f_idpeminjaman
        WHERE t_peminjaman.f_id = $idborrow"
    );
    $itemold = mysqli_fetch_assoc($itemoldResult);

    $oldidbuku = $itemold['f_iddetailbuku'];
    mysqli_query($conn, "UPDATE `t_detailbuku` 
        SET `f_status`='Tersedia' 
        WHERE f_id = $oldidbuku"
    );

    mysqli_query($conn, "UPDATE `t_detailpeminjaman` 
        SET f_iddetailbuku = $book
        WHERE f_idpeminjaman = $idborrow"
    );

    $itemnewResult = mysqli_query($conn, "SELECT f_iddetailbuku, f_idanggota, f_idadmin, f_tanggalpeminjaman, t_peminjaman.f_id 
        FROM `t_peminjaman`
        INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id = t_detailpeminjaman.f_idpeminjaman
        WHERE t_peminjaman.f_id = $idborrow"
    );
    $itemnew = mysqli_fetch_assoc($itemnewResult);

    $newidbook = $itemnew['f_iddetailbuku'];
    mysqli_query($conn, "UPDATE `t_detailbuku` 
        SET `f_status`='Tidak Tersedia' 
        WHERE f_id = $newidbook"
    );
    if (isset($_SESSION['id'])) {
        $admin_id = $_SESSION['id'];
    
        // Get the admin's username
        $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
        $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];
    
        // Construct the message using the borrowing ID ($idborrow)
        $tanggall = date("Y-m-d H:i:s");
        $catatan = "$adminUsername telah melakukan perubahan pada peminjaman buku dengan id peminjaman $idborrow";
    
        // Insert the record into the t_riwayat table
        $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                         VALUES ('$admin_id', '$tanggall', '$catatan')";
        mysqli_query($conn, $queryRiwayat);
    } else {
        echo "<script>alert('Session ID not set');</script>";
    }
    
    }
    echo "<script> window.location.assign('?f=peminjaman&m=select&p=1'); </script>";
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
                <li class="nav-item"><a class="nav-link" href="./read.php"><i class="fas fa-shopping-cart"></i> Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="../pengembalian/read.php"><i class="fas fa-undo"></i> Pengembalian</a></li>
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
<div class="d-flex justify-content-center gap-5 mb-5">
<div class="p-4 col-md-8 mt-auto mb-auto">
  <div class="card mt-5 m-lg-4" id="card">
  <h5 class="card-header" style=" background-color: #072448; color:#ffffff;">Form Update Peminjaman Buku</h5>
  <div class="card-body" id="card1">

  <div id="form-container position-absolute top-0 start-50 translate-middle">
  <form method="post" enctype="multipart/form-data">
    <!-- <div class="mb-3">
        <label for="">Anggota</label><br>
        <select class="form-control" name="f_idanggota" id="">
        <?php foreach ($itemm as $r) : ?>
            <option value="<?= $r['f_id'] ?>"><?= $r['f_nama'] ?></option>
        <?php endforeach; ?>
        </select>
    </div> -->
    <div class="mb-3">
        <label for="">Anggota</label>
        <input class="form-control mb-3" type="text" name="f_idanggota" value="<?= $itemold['f_namaanggota'] ?? ''; ?>" readonly>
    </div>

        <div class="mb-3">
            <label for="">Tanggal Peminjam</label>
            <input class="form-control mb-3"  type="date" name="f_tanggalpeminjaman" value="<?= $itemold['f_tanggalpeminjaman'] ?? ''; ?>">
        </div>

        <div class="mb-3">
        <select id="" class="chzn-select form-control" name="f_judul"> 
            <option value="" disabled selected>-- Biarkan judul buku seperti ini jika tidak ingin mengubah judul buku --</option>
            <?php foreach ($itemb as $r) : ?>
                <option value="<?= $r['idbookdetail']; ?>" <?php if ($idbuku == $r['idbookdetail']) {
                                                                echo "selected";
                                                                } ?>>
                    <?= $r['f_judul']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        </div>
        <!-- <label for="">Admin</label><br>
            <select name="f_idadmin" id="" class=" form-control">
                <?php foreach ($itema as $r) : ?>
                <option value="<?= $r['f_id'] ?>">
                <?= $r['f_nama'] ?>
                </option>
                <?php endforeach ?>
            </select> -->
            <div class="mb-3">
            <label for="">Admin</label>
            <input class="form-control mb-3" type="text" name="f_idadmin" value="<?= $itemold['f_namaadmin'] ?? ''; ?>" readonly>
            </div>
        </div>

        <input type="hidden" name="editId" value="<?= $idborrow; ?>">

        <button type="submit" name="updatePeminjaman" class="btn btn-primary">Update Peminjaman</button>
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