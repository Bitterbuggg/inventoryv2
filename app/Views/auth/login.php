<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - InventoryV2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 560px; }
        .error { color: #b00020; margin-bottom: 1rem; }
        .message { color: #0a7a2a; margin-bottom: 1rem; }
        label { display: block; margin-top: 1rem; font-weight: 600; }
        input { width: 100%; padding: 0.55rem; margin-top: 0.35rem; }
        button { margin-top: 1rem; padding: 0.6rem 1rem; }
    </style>
</head>
<body>
    <h1>Login</h1>

    <?php if (session('message')): ?>
        <div class="message"><?= esc((string) session('message')) ?></div>
    <?php endif ?>

    <?php if (session('error')): ?>
        <div class="error"><?= esc((string) session('error')) ?></div>
    <?php endif ?>

    <?php if (session('errors')): ?>
        <div class="error">
            <?php foreach ((array) session('errors') as $error): ?>
                <div><?= esc((string) $error) ?></div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <label for="identifier">Email or Username</label>
        <input id="identifier" name="identifier" value="<?= esc((string) old('identifier')) ?>" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p><a href="<?= site_url('signup') ?>">Create account</a></p>
</body>
</html>

