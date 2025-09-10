<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'Login') ?></title>
    <style>
        body { font-family: Arial; }
        .wrap { width: 400px; margin: 40px auto; }
        label { display:block; margin-top:10px; }
        .err { color:#b00; }
        .msg { color: green; }
    </style>
</head>
<body>
<div class="wrap">
    <h2>Login</h2>
    <?php if (session()->getFlashdata('message')): ?>
        <p class="msg"><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p class="err"><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="err">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <div><?= esc($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <label>Username
            <input type="text" name="username" value="<?= esc(old('username')) ?>">
        </label>
        <label>Password
            <input type="password" name="password">
        </label>
        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="<?= site_url('register') ?>">Register</a></p>
    <p><a href="<?= site_url('mahasiswa') ?>">Kembali</a></p>
 </div>
</body>
<html>
