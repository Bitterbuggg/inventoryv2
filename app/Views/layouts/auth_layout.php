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
    <main class="auth-shell">
        <section class="auth-card">
            <h1 class="auth-title"><?= esc((string) $pageTitle) ?></h1>
            <?php if (! empty($pageSubtitle)): ?>
                <p class="auth-subtitle"><?= esc((string) $pageSubtitle) ?></p>
            <?php endif ?>

            <?= view('components/shared/alerts') ?>
            <?= $this->renderSection('content') ?>
        </section>
    </main>
</body>
</html>
