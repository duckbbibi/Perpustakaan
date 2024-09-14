<?php
session_start();
require '../../../private/function.php';
$adminInfo = $_SESSION['admin'];
$id = $_SESSION['id'];

$admin = connect("SELECT * FROM t_admin WHERE f_status = 'Aktif' ORDER BY f_nama ASC");
$anggota = connect("SELECT * FROM t_anggota ORDER BY f_nama ASC");
$buku = connect("SELECT t_detailbuku.f_id AS f_iddetailbuku, f_judul, t_kategori.f_kategori
    FROM t_buku
    INNER JOIN t_detailbuku ON t_buku.f_id = t_detailbuku.f_idbuku
    INNER JOIN t_kategori ON t_buku.f_idkategori = t_kategori.f_id
    WHERE t_detailbuku.f_status = 'Tersedia'");
$jumlahdataperhalaman = 5;
$totaldata = count(connect("SELECT t_anggota.f_nama AS f_namaanggota, t_kategori.f_kategori,t_peminjaman.f_id, t_peminjaman.f_tanggalpeminjaman, t_buku.f_judul, t_admin.f_nama AS f_namaadmin, t_detailbuku.f_id as f_iddetb
FROM t_peminjaman
INNER JOIN t_admin ON t_peminjaman.f_idadmin=t_admin.f_id
INNER JOIN t_anggota ON t_peminjaman.f_idanggota=t_anggota.f_id
INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id=t_detailpeminjaman.f_idpeminjaman
INNER JOIN t_detailbuku ON t_detailpeminjaman.f_iddetailbuku=t_detailbuku.f_id
INNER JOIN t_buku ON t_detailbuku.f_idbuku=t_buku.f_id
INNER JOIN t_kategori ON t_buku.f_idkategori = t_kategori.f_id ORDER BY t_peminjaman.f_id DESC"));
$jumlahhalaman = ceil ($totaldata / $jumlahdataperhalaman);

if (isset($_GET["halaman"])) {
    $halamanberapa = $_GET["halaman"];
    $awaldata = ($halamanberapa * $jumlahdataperhalaman) - $jumlahdataperhalaman;
} else {
    $awaldata = 0;
}

$peminjaman = connect("SELECT t_anggota.f_nama AS f_namaanggota, t_kategori.f_kategori,t_peminjaman.f_id, t_peminjaman.f_tanggalpeminjaman, t_buku.f_judul, t_admin.f_nama AS f_namaadmin, t_detailbuku.f_id as f_iddetb
FROM t_peminjaman
INNER JOIN t_admin ON t_peminjaman.f_idadmin=t_admin.f_id
INNER JOIN t_anggota ON t_peminjaman.f_idanggota=t_anggota.f_id
INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id=t_detailpeminjaman.f_idpeminjaman
INNER JOIN t_detailbuku ON t_detailpeminjaman.f_iddetailbuku=t_detailbuku.f_id
INNER JOIN t_buku ON t_detailbuku.f_idbuku=t_buku.f_id
INNER JOIN t_kategori ON t_buku.f_idkategori = t_kategori.f_id
LIMIT $awaldata, $jumlahdataperhalaman");

$no = 1 + $awaldata;


if(isset($_POST["addPeminjaman"])){
    $admin = $_POST["f_idadmin"];
    $anggota = $_POST["f_idanggota"];
    $judulbuku = $_POST["f_judul"];
    $tanggal = $_POST["f_tanggalpeminjaman"];

    if (strtotime($tanggal) < strtotime(date('Y-m-d'))) {
        echo "<script>
                alert('Tanggal peminjaman tidak boleh lebih kecil dari hari ini');
                window.history.back();
            </script>";
        return false;
    }

    // Insert into t_peminjaman
    $query = "INSERT INTO t_peminjaman (f_id, f_idadmin, f_idanggota, f_tanggalpeminjaman) VALUES ('','$admin', '$anggota', '$tanggal')";
    mysqli_query($conn, $query);

    // Get the last inserted ID
    $idpeminjamanterakhir = mysqli_insert_id($conn);
    
    $tanggal = date("Y-m-d H:i:s");
    $catatan = "Admin $adminInfo telah menambahkan peminjaman baru dengan ID Peminjaman: $idpeminjamanterakhir";
    $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) VALUES ('$id', '$tanggal', '$catatan')";
    mysqli_query($conn, $queryRiwayat);
    
    // Insert into t_detailpeminjaman
    $query = "INSERT INTO t_detailpeminjaman VALUES('', '$idpeminjamanterakhir', '$judulbuku', '')";
    mysqli_query($conn, $query);

    // Update t_detailbuku
    $query = "UPDATE t_detailbuku SET f_status= 'Tidak Tersedia' WHERE f_id=$judulbuku";
    mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
        echo "<script>
        alert ('data berhasil ditambahkan!');
        document.location.href = './read.php'
        </script>";
        exit;
    } else {
        echo "<script>
        alert ('data gagal ditambahkan!');
        document.location.href = './read.php'
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
        main{
            display: flex;
            flex-grow: column;
        }
        #card{
            width: 50vh;
        }
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
                <li class="nav-item"><a class="nav-link" href="../kategori/read.php"><i class="fas fa-book"></i> Kategori Buku</a></li>
                <li class="nav-item ">
                    <a class="nav-link" href="../buku/read.php" aria-expanded="false">
                        <i class="fas fa-book-open"></i> Koleksi Buku
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="./read.php"><i class="fas fa-shopping-cart"></i> Peminjaman</a></li>
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
<div class="d-flex flex-row mb-5">
<div class="p-4 col-md-5 w-100 mt-auto mb-auto">
  <div class="card mt-5 m-lg-4" id="card">
  <h5 class="card-header" style=" background-color: #ffcb00; color:#ffffff;">Form Peminjaman Buku</h5>
  <div class="card-body" id="card1">

  <div id="form-container position-absolute top-0 start-50 translate-middle">
  <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="">Anggota</label><br>
                <select name="f_idanggota" id="id_anggota" onchange="fillNama()" class="form-control">
                    <option value="" disabled selected>Pilih Id Anggota</option>
                    <?php foreach ($anggota as $row) : ?>
                        <option value="<?= $row['f_id'] ?>" data-nama="<?= $row['f_nama'] ?>"><?= $row['f_id'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="id_anggota_hidden" name="f_idanggota">
            </div>

            <div class="mb-3">
                <label for="">Nama Anggota</label><br>
                <input class="form-control mb-3" type="text" id="nama" readonly style="cursor: no-drop;">   
            </div>
          <div class="mb-3">
            <label for="">Tanggal Peminjaman</label>
            <input class="form-control mb-3" type="date" name="f_tanggalpeminjaman">
          </div>
          <div class="mb-3">
          <label for="">Judul Buku</label><br>
            <select name="f_judul" id=""  class=" form-control">
                <option value="" disabled selected>
                   Pilih Judul Buku
                </option>
                <?php foreach ($buku as $row) : ?>
                        <?php
                            $optionValue = $row['f_judul'] . " - " . $row['f_kategori'];
                        ?>
                        <option value="<?php echo $row['f_iddetailbuku']; ?>">
                            <?php echo htmlspecialchars($optionValue); ?>
                        </option>
                    <?php endforeach ?>

            </select>
          </div>
          <label for="">Admin</label><br>
            <input class="form-control mb-3" type="hidden" name="f_idadmin" name="" value="<?php echo $id; ?>">
            <input class="form-control mb-3" type="text" value="<?php echo $adminInfo; ?>" readonly style="cursor: no-drop;">
          </div>

          <button type="submit" name="addPeminjaman" class="btn btn-primary">Tambah Peminjaman</button>
        </form>
  </div>
  </div>
</div>
</div>

  <div class="p-2 col-md-7">
    <div class="card mt-5">
    <h5 class="card-header" style=" background-color: #072448; color:#ffffff;">Daftar Peminjaman Buku</h5>
    <div class="card-body" id="card1">

    <table class="table table-striped w-auto ">
            <tr class="text-center" id="th" >
            <th>No</th>
            <th>Id PEminjaman</th>
            <th>Anggota</th>
            <th>Tanggal Peminjaman</th>
            <th>Kategori</th>
            <th>Judul Buku</th>
            <th>Admin/Pustakawan</th>
            <th>Ubah</th>
            </tr>

            <?php
            foreach($peminjaman  as $row):
            ?>
            <tr class="text-center">
            <td><center><?php echo $no++ ?></center></td>
            <td><center><?php echo $row["f_id"]; ?></center></td>
            <td><center><?php echo $row["f_namaanggota"]; ?></center></td>
            <td><center><?php echo $row["f_tanggalpeminjaman"]; ?></center></td>
            <td><center><?php echo $row["f_kategori"]; ?></center></td>
            <td><center><?php echo $row["f_judul"]; ?></center></td>
            <td><center><?php echo $row["f_namaadmin"]; ?></center></td>

            <td>
                <button type="button" class="btn btn-warning">
                <a href="update.php?id=<?= $row['f_id']; ?>">
                <i class="fa-solid fa-wand-magic-sparkles" style="color: #ffffff;"></i>
                </a>
                </button>
            </td>
            </tr>
            <?php
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
</div>
</main>

<footer>
    <center>
    <p class="footer">&copy; 2024 Perpustakaan Digital. All rights reserved.</p>
    </center>
</footer>

<script>
    function fillNama() {
        var selectedId = document.getElementById('id_anggota').value;
        var selectedNama = document.getElementById('id_anggota').options[document.getElementById('id_anggota').selectedIndex].getAttribute('data-nama');

        document.getElementById('id_anggota_hidden').value = selectedId;
        document.getElementById('nama').value = selectedNama;
    }
</script>
</body>
</html>