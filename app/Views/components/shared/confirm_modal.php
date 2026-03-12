<?php

declare(strict_types=1);

$modalId = (string) ($id ?? 'confirm-modal');
$title = (string) ($title ?? 'Confirm Action');
$description = (string) ($description ?? 'Please confirm this action.');
$confirmLabel = (string) ($confirmLabel ?? 'Confirm');
$cancelLabel = (string) ($cancelLabel ?? 'Cancel');
$variant = (string) ($variant ?? 'danger');

$_iconDanger  = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
$_iconWarning = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
$_iconInfo    = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';

$_variantIcons = ['danger' => $_iconDanger, 'warning' => $_iconWarning, 'info' => $_iconInfo];
$_currentVariant = array_key_exists($variant, $_variantIcons) ? $variant : 'danger';
$_currentIcon    = $_variantIcons[$_currentVariant];
$_btnClass       = ($_currentVariant === 'info') ? 'btn-primary' : 'btn-danger';
?>
<div id="<?= esc($modalId) ?>"
     class="modal-backdrop"
     hidden
     aria-hidden="true"
     role="dialog"
     aria-modal="true"
     aria-labelledby="<?= esc($modalId) ?>-title"
     data-component="confirm-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <span class="modal-icon modal-icon-<?= esc($_currentVariant) ?>"><?= $_currentIcon ?></span>
            <h2 id="<?= esc($modalId) ?>-title"><?= esc($title) ?></h2>
        </div>
        <p class="modal-desc"><?= esc($description) ?></p>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" data-modal-cancel><?= esc($cancelLabel) ?></button>
            <button type="button" class="btn <?= esc($_btnClass) ?>" data-modal-confirm><?= esc($confirmLabel) ?></button>
        </div>
    </div>
</div>
