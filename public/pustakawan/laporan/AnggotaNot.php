<?php
require '../../../private/function.php';

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT f_nama, COUNT(*) AS kembali FROM t_anggota 
INNER JOIN t_peminjaman ON t_anggota.f_id = t_peminjaman.f_idanggota
INNER JOIN t_detailpeminjaman ON t_peminjaman.f_id = t_detailpeminjaman.f_idpeminjaman 
WHERE f_tanggalkembali ='0000-00-00' 
GROUP BY f_nama ORDER BY COUNT(*) DESC LIMIT 5";

// Execute the SQL query
$result = mysqli_query($conn, $sql);

// Fetch the results as an associative array
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($conn);

$no = 1;
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
        <h1>LAPORAN ANGGOTA YANG BELUM MENGEMBALIKAN BUKU</h1>
        <table class="table table-bordered table-info">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Anggota Yang Belum Mengembalikan</th>
                    <th scope="col">Buku</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['f_nama'] ?></td>
                        <td><?= $row['kembali'] ?></td>
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
