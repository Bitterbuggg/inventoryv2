<?php

declare(strict_types=1);

$items = $items ?? [];
?>
<section class="card stack-md">
    <h2>Requested Items</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>Requested Qty</th>
                    <th>Approved Qty</th>
                    <th>Est. Unit Cost</th>
                    <th>Line Estimate</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr><td colspan="8" class="empty-state">No purchase request items found.</td></tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $qty = (float) ($item['requested_qty'] ?? 0);
                        $cost = (float) ($item['estimated_unit_cost'] ?? 0);
                        ?>
                        <tr>
                            <td><?= esc((string) ($item['id'] ?? '')) ?></td>
                            <td><?= esc((string) ($item['item_name'] ?? '')) ?></td>
                            <td><?= esc((string) ($item['unit'] ?? '')) ?></td>
                            <td><?= esc(app_format_quantity($item['requested_qty'] ?? 0)) ?></td>
                            <td><?= esc(app_format_quantity($item['approved_qty'] ?? null, '-')) ?></td>
                            <td><?= esc(number_format($cost, 2)) ?></td>
                            <td><?= esc(number_format($qty * $cost, 2)) ?></td>
                            <td><?= esc((string) ($item['notes'] ?? '')) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</section>
