<?php

declare(strict_types=1);

$title = 'Signup - InventoryV2';
$pageTitle = 'Create Account';
$pageSubtitle = 'Register and wait for admin approval before first login.';
?>
<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('content') ?>
<form method="post" action="<?= site_url('signup') ?>" class="stack-md">
    <?= csrf_field() ?>

    <div class="field">
        <label for="username">Username</label>
        <input id="username" name="username" value="<?= esc((string) old('username')) ?>" required>
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="<?= esc((string) old('email')) ?>" required>
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
    </div>

    <div class="field">
        <label for="password_confirm">Confirm Password</label>
        <input id="password_confirm" type="password" name="password_confirm" required>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
</form>

<p class="muted">Already have an account? <a href="<?= site_url('login') ?>">Login</a></p>
<?= $this->endSection() ?>
