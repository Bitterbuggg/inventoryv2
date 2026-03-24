<?php

declare(strict_types=1);

$userId = $user->id ?? 0;
$username = $user->username ?? '';
$email = $user->email ?? '';

$title = 'Edit User - InventoryV2';
$pageTitle = 'Edit User Account';
$pageSubtitle = 'Update profile and access for ' . esc($username);
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Users', 'url' => site_url('admin/users')],
    ['label' => 'Edit'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- NO-SCROLL CONSOLE LAYOUT --- */
    .console-layout { 
        display: grid; 
        grid-template-columns: 320px 1fr; 
        gap: 20px; 
        max-width: 1600px; 
        height: calc(100vh - 160px); 
        min-height: 500px;
    }
    
    .panel { 
        background: #ffffff; 
        border: 1px solid var(--color-border); 
        border-radius: 12px; 
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); 
        display: flex; 
        flex-direction: column; 
        overflow: hidden;
    }

    .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--color-border); background: #f8fafc; }
    .panel-header h3 { margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--color-text-strong); }
    .panel-header p { margin: 2px 0 0 0; font-size: 0.8rem; color: var(--color-text-muted); }
    
    .panel-body { padding: 20px; flex: 1; display: flex; flex-direction: column; gap: 14px; }

    /* --- COMPACT INPUTS --- */
    .input-group { display: flex; flex-direction: column; gap: 4px; position: relative; }
    .field-label { font-size: 0.75rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; color: var(--color-text-muted); }
    .form-input { padding: 8px 12px; font-size: 0.9rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; background: #ffffff; color: var(--color-text-strong); transition: border-color 0.2s; }
    .form-input:focus { border-color: var(--color-brand-500); box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }

    /* --- 3-COLUMN PERMISSIONS GRID --- */
    .perms-master-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    
    .perm-group-title { font-size: 0.8rem; font-weight: 800; color: var(--color-brand-700); margin-bottom: 12px; display: flex; align-items: center; gap: 6px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;}
    
    .perm-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px dashed #e2e8f0; }
    .perm-row:last-child { border-bottom: none; }
    .perm-info { display: flex; flex-direction: column; gap: 2px; padding-right: 12px; }
    .perm-title { font-weight: 700; font-size: 0.85rem; color: var(--color-text-strong); }
    .perm-desc { font-size: 0.7rem; color: var(--color-text-muted); line-height: 1.2; }

    /* --- COMPACT IOS TOGGLES --- */
    .ios-toggle { position: relative; display: inline-block; width: 36px; height: 20px; flex-shrink: 0; }
    .ios-toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .3s ease-in-out; border-radius: 34px; }
    .toggle-slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .3s ease-in-out; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
    .ios-toggle input:checked + .toggle-slider { background-color: var(--color-brand-600); }
    .ios-toggle input:checked + .toggle-slider:before { transform: translateX(16px); }

    /* Push buttons to bottom of left panel */
    .panel-footer-actions { margin-top: auto; display: flex; flex-direction: column; gap: 8px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form method="post" action="<?= site_url('admin/users/' . $userId) ?>" id="userForm" class="console-layout">
    <?= csrf_field() ?>

    <aside class="panel">
        <div class="panel-header">
            <h3>Profile Setup</h3>
            <p>Update account credentials</p>
        </div>
        <div class="panel-body">
            <div class="input-group">
                <label class="field-label">Username <span style="color:var(--color-danger)">*</span></label>
                <input type="text" name="username" class="form-input" placeholder="e.g. j.doe" value="<?= esc((string) old('username', $username)) ?>" required autocomplete="off">
            </div>
            
            <div class="input-group">
                <label class="field-label">Email Address <span style="color:var(--color-danger)">*</span></label>
                <input type="email" name="email" class="form-input" placeholder="staff@hospital.local" value="<?= esc((string) old('email', $email)) ?>" required autocomplete="off">
            </div>
            
            <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; margin-top: 8px; display: flex; gap: 10px; align-items: flex-start;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748b; flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <p style="margin: 0; font-size: 0.75rem; color: #475569; line-height: 1.4; font-weight: 500;">
                    <strong style="color: var(--color-brand-700);">Note:</strong> Role assignments and password resets are managed via the main Users list.
                </p>
            </div>

            <div class="panel-footer-actions">
                <button type="submit" class="btn btn-primary" style="padding: 10px; justify-content: center; font-weight: 700;">Save Changes</button>
                <a href="<?= site_url('admin/users') ?>" class="btn btn-outline" style="padding: 8px; justify-content: center; font-size: 0.85rem;">Cancel & Discard</a>
            </div>
        </div>
    </aside>

    <section class="panel">
        <div class="panel-header">
            <h3>Access Control Matrix</h3>
            <p>Manually toggle specific module access for this user.</p>
        </div>
        <div class="panel-body" style="overflow-y: auto;">
            
            <div class="perms-master-grid">
                <?php
                // Upgraded to match the rich array from create.php
                $structure = [
                    'Procurement' => [
                        'procurement.pr.create'  => ['label' => 'Create PRs', 'desc' => 'Draft & submit requests.'],
                        'procurement.pr.approve' => ['label' => 'Approve PRs', 'desc' => 'Approval authority.'],
                        'procurement.po.create'  => ['label' => 'Manage POs', 'desc' => 'Generate vendor orders.'],
                        'procurement.view'       => ['label' => 'View Data', 'desc' => 'Read-only access.'],
                    ],
                    'Inventory & Issuance' => [
                        'inventory.issuance.create'  => ['label' => 'Request Issuance', 'desc' => 'Request stock pulls.'],
                        'inventory.issuance.approve' => ['label' => 'Approve Release', 'desc' => 'Deduct inventory.'],
                        'inventory.quantity.update'  => ['label' => 'Stock Adjustments', 'desc' => 'Manual corrections.'],
                    ],
                    'Receiving & Operations' => [
                        'receiving.convert' => ['label' => 'Log Receiving', 'desc' => 'Verify vendor deliveries.'],
                        'reports.view'      => ['label' => 'System Reports', 'desc' => 'View analytics.'],
                        'audit.view'        => ['label' => 'Audit Logs', 'desc' => 'View system history.'],
                    ]
                ];
                $oldPerms = old('permissions');
                ?>

                <?php foreach ($structure as $module => $perms): ?>
                    <div class="perm-col">
                        <div class="perm-group-title"><?= $module ?></div>
                        
                        <?php foreach ($perms as $val => $info): ?>
                            <?php 
                                // FIXED: Removed the stray 'clone' keyword
                                $isChecked = is_array($oldPerms) 
                                    ? in_array($val, $oldPerms, true) 
                                    : $user->hasPermission($val); 
                            ?>
                            <div class="perm-row">
                                <div class="perm-info">
                                    <span class="perm-title"><?= $info['label'] ?></span>
                                    <span class="perm-desc"><?= $info['desc'] ?></span>
                                </div>
                                <label class="ios-toggle">
                                    <input type="checkbox" name="permissions[]" value="<?= $val ?>" class="perm-checkbox" <?= $isChecked ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

</form>
<?= $this->endSection() ?>