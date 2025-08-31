<?php
// Konfigurasi koneksi
$host = "localhost";
$user = "root";        // ganti jika user MySQL berbeda
$pass = "";            // ganti jika ada password
$db   = "akademik_db";

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil parameter search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Query ambil semua data mahasiswa dengan search
if (!empty($search)) {
    $sql = "SELECT nim, nama, umur FROM mahasiswa WHERE nama LIKE '%$search%' OR nim LIKE '%$search%' ORDER BY nim ASC";
} else {
    $sql = "SELECT nim, nama, umur FROM mahasiswa ORDER BY nim ASC";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Mahasiswa</title>
</head>
<body>
    <h2>Daftar Mahasiswa</h2>

    <!-- Search Box -->
    <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Cari nama atau NIM..." value="<?= ($search); ?>" style="padding: 5px; width: 200px;">
        <input type="submit" value="Cari" style="padding: 5px;">
        <?php if (!empty($search)): ?>
            <a href="displaymahasiswa.php" style="margin-left: 10px;">Reset</a>
        <?php endif; ?>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Umur</th>
                <th>Aksi</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['nim']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['umur']; ?></td>
                <td>
                    <a href="detail_mahasiswa.php?nim=<?= urlencode($row['nim']); ?>">View Detail</a>
                </td>
            </tr>
            <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Tidak ada data mahasiswa<?= !empty($search) ? " yang cocok dengan '$search'" : ''; ?>.</p>
        <?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>