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

$roleLabel = 'Employee';
if ($isAdmin) {
    $roleLabel = 'Administrator';
} elseif ($isItStaff) {
    $roleLabel = 'IT dev/staff';
}

$currentPath = trim(service('uri')->getPath(), '/');

$isActivePath = static function (string $target) use ($currentPath): bool {
    $target = trim($target, '/');

    if ($target === '') {
        return $currentPath === '';
    }

    return $currentPath === $target || str_starts_with($currentPath, $target . '/');
};

$navGroups = [];

if ($isAdmin) {
    $navGroups[] = [
        'title' => 'Administration',
        'items' => [
            ['path' => 'admin/dashboard', 'label' => 'Dashboard'],
            ['path' => 'admin/users', 'label' => 'Manage Users'],
        ],
    ];
}

if ($canProcurement) {
    $procurementItems = [
        ['path' => 'procurement/purchase-requests', 'label' => 'Purchase Requests'],
    ];

    if ($canOps) {
        $procurementItems[] = ['path' => 'procurement/approvals/pending', 'label' => 'Approvals'];
        $procurementItems[] = ['path' => 'procurement/purchase-orders', 'label' => 'Purchase Orders'];
        $procurementItems[] = ['path' => 'procurement/po-requests', 'label' => 'PO Requests'];
    }

    $navGroups[] = [
        'title' => 'Procurement',
        'items' => $procurementItems,
    ];

    $inventoryItems = [
        ['path' => 'inventory/quantities', 'label' => 'Inventory'],
        ['path' => 'inventory/issuance', 'label' => 'Issuance'],
    ];

    if ($canOps) {
        array_unshift($inventoryItems, ['path' => 'receiving', 'label' => 'Receiving']);
    }

    $navGroups[] = [
        'title' => 'Inventory Ops',
        'items' => $inventoryItems,
    ];
}

if ($canOps) {
    $navGroups[] = [
        'title' => 'Reports and Analytics',
        'items' => [
            ['path' => 'reports/stock-balance', 'label' => 'Stock Balance'],
            ['path' => 'reports/stock-movements', 'label' => 'Stock Movements'],
            ['path' => 'reports/issuances', 'label' => 'Issuances'],
            ['path' => 'reports/low-stock', 'label' => 'Low Stock'],
            ['path' => 'reports/fast-moving', 'label' => 'Fast Moving'],
            ['path' => 'analytics/dashboard', 'label' => 'Analytics Dashboard'],
            ['path' => 'analytics/events', 'label' => 'Events'],
            ['path' => 'analytics/metrics', 'label' => 'Metrics'],
        ],
    ];
}
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
    <div class="app-shell" id="appShell">
        <aside class="side-panel" id="sidePanel">
            <div class="side-brand-wrap">
                <a class="brand side-brand" href="<?= site_url('/') ?>">InventoryV2</a>
                <p class="side-brand-sub">Pharmacy Inventory System</p>
            </div>

            <div class="side-user">
                <p class="side-user-name"><?= esc($displayName) ?></p>
                <p class="side-user-role"><?= esc($roleLabel) ?></p>
            </div>

            <nav class="side-nav" aria-label="Primary navigation">
                <?php foreach ($navGroups as $group): ?>
                    <section class="side-section">
                        <h2 class="side-section-title"><?= esc((string) $group['title']) ?></h2>
                        <div class="side-links">
                            <?php foreach ($group['items'] as $item): ?>
                                <?php $activeClass = $isActivePath((string) $item['path']) ? ' is-active' : ''; ?>
                                <a class="side-link<?= $activeClass ?>" href="<?= site_url((string) $item['path']) ?>">
                                    <span class="side-link-label"><?= esc((string) $item['label']) ?></span>
                                </a>
                            <?php endforeach ?>
                        </div>
                    </section>
                <?php endforeach ?>
            </nav>

            <?php if ($user !== null): ?>
                <form method="post" action="<?= site_url('logout') ?>" class="side-logout-form">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-block">Logout</button>
                </form>
            <?php endif ?>
        </aside>

        <button type="button" class="side-overlay" id="sideOverlay" aria-label="Close navigation" hidden></button>

        <div class="main-panel">
            <header class="app-header">
                <div class="container header-inner">
                    <div class="header-main stack-sm">
                        <button type="button" class="side-toggle" id="sideToggle" aria-controls="sidePanel" aria-expanded="false">Menu</button>
                        <p class="top-kicker">Operations Console</p>
                        <h1><?= esc((string) $pageTitle) ?></h1>
                        <?php if (! empty($pageSubtitle)): ?>
                            <p class="page-subtitle"><?= esc((string) $pageSubtitle) ?></p>
                        <?php endif ?>
                    </div>

                    <div class="user-strip">
                        <span><?= esc(date('F j, Y')) ?></span>
                        <?php if ($pageActions !== ''): ?>
                            <div class="page-actions">
                                <?= $pageActions ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </header>

            <main class="content-wrap">
                <div class="container">
                    <?php if ($crumbs !== []): ?>
                        <?= view('components/shared/breadcrumbs', ['crumbs' => $crumbs]) ?>
                    <?php endif ?>

                    <?= view('components/shared/alerts') ?>
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        (function () {
            const shell = document.getElementById('appShell');
            const toggle = document.getElementById('sideToggle');
            const overlay = document.getElementById('sideOverlay');

            if (!shell || !toggle || !overlay) {
                return;
            }

            const closeNav = () => {
                shell.classList.remove('is-side-open');
                overlay.hidden = true;
                toggle.setAttribute('aria-expanded', 'false');
            };

            const openNav = () => {
                shell.classList.add('is-side-open');
                overlay.hidden = false;
                toggle.setAttribute('aria-expanded', 'true');
            };

            toggle.addEventListener('click', () => {
                if (shell.classList.contains('is-side-open')) {
                    closeNav();
                    return;
                }

                openNav();
            });

            overlay.addEventListener('click', closeNav);

            window.addEventListener('resize', () => {
                if (window.innerWidth > 900) {
                    closeNav();
                }
            });
        })();
    </script>
</body>
</html>
