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

      // ngambil data dari kategori yang didelete sama admin di atas
      $resultDeletedAdmin = mysqli_query($conn, "SELECT f_kategori FROM t_kategori WHERE f_id = '$id'");
      $deletedAdminUsername = mysqli_fetch_assoc($resultDeletedAdmin)['f_kategori'];

      $tanggal = date("Y-m-d H:i:s");
      $catatan = "Admin $adminUsername telah Menghapus kategori dengan nama kategori $deletedAdminUsername";

      //CATET KE T_RIWAYAT
      $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                      VALUES ('$admin_id', '$tanggal', '$catatan')";
      mysqli_query($conn, $queryRiwayat);
  } else {
      echo "<script>alert('Session ID not set');</script>";
  }

  // CEK KALO ADA BUKU DENGAN KATEGORI YANG MAU DI HAPUS
  $checkBooksQuery = "SELECT COUNT(*) AS bookCount FROM t_buku WHERE f_idkategori = $id";
  $result = mysqli_query($conn, $checkBooksQuery);

  if ($result) {
      $row = mysqli_fetch_assoc($result);
      $bookCount = $row['bookCount'];

      if ($bookCount > 0) {
          // KMUNCULIN ALERT KALO ADA BUKU DENGAN KATEGORI INI, MAKA KATEGORI GABISA DI APUS
          echo "<script>
                  alert('Terdapat Buku Dengan Kategori ini, Kategori Gagal DiHapus');
                  window.location.href = './read.php';
                </script>";
          exit;
      } else {
          // KALO GAADA BUKU DENGAN KATEGORI YANG INGIN DI HAPUS MAKA PENGHAPUSAN BERHASIL
          $query = "DELETE FROM t_kategori WHERE f_id = $id";
          $result = mysqli_query($conn, $query);

          if ($result) {
              echo "<script>
                      alert('Kategori berhasil dihapus.');
                      window.location.href = './read.php';
                    </script>";
              exit;
          } else {
              echo "<script>
                      alert('Terjadi kesalahan saat menghapus kategori.');
                      window.location.href = 'select.php';
                    </script>";
              exit;
          }
      }
  }
} else {
  echo "<script>
          alert('ID kategori tidak valid.');
          window.location.href = 'select.php';
        </script>";
  exit;
}

?>