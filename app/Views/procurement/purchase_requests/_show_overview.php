<?php

declare(strict_types=1);

$purchaseRequest = $purchaseRequest ?? [];
$items = $items ?? [];
$totalRequested = (float) ($totalRequested ?? 0);
$totalEstimated = (float) ($totalEstimated ?? 0);
$canApprovePr = (bool) ($canApprovePr ?? false);
?>
<section class="card stack-md">
    <div class="kpi-grid">
        <article class="kpi-card">
            <p class="kpi-label">Status</p>
            <p class="kpi-value"><?= esc(ucfirst((string) ($purchaseRequest['status'] ?? 'unknown'))) ?></p>
            <p class="kpi-note">Current workflow state.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Item Lines</p>
            <p class="kpi-value"><?= esc((string) count($items)) ?></p>
            <p class="kpi-note">Requested line entries.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Total Qty</p>
            <p class="kpi-value"><?= esc(number_format($totalRequested, 0)) ?></p>
            <p class="kpi-note">Whole-number item request quantity.</p>
        </article>
        <article class="kpi-card">
            <p class="kpi-label">Estimated Cost</p>
            <p class="kpi-value">PHP <?= esc(number_format($totalEstimated, 2)) ?></p>
            <p class="kpi-note">Based on requested qty x estimated unit cost.</p>
        </article>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">PR Number</span>
            <span class="detail-value"><?= esc((string) ($purchaseRequest['pr_number'] ?? '')) ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Requested By</span>
            <span class="detail-value"><?= esc((string) ($purchaseRequest['requested_by'] ?? '')) ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Request Date</span>
            <span class="detail-value"><?= esc((string) ($purchaseRequest['request_date'] ?? '')) ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Needed Date</span>
            <span class="detail-value"><?= esc((string) ($purchaseRequest['needed_date'] ?? '-')) ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Remarks</span>
            <span class="detail-value"><?= esc((string) ($purchaseRequest['remarks'] ?? '-')) ?></span>
        </div>
    </div>

    <?php if (($purchaseRequest['status'] ?? '') === 'submitted' && $canApprovePr): ?>
        <div class="toolbar">
            <a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Open Pending Approvals</a>
        </div>
    <?php endif ?>
</section>
