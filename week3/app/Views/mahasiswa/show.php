<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'Detail Mahasiswa') ?></title>
    <style>
        body { font-family: Arial; }
        .wrap { width: 700px; margin: 30px auto; }
    </style>
</head>
<body>
<div class="wrap">
    <h2>Detail Mahasiswa</h2>
    <p><b>ID:</b> <?= esc($row['ID']) ?></p>
    <p><b>NIM:</b> <?= esc($row['NIM']) ?></p>
    <p><b>Nama:</b> <?= esc($row['NAMA']) ?></p>
    <p><b>Umur:</b> <?= esc($row['UMUR']) ?></p>

    <p>
        <a href="<?= site_url('mahasiswa') ?>">Kembali</a>
        <?php if (session()->get('isLoggedIn')): ?>
            | <a href="<?= site_url('mahasiswa/' . $row['ID'] . '/edit') ?>">Edit</a>
        <?php endif; ?>
    </p>
</div>
</body>
</html>
