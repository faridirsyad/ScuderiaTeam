<?php
//session
session_start();

//koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_qr";
//membuat koneksi ke database
$koneksi = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

?>


