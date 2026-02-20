<?php

declare(strict_types=1);

$modalId = (string) ($id ?? 'confirm-modal');
$title = (string) ($title ?? 'Confirm Action');
$description = (string) ($description ?? 'Please confirm this action.');
$confirmLabel = (string) ($confirmLabel ?? 'Confirm');
$cancelLabel = (string) ($cancelLabel ?? 'Cancel');
?>
<div id="<?= esc($modalId) ?>" class="card" hidden aria-hidden="true" data-component="confirm-modal">
    <div class="stack-md">
        <h2><?= esc($title) ?></h2>
        <p class="muted"><?= esc($description) ?></p>
        <div class="toolbar">
            <button type="button" class="btn btn-danger" data-modal-confirm><?= esc($confirmLabel) ?></button>
            <button type="button" class="btn btn-outline" data-modal-cancel><?= esc($cancelLabel) ?></button>
        </div>
    </div>
</div>
