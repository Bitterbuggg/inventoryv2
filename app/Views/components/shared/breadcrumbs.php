<?php

declare(strict_types=1);

$crumbs = array_values((array) ($crumbs ?? []));
?>
<?php if ($crumbs !== []): ?>
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol>
            <?php foreach ($crumbs as $crumb): ?>
                <?php
                $label = (string) ($crumb['label'] ?? '');
                $url = $crumb['url'] ?? null;
                ?>
                <li>
                    <?php if (! empty($url)): ?>
                        <a href="<?= esc((string) $url) ?>"><?= esc($label) ?></a>
                    <?php else: ?>
                        <span><?= esc($label) ?></span>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ol>
    </nav>
<?php endif ?>
