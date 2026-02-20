<?php

declare(strict_types=1);

$title = 'Admin Users - InventoryV2';
$pageTitle = 'Manage Users';
$pageSubtitle = 'Assign role groups for route access and workflow permissions.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Manage Users'],
];

$availableRoles = ['admin', 'employee', 'it_staff'];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<section class="card stack-md">
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
                <?php foreach ($users as $user): ?>
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
<?= $this->endSection() ?>
