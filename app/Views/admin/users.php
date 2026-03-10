<?php

declare(strict_types=1);

$title = 'Admin Users - InventoryV2';
$pageTitle = 'Manage Users';
$pageSubtitle = 'Manage user accounts. Role selection is available only when creating users.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Manage Users'],
];

$usersList = $users ?? [];
$totalUsers = count($usersList);
$adminCount = 0;
$employeeCount = 0;
$itStaffCount = 0;

foreach ($usersList as $userRow) {
    $groups = $userRow->getGroups() ?? [];

    if (in_array('admin', $groups, true)) {
        $adminCount++;
        continue;
    }

    if (in_array('it_staff', $groups, true)) {
        $itStaffCount++;
        continue;
    }

    $employeeCount++;
}
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-primary" href="<?= site_url('admin/users/create') ?>">Create User</a>
<a class="btn btn-outline" href="<?= site_url('admin/users?export=csv') ?>">Export CSV</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Users</p>
                <p class="kpi-value"><?= esc((string) $totalUsers) ?></p>
                <p class="kpi-note">Registered user accounts.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Admins</p>
                <p class="kpi-value"><?= esc((string) $adminCount) ?></p>
                <p class="kpi-note">Full administration access.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">IT Staff</p>
                <p class="kpi-value"><?= esc((string) $itStaffCount) ?></p>
                <p class="kpi-note">Operational + technical access.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Employees</p>
                <p class="kpi-value"><?= esc((string) $employeeCount) ?></p>
                <p class="kpi-note">Standard internal user role.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Groups</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersList as $user): ?>
                        <?php
                        $userId = '';
                        $username = '';
                        $email = '';
                        $userGroups = [];

                        if (is_object($user)) {
                            $userId = (string) ($user->id ?? '');
                            $username = (string) ($user->username ?? '');
                            $email = (string) ($user->email ?? '');

                            if (method_exists($user, 'getGroups')) {
                                $userGroups = $user->getGroups() ?? [];
                            }
                        }

                        ?>
                        <tr>
                            <td><?= esc($userId) ?></td>
                            <td><?= esc($username) ?></td>
                            <td><?= esc($email) ?></td>
                            <td><?= esc(implode(', ', $userGroups)) ?></td>
                            <td>
                                <div class="button-group-compact">
                                    <a href="<?= site_url('admin/users/' . $userId . '/edit') ?>" class="btn btn-outline btn-small">Edit</a>
                                    <?php if (! in_array('admin', $userGroups, true)): ?>
                                        <form class="inline-form" method="post" action="<?= site_url('admin/users/' . $userId . '/delete') ?>" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
