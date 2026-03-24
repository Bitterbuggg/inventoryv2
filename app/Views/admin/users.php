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
    /* --- MODERN KPI CARDS --- */
    .kpi-grid-modern { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 24px; }
    .kpi-card-modern { background: #ffffff; border: 1px solid var(--color-border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s; }
    .kpi-card-modern:hover { transform: translateY(-2px); box-shadow: 0 8px 12px -4px rgba(0,0,0,0.05); }
    
    .kpi-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .kpi-icon.total { background: #f1f5f9; color: #475569; }
    .kpi-icon.admin { background: #f5f3ff; color: #7c3aed; }
    .kpi-icon.it { background: #eff6ff; color: #2563eb; }
    .kpi-icon.employee { background: #ecfccb; color: #d97706; }

    .kpi-details { display: flex; flex-direction: column; }
    .kpi-value { font-size: 1.5rem; font-weight: 800; color: var(--color-text-strong); line-height: 1.1; margin-bottom: 4px; }
    .kpi-label { font-size: 0.8rem; font-weight: 700; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- MODERN DATA TABLE --- */
    .table-card { background: #ffffff; border: 1px solid var(--color-border); border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden; }
    .table-toolbar { padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; background: #ffffff; }
    
    .search-wrap { position: relative; width: 300px; }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 16px; height: 16px; }
    .search-input { width: 100%; padding: 8px 12px 8px 36px; font-size: 0.9rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; transition: border-color 0.2s; }
    .search-input:focus { border-color: var(--color-brand-500); box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }

    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th { background: #f8fafc; padding: 14px 20px; font-size: 0.75rem; text-transform: uppercase; font-weight: 800; color: #64748b; border-bottom: 1px solid #e2e8f0; text-align: left; letter-spacing: 0.05em; }
    .modern-table td { padding: 16px 20px; font-size: 0.9rem; color: var(--color-text-strong); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:last-child td { border-bottom: none; }
    .modern-table tr:hover td { background: #f8fafc; }

    /* --- BEAUTIFUL PILL BADGES --- */
    .role-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; }
    .role-admin { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
    .role-it { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .role-employee { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }

    .mod-badge { display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .mod-badge-full { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-weight: 700; }

    .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--color-brand-100); color: var(--color-brand-700); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; }
    
    /* --- ACTION BUTTONS --- */
    .btn-action-group { display: flex; gap: 8px; align-items: center; }
    .btn-table-action { padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px; transition: all 0.2s; cursor: pointer; text-decoration: none; border: 1px solid transparent; background: transparent; }
    .btn-edit { color: var(--color-brand-600); border-color: var(--color-brand-200); background: var(--color-brand-50); }
    .btn-edit:hover { background: var(--color-brand-600); color: white; border-color: var(--color-brand-600); }
    .btn-delete { color: #ef4444; border-color: #fecaca; background: #fef2f2; }
    .btn-delete:hover { background: #ef4444; color: white; border-color: #ef4444; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-primary" href="<?= site_url('admin/users/create') ?>" style="padding: 10px 16px; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2);">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    Create New User
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    
    <div class="kpi-grid-modern">
        <div class="kpi-card-modern">
            <div class="kpi-icon total">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="kpi-details">
                <span class="kpi-value"><?= esc((string) $totalUsers) ?></span>
                <span class="kpi-label">Registered Users</span>
            </div>
        </div>
        
        <div class="kpi-card-modern">
            <div class="kpi-icon admin">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <div class="kpi-details">
                <span class="kpi-value"><?= esc((string) $adminCount) ?></span>
                <span class="kpi-label">Administrators</span>
            </div>
        </div>
        
        <div class="kpi-card-modern">
            <div class="kpi-icon it">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            </div>
            <div class="kpi-details">
                <span class="kpi-value"><?= esc((string) $itStaffCount) ?></span>
                <span class="kpi-label">IT Staff</span>
            </div>
        </div>
        
        <div class="kpi-card-modern">
            <div class="kpi-icon employee">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="kpi-details">
                <span class="kpi-value"><?= esc((string) $employeeCount) ?></span>
                <span class="kpi-label">Employees</span>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-toolbar">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--color-text-strong);">User Directory</h3>
            
            <div class="search-wrap">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="user-search" class="search-input" placeholder="Search by name, email, or role...">
            </div>
        </div>

        <div style="overflow-x: auto;">
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
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                                No users found in the system.
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

                            // Determine Primary Role & Avatar Initial
                            $primaryRole = 'employee';
                            $roleLabel = 'Employee';
                            $roleClass = 'role-employee';
                            
                            if (in_array('admin', $userGroups, true)) {
                                $primaryRole = 'admin'; $roleLabel = 'Administrator'; $roleClass = 'role-admin';
                            } elseif (in_array('it_staff', $userGroups, true)) {
                                $primaryRole = 'it_staff'; $roleLabel = 'IT Staff'; $roleClass = 'role-it';
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
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="user-avatar"><?= esc($initial) ?></div>
                                        <div style="display: flex; flex-direction: column;">
                                            <span style="font-weight: 700; font-size: 0.95rem; color: var(--color-text-strong);"><?= esc($username) ?></span>
                                            <span style="font-size: 0.8rem; color: var(--color-text-muted);"><?= esc($email) ?></span>
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
                                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
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
                                                <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">No specific modules</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif ?>
                                </td>
                                
                                <td style="text-align: right;">
                                    <div class="btn-action-group" style="justify-content: flex-end;">
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