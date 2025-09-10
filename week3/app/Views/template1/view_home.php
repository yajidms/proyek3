<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; padding:0; }
        .header { background:#007bff; color:#fff; padding:15px; text-align:center; }
        .menu { background:#f4f4f4; padding:10px; text-align:center; }
        .menu a { margin:0 10px; text-decoration:none; color:#007bff; }
        .content { padding:20px; min-height:300px; }
        .footer { background:#333; color:#fff; text-align:center; padding:10px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>My Website</h2>
    </div>

    <div class="menu">
        <a href="<?= base_url('home') ?>">Home</a>
        <a href="<?= base_url('berita') ?>">Berita</a>
    </div>

    <div class="content">
        <h1>Selamat Datang di Website Kami</h1>
        <p>Ini adalah halaman utama website kami. Di sini Anda dapat menemukan informasi terbaru dan layanan yang kami sediakan.</p>
        <p>Website ini menyediakan berbagai macam informasi menarik seputar teknologi, pendidikan, dan berita terkini. Kami berkomitmen untuk memberikan konten berkualitas dan bermanfaat bagi para pengunjung.</p>
        <p>Jangan lupa untuk mengunjungi halaman berita untuk mendapatkan update informasi terbaru dari kami.</p>
        <?= $content ?>
    </div>

    <div class="footer">
        <p>&copy; <?= date('Y') ?> My Website</p>
    </div>

</body>
</html>