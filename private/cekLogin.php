<?php
session_start();

require './function.php';

// CEK UNTUK LOGIN MASING-MASING AKUN, BERDASSARKAN PILIHAN PADA HALAMAN INDEX

if (isset($_POST['loginadmin'])) {
    $user = $_POST['f_username'];
    $password = md5($_POST['f_password']);

    $sql = "SELECT t_admin.f_id AS idadmin, t_admin.* FROM t_admin WHERE f_username='$user' AND f_password='$password' AND f_level='Admin'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        $errorMessage = "Gagal Masuk! Nama pengguna atau kata sandi salah.";
        echo "<script>alert('$errorMessage'); window.location.href = '../index.html';</script>";
        exit();
    } else {
        if ($row['f_status'] != 'Aktif') {
            $errorMessage = "Gagal Masuk! Akun tidak aktif.";
            echo "<script>alert('$errorMessage'); window.location.href = '../index.html';</script>";
            exit();
        }


        $alertMessage = "Berhasil masuk!";
        //UP DATA KE SESSION JADI SAAT DI PANGGIL AKAN MUNCUL DATANYA
        $_SESSION['admin'] = $row['f_username'];
        $_SESSION['namaadmin'] = $row['f_nama'];
        $_SESSION['level'] = $row['f_level'];
        $_SESSION['id'] = $row['f_id'];

        echo "<script>alert('$alertMessage'); window.location.href = '../public/admin/index.php';</script>";
        exit();
    }
}

// LOGIN ANGGOTA
if (isset($_POST['loginanggota'])) {
    $f_username = $_POST['f_username'];
    $password = md5($_POST['f_password']);

    $sql = "SELECT * FROM t_anggota WHERE f_username='$f_username' AND f_password='$password' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        $errorMessage = "Gagal Masuk! Nama pengguna atau kata sandi salah.";
        echo "<script>alert('$errorMessage'); window.location.href = '../index.html';</script>";
        exit();
    } else {
        $alertMessage = "Berhasil masuk!";
        $_SESSION['anggota'] = $row['f_username'];
        $_SESSION['id'] = $row['f_id'];

        echo "<script>alert('$alertMessage'); window.location.href = '../public/anggota/index.php';</script>";
    }
}

//LOGIN PUSTAKAWAN
if (isset($_POST['loginpustakawan'])) {
    $user = $_POST['f_username'];
    $password = md5($_POST['f_password']);

    $sql = "SELECT * FROM t_admin WHERE f_username='$user' AND f_password='$password' AND f_level='Pustakawan'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        $errorMessage = "Gagal Masuk! Nama pengguna atau kata sandi salah.";
        echo "<script>alert('$errorMessage'); window.location.href = '../index.html';</script>";
        exit();
    } else {
        if ($row['f_status'] != 'Aktif') {
            $errorMessage = "Gagal Masuk! Akun tidak aktif.";
            echo "<script>alert('$errorMessage'); window.location.href = '../index.html';</script>";
            exit();
        }

        $alertMessage = "Berhasil masuk!";
        $_SESSION['pustakawan'] = $row['f_username'];
        $_SESSION['namapustakawan'] = $row['f_nama'];
        $_SESSION['level'] = $row['f_level'];
        $_SESSION['id'] = $row['f_id'];

        echo "<script>alert('$alertMessage'); window.location.href = '../public/pustakawan/index.php';</script>";
        exit();
    }
}
?>