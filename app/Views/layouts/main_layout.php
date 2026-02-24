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

$currentPath = trim(uri_string(), '/');

$isActivePath = static function (string $target) use ($currentPath): bool {
    $target = trim($target, '/');

    if ($target === '') {
        return $currentPath === '';
    }

    $cleanPath = str_replace('index.php/', '', $currentPath);

    return $cleanPath === $target || str_starts_with($cleanPath, $target . '/');
};

$navGroups = [];

if ($isAdmin) {
    $navGroups[] = [
        'title' => 'Administration',
        // Users/Settings Icon
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
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
        // Shopping Cart Icon
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
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
        // Box/Package Icon
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
        'items' => $inventoryItems,
    ];
}

if ($canOps) {
    $navGroups[] = [
        'title' => 'Reports and Analytics',
        // Bar Chart Icon
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
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
                    <?php 
                        // Check if any link in this group is currently active
                        $isGroupActive = false;
                        foreach ($group['items'] as $item) {
                            if ($isActivePath((string) $item['path'])) {
                                $isGroupActive = true;
                                break;
                            }
                        }
                    ?>
                    <section class="side-section <?= $isGroupActive ? 'is-expanded' : '' ?>">
                        <button type="button" class="side-section-title toggle-section" aria-expanded="<?= $isGroupActive ? 'true' : 'false' ?>">
                            <span style="display: flex; align-items: center; gap: 12px;">
                                <?= $group['icon'] ?>
                                <?= esc((string) $group['title']) ?>
                            </span>
                            <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>

                        <div class="side-links-wrapper">
                            <div class="side-links">
                                <?php foreach ($group['items'] as $item): ?>
                                    <?php $activeClass = $isActivePath((string) $item['path']) ? ' is-active' : ''; ?>
                                    <a class="side-link child-link<?= $activeClass ?>" href="<?= site_url((string) $item['path']) ?>">
                                        <span class="side-link-label"><?= esc((string) $item['label']) ?></span>
                                    </a>
                                <?php endforeach ?>
                            </div>
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
                <div class="container header-inner" style="display: grid; grid-template-columns: 1fr auto; align-items: start; gap: 20px;">
                    
                    <div class="header-main stack-sm">
                        <button type="button" class="side-toggle" id="sideToggle" aria-controls="sidePanel" aria-expanded="false">Menu</button>
                        <p class="top-kicker">Operations Console</p>
                        <h1><?= esc((string) $pageTitle) ?></h1>
                        <?php if (! empty($pageSubtitle)): ?>
                            <p class="page-subtitle"><?= esc((string) $pageSubtitle) ?></p>
                        <?php endif ?>
                    </div>

                    <div class="user-strip" style="display: flex; flex-direction: column; align-items: flex-end; text-align: right; padding-top: 0.25rem;">
                        
                        <div style="margin-bottom: 12px; display: flex; flex-direction: column; align-items: flex-end;">
                            <span id="dashboardTime" style="font-size: 1.85rem; font-weight: 800; color: var(--color-text); line-height: 1; letter-spacing: -0.02em;">
                                <?= esc(date('h:i A')) ?>
                            </span>
                            <span id="dashboardDate" style="font-size: 0.9rem; color: var(--color-text-muted); font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.05em;">
                                <?= esc(date('F j, Y')) ?>
                            </span>
                        </div>

                        <?php if ($pageActions !== ''): ?>
                            <div class="page-actions" style="display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap;">
                                <?= $pageActions ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </header>

            <main class="content-wrap">
                <div class="container stack-lg"> 
                    <?php if ($crumbs !== []): ?>
                        <?= view('components/shared/breadcrumbs', ['crumbs' => $crumbs]) ?>
                    <?php endif ?>

                    <?= view('components/shared/alerts') ?>
                    
                    <div class="stack-lg">
                        <?= $this->renderSection('content') ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // ==========================================
        // 1. MOBILE MENU TOGGLE 
        // ==========================================
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

        // ==========================================
        // 2. ACCORDION DROPDOWN LOGIC (This was missing!)
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const sectionToggles = document.querySelectorAll('.toggle-section');
            
            sectionToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevents page jump
                    
                    const section = this.closest('.side-section');
                    const isExpanded = section.classList.contains('is-expanded');
                    
                    if (isExpanded) {
                        section.classList.remove('is-expanded');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        section.classList.add('is-expanded');
                        this.setAttribute('aria-expanded', 'true');
                    }
                });
            });
        });
    </script>
</body>
</html>
