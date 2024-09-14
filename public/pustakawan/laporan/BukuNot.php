<?php
require '../../../private/function.php';

$no = 1;

// Get the count of all rows from the t_buku table
$bukuseluruh = mysqli_num_rows(mysqli_query($conn, "SELECT f_judul FROM t_buku"));

// Fetch data for books that are not available
$bukupinjam = mysqli_query($conn, "SELECT DISTINCT t_detailbuku.f_id AS f_iddetailbuku, t_buku.f_id as f_id , f_judul, f_kategori, f_pengarang, f_penerbit, f_deskripsi FROM t_buku 
INNER JOIN t_kategori ON t_buku.f_idkategori=t_kategori.f_id
LEFT JOIN t_detailbuku ON t_buku.f_id = t_detailbuku.f_idbuku WHERE f_status='Tidak Tersedia'
GROUP BY t_buku.f_id ORDER BY t_detailbuku.f_id ASC");

// Get the count of available books
$bukutersedia = mysqli_num_rows(mysqli_query($conn, "SELECT f_judul FROM t_buku INNER JOIN t_detailbuku ON 
t_buku.f_id=t_detailbuku.f_idbuku WHERE f_status='Tersedia'"));

// Fetch data for the top 5 most borrowed books
$limabuku = mysqli_query($conn, "SELECT f_judul, COUNT(*) AS dipinjam FROM t_peminjaman 
INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id=t_detailpeminjaman.f_idpeminjaman 
INNER JOIN t_detailbuku ON t_detailpeminjaman.f_iddetailbuku=t_detailbuku.f_id 
INNER JOIN t_buku ON t_detailbuku.f_idbuku=t_buku.f_id 
WHERE NOT f_tanggalkembali = '0000-00-00'
GROUP BY f_judul ORDER BY COUNT(*) DESC");

// Fetch data for the top 5 most borrowing members
$limaanggota = mysqli_query($conn, "SELECT f_nama, COUNT(*) AS pinjam FROM t_anggota 
INNER JOIN t_peminjaman ON t_anggota.f_id=t_peminjaman.f_idanggota 
GROUP BY f_nama ORDER BY COUNT(*) DESC LIMIT 5");

// Fetch data for the members who have not returned books
$anggota = mysqli_query($conn, "SELECT f_nama, COUNT(*) AS kembali FROM t_anggota 
INNER JOIN t_peminjaman ON t_anggota.f_id=t_peminjaman.f_idanggota
INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id=t_detailpeminjaman.f_idpeminjaman 
WHERE f_tanggalkembali ='0000-00-00' 
GROUP BY f_nama ORDER BY COUNT(*)");

// Fetch all data for books that are not returned
$bukupinjam = mysqli_fetch_all($bukupinjam, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h1 {
            text-align: center;
        }

        .tombol {
            margin-left: 127px;
            margin-top: 20px;
        }
        
        .print button {
            background-color: #18283b;
            padding: 10px 535px;
            border: 1px solid #18283b;
        }

        .back a {
            background-color: #18283b;
            padding: 10px 527px;
        }

        .print button,
        .back a {
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .print button:hover,
        .back a:hover {
            background-color: #000;
        }

        @media print {
            body{
                visibility: hidden;
            }
            .container {
                visibility: visible;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>LAPORAN BUKU YANG BELUM DI KEMBALIKAN</h1>
        <table class="table table-bordered table-info">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Buku</th>
                    <th scope="col">Jumlah Buku Yang Belum Dikembalikan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bukupinjam as $b) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $b['f_judul'] ?></td>
                        <td>
                            <?php
                            // Get the count of not returned books for each book
                            $eksemplar = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM t_detailbuku WHERE f_status='Tidak Tersedia' AND f_idbuku = " . $b['f_id']));
                            echo $eksemplar;
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="tombol">
        <div class="print">
            <button id="printButton">Cetak</button>
        </div><br>
        <div class="back">
        <a href="../index.php">Kembali</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const printButton = document.getElementById("printButton");

            printButton.addEventListener("click", function () {
            window.print();
            });
        });

        window.print();
    </script>
</body>
</html>