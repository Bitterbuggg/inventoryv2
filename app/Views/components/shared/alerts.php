<?php

declare(strict_types=1);

$flashMessage = session('message');
$flashInfo = session('info');
$flashWarning = session('warning');
$flashError = session('error');
$flashErrors = (array) session('errors');
?>

<?php if (! empty($flashMessage)): ?>
    <div class="alert alert-success" role="status">
        <?= esc((string) $flashMessage) ?>
    </div>
<?php endif ?>

<?php if (! empty($flashInfo)): ?>
    <div class="alert alert-info" role="status">
        <?= esc((string) $flashInfo) ?>
    </div>
<?php endif ?>

<?php if (! empty($flashWarning)): ?>
    <div class="alert alert-warning" role="alert">
        <?= esc((string) $flashWarning) ?>
    </div>
<?php endif ?>

<?php if (! empty($flashError)): ?>
    <div class="alert alert-error" role="alert">
        <?= esc((string) $flashError) ?>
    </div>
<?php endif ?>

<?php if ($flashErrors !== []): ?>
    <div class="alert alert-error" role="alert">
        <ul class="alert-list">
            <?php foreach ($flashErrors as $error): ?>
                <li><?= esc((string) $error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
