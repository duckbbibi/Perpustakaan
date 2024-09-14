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

      
        // ngambil data dari admin yang didelete sama admin di atas
        $resultDeletedAdmin = mysqli_query($conn, "SELECT f_username FROM t_anggota WHERE f_id = '$id'");
        $deletedAdminUsername = mysqli_fetch_assoc($resultDeletedAdmin)['f_username'];
        
        $tanggal = date("Y-m-d H:i:s");
        $catatan = "Admin $adminUsername telah Menghapus anggota dengan Username $deletedAdminUsername";

        $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                         VALUES ('$admin_id', '$tanggal', '$catatan')";
        mysqli_query($conn, $queryRiwayat);
    } else {
        echo "<script>alert('Session ID not set');</script>";
    }
  
    $sql = "DELETE FROM t_anggota WHERE f_id=$id";
    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0) {
        echo "<script>
                alert('Anggota Berhasil Di Hapus');
                document.location.href ='./anggota.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal Menghapus Anggota');
                document.location.href ='./anggota.php';
              </script>";
    };
}
?>