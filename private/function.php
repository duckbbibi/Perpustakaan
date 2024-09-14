<?php
$conn = new mysqli('localhost', 'root', '','perpustakaan');
date_default_timezone_set('Asia/Jakarta');

function connect($query){
    global $conn;
    $result=$conn->query($query);
    $rows=[];

    while($row=$result->fetch_assoc()){
        $rows[]=$row;
    }
    return $rows;
}function tambahKategori($admin_id){
  global $conn;

  if (isset($_POST['addkategori'])) {
      $namakategori = $_POST['f_kategori'];
      $tglinput = $_POST['f_tglinpt'];
      $tglupdate = $_POST['f_tglupdt'];

      $checkSql = "SELECT * FROM t_kategori WHERE f_kategori = '$namakategori'";
      $checkResult = mysqli_query($conn, $checkSql);

      if (mysqli_num_rows($checkResult) > 0) {
          echo "<script>
                  alert('Kategori dengan nama yang sama sudah ada');
                  document.location.href ='./read.php';
                </script>";
      } else {
          $sql = "INSERT INTO t_kategori VALUES('', '$namakategori','$tglinput','$tglupdate')";
          mysqli_query($conn, $sql);

          $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
          if ($resultAdmin && mysqli_num_rows($resultAdmin) > 0) {
              $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];
              $tanggal = date("Y-m-d H:i:s");
              $catatan = "Admin $adminUsername telah menambahkan kategori baru dengan nama kategori $namakategori";
  
              $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                               VALUES ('$admin_id', '$tanggal', '$catatan')";
              mysqli_query($conn, $queryRiwayat);
          } else {
              echo "<script>alert('Admin ID tidak di temukan');</script>";
          }

          if (mysqli_affected_rows($conn) > 0) {
              echo "<script>
                      alert('Berhasil menambahkan Kategori Baru');
                      document.location.href ='./read.php';
                    </script>";
          } else {
              echo "<script>
                      alert('Gagal menambahkan Kategori Baru');
                      document.location.href ='./read.php';
                    </script>";
          }
      }
  }
}

function tambahBuku($admin_id){
    global $conn;

    if (isset($_POST['addBuku'])) {
        $kategori = mysqli_real_escape_string($conn, $_POST['f_kategori']);
        $judul = mysqli_real_escape_string($conn, $_POST['f_judul']);
        $pengarang = mysqli_real_escape_string($conn, $_POST['f_pengarang']);
        $penerbit = mysqli_real_escape_string($conn, $_POST['f_penerbit']);
        $deskripsi = mysqli_real_escape_string($conn, $_POST['f_deskripsi']);
        $tglinput = mysqli_real_escape_string($conn, $_POST['f_tglinput']);
        $tglupdate = mysqli_real_escape_string($conn, $_POST['f_tglupdate']);
        $eksemplar = (int)$_POST['eksemplar'];


        $images = $_FILES['f_gambar']['name'];
        $source = $_FILES['f_gambar']['tmp_name'];
        $folder = '../gambar/';

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        if ($_FILES['f_gambar']['error'] > 0) {
            echo 'Error: ' . $_FILES['f_gambar']['error'];
        } else {
            move_uploaded_file($source, $folder.$images);
            echo 'File uploaded successfully.';
        }

        // Cek buku kalo ada judul yang sama
        $checkIfExistsQuery = "SELECT * FROM t_buku WHERE f_judul = '$judul'";
        $resultCheckIfExists = mysqli_query($conn, $checkIfExistsQuery);

        if ($resultCheckIfExists && mysqli_num_rows($resultCheckIfExists) > 0) {
            // cek kalo ada buku yang judulnya dan semua fieldnya sama maka nambahinnya ke stok
            $row = mysqli_fetch_assoc($resultCheckIfExists);
            $idBukuExisting = $row['f_id'];

            $sqlIncrementStock = "UPDATE t_detailbuku SET f_status = 'Tersedia' WHERE f_idbuku = $idBukuExisting LIMIT $eksemplar";
            mysqli_query($conn, $sqlIncrementStock);

            // cek kalo eksemplarnya 0 maka buku akan dapat di hapus
            if ($eksemplar === 0) {
                $sqlDeleteBook = "DELETE FROM t_buku WHERE f_id = $idBukuExisting";
                mysqli_query($conn, $sqlDeleteBook);

                $sqlDeleteStock = "DELETE FROM t_detailbuku WHERE f_idbuku = $idBukuExisting";
                mysqli_query($conn, $sqlDeleteStock);
            }
        } else {
            //source code untuk menambahkan riwayat
            $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
                if ($resultAdmin && mysqli_num_rows($resultAdmin) > 0) {
                    $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];
                    $tanggal = date("Y-m-d H:i:s");
                    $catatan = "Admin $adminUsername telah menambahkan buku baru dengan judul $judul";

                    $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                                    VALUES ('$admin_id', '$tanggal', '$catatan')";
                    mysqli_query($conn, $queryRiwayat);
                } else {
                    echo "<script>alert('Admin ID not found');</script>";
                }
            // Buku dengan judul yang berbeda bakal berhasil di tambahin
            $sqlAddBook = "INSERT INTO t_buku VALUES('', '$kategori', '$judul', '$images', '$pengarang', '$penerbit', '$deskripsi', '$tglinput', '$tglupdate')";
            mysqli_query($conn, $sqlAddBook);

            $idBukuTerakhir = mysqli_insert_id($conn);

            for ($i = 0; $i < $eksemplar; $i++) {
                $sqlAddStock = "INSERT INTO t_detailbuku VALUES(NULL, '$idBukuTerakhir', 'Tersedia')";
                mysqli_query($conn, $sqlAddStock);
            }
        }
    }
}

function getEksemplarForBook($bookId, $conn) {
    $getEksemplarQuery = "SELECT COUNT(*) as count FROM t_detailbuku WHERE f_idbuku = $bookId";
    $result = mysqli_query($conn, $getEksemplarQuery);
    $row = mysqli_fetch_assoc($result);

    return $row['count'];
}

function updateBuku(){
    global $conn;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        if (isset($_POST['updateBuku'])) {
            $kategori = $_POST['f_kategori'];
            $judul = $_POST['f_judul'];
            $pengarang = $_POST['f_pengarang'];
            $penerbit = $_POST['f_penerbit'];
            $deskripsi = $_POST['f_deskripsi'];
            $tglupdate = $_POST['f_tglupdate'];

            // Cek kalo ada buku dengan judul yang sama
            $checkIfExistsQuery = "SELECT * FROM t_buku WHERE f_judul = '$judul' AND f_id != $id";
            $resultCheckIfExists = mysqli_query($conn, $checkIfExistsQuery);

            if ($resultCheckIfExists && mysqli_num_rows($resultCheckIfExists) > 0) {
                // kalo ada, maka akan muncul pesan error seperti ini
                echo "Error: Buku Dengan Judul Sama Sudah Ada.";
            } else {
                $perubahan = "";
                // ambil semua data dari tabel buku
                $resultExistingBook = mysqli_query($conn, "SELECT * FROM t_buku WHERE f_id = '$id'");
                $row = mysqli_fetch_assoc($resultExistingBook);

                // ambil id buku
                $oldCategoryId = $row['f_idkategori'];

                // ambil id kategori
                $resultOldCategory = mysqli_query($conn, "SELECT f_kategori FROM t_kategori WHERE f_id = '$oldCategoryId'");
                $rowOldCategory = mysqli_fetch_assoc($resultOldCategory);
                $oldCategoryName = $rowOldCategory['f_kategori'];

                if ($row['f_idkategori'] != $kategori) {
                    // Ganti kategori yang lama dengan yang baru
                    $resultNewCategory = mysqli_query($conn, "SELECT f_kategori FROM t_kategori WHERE f_id = '$kategori'");
                    $rowNewCategory = mysqli_fetch_assoc($resultNewCategory);
                    $newCategoryName = $rowNewCategory['f_kategori'];
                    
                    // Print riwayat perubahan kategori
                    $perubahan .= "kategori dari '$oldCategoryName' (ID: $oldCategoryId) menjadi '$newCategoryName' (ID: $kategori), ";
                }
                


                if ($row['f_judul'] != $judul) {
                    $perubahan .= "judul buku dari '{$row['f_judul']}' menjadi '$judul', ";
                }
                if ($row['f_pengarang'] != $pengarang) {
                    $perubahan .= "nama pengarang dari '{$row['f_pengarang']}' menjadi '$pengarang', ";
                }
                if ($row['f_penerbit'] != $penerbit) {
                    $perubahan .= "nama penerbit dari '{$row['f_penerbit']}' menjadi '$penerbit', ";
                }
                if ($row['f_deskripsi'] != $deskripsi) {
                    $perubahan .= "deskripsi buku dari '{$row['f_deskripsi']}' menjadi '$deskripsi', ";
                }

                $perubahan = rtrim($perubahan, ', ');

                $admin_id = $_SESSION['id'];

                // Ambil data username admin yang ngubah buu
                $resultAdmin = mysqli_query($conn, "SELECT f_username FROM t_admin WHERE f_id = '$admin_id'");
                $adminUsername = mysqli_fetch_assoc($resultAdmin)['f_username'];

                // Ambil judul buku yang diubah admin
                $resultChangedBook = mysqli_query($conn, "SELECT f_judul FROM t_buku WHERE f_id = '$id'");
                $changedBookTitle = mysqli_fetch_assoc($resultChangedBook)['f_judul'];

                $tanggal = date("Y-m-d H:i:s");

                // print riwayat perubahan
                $catatan = mysqli_real_escape_string($conn, "Admin $adminUsername melakukan perubahan $perubahan pada buku dengan judul '$changedBookTitle'");

                if ($_FILES['f_gambar']['name']) {
                    $newImage = $_FILES['f_gambar']['name'];
                    $source = $_FILES['f_gambar']['tmp_name'];
                    $folder = '../gambar/';

                    move_uploaded_file($source, $folder.$newImage);

                    $updateSql = "UPDATE t_buku 
                                  SET f_idkategori='$kategori', f_judul='$judul', f_pengarang='$pengarang', 
                                      f_penerbit='$penerbit', f_deskripsi='$deskripsi',f_tglupdate = '$tglupdate', f_gambar='$newImage' 
                                  WHERE f_id = $id";
                } else {
                    $updateSql = "UPDATE t_buku 
                                  SET f_idkategori='$kategori', f_judul='$judul', f_pengarang='$pengarang', 
                                      f_penerbit='$penerbit', f_deskripsi='$deskripsi', f_tglupdate='$tglupdate'
                                  WHERE f_id = $id";
                }

                // jalanin query perubahan
                if (mysqli_query($conn, $updateSql)) {
                    // masukin data ke tabel riwayat
                    $queryRiwayat = "INSERT INTO t_riwayat (f_idadmin, f_tanggalriwayat, f_catatan) 
                                    VALUES ('$admin_id', '$tanggal', '$catatan')";
                    mysqli_query($conn, $queryRiwayat);
                    echo "<script>alert('Berhasil mengubah data'); document.location.href ='./read.php';</script>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }

                // Update eksemplar if it's provided in the form
                if (isset($_POST['eksemplar'])) {
                    $newEksemplar = (int)$_POST['eksemplar'];
                    $currentEksemplar = getEksemplarForBook($id, $conn);

                    if ($newEksemplar > $currentEksemplar) {
                        // Add new stock
                        $numToAdd = $newEksemplar - $currentEksemplar;
                        for ($i = 0; $i < $numToAdd; $i++) {
                            $sqlAddStock = "INSERT INTO t_detailbuku VALUES(NULL, '$id', 'Tersedia')";
                            mysqli_query($conn, $sqlAddStock);
                        }
                    } elseif ($newEksemplar < $currentEksemplar) {
                        // Remove excess stock
                        $numToRemove = $currentEksemplar - $newEksemplar;
                        $sqlRemoveStock = "DELETE FROM t_detailbuku WHERE f_idbuku = $id LIMIT $numToRemove";
                        mysqli_query($conn, $sqlRemoveStock);
                    }
                }
            }
        }
    }
}




//FUNCTION PENCARIAN KATEGORI
function srckategori($key)
{
    $query = "SELECT * FROM t_kategori
    WHERE
    f_kategori LIKE '%$key%'";
    return connect($query);
}
function srcadmin($key)
{
    $query = "SELECT * FROM t_admin
    WHERE
    f_username LIKE '%$key%' OR
    f_nama LIKE '%$key%' OR
    f_level LIKE '%$key% OR
    f_status LIKE '%$key%'";
    return connect($query);
}
function srcbuku($key)
{
    $query = "SELECT t_buku.*, t_kategori.f_kategori
    FROM t_buku
    LEFT JOIN t_kategori ON t_buku.f_idkategori = t_kategori.f_id
    WHERE
    f_penerbit LIKE '%$key%' OR
    f_judul LIKE '%$key%' OR
    f_pengarang LIKE '%$key%'";
    return connect($query);
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body{
            padding: 20px;
            overflow-x: hidden;
        }
        .nav{
            justify-content: center;
            margin: 10px;
        }
        #buttonlaporan{
          height: 30vh;
          width: 35vh;
        }
    </style>
  </head>
  <body>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>