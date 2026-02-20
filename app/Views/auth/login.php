<?php

declare(strict_types=1);

$title = 'Login - InventoryV2';
$pageTitle = 'Login';
$pageSubtitle = 'Use your approved account credentials.';
?>
<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('content') ?>
<form method="post" action="<?= site_url('login') ?>" class="stack-md">
    <?= csrf_field() ?>

    <div class="field">
        <label for="identifier">Email or Username</label>
        <input id="identifier" name="identifier" value="<?= esc((string) old('identifier')) ?>" required>
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Login</button>
</form>

<p class="muted">No account yet? <a href="<?= site_url('signup') ?>">Create account</a></p>
<?= $this->endSection() ?>
