<?php

declare(strict_types=1);

$title = 'Admin Users - InventoryV2';
$pageTitle = 'Manage Users';
$pageSubtitle = 'Manage system accounts, roles, and access privileges.';
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

<?= $this->section('head') ?>
<style>
    :root {
        --v2-border: #b2e0eb;
        --v2-title: #00476b;
        --v2-label: #00668c;
        --v2-active-bg: #00638a;
        --v2-text-main: #1e3a8a;
        --v2-text-muted: #64748b;
    }

    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: calc(100vh - 120px);
        min-height: 640px;
        overflow: hidden;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        flex-shrink: 0;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--v2-border);
        border-radius: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .kpi-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-total { background: #f1f5f9; color: #475569; }
    .icon-admin { background: #f5f3ff; color: #8b5cf6; }
    .icon-it { background: #eff6ff; color: #2563eb; }
    .icon-employee { background: #ecfccb; color: #d97706; }

    .kpi-details {
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: center;
        min-width: 0;
    }

    .kpi-value {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--v2-title);
        line-height: 1.2;
        margin: 0;
    }

    .kpi-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--v2-text-muted);
        margin: 2px 0 0 0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .table-card {
        background: #ffffff;
        border: 1px solid var(--v2-border);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--v2-border);
        background: #ffffff;
        flex-shrink: 0;
        flex-wrap: wrap;
    }

    .table-toolbar h3 {
        margin: 0;
        font-size: 1.1rem;
        color: var(--v2-title);
        font-weight: 800;
    }

    .toolbar-copy p {
        margin: 2px 0 0 0;
        font-size: 0.75rem;
        color: var(--v2-text-muted);
    }

    .toolbar-controls {
        display: flex;
        gap: 8px;
        align-items: center;
        flex: 1;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .search-wrap {
        position: relative;
        width: 320px;
        max-width: 100%;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        width: 14px;
        height: 14px;
    }

    .search-input {
        width: 100%;
        padding: 8px 12px 8px 32px;
        font-size: 0.85rem;
        border: 1px solid var(--v2-border);
        border-radius: 6px;
        outline: none;
        color: var(--v2-text-main);
        background: #ffffff;
        transition: all 0.2s;
    }

    .search-input:focus {
        border-color: var(--v2-label);
        box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1);
    }

    .btn-create-user {
        padding: 8px 16px;
        font-weight: 800;
        font-size: 0.85rem;
        box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);
    }

    .table-scroll-container {
        flex: 1;
        overflow: auto;
        background: #ffffff;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #ffffff !important;
        padding: 14px 16px;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 800;
        color: var(--v2-title);
        border-bottom: 2px solid var(--v2-border);
        text-align: left;
        letter-spacing: 0.05em;
        vertical-align: middle;
    }

    .modern-table td {
        padding: 12px 16px;
        font-size: 0.85rem;
        color: var(--v2-text-main);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .modern-table tr:last-child td {
        border-bottom: none;
    }

    .modern-table tr:hover td {
        background: #f8fafc;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f0f9ff;
        color: var(--v2-label);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .user-meta {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .user-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--v2-title);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-email {
        font-size: 0.8rem;
        color: var(--v2-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .role-admin { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
    .role-it { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .role-employee { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }

    .mod-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .mod-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .mod-badge-full {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        font-weight: 700;
    }

    .no-modules {
        font-size: 0.8rem;
        color: #94a3b8;
        font-style: italic;
    }

    .action-row {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn-table-action {
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 800;
        border-radius: 4px;
        text-transform: uppercase;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        text-decoration: none;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        background: #ffffff;
    }

    .btn-edit {
        border-color: var(--v2-label);
        color: var(--v2-label);
    }

    .btn-edit:hover {
        background: rgba(178, 224, 235, 0.2);
    }

    .btn-delete {
        color: #ef4444;
        border-color: #fca5a5;
    }

    .btn-delete:hover {
        background: #fef2f2;
        color: #dc2626;
        border-color: #f87171;
    }

    .empty-state {
        text-align: center;
        padding: 40px 16px;
        color: var(--v2-text-muted);
    }

    @media (max-width: 1024px) {
        .viewport-wrapper {
            height: auto;
            min-height: 0;
            overflow: visible;
        }

        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .toolbar-controls {
            width: 100%;
            justify-content: stretch;
        }

        .search-wrap {
            width: 100%;
        }

        .btn-create-user {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="viewport-wrapper">
    <div style="flex-shrink: 0;">
        <h2 style="margin: 0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Manage Users</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $totalUsers) ?></span>
                    <span class="kpi-label">Registered Users</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-admin">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $adminCount) ?></span>
                    <span class="kpi-label">Administrators</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-royal">
                <div class="kpi-icon-box icon-it">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                </div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $itStaffCount) ?></span>
                    <span class="kpi-label">IT Staff</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-amber">
                <div class="kpi-icon-box icon-employee">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="kpi-details">
                    <span class="kpi-value"><?= esc((string) $employeeCount) ?></span>
                    <span class="kpi-label">Employees</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div class="toolbar-copy">
                <h3>User Directory</h3>
                <p>Review account roles, module access, and available actions.</p>
            </div>

            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="user-search" class="search-input" placeholder="Search by name, email, or role..." autocomplete="off">
                </div>
                <a class="btn btn-primary btn-create-user" href="<?= site_url('admin/users/create') ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Create New User
                </a>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="users-table">
                <thead>
                    <tr>
                        <th style="width: 250px;">User Profile</th>
                        <th style="width: 150px;">Assigned Role</th>
                        <th>Module Access</th>
                        <th style="width: 160px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usersList)): ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">No users found in the system.</div>
                            </td>
                        </tr>
                    <?php else: ?>
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

                            $primaryRole = 'employee';
                            $roleLabel = 'Employee';
                            $roleClass = 'role-employee';

                            if (in_array('admin', $userGroups, true)) {
                                $primaryRole = 'admin';
                                $roleLabel = 'Administrator';
                                $roleClass = 'role-admin';
                            } elseif (in_array('it_staff', $userGroups, true)) {
                                $primaryRole = 'it_staff';
                                $roleLabel = 'IT Staff';
                                $roleClass = 'role-it';
                            }

                            $initial = strtoupper(substr($username, 0, 1));
                            $isAdmin = ($primaryRole === 'admin');

                            $modulePermsMap = [
                                'Procurement' => ['procurement.view', 'procurement.pr.create'],
                                'Receiving'   => ['receiving.view', 'receiving.convert'],
                                'Inventory'   => ['inventory.issuance.create', 'inventory.quantity.update'],
                                'Reports'     => ['reports.view'],
                            ];
                            ?>
                            <tr class="user-row">
                                <td>
                                    <div class="user-profile">
                                        <div class="user-avatar"><?= esc($initial) ?></div>
                                        <div class="user-meta">
                                            <span class="user-name"><?= esc($username) ?></span>
                                            <span class="user-email"><?= esc($email) ?></span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="role-badge <?= $roleClass ?>"><?= esc($roleLabel) ?></span>
                                </td>

                                <td>
                                    <?php if ($isAdmin): ?>
                                        <span class="mod-badge mod-badge-full">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                            Full Access
                                        </span>
                                    <?php else: ?>
                                        <div class="mod-badges">
                                            <?php
                                            $hasAny = false;
                                            foreach ($modulePermsMap as $modName => $perms):
                                                $userHasModule = false;
                                                foreach ($perms as $p) {
                                                    if ($user->hasPermission($p)) {
                                                        $userHasModule = true;
                                                        break;
                                                    }
                                                }

                                                if ($userHasModule):
                                                    $hasAny = true;
                                            ?>
                                                    <span class="mod-badge"><?= esc($modName) ?></span>
                                            <?php
                                                endif;
                                            endforeach;

                                            if (! $hasAny): ?>
                                                <span class="no-modules">No specific modules</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif ?>
                                </td>

                                <td style="text-align: right;">
                                    <div class="action-row">
                                        <a href="<?= site_url('admin/users/' . $userId . '/edit') ?>" class="btn-table-action btn-edit">Edit</a>

                                        <?php if (! $isAdmin): ?>
                                            <form method="post" action="<?= site_url('admin/users/' . $userId . '/delete') ?>" onsubmit="return confirm('Permanently delete this user account? This cannot be undone.');" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table-action btn-delete">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// --- QUICK SEARCH FUNCTIONALITY ---
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('user-search');
    const tableRows = document.querySelectorAll('.user-row');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();

            tableRows.forEach(row => {
                // Grab the text from the profile and role columns
                const profileText = row.children[0].textContent.toLowerCase();
                const roleText = row.children[1].textContent.toLowerCase();
                
                if (profileText.includes(term) || roleText.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
<?= $this->endSection() ?>
