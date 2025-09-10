<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'Website SMA XYZ') ?></title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 900px; margin: 0 auto; border: 1px solid #000; }
        .header, .footer { padding: 15px; border-bottom: 1px solid #000; }
        .footer { border-top: 1px solid #000; border-bottom: none; }
        .menu { border-bottom: 1px solid #000; padding: 10px; text-align: left; }
        .menu a { margin-right: 20px; text-decoration: none; }
        .content { padding: 30px; min-height: 200px; }
        table { margin: 0 auto; border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid black; padding: 8px; }
        .right { float: right; }
        .msg { color: green; }
        .err { color: #b00; }
        .actions a { margin-right: 8px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>WEBSITE SMA XYZ</h2>
    </div>

    <div class="menu">
        <a href="<?= site_url('/') ?>">Home</a>
        <a href="<?= site_url('mahasiswa') ?>">Data Mahasiswa</a>
        <?php if (session()->get('isLoggedIn')): ?>
            <span class="right"><a href="<?= site_url('logout') ?>">Logout</a></span>
        <?php else: ?>
            <span class="right"><a href="<?= site_url('login') ?>">Login</a> | <a href="<?= site_url('register') ?>">Register</a></span>
        <?php endif; ?>
    </div>

    <div class="content">
        <?php if (session()->getFlashdata('message')): ?>
            <p class="msg"><?= esc(session()->getFlashdata('message')) ?></p>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <p class="err"><?= esc(session()->getFlashdata('error')) ?></p>
        <?php endif; ?>

        <h3>Home</h3>
        <p>Selamat datang di website SMA XYZ.</p>

        <hr>
        <h3>Data Mahasiswa</h3>
        <div style="text-align:right;margin-bottom:10px;">
            <?php if (session()->get('isLoggedIn')): ?>
                <a href="<?= site_url('mahasiswa/create') ?>">+ Tambah</a>
            <?php endif; ?>
        </div>
        <?php if (!empty($mahasiswa) && is_array($mahasiswa)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Umur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($mahasiswa as $m): ?>
                    <tr>
                        <td><?= esc($m['ID']) ?></td>
                        <td><?= esc($m['NIM']) ?></td>
                        <td><?= esc($m['NAMA']) ?></td>
                        <td><?= esc($m['UMUR']) ?></td>
                        <td class="actions">
                            <a href="<?= site_url('mahasiswa/' . $m['ID']) ?>">Detail</a>
                            <?php if (session()->get('isLoggedIn')): ?>
                                <a href="<?= site_url('mahasiswa/' . $m['ID'] . '/edit') ?>">Edit</a>
                                <a href="<?= site_url('mahasiswa/' . urlencode((string)$m['ID']) . '/delete') ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Belum ada data.
            <?php if (session()->get('isLoggedIn')): ?>
                <a href="<?= site_url('mahasiswa/create') ?>">Tambah pertama</a>
            <?php endif; ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <b>Bandung - Jawa Barat</b>
    </div>
</div>
</body>
</html>
