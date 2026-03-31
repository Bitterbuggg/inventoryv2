<?php

declare(strict_types=1);

$flashMessage = session('message');
$flashInfo = session('info');
$flashWarning = session('warning');
$flashError = session('error');
$flashErrors = (array) session('errors');
$validationSummary = $flashErrors !== []
    ? esc((string) json_encode($flashErrors, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), 'attr')
    : '';

$_iconSuccess = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
$_iconInfo    = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
$_iconWarning = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
$_iconError   = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
$_iconClose   = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
?>

<?php if (! empty($flashMessage)): ?>
    <div class="alert alert-success" role="status" aria-live="polite" data-auto-dismiss="5000">
        <span class="alert-icon"><?= $_iconSuccess ?></span>
        <span class="alert-body"><?= esc((string) $flashMessage) ?></span>
        <button type="button" class="alert-close" aria-label="Dismiss"><?= $_iconClose ?></button>
        <div class="alert-progress" aria-hidden="true" style="animation: progressDrain 5000ms linear forwards;"></div>
    </div>
<?php endif ?>

<?php if (! empty($flashInfo)): ?>
    <div class="alert alert-info" role="status" aria-live="polite" data-auto-dismiss="7000">
        <span class="alert-icon"><?= $_iconInfo ?></span>
        <span class="alert-body"><?= esc((string) $flashInfo) ?></span>
        <button type="button" class="alert-close" aria-label="Dismiss"><?= $_iconClose ?></button>
        <div class="alert-progress" aria-hidden="true" style="animation: progressDrain 7000ms linear forwards;"></div>
    </div>
<?php endif ?>

<?php if (! empty($flashWarning)): ?>
    <div class="alert alert-warning" role="alert" aria-live="assertive" tabindex="-1">
        <span class="alert-icon"><?= $_iconWarning ?></span>
        <span class="alert-body"><?= esc((string) $flashWarning) ?></span>
        <button type="button" class="alert-close" aria-label="Dismiss"><?= $_iconClose ?></button>
    </div>
<?php endif ?>

<?php if (! empty($flashError)): ?>
    <div class="alert alert-error" role="alert" aria-live="assertive" tabindex="-1">
        <span class="alert-icon"><?= $_iconError ?></span>
        <span class="alert-body"><?= esc((string) $flashError) ?></span>
        <button type="button" class="alert-close" aria-label="Dismiss"><?= $_iconClose ?></button>
    </div>
<?php endif ?>

<?php if ($flashErrors !== []): ?>
    <div class="alert alert-error" role="alert" aria-live="assertive" tabindex="-1" data-validation-summary="<?= $validationSummary ?>">
        <span class="alert-icon"><?= $_iconError ?></span>
        <span class="alert-body">
            <ul class="alert-list">
                <?php foreach ($flashErrors as $error): ?>
                    <li><?= esc((string) $error) ?></li>
                <?php endforeach ?>
            </ul>
        </span>
        <button type="button" class="alert-close" aria-label="Dismiss"><?= $_iconClose ?></button>
    </div>
<?php endif ?>
