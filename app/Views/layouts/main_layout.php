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

// Effective permission checks include both direct grants and inherited group permissions.
$can = static function (string ...$permissions) use ($user): bool {
    return $user !== null
        && method_exists($user, 'can')
        && $user->can(...$permissions);
};

$canViewProcurement = $can(
    'procurement.view',
    'procurement.pr.create',
    'procurement.pr.approve',
    'procurement.po.create',
    'procurement.por.manage',
);
$canViewReceiving = $can('receiving.view', 'receiving.convert');
$canViewInventory = $can(
    'inventory.view',
    'inventory.quantity.update',
    'inventory.issuance.create',
    'inventory.issuance.approve',
);
$canViewReports = $can('reports.view');
$canViewAudit = $can('audit.view');

// Operational access check (Admin or specific staff)
$canOps = $isAdmin || $isItStaff;

$roleLabel = 'Employee';
if ($isAdmin) {
    $roleLabel = 'Administrator';
} elseif ($isItStaff) {
    $roleLabel = 'IT dev/staff';
}

$currentPath = trim(uri_string(), '/');
$cleanCurrentPath = str_replace('index.php/', '', $currentPath);

$isActivePath = static function (string $target) use ($cleanCurrentPath): bool {
    $target = trim($target, '/');

    if ($target === '') {
        return $cleanCurrentPath === '';
    }

    return $cleanCurrentPath === $target || str_starts_with($cleanCurrentPath, $target . '/');
};

$isActiveNavGroup = static function (array $items) use ($cleanCurrentPath, $isActivePath): bool {
    foreach ($items as $item) {
        if ($isActivePath((string) $item['path'])) {
            return true;
        }
    }

    if (! str_starts_with($cleanCurrentPath, 'reports/') && ! str_starts_with($cleanCurrentPath, 'analytics/')) {
        return false;
    }

    foreach ($items as $item) {
        $itemPath = (string) $item['path'];
        if (str_starts_with($itemPath, 'reports/') || str_starts_with($itemPath, 'analytics/')) {
            return true;
        }
    }

    return false;
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
            ['path' => 'admin/products', 'label' => 'Product Catalog'],
            ['path' => 'admin/suppliers', 'label' => 'Supplier Catalog'],
        ],
    ];
}

if ($isAdmin || $canViewProcurement) {
    $procurementItems = [
        ['path' => 'procurement/purchase-requests', 'label' => 'Purchase Requests'],
    ];

    if ($can('procurement.pr.approve')) {
        $procurementItems[] = ['path' => 'procurement/approvals/pending', 'label' => 'Approvals'];
    }
    
    if ($can('procurement.po.create')) {
        $procurementItems[] = ['path' => 'procurement/purchase-orders', 'label' => 'Purchase Orders'];
    }

    if ($can('procurement.por.manage')) {
        $procurementItems[] = ['path' => 'procurement/po-requests', 'label' => 'PO Requests'];
    }

    $navGroups[] = [
        'title' => 'Procurement',
        // Shopping Cart Icon
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
        'items' => $procurementItems,
    ];
}

if ($isAdmin || $canViewInventory || $canViewReceiving) {
    $inventoryItems = [];

    if ($canViewReceiving) {
        $inventoryItems[] = ['path' => 'receiving', 'label' => 'Receiving'];
    }

    if ($can('inventory.view', 'inventory.quantity.update')) {
        $inventoryItems[] = ['path' => 'inventory/quantities', 'label' => 'Inventory'];
    }

    if ($can('inventory.view', 'inventory.issuance.create', 'inventory.issuance.approve')) {
        $inventoryItems[] = ['path' => 'inventory/issuance', 'label' => 'Issuance'];
    }

    $navGroups[] = [
        'title' => 'Inventory Ops',
        // Box/Package Icon
        'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
        'items' => $inventoryItems,
    ];
}

if ($canViewReports || $canViewAudit) {
    $reportItems = [];

    if ($canViewReports) {
        $reportItems = array_merge($reportItems, [
            ['path' => 'reports/stock-balance', 'label' => 'Stock Balance'],
            ['path' => 'reports/stock-movements', 'label' => 'Stock Movements'],
            ['path' => 'reports/issuances', 'label' => 'Issuance Report'],
            ['path' => 'reports/low-stock', 'label' => 'Low Stock'],
            ['path' => 'reports/fast-moving', 'label' => 'Fast Moving'],
        ]);
    }

    if ($canViewAudit) {
        $reportItems[] = ['path' => 'analytics/activity-logs', 'label' => 'Activity Logs'];
        $reportItems[] = ['path' => 'analytics/system-architecture', 'label' => 'System Architecture'];
    }

    if ($reportItems !== []) {
        $navGroups[] = [
            'title' => 'Reports and Analytics',
            // Bar Chart Icon
            'icon'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
            'items' => $reportItems,
        ];
    }
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
    <link rel="stylesheet" href="<?= base_url('assets/css/table-density.css') ?>">
</head>
<body>
    <a class="skip-link" href="#mainContent">Skip to main content</a>
    <div class="app-shell" id="appShell">
        <aside class="side-panel" id="sidePanel" aria-label="Sidebar navigation">
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
                        $isGroupActive = $isActiveNavGroup($group['items']);
                        $sectionId = 'nav-group-' . preg_replace('/[^a-z0-9]+/i', '-', strtolower((string) $group['title']));
                    ?>
                    <section class="side-section <?= $isGroupActive ? 'is-expanded' : '' ?>">
                        <button type="button" class="side-section-title toggle-section" aria-expanded="<?= $isGroupActive ? 'true' : 'false' ?>" aria-controls="<?= esc($sectionId) ?>">
                            <span style="display: flex; align-items: center; gap: 12px;">
                                <?= $group['icon'] ?>
                                <?= esc((string) $group['title']) ?>
                            </span>
                            <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>

                        <div class="side-links-wrapper" id="<?= esc($sectionId) ?>">
                            <div class="side-links">
                                <?php foreach ($group['items'] as $item): ?>
                                    <?php $activeClass = $isActivePath((string) $item['path']) ? ' is-active' : ''; ?>
                                    <a class="side-link child-link<?= $activeClass ?>" href="<?= site_url((string) $item['path']) ?>"<?= $activeClass !== '' ? ' aria-current="page"' : '' ?>>
                                        <span class="side-link-label"><?= esc((string) $item['label']) ?></span>
                                    </a>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </section>
                <?php endforeach ?>
            </nav>

            <?php if ($user !== null): ?>
                <form method="post" action="<?= site_url('logout') ?>" class="side-logout-form" style="margin-top: auto; padding-bottom: 8px;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-block">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            <?php endif ?>
        </aside>

        <button type="button" class="side-overlay" id="sideOverlay" aria-label="Close navigation" hidden></button>

        <div class="main-panel">
            <header class="app-header">
                <div class="container header-inner" style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, auto); align-items: start; gap: 20px;">
                    
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
                                <?= esc(date('h:i:s A')) ?>
                            </span>
                            <span id="dashboardDate" style="font-size: 0.9rem; color: var(--color-text-muted); font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.05em;">
                                <?= esc(date('F j, Y')) ?>
                            </span>
                        </div>

                        <?php if ($pageActions !== ''): ?>
                            <div class="page-actions" aria-label="Page actions" style="display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap;">
                                <?= $pageActions ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </header>

            <main class="content-wrap" id="mainContent" tabindex="-1">
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

    <?= view('components/shared/confirm_modal', [
        'id' => 'confirm-modal',
        'title' => 'Confirm Action',
        'description' => 'Please confirm this action.',
        'confirmLabel' => 'Confirm',
        'cancelLabel' => 'Cancel',
        'variant' => 'warning',
    ]) ?>

    <script src="<?= base_url('assets/js/hci.js') ?>"></script>
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

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && shell.classList.contains('is-side-open')) {
                    closeNav();
                    toggle.focus();
                }
            });

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

        // ==========================================
        // 3. LIVE HEADER CLOCK
        // ==========================================
        (function () {
            const timeElement = document.getElementById('dashboardTime');
            const dateElement = document.getElementById('dashboardDate');

            if (!timeElement || !dateElement) {
                return;
            }

            const updateHeaderClock = () => {
                const now = new Date();

                const timeText = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });

                const dateText = now.toLocaleDateString([], {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });

                timeElement.textContent = timeText;
                dateElement.textContent = dateText;
            };

            updateHeaderClock();
            setInterval(updateHeaderClock, 1000);
        })();

        // ==========================================
        // 4. GLOBAL MODAL CONTROLLER
        // ==========================================
        (function () {
            let _pendingForm  = null;
            let _pendingModal = null;
            let _returnFocus = null;
            const focusableSelector = 'a[href], area[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

            const trapFocus = function (event) {
                if (event.key !== 'Tab' || ! _pendingModal) {
                    return;
                }

                const focusable = Array.from(_pendingModal.querySelectorAll(focusableSelector))
                    .filter((element) => element.offsetParent !== null);

                if (focusable.length === 0) {
                    event.preventDefault();
                    return;
                }

                const first = focusable[0];
                const last = focusable[focusable.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                    return;
                }

                if (! event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            };

            // Open a modal by element reference or selector
            window.openModal = function (modal, trigger) {
                if (typeof modal === 'string') modal = document.getElementById(modal);
                if (!modal) return;
                _returnFocus = trigger instanceof HTMLElement ? trigger : document.activeElement;
                modal.hidden = false;
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('has-modal-open');
                _pendingModal = modal;
                const focusable = modal.querySelector('[data-modal-cancel], ' + focusableSelector);
                if (focusable instanceof HTMLElement) {
                    focusable.focus();
                }
            };

            window.closeModal = function (modal) {
                if (typeof modal === 'string') modal = document.getElementById(modal);
                if (!modal) return;
                modal.hidden = true;
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('has-modal-open');
                _pendingModal = null;
                _pendingForm  = null;
                if (_returnFocus instanceof HTMLElement) {
                    _returnFocus.focus();
                }
                _returnFocus = null;
            };

            // Handle confirm button → submit pending form
            document.addEventListener('click', function (e) {
                const confirmBtn = e.target.closest('[data-modal-confirm]');
                if (confirmBtn) {
                    const modal = confirmBtn.closest('[data-component="confirm-modal"]');
                    if (modal) {
                        modal.hidden = true;
                        modal.setAttribute('aria-hidden', 'true');
                    }
                    if (_pendingForm) {
                        _pendingForm.dataset.confirmed = 'true';
                        _pendingForm.requestSubmit ? _pendingForm.requestSubmit() : _pendingForm.submit();
                        _pendingForm  = null;
                        _pendingModal = null;
                        _returnFocus = null;
                    }
                }
            });

            // Handle cancel button → close modal
            document.addEventListener('click', function (e) {
                const cancelBtn = e.target.closest('[data-modal-cancel]');
                if (cancelBtn) {
                    const modal = cancelBtn.closest('[data-component="confirm-modal"]');
                    if (modal) closeModal(modal);
                    _pendingForm  = null;
                    _pendingModal = null;
                }
            });

            // Click on backdrop (outside dialog) → close
            document.addEventListener('click', function (e) {
                if (_pendingModal && e.target === _pendingModal) {
                    closeModal(_pendingModal);
                    _pendingForm = null;
                }
            });

            // ESC key → close modal
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && _pendingModal) {
                    closeModal(_pendingModal);
                    _pendingForm = null;
                    return;
                }

                trapFocus(e);
            });

            // Intercept forms with data-confirm attribute
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!form.dataset.confirm || form.dataset.confirmed === 'true') return;

                e.preventDefault();

                // Find the linked modal or the page's default confirm modal
                const modalId = form.dataset.confirmModal || 'confirm-modal';
                const modal   = document.getElementById(modalId);
                if (!modal) {
                    // Fallback to native confirm if no modal found
                    if (window.confirm(form.dataset.confirm)) {
                        form.dataset.confirmed = 'true';
                        form.requestSubmit ? form.requestSubmit() : form.submit();
                    }
                    return;
                }

                // Update modal text dynamically if specified
                const titleEl = modal.querySelector('[id$="-title"]');
                const descEl  = modal.querySelector('.modal-desc');
                if (titleEl && form.dataset.confirmTitle) titleEl.textContent = form.dataset.confirmTitle;
                if (descEl  && form.dataset.confirm)      descEl.textContent  = form.dataset.confirm;

                _pendingForm = form;
                openModal(modal, form.querySelector('button[type="submit"], input[type="submit"]'));
            }, true);
        })();
        </script>
        <script src="<?= base_url('assets/js/table-alignment.js') ?>"></script>
        <?= $this->renderSection('scripts') ?>
</body>
</html>
