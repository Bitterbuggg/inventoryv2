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

    <div class="field field-required">
        <label for="identifier">Email or Username</label>
        <input id="identifier" name="identifier" autocomplete="username" placeholder="e.g. admin@local.test" value="<?= esc((string) old('identifier')) ?>" required aria-required="true">
    </div>

    <div class="field field-required">
        <label for="password">Password</label>
        <div class="input-wrapper">
            <input id="password" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" required aria-required="true">
            <button type="button"
                    class="input-toggle-btn"
                    data-pw-toggle="password"
                    aria-label="Show password">
                <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
</form>
<?= $this->endSection() ?>
