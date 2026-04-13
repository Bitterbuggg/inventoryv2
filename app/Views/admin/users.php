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
$modulePermsMap = is_array($modulePermsMap ?? null) ? $modulePermsMap : [];
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
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #b2e0eb; 
        --v2-title: #00476b;  
        --v2-label: #00668c;  
        --v2-active-bg: #00638a; 
        --v2-text-main: #1e3a8a; 
        --v2-text-muted: #64748b;
    }

    /* --- VIEWPORT WRAPPER --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: calc(100vh - 120px); 
        min-height: 640px;
        overflow: hidden;
    }

    /* --- PASTEL KPI CARDS --- */
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
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
    }

    .kpi-icon-box {
        width: 46px; height: 46px; 
        border-radius: 10px; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0;
    }

    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-admin { background: #e0f2fe; color: #0284c7; } 
    .icon-it { background: #f5f3ff; color: #8b5cf6; }   
    .icon-employee { background: #fffbeb; color: #d97706; }   

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }
    
    /* Using <p> tags for block stacking */
    .kpi-value { font-size: 1.15rem; font-weight: 900; color: var(--v2-title); line-height: 1.2; margin: 0; }
    .kpi-label { font-size: 0.75rem; font-weight: 600; color: var(--v2-text-muted); margin: 0; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- V2 TABLE CARD & TOOLBAR --- */
    .table-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        display: flex;
        flex-direction: column;
        flex: 1; 
        min-height: 0; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 14px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; 
        flex-shrink: 0;
        flex-wrap: wrap; 
    }
    
    .table-toolbar h3 { margin: 0; font-size: 1.05rem; color: var(--v2-title); font-weight: 800; }
    
    /* Fixed Toolbar Controls */
    .toolbar-controls { 
        display: flex; 
        gap: 12px; 
        align-items: center; 
        flex: 1; 
        justify-content: flex-end; 
    }
    
    .search-wrap { position: relative; width: 280px; flex-shrink: 0; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    .search-input { 
        width: 100%; 
        padding: 6px 12px 6px 30px; 
        font-size: 0.85rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-text-main);
        background: #ffffff;
        transition: all 0.2s;
        height: 34px; 
        box-sizing: border-box;
    }
    .search-input:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

    /* Scrollable Table Area */
    .table-scroll-container {
        flex: 1;
        overflow-y: auto; 
        background: #ffffff;
    }

    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th { 
        position: sticky; top: 0; z-index: 10;
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
    .modern-table td { padding: 12px 16px; font-size: 0.85rem; color: var(--v2-text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:hover td { background: #f8fafc; }

    /* --- SPECIFIC USER TABLE STYLES --- */
    .user-profile { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .user-avatar { width: 36px; height: 36px; border-radius: 8px; background: #f0f9ff; color: var(--v2-label); border: 1px solid var(--v2-border); display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1rem; flex-shrink: 0; }
    .user-meta { display: flex; flex-direction: column; min-width: 0; }
    .user-name { font-weight: 800; font-size: 0.95rem; color: var(--v2-title); }
    .user-email { font-size: 0.75rem; font-weight: 600; color: var(--v2-text-muted); }

    .role-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 9999px; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
    .role-admin { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
    .role-it { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
    .role-employee { background: #fffbeb; color: #d97706; border: 1px solid #fef08a; }

    .mod-badges { display: flex; flex-wrap: wrap; gap: 6px; }
    .mod-badge { display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; text-transform: uppercase; }
    .mod-badge-full { background: #ecfccb; color: #16a34a; border: 1px solid #d9f99d; font-weight: 800; }
    .no-modules { font-size: 0.8rem; color: #94a3b8; font-style: italic; font-weight: 600; }

    /* Action Buttons */
    .action-row { display: flex; gap: 6px; align-items: center; justify-content: flex-end; }
    .btn-table-action { padding: 4px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; transition: all 0.2s ease; cursor: pointer; display: inline-flex; text-decoration: none; align-items: center; justify-content: center; background: transparent; border: none; }
    
    .btn-edit { color: var(--v2-label); }
    .btn-edit:hover { background: rgba(178, 224, 235, 0.3); color: var(--v2-title); }
    
    .btn-delete { color: #ef4444; }
    .btn-delete:hover { background: #fef2f2; color: #dc2626; }

    @media (max-width: 1024px) {
        .viewport-wrapper { height: auto; min-height: 0; overflow: visible; }
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .kpi-grid { grid-template-columns: 1fr; }
        .toolbar-controls { width: 100%; justify-content: stretch; }
        .search-wrap { width: 100%; flex: 1; }
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
            <article class="kpi-card">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value"><?= esc((string) $totalUsers) ?></p>
                    <p class="kpi-label">Registered Users</p>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-admin"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" style="color: #0284c7;"><?= esc((string) $adminCount) ?></p>
                    <p class="kpi-label">Administrators</p>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-it"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" style="color: #7c3aed;"><?= esc((string) $itStaffCount) ?></p>
                    <p class="kpi-label">IT Staff</p>
                </div>
            </article>
            <article class="kpi-card">
                <div class="kpi-icon-box icon-employee"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
                <div class="kpi-details">
                    <p class="kpi-value" style="color: #d97706;"><?= esc((string) $employeeCount) ?></p>
                    <p class="kpi-label">Employees</p>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>User Directory</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Review account roles, module access, and actions.</p>
            </div>

            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="user-search" class="search-input" placeholder="Search by name, email, or role" autocomplete="off">
                </div>
                <a class="btn btn-primary" href="<?= site_url('admin/users/create') ?>" style="padding: 6px 14px; font-weight: 800; font-size: 0.8rem; background: var(--v2-label); border: none; border-radius: 6px; color: white; display: inline-flex; align-items: center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Create User
                </a>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="users-table" style="table-layout: fixed; width: 100%; min-width: 900px;">
                <colgroup>
                    <col style="width: 250px;">
                    <col style="width: 140px;">
                    <col style="width: auto;">
                    <col style="width: 130px;">
                </colgroup>
                <thead>
                    <tr>
                        <th>User Profile</th>
                        <th>Assigned Role</th>
                        <th>Module Access</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usersList)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No users found in the system.</strong>
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
                                if (method_exists($user, 'getGroups')) $userGroups = $user->getGroups() ?? [];
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
                            ?>
                            <tr class="user-row">
                                <td>
                                    <div class="user-profile">
                                        <div class="user-avatar"><?= esc($initial) ?></div>
                                        <div class="user-meta">
                                            <span class="user-name" title="<?= esc($username) ?>"><?= esc($username) ?></span>
                                            <span class="user-email" title="<?= esc($email) ?>"><?= esc($email) ?></span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="role-badge <?= $roleClass ?>"><?= esc($roleLabel) ?></span>
                                </td>

                                <td>
                                    <?php if ($isAdmin): ?>
                                        <span class="mod-badge mod-badge-full">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                            Full Access
                                        </span>
                                    <?php else: ?>
                                        <div class="mod-badges">
                                            <?php
                                            $hasAny = false;
                                            foreach ($modulePermsMap as $modName => $perms):
                                                $userHasModule = false;
                                                foreach ($perms as $p) {
                                                    if (method_exists($user, 'can') && $user->can($p)) {
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
                                            <form method="post" action="<?= site_url('admin/users/' . $userId . '/delete') ?>" data-confirm="Permanently delete this user account? This cannot be undone." style="margin: 0;">
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