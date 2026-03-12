<?php

declare(strict_types=1);

$userId = $user->id ?? 0;
$username = $user->username ?? '';
$email = $user->email ?? '';

$title = 'Edit User - InventoryV2';
$pageTitle = 'Edit User Account';
$pageSubtitle = 'Update user account details.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Manage Users', 'url' => site_url('admin/users')],
    ['label' => 'Edit User'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="card stack-md max-width-sm">
    <div class="status-callout status-callout-info">
        <strong>Profile update:</strong> Keep username/email unique. Role and permissions are managed from the users list.
    </div>
    <form method="post" action="<?= site_url('admin/users/' . $userId) ?>" class="stack-md">
        <?= csrf_field() ?>

        <div class="field field-required">
            <label for="username">Username</label>
            <input id="username" name="username" autocomplete="username" placeholder="john.doe" value="<?= esc((string) old('username', $username)) ?>" required>
            <?php if (isset($errors['username'])): ?>
                <p class="field-error"><?= esc($errors['username']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field field-required">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" autocomplete="email" placeholder="john@company.local" value="<?= esc((string) old('email', $email)) ?>" required>
            <?php if (isset($errors['email'])): ?>
                <p class="field-error"><?= esc($errors['email']) ?></p>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Update Account</button>
            <a href="<?= site_url('admin/users') ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
