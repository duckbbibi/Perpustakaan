<?php
session_start();

require '../../../private/function.php';
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  if (isset($_SESSION['id'])) {
    $admin_id = $_SESSION['id'];

    // Ngambil data dari admin yang ngedelete
    $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
    $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];

    // ngambil data dari buku yang didelete sama admin di atas
    $resultDeletedAdmin = mysqli_query($conn, "SELECT f_judul FROM t_buku WHERE f_id = '$id'");
    $deletedAdminUsername = mysqli_fetch_assoc($resultDeletedAdmin)['f_judul'];

    $tanggal = date("Y-m-d H:i:s");
    $catatan = "Admin $adminUsername telah Menghapus buku dengan judul $deletedAdminUsername";

    $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                     VALUES ('$admin_id', '$tanggal', '$catatan')";
    mysqli_query($conn, $queryRiwayat);
} else {
    echo "<script>alert('Session ID not set');</script>";
}


  $checkPinjamQuery = "SELECT COUNT(*) AS total FROM t_detailpeminjaman dp
  INNER JOIN t_detailbuku db ON dp.f_iddetailbuku = db.f_id
  WHERE db.f_idbuku = '$id' AND dp.f_tanggalkembali = '0000-00-00'";
$checkPinjamResult = mysqli_query($conn, $checkPinjamQuery);
$pinjamData = mysqli_fetch_assoc($checkPinjamResult);
$totalPinjam = $pinjamData['total'];

if ($totalPinjam > 0) {
echo "<script>
alert('Gagal menghapus buku karena buku masih dipinjam.');
document.location.href = './read.php';
</script>";
return false;
}

$checkDetailPinjamQuery = "SELECT COUNT(*) AS total FROM t_detailpeminjaman dp
        INNER JOIN t_detailbuku db ON dp.f_iddetailbuku = db.f_id
        WHERE db.f_idbuku = '$id' AND dp.f_tanggalkembali = '0000-00-00'";
$checkDetailPinjamResult = mysqli_query($conn, $checkDetailPinjamQuery);
$detailPinjamData = mysqli_fetch_assoc($checkDetailPinjamResult);
$totalDetailPinjam = $detailPinjamData['total'];

$checkStokQuery = "SELECT COUNT(*) AS total FROM t_detailbuku WHERE f_idbuku = '$id'";
$checkStokResult = mysqli_query($conn, $checkStokQuery);
$stokData = mysqli_fetch_assoc($checkStokResult);
$stok = $stokData['total'];

if ($stok > 0) {
echo "<script>
alert('Gagal menghapus buku karena stok masih tersedia.');
document.location.href = './read.php';
</script>";
return false;
}

$showID = mysqli_query($conn, "SELECT * FROM t_buku WHERE f_id = '$id'");
$result = mysqli_fetch_assoc($showID);
$gambarPath = "./images" . $result['f_gambar'];

if (file_exists($gambarPath)) {
unlink($gambarPath);
}

mysqli_query($conn, "DELETE FROM t_buku WHERE f_id=$id");

if (mysqli_affected_rows($conn) > 0) {
echo "<script>
alert('Buku berhasil dihapus');
document.location.href = './read.php';
</script>";
return true;
} else {
echo "<script>
alert('Gagal menghapus buku');
document.location.href = './read.php';
</script>";
return false;
}

}
?>