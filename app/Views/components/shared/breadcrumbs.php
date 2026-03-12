<?php

declare(strict_types=1);

$crumbs = array_values((array) ($crumbs ?? []));
$lastIndex = count($crumbs) - 1;
?>
<?php if ($crumbs !== []): ?>
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol>
            <li><a href="<?= site_url('/') ?>">Home</a></li>
            <?php foreach ($crumbs as $index => $crumb): ?>
                <?php
                $label = (string) ($crumb['label'] ?? '');
                $url   = $crumb['url'] ?? null;
                $isCurrent = ($index === $lastIndex);
                ?>
                <li <?= $isCurrent ? 'aria-current="page"' : '' ?>>
                    <?php if (! empty($url) && ! $isCurrent): ?>
                        <a href="<?= esc((string) $url) ?>"><?= esc($label) ?></a>
                    <?php else: ?>
                        <span><?= esc($label) ?></span>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ol>
    </nav>
<?php endif ?>
