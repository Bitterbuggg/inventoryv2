<?php

declare(strict_types=1);

$title = $title ?? 'InventoryV2';
$pageTitle = $pageTitle ?? 'InventoryV2';
$pageSubtitle = $pageSubtitle ?? null;
$crumbs = $crumbs ?? [];
$pageActions = trim((string) $this->renderSection('page_actions'));

$user = function_exists('auth') ? auth()->user() : null;
$displayName = (string) ($user->username ?? 'User');

$hasGroup = static function ($userEntity, string $group): bool {
    return $userEntity !== null
        && method_exists($userEntity, 'inGroup')
        && $userEntity->inGroup($group);
};

$isAdmin = $hasGroup($user, 'admin');
$isEmployee = $hasGroup($user, 'employee');
$isItStaff = $hasGroup($user, 'it_staff');
$canProcurement = $isAdmin || $isEmployee || $isItStaff;
$canOps = $isAdmin || $isItStaff;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc((string) $title) ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">
    <?= $this->renderSection('head') ?>
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div class="container header-inner">
                <a class="brand" href="<?= site_url('/') ?>">InventoryV2</a>

                <nav class="top-nav" aria-label="Primary">
                    <?php if ($isAdmin): ?>
                        <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a>
                        <a href="<?= site_url('admin/users') ?>">Users</a>
                    <?php endif ?>

                    <?php if ($canProcurement): ?>
                        <a href="<?= site_url('procurement/purchase-requests') ?>">Purchase Requests</a>
                        <a href="<?= site_url('inventory/quantities') ?>">Inventory</a>
                        <a href="<?= site_url('inventory/issuance') ?>">Issuance</a>
                    <?php endif ?>

                    <?php if ($canOps): ?>
                        <a href="<?= site_url('procurement/approvals/pending') ?>">Approvals</a>
                        <a href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
                        <a href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
                        <a href="<?= site_url('receiving') ?>">Receiving</a>
                        <a href="<?= site_url('reports/stock-balance') ?>">Reports</a>
                        <a href="<?= site_url('analytics/dashboard') ?>">Analytics</a>
                    <?php endif ?>
                </nav>

                <div class="user-strip">
                    <span><?= esc($displayName) ?></span>
                    <?php if ($user !== null): ?>
                        <form method="post" action="<?= site_url('logout') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    <?php endif ?>
                </div>
            </div>
        </header>

        <main class="content-wrap">
            <div class="container">
                <?php if ($crumbs !== []): ?>
                    <?= view('components/shared/breadcrumbs', ['crumbs' => $crumbs]) ?>
                <?php endif ?>

                <div class="page-header">
                    <div class="stack-sm">
                        <h1><?= esc((string) $pageTitle) ?></h1>
                        <?php if (! empty($pageSubtitle)): ?>
                            <p class="page-subtitle"><?= esc((string) $pageSubtitle) ?></p>
                        <?php endif ?>
                    </div>

                    <?php if ($pageActions !== ''): ?>
                        <div class="page-actions">
                            <?= $pageActions ?>
                        </div>
                    <?php endif ?>
                </div>

                <?= view('components/shared/alerts') ?>
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>
</body>
</html>
