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
    <div class="status-callout status-callout-info">
        <strong>Account setup:</strong> Required fields are marked with an asterisk. Choose the role carefully because it controls module visibility.
    </div>
    <form method="post" action="<?= site_url('admin/users') ?>" class="stack-md">
        <?= csrf_field() ?>

        <div class="field field-required">
            <label for="username">Username</label>
            <input id="username" name="username" autocomplete="username" placeholder="john.doe" value="<?= esc((string) old('username')) ?>" required>
            <?php if (isset($errors['username'])): ?>
                <p class="field-error"><?= esc($errors['username']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field field-required">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" autocomplete="email" placeholder="john@company.local" value="<?= esc((string) old('email')) ?>" required>
            <?php if (isset($errors['email'])): ?>
                <p class="field-error"><?= esc($errors['email']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field field-required">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <input id="password" type="password" name="password" autocomplete="new-password" placeholder="Minimum 8 characters" minlength="8" required>
                <button type="button" class="input-toggle-btn" data-pw-toggle="password" aria-label="Show password">
                    <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <?php if (isset($errors['password'])): ?>
                <p class="field-error"><?= esc($errors['password']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field field-required">
            <label for="password_confirm">Confirm Password</label>
            <div class="input-wrapper">
                <input id="password_confirm" type="password" name="password_confirm" autocomplete="new-password" placeholder="Re-enter password" required>
                <button type="button" class="input-toggle-btn" data-pw-toggle="password_confirm" aria-label="Show password">
                    <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <?php if (isset($errors['password_confirm'])): ?>
                <p class="field-error"><?= esc($errors['password_confirm']) ?></p>
            <?php endif; ?>
        </div>

        <div class="field field-required">
            <label for="role">User Role</label>
            <select id="role" name="role" required>
                <option value="">-- Select Role --</option>
                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Administrator</option>
                <option value="employee" <?= old('role') === 'employee' ? 'selected' : '' ?>>Employee</option>
                <option value="it_staff" <?= old('role') === 'it_staff' ? 'selected' : '' ?>>IT Staff</option>
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
