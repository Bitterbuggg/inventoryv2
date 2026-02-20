<?php

declare(strict_types=1);

$title = 'Admin Users - InventoryV2';
$pageTitle = 'Manage Users';
$pageSubtitle = 'Assign role groups for route access and workflow permissions.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Manage Users'],
];
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
                    <tr>
                        <td><?= esc((string) $user->id) ?></td>
                        <td><?= esc((string) ($user->username ?? '')) ?></td>
                        <td><?= esc((string) ($user->email ?? '')) ?></td>
                        <td><?= esc(implode(', ', $user->getGroups() ?? [])) ?></td>
                        <td>
                            <form class="inline-form" method="post" action="<?= site_url('admin/users/' . $user->id . '/role') ?>">
                                <?= csrf_field() ?>
                                <select name="role" aria-label="Assign role group">
                                    <option value="admin">admin</option>
                                    <option value="employee">employee</option>
                                    <option value="it_staff">it_staff</option>
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
