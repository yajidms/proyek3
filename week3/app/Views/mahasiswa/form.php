<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'Form Mahasiswa') ?></title>
    <style>
        body { font-family: Arial; }
        .wrap { width: 600px; margin: 30px auto; }
        label { display:block; margin-top:10px; }
        input { width: 100%; padding: 8px; }
        .err { color:#b00; }
    </style>
</head>
<body>
<div class="wrap">
    <h2><?= esc($title ?? 'Form Mahasiswa') ?></h2>

    <?php $errors = session()->getFlashdata('errors') ?? []; ?>
    <?php if ($errors): ?>
        <div class="err">
            <?php foreach ($errors as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php $isEdit = isset($row); ?>
    <form method="post" action="<?= $isEdit ? site_url('mahasiswa/' . $row['ID']) : site_url('mahasiswa') ?>">
        <?= csrf_field() ?>
        <label>NIM
            <input type="text" name="NIM" value="<?= esc(old('NIM', $row['NIM'] ?? '')) ?>">
        </label>
        <label>Nama
            <input type="text" name="NAMA" value="<?= esc(old('NAMA', $row['NAMA'] ?? '')) ?>">
        </label>
        <label>Umur
            <input type="number" name="UMUR" value="<?= esc(old('UMUR', $row['UMUR'] ?? '')) ?>">
        </label>

        <button type="submit">Simpan</button>
        <a href="<?= site_url('mahasiswa') ?>">Batal</a>
    </form>
</div>
</body>
</html>
