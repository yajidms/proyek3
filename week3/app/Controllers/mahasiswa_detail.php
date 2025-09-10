<?php
// koneksi database
$host = "localhost";
$user = "root";   // ganti sesuai user DB
$pass = "";       // ganti sesuai password DB
$db   = "akademik_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$nim = isset($_GET['nim']) ? $_GET['nim'] : '';
$sql = "SELECT * FROM mahasiswa WHERE nim='$nim'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo "<h3>Detail Mahasiswa</h3>";
    echo "<p><b>NIM:</b> " . htmlspecialchars($data['ID']) . "</p>";
    echo "<p><b>NIM:</b> " . htmlspecialchars($data['NIM']) . "</p>";
    echo "<p><b>Nama:</b> " . htmlspecialchars($data['NAMA']) . "</p>";
    echo "<p><b>Umur:</b> " . htmlspecialchars($data['UMUR']) . "</p>";
} else {
    echo "<p>Data tidak ditemukan.</p>";
}

$conn->close();
?>
<br>
<a href="template_02.php?page=mhsw">Kembali</a>
