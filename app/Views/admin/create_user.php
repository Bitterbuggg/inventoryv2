<?php

declare(strict_types=1);

$title = 'Create User - InventoryV2';
$pageTitle = 'Create User Account';
$pageSubtitle = 'Configure account details and system access levels.';
$crumbs = [
    ['label' => 'Admin Dashboard', 'url' => site_url('admin/dashboard')],
    ['label' => 'Users', 'url' => site_url('admin/users')],
    ['label' => 'Create'],
];

$permissionStructure = is_array($permissionStructure ?? null) ? $permissionStructure : [];
$rolePresets = is_array($rolePresets ?? null) ? $rolePresets : [];
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
        min-height: 600px; /* Slightly taller to fit the new password field */
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
    .form-input.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15); }
    
    .error-text { font-size: 0.75rem; font-weight: 700; color: #ef4444; display: none; margin-top: 2px; }

    /* --- ROLE PRESETS --- */
    .role-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .role-card { border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; cursor: pointer; transition: all 0.2s ease; background: #ffffff; position: relative; display: flex; align-items: flex-start; gap: 12px; }
    .role-card:hover { border-color: #94a3b8; background: #f8fafc; }
    .role-card.active { border-color: var(--color-brand-600); background: #f0f9ff; box-shadow: 0 2px 4px rgba(2, 132, 199, 0.1); }
    .role-card input[type="radio"] { display: none; }
    
    .role-icon { width: 32px; height: 32px; border-radius: 6px; background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.2s ease; }
    .role-card.active .role-icon { background: var(--color-brand-600); color: #ffffff; }
    
    .role-text { display: flex; flex-direction: column; }
    .role-title { font-weight: 800; font-size: 0.9rem; color: var(--color-text-strong); }
    .role-desc { font-size: 0.75rem; color: var(--color-text-muted); line-height: 1.3; margin-top: 2px; }

    /* --- 3-COLUMN PERMISSIONS GRID --- */
    .perms-master-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 8px; border-top: 1px solid var(--color-border); padding-top: 20px; }
    
    .perm-group-title { font-size: 0.8rem; font-weight: 800; color: var(--color-brand-700); margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
    
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
<form method="post" action="<?= site_url('admin/users') ?>" id="userForm" class="console-layout">
    <?= csrf_field() ?>

    <aside class="panel">
        <div class="panel-header">
            <h3>Profile Setup</h3>
            <p>Account credentials</p>
        </div>
        <div class="panel-body">
            <div class="input-group">
                <label class="field-label">Username <span style="color:var(--color-danger)">*</span></label>
                <input type="text" name="username" class="form-input" placeholder="e.g. j.doe" value="<?= esc((string) old('username')) ?>" required autocomplete="off">
            </div>
            
            <div class="input-group">
                <label class="field-label">Email Address <span style="color:var(--color-danger)">*</span></label>
                <input type="email" name="email" class="form-input" placeholder="staff@hospital.local" value="<?= esc((string) old('email')) ?>" required autocomplete="off">
            </div>
            
            <div class="input-group">
                <label class="field-label">Password <span style="color:var(--color-danger)">*</span></label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Min. 8 characters" required minlength="8">
            </div>

            <div class="input-group">
                <label class="field-label">Confirm Password <span style="color:var(--color-danger)">*</span></label>
                <input type="password" id="password_confirm" name="password_confirm" class="form-input" placeholder="Repeat password" required minlength="8">
                <span id="password-error" class="error-text">Passwords do not match.</span>
            </div>

            <div class="panel-footer-actions">
                <button type="submit" id="btn-submit" class="btn btn-primary" style="padding: 10px; justify-content: center; font-weight: 700;">Create User Account</button>
                <a href="<?= site_url('admin/users') ?>" class="btn btn-outline" style="padding: 8px; justify-content: center; font-size: 0.85rem;">Cancel & Discard</a>
            </div>
        </div>
    </aside>

    <section class="panel">
        <div class="panel-header">
            <h3>Role & Access Control</h3>
            <p>Select a base role, then adjust permissions below if this account needs overrides.</p>
        </div>
        <div class="panel-body" style="padding-bottom: 12px; overflow-y: auto;">
            
            <div class="role-grid">
                <label class="role-card" id="card-admin">
                    <input type="radio" name="role" value="admin" class="role-radio" <?= old('role') === 'admin' ? 'checked' : '' ?> required>
                    <div class="role-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <div class="role-text">
                        <span class="role-title">Administrator</span>
                        <span class="role-desc">Full system & approval access.</span>
                    </div>
                </label>

                <label class="role-card" id="card-it">
                    <input type="radio" name="role" value="it_staff" class="role-radio" <?= old('role') === 'it_staff' ? 'checked' : '' ?>>
                    <div class="role-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <div class="role-text">
                        <span class="role-title">IT Operations</span>
                        <span class="role-desc">System logs & report visibility.</span>
                    </div>
                </label>

                <label class="role-card active" id="card-employee">
                    <input type="radio" name="role" value="employee" class="role-radio" <?= old('role', 'employee') === 'employee' ? 'checked' : '' ?>>
                    <div class="role-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div class="role-text">
                        <span class="role-title">Standard Employee</span>
                        <span class="role-desc">Create requests, read-only data.</span>
                    </div>
                </label>
            </div>

            <div class="perms-master-grid">
                <?php
                $oldPerms = is_array(old('permissions')) ? old('permissions') : [];
                ?>

                <?php foreach ($permissionStructure as $module => $perms): ?>
                    <div class="perm-col">
                        <div class="perm-group-title"><?= $module ?></div>
                        
                        <?php foreach ($perms as $val => $info): ?>
                            <div class="perm-row">
                                <div class="perm-info">
                                    <span class="perm-title"><?= $info['label'] ?></span>
                                    <span class="perm-desc"><?= $info['desc'] ?></span>
                                </div>
                                <label class="ios-toggle">
                                    <input type="checkbox" name="permissions[]" value="<?= $val ?>" class="perm-checkbox" <?= in_array($val, $oldPerms, true) ? 'checked' : '' ?>>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- PASSWORD VALIDATION LOGIC ---
    const form = document.getElementById('userForm');
    const pass = document.getElementById('password');
    const confirmPass = document.getElementById('password_confirm');
    const errorText = document.getElementById('password-error');
    
    function validatePasswords() {
        if (confirmPass.value !== '' && pass.value !== confirmPass.value) {
            confirmPass.classList.add('is-invalid');
            errorText.style.display = 'block';
            return false;
        } else {
            confirmPass.classList.remove('is-invalid');
            errorText.style.display = 'none';
            return true;
        }
    }

    pass.addEventListener('input', validatePasswords);
    confirmPass.addEventListener('input', validatePasswords);

    form.addEventListener('submit', function(e) {
        if (!validatePasswords()) {
            e.preventDefault(); // Stop form submission
            confirmPass.focus();
        }
    });

    // --- ROLE AND PERMISSION LOGIC ---
    const radioCards = document.querySelectorAll('.role-card');
    const roleRadios = document.querySelectorAll('.role-radio');
    const permCheckboxes = document.querySelectorAll('.perm-checkbox');
    const rolePresets = <?= json_encode($rolePresets, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

    function updateActiveCard() {
        radioCards.forEach(card => card.classList.remove('active'));
        const checkedRadio = document.querySelector('.role-radio:checked');
        if (checkedRadio) checkedRadio.closest('.role-card').classList.add('active');
    }

    function applyPreset(roleValue) {
        if (rolePresets[roleValue]) {
            permCheckboxes.forEach(cb => {
                cb.checked = rolePresets[roleValue].includes(cb.value);
            });
        }
    }

    roleRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateActiveCard();
            applyPreset(this.value);
        });
    });

    updateActiveCard();
});
</script>
<?= $this->endSection() ?>
