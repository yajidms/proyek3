<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "akademik_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['nim'])) {
    $nim = $conn->real_escape_string($_GET['nim']);
    $sql = "SELECT * FROM mahasiswa WHERE nim='$nim'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $mhs = $result->fetch_assoc();
    } else {
        $mhs = null;
    }
} else {
    $mhs = null;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Mahasiswa</title>
</head>
<body>
    <h2>Detail Mahasiswa</h2>

    <?php if ($mhs): ?>
        <p><strong>NIM:</strong> <?= $mhs['NIM']; ?></p>
        <p><strong>Nama:</strong> <?= $mhs['NAMA']; ?></p>
        <p><strong>Umur:</strong> <?= $mhs['UMUR']; ?></p>
    <?php else: ?>
        <p>Data tidak ditemukan.</p>
    <?php endif; ?>

    <p><a href="displaymahasiswa.php">Kembali ke Daftar</a></p>
</body>
</html>
<?php
$conn->close();
?>