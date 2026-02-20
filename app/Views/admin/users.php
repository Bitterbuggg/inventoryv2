<?php

declare(strict_types=1);

$title = 'Admin Users - InventoryV2';
$pageTitle = 'Manage Users';
$pageSubtitle = 'Assign role groups for route access and workflow permissions. Each user keeps one active role.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Manage Users'],
];

$availableRoles = ['admin', 'employee', 'it_staff'];

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
        <p class="muted">Assigning a role replaces the user's previous role assignment.</p>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Groups</th>
                        <th>Assign Group</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersList as $user): ?>
                        <?php
                        $userGroups = $user->getGroups() ?? [];
                        $currentRole = 'employee';
                        foreach ($availableRoles as $role) {
                            if (in_array($role, $userGroups, true)) {
                                $currentRole = $role;
                                break;
                            }
                        }
                        ?>
                        <tr>
                            <td><?= esc((string) $user->id) ?></td>
                            <td><?= esc((string) ($user->username ?? '')) ?></td>
                            <td><?= esc((string) ($user->email ?? '')) ?></td>
                            <td><?= esc(implode(', ', $userGroups)) ?></td>
                            <td>
                                <form class="inline-form" method="post" action="<?= site_url('admin/users/' . $user->id . '/role') ?>">
                                    <?= csrf_field() ?>
                                    <select name="role" aria-label="Assign role group">
                                        <option value="admin" <?= $currentRole === 'admin' ? 'selected' : '' ?>>admin</option>
                                        <option value="employee" <?= $currentRole === 'employee' ? 'selected' : '' ?>>employee</option>
                                        <option value="it_staff" <?= $currentRole === 'it_staff' ? 'selected' : '' ?>>it_staff</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Assign</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
