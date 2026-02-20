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

<?= $this->section('content') ?>
<section class="card stack-md">
    <p>Use the conversion endpoint to create receiving from approved PO requests.</p>
    <a class="btn btn-outline" href="<?= site_url('receiving') ?>">Back to Receiving</a>
</section>
<?= $this->endSection() ?>
