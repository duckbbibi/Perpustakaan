<?php
require '../../../private/function.php';

$sql = "SELECT f_judul, COUNT(*) AS dipinjam FROM t_peminjaman 
        INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id = t_detailpeminjaman.f_idpeminjaman 
        INNER JOIN t_detailbuku ON t_detailpeminjaman.f_iddetailbuku = t_detailbuku.f_id 
        INNER JOIN t_buku ON t_detailbuku.f_idbuku = t_buku.f_id 
        -- WHERE NOT f_tanggalkembali = '0000-00-00'
        GROUP BY f_judul ORDER BY COUNT(*) DESC LIMIT 5";

$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    // Fetch the data as an associative array
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Free the result set
    mysqli_free_result($result);
    
    $no = 1;
} else {
    // Handle the query error, e.g., log it or display an error message
    die('Error: ' . mysqli_error($conn));
}
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
<body onmouseover="keluar()">
    <div class="container mt-5">
        <h1>LAPORAN BUKU YANG SERING DI PINJAM</h1>
        <div class="table-katalog">
            <table class="table table-bordered table-info">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Buku</th>
                        <th scope="col">Jumlah Peminjaman Buku</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['f_judul'] ?></td>
                            <td><?= $row['dipinjam'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="tombol">
        <div class="print">
            <button id="printButton">Cetak</button>
        </div><br>
        <div class="back">
            <a href="../index.php">Kembali</a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const printButton = document.getElementById("printButton");

            printButton.addEventListener("click", function () {
            window.print();
            });
        });

        window.print();
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
