<?php

declare(strict_types=1);

$title = 'Create Receiving - InventoryV2';
$pageTitle = 'Create Receiving';
$pageSubtitle = 'Receiving drafts are created via PO request conversion.';
$crumbs = [
    ['label' => 'Receiving', 'url' => site_url('receiving')],
    ['label' => 'Create'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('receiving') ?>">Back to Receiving</a>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Creation Mode</p>
                <p class="kpi-value">Conversion</p>
                <p class="kpi-note">Receiving starts from approved PO requests.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Draft Status</p>
                <p class="kpi-value">Required</p>
                <p class="kpi-note">Draft must be validated then posted.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Source</p>
                <p class="kpi-value">PO Request</p>
                <p class="kpi-note">Use conversion button from receiving list.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Action</p>
                <p class="kpi-value">Convert</p>
                <p class="kpi-note">Open a conversion form with line items.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <p>Use the conversion endpoint to create receiving from approved PO requests.</p>
        <div class="toolbar">
            <a class="btn btn-primary" href="<?= site_url('receiving') ?>">Open Receiving List</a>
            <a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">Review PO Requests</a>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
