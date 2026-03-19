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
                        <th>Module Access</th>
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

                        $isAdmin = in_array('admin', $userGroups, true);

                        $modules = [
                            'procurement' => 'procurement.view',
                            'receiving'   => 'receiving.view',
                            'inventory'   => 'inventory.issuance.create',
                            'reports'     => 'reports.view',
                        ];
                        ?>
                        <tr>
                            <td><?= esc($userId) ?></td>
                            <td><?= esc($username) ?></td>
                            <td><?= esc($email) ?></td>
                            <td><?= esc(implode(', ', $userGroups)) ?></td>
                            <td>
                                <?php if ($isAdmin): ?>
                                    <span class="badge" style="background: var(--color-brand-100); color: var(--color-brand-700);">Full Access (Admin)</span>
                                <?php else: ?>
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                        <?php foreach ($modules as $modName => $perm): ?>
                                            <?php $hasAccess = $user->hasPermission($perm); ?>
                                            <form class="inline-form" method="post" action="<?= site_url('admin/users/' . $userId . '/permissions/module') ?>">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="module" value="<?= $modName ?>">
                                                <input type="hidden" name="action" value="<?= $hasAccess ? 'revoke' : 'grant' ?>">
                                                <button type="submit" 
                                                        class="badge" 
                                                        style="cursor: pointer; border: none; font-family: inherit; transition: opacity 0.2s; background: <?= $hasAccess ? '#dcfce7' : '#f1f5f9' ?>; color: <?= $hasAccess ? '#166534' : '#475569' ?>;"
                                                        title="Click to <?= $hasAccess ? 'revoke' : 'grant' ?> <?= ucfirst($modName) ?> access">
                                                    <?= ucfirst($modName) ?>: <?= $hasAccess ? 'ON' : 'OFF' ?>
                                                </button>
                                            </form>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif ?>
                            </td>
                            <td>
                                <div class="button-group-compact">
                                    <a href="<?= site_url('admin/users/' . $userId . '/edit') ?>" class="btn btn-outline btn-small" title="Edit user details">Edit</a>

                                    <?php if (! $isAdmin): ?>
                                        <form class="inline-form"
                                              method="post"
                                              action="<?= site_url('admin/users/' . $userId . '/delete') ?>"
                                              data-confirm="Delete this user account? This action cannot be undone."
                                              data-confirm-title="Delete User">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-small" title="Permanently delete this user">Delete</button>
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
