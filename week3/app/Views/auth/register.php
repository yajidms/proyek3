<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'Register') ?></title>
    <style>
        body { font-family: Arial; }
        .wrap { width: 420px; margin: 40px auto; }
        label { display:block; margin-top:10px; }
        .err { color:#b00; }
    </style>
</head>
<body>
<div class="wrap">
    <h2>Register</h2>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="err">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <div><?= esc($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('register') ?>">
        <?= csrf_field() ?>
        <label>Username
            <input type="text" name="username" value="<?= esc(old('username')) ?>">
        </label>
        <label>Password
            <input type="password" name="password">
        </label>
        <label>Konfirmasi Password
            <input type="password" name="password_confirm">
        </label>
        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="<?= site_url('login') ?>">Login</a></p>
    <p><a href="<?= site_url('mahasiswa') ?>">Kembali</a></p>
</div>
</body>
</html>
