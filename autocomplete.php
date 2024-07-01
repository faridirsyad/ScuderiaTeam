<?php
include 'koneksi.php';

$term = $_GET['term'];
$query = $conn->query("SELECT nama FROM karyawan WHERE nama LIKE '%$term%'");

$suggestions = array();
while ($row = $query->fetch_assoc()) {
    $suggestions[] = $row['nama'];
}

echo json_encode($suggestions);
?>