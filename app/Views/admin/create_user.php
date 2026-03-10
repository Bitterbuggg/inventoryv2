<?php

declare(strict_types=1);

$title = 'Create User - InventoryV2';
$pageTitle = 'Create User Account';
$pageSubtitle = 'Add a new user account and assign their role.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Manage Users', 'url' => site_url('admin/users')],
    ['label' => 'Create User'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="card stack-md max-width-sm">
    <form method="post" action="<?= site_url('admin/users') ?>" class="stack-md">
        <?= csrf_field() ?>

        <div class="field">
            <label for="username">Username</label>
            <input id="username" name="username" autocomplete="username" placeholder="john.doe" value="<?= esc((string) old('username')) ?>" required>
            <?php if (isset($errors['username'])): ?>
                <p class="field-error"><?= esc($errors['username']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" autocomplete="email" placeholder="john@company.local" value="<?= esc((string) old('email')) ?>" required>
            <?php if (isset($errors['email'])): ?>
                <p class="field-error"><?= esc($errors['email']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" autocomplete="new-password" placeholder="Minimum 8 characters" required>
            <?php if (isset($errors['password'])): ?>
                <p class="field-error"><?= esc($errors['password']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="password_confirm">Confirm Password</label>
            <input id="password_confirm" type="password" name="password_confirm" autocomplete="new-password" placeholder="Re-enter password" required>
            <?php if (isset($errors['password_confirm'])): ?>
                <p class="field-error"><?= esc($errors['password_confirm']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="role">User Role</label>
            <select id="role" name="role" required>
                <option value="">-- Select Role --</option>
                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>admin</option>
                <option value="employee" <?= old('role') === 'employee' ? 'selected' : '' ?>>employee</option>
                <option value="it_staff" <?= old('role') === 'it_staff' ? 'selected' : '' ?>>it_staff</option>
            </select>
            <?php if (isset($errors['role'])): ?>
                <p class="field-error"><?= esc($errors['role']) ?></p>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="<?= site_url('admin/users') ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
