<?php
session_start();
require '../../../private/function.php';
if (isset($_GET['id'], $_GET['iddetailbuku'])) {
  $id = $_GET['id'];
  $iddetailbuku = $_GET['iddetailbuku'];

  // Update the book status to 'Tersedia'
  $sqlUpdateStatus = "UPDATE t_detailbuku SET f_status='Tersedia' WHERE f_id=$iddetailbuku";
  if (mysqli_query($conn, $sqlUpdateStatus)) {
      // Update the return date in t_detailpeminjaman
      $tanggal = date('Y-m-d');
      $sqlUpdateReturnDate = "UPDATE t_detailpeminjaman SET f_tanggalkembali='$tanggal' WHERE f_id=$id";

      if (isset($_SESSION['id'])) {
        $admin_id = $_SESSION['id'];

        // Ngambil data dari admin yang ngedelete
        $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
        $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];

        // ngambil data dari admin yang didelete sama admin di atas
        $resultDeletedAdmin = mysqli_query($conn, "SELECT f_idpeminjaman FROM t_detailpeminjaman WHERE f_id = '$id'");
        $deletedAdminUsername = mysqli_fetch_assoc($resultDeletedAdmin)['f_idpeminjaman'];

        $tanggall = date("Y-m-d H:i:s");
        $catatan = "$adminUsername telah menerima pengembalian buku dengan id peminjaman $deletedAdminUsername";

        $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                         VALUES ('$admin_id', '$tanggall', '$catatan')";
        mysqli_query($conn, $queryRiwayat);
    } else {
        echo "<script>alert('Session ID not set');</script>";
    }

      if (mysqli_query($conn, $sqlUpdateReturnDate)) {
          // Redirect to select.php
          header("Location: ./read.php");
          exit();
      } else {
          echo "Error updating return date: " . mysqli_error($conn);
      }
  } else {
      echo "Error updating book status: " . mysqli_error($conn);
  }
} else {
  echo "Invalid parameters";
}

?>
