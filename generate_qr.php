<?php 
require 'koneksi.php';
require 'cek.php';
require 'assets/phpqrcode/qrlib.php';

// If the form is not submitted, show all users
$result = $koneksi->query("SELECT username FROM login");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    // Fetch the employee's name from the database
    $employeeQuery = $koneksi->prepare("SELECT nama FROM karyawan WHERE id_karyawan = ?");
    $employeeQuery->bind_param('i', $id);
    $employeeQuery->execute();
    $employeeResult = $employeeQuery->get_result();
    
    if ($employeeResult->num_rows > 0) {
        $employee = $employeeResult->fetch_assoc();
        $nama = $employee['nama'];
        
        // Generate QR Code with ID and Name
        $tempDir = 'images/';
        $data = "ID: $id\nNama: $nama";
        $fileName = $tempDir . 'QR_' . $id . '.png';
        QRcode::png($data, $fileName, QR_ECLEVEL_L, 10);
        
        $qrGenerated = true;
        $qrFile = $fileName;
    } else {
        $error = "Karyawan tidak ditemukan!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body onLoad="pindah()">

<div class="sidebar">
    <div class="sidetitle">
        <img class="logo" src="images/kemenkes.svg" alt="Logo">
        <h5>DINAS KESEHATAN KABUPATEN SUMBAWA</h5>
    </div>

    <div class="sidemenu">
        <a href="index.php"><i class='bx bxs-dashboard'></i> Dashboard</a>
    </div>
    <div class="sidemenu">
        <a href="karyawan.php"><i class='bx bx-folder'></i> Data Karyawan</a>
    </div>
    <div class="sidemenu">
        <a href="cetak.php"><i class='bx bx-qr'></i> Cetak QR</a>
    </div>
    <div class="sidemenu">
        <a href="scan.php"><i class='bx bx-qr-scan' ></i> Scan QR</a>
    </div>
    <div class="sidemenu">
        <a href="absensi.php"><i class='bx bxs-file-blank'></i> Data Absensi</a>
    </div>
    <div class="sidemenu">
        <a href="logout.php"><i class='bx bx-log-out'></i> Logout</a>
    </div>
</div>   

<div class="container">
    <div class="topbar">
        <span class="toggle-btn">&#9776;</span>
        <span>Welcome, 
        <?php
        while ($user_data = mysqli_fetch_array($result)) {
            echo $user_data['username'];
        }
        ?>
        </span>
        <img src="images/profile.jpeg" alt="Admin">
    </div>

    <div class="content">
        <div class="form-container">
            <h2>Generate QRCode</h2>
            <form method="POST" action="cetak.php">
                <div class="form-group">
                    <label for="exampleInputEmail1">INPUT ID KARYAWAN DI SINI</label>
                    <input type="text" id="id" name="id" class="form-control" placeholder="Masukkan ID Karyawan yang terdaftar di Data Karyawan" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn3d">Submit</button>
            </form>
        </div>
        <div class="result-container">
            <h2>Informasi QRCode akan muncul disini</h2>
            <div id="qr-result">
                <?php 
                if (isset($qrGenerated) && $qrGenerated) {
                    echo '<p>Nama: ' . htmlspecialchars($nama) . '</p>';
                    echo '<img src="' . htmlspecialchars($qrFile) . '" alt="QRCode">';
                } elseif (isset($error)) {
                    echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">
    function pindah() {
        $('#id').focus();
    }
</script>
</body>
</html>
