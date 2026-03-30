<?php

declare(strict_types=1);

$statusText = strtolower(trim((string) ($status ?? 'unknown')));
$statusClass = preg_replace('/[^a-z0-9_-]+/', '-', $statusText);
$statusClass = trim((string) $statusClass, '-');
$statusClass = $statusClass !== '' ? $statusClass : 'unknown';

$label = $label ?? str_replace('_', ' ', $statusText);
$label = ucwords((string) $label);
?>
<span class="status-badge status-<?= esc($statusClass) ?>" title="<?= esc((string) $label) ?>">
    <?= esc((string) $label) ?>
</span>
