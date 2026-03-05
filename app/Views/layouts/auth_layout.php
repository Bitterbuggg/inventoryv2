<?php

declare(strict_types=1);

$title = $title ?? 'InventoryV2';
$pageTitle = $pageTitle ?? 'Account';
$pageSubtitle = $pageSubtitle ?? null;
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
    <main class="auth-screen">
        <section class="auth-panel auth-panel-brand">
            <div class="auth-brand-wrap stack-md">
                <p class="auth-kicker">InventoryV2</p>
                <h1 class="auth-brand-title">Pharmacy Inventory System</h1>
                <p class="auth-brand-copy">Secure operations for procurement, receiving, stock control, issuance, and reporting.</p>

                <div class="auth-feature-list stack-sm">
                    <p class="auth-feature-item">Role-protected module access</p>
                    <p class="auth-feature-item">End-to-end inventory traceability</p>
                    <p class="auth-feature-item">Operational analytics and audit visibility</p>
                </div>
            </div>
        </section>

        <section class="auth-panel auth-panel-form">
            <section class="auth-card">
                <h2 class="auth-title"><?= esc((string) $pageTitle) ?></h2>
                <?php if (! empty($pageSubtitle)): ?>
                    <p class="auth-subtitle"><?= esc((string) $pageSubtitle) ?></p>
                <?php endif ?>

                <?= view('components/shared/alerts') ?>
                <?= $this->renderSection('content') ?>
            </section>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('form');

            forms.forEach((form) => {
                form.addEventListener('submit', function (event) {
                    if (event.defaultPrevented) {
                        return;
                    }

                    if (form.dataset.submitting === 'true') {
                        event.preventDefault();
                        return;
                    }

                    form.dataset.submitting = 'true';

                    const submitControls = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                    submitControls.forEach((control) => {
                        control.disabled = true;
                    });

                });
            });

            window.addEventListener('pageshow', function () {
                const lockedForms = document.querySelectorAll('form[data-submitting="true"]');
                lockedForms.forEach((form) => {
                    delete form.dataset.submitting;
                    const submitControls = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                    submitControls.forEach((control) => {
                        control.disabled = false;
                    });
                });
            });
        });

        (function () {
            const defaultClickLockMs = 800;

            document.addEventListener('click', function (event) {
                const button = event.target.closest('button, input[type="button"], input[type="submit"], input[type="reset"]');
                if (!button || button.dataset.allowMultiClick === 'true') {
                    return;
                }

                const lockMs = Number(button.dataset.clickLockMs ?? defaultClickLockMs);
                const now = Date.now();
                const lastClickAt = Number(button.dataset.lastClickAt ?? 0);

                if (now - lastClickAt < lockMs) {
                    event.preventDefault();
                    event.stopPropagation();
                    return;
                }

                button.dataset.lastClickAt = String(now);
            }, true);
        })();
    </script>
</body>
</html>
