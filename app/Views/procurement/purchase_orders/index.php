<?php

declare(strict_types=1);

$title = 'Purchase Orders - InventoryV2';
$pageTitle = 'Procurement - Purchase Orders';
$pageSubtitle = 'Issue draft purchase orders and convert issued orders to PO requests.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Purchase Orders'],
];

$user = function_exists('auth') ? auth()->user() : null;
$isAdmin = $user !== null && method_exists($user, 'inGroup') && $user->inGroup('admin');
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* Custom JS Pager */
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li { display: block; }
    .ci-pager li a, .ci-pager li span { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; font-size: 0.85rem; min-width: 32px; border: 1px solid var(--color-border-strong); border-radius: var(--radius-sm); background: var(--color-surface); color: var(--color-brand-700); text-decoration: none; font-weight: 600; transition: all 0.2s ease; }
    .ci-pager li a:hover { background: var(--color-brand-100); border-color: var(--color-brand-500); }
    .ci-pager li.active a { background: var(--color-brand-500); color: #ffffff; border-color: var(--color-brand-600); }
    .ci-pager li.disabled a { opacity: 0.5; background: var(--color-surface-alt); color: var(--color-text-muted); pointer-events: none; border-color: var(--color-border); }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--color-text-muted); }

    /* Fixed Sortable Headers */
    #po-table th { padding-top: 12px !important; padding-bottom: 12px !important; }
    th.sortable { cursor: pointer; position: relative; padding-right: 28px !important; user-select: none; transition: background 0.2s ease; line-height: 1.4 !important; white-space: normal !important; vertical-align: middle !important; overflow: visible !important; }
    th.sortable:hover { background: rgba(0, 0, 0, 0.03) !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 8px; top: 50%; transform: translateY(-50%); font-size: 0.85rem; opacity: 0.3; pointer-events: none; }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    
    .filter-active-text { color: var(--color-brand-600); font-weight: 800; text-transform: capitalize; }

    /* --- HCI UNIFIED ACTION BUTTONS --- */
    .action-forms-container { display: flex; gap: 8px; align-items: center; justify-content: flex-end; flex-wrap: nowrap; }

    .btn-link-view { font-size: 0.78rem; color: #0284c7; text-decoration: underline; font-weight: 700; padding: 4px 6px; white-space: nowrap; border-radius: 4px; transition: background 0.2s ease; cursor: pointer; border: none; background: transparent; }
    .btn-link-view:hover { color: #0369a1; background: #f0f9ff; }
    
    .btn-table { padding: 4px 10px !important; font-size: 0.7rem !important; font-weight: 700 !important; border-radius: 4px !important; text-transform: uppercase !important; transition: all 0.2s ease; border: none !important; cursor: pointer; display: inline-flex; text-decoration: none !important; align-items: center; justify-content: center; white-space: nowrap; }
    
    .btn-action-primary { background: var(--color-brand-600) !important; color: white !important; }
    .btn-action-primary:hover { background: var(--color-brand-700) !important; transform: translateY(-1px); }

    /* Solid Indigo Button (Inverted) */
    .btn-action-indigo { background: #4f46e5 !important; color: white !important; border: 1px solid #4338ca !important; }
    .btn-action-indigo:hover { background: #4338ca !important; color: white !important; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(67, 56, 202, 0.2); }

    /* --- SPECIFIC ACTION COLUMN BADGES --- */
    .action-badge-neutral { background: transparent; color: #64748b; border: 1px solid #cbd5e1; padding: 4px 8px; font-size: 0.65rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; cursor: default; white-space: nowrap; }
    
    /* Yellow filled for Pending PO Requests (Restored) */
    .action-badge-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; padding: 4px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; cursor: default; white-space: nowrap; }
    
    /* Outline style for Approved PO Requests (Prevents clash with Fully Received status) */
    .action-badge-success { background: #ffffff; color: #15803d; border: 1px solid #4ade80; padding: 4px 10px; font-size: 0.7rem; font-weight: 800; border-radius: 4px; text-transform: uppercase; cursor: default; white-space: nowrap; }

    /* --- MAIN STATUS COLUMN BADGES (SOLID) --- */
    .status-badge { padding: 4px 10px; font-size: 0.75rem; font-weight: 700; border-radius: 9999px; text-transform: uppercase; white-space: nowrap; display: inline-block; letter-spacing: 0.02em; }
    .status-draft { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
    .status-issued { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .status-partial { background: #ccfbf1; color: #0f766e; border: 1px solid #99f6e4; }
    .status-full { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-cancelled { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php $purchaseOrderExportQuery = http_build_query(['export' => 'csv', 'status' => ($status ?? '')]); ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') . '?' . $purchaseOrderExportQuery ?>">Export CSV</a>
<?php if ($isAdmin): ?>
<a class="btn btn-outline" href="<?= site_url('procurement/approvals/pending') ?>">Pending Approvals</a>
<?php endif ?>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $purchaseOrders ?? [];
$totalOrders = count($rows);
$draftOrders = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'draft'));
$issuedOrders = count(array_filter($rows, static fn (array $row): bool => ($row['status'] ?? '') === 'issued'));
$receivedOrders = count(array_filter($rows, static fn (array $row): bool => in_array((string) ($row['status'] ?? ''), ['partially_received', 'fully_received'], true)));
?>

<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Visible POs</p>
                <p class="kpi-value" id="kpi-visible" style="font-size: 1.25rem;"><?= esc((string) $totalOrders) ?></p>
                <p class="kpi-note">Purchase orders in current view.</p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Draft</p>
                <p class="kpi-value" id="kpi-draft" style="font-size: 1.25rem; color: #d97706;"><?= esc((string) $draftOrders) ?></p>
                <p class="kpi-note">Pending PO issuance.</p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Issued</p>
                <p class="kpi-value" id="kpi-issued" style="font-size: 1.25rem; color: var(--color-brand-600);"><?= esc((string) $issuedOrders) ?></p>
                <p class="kpi-note">Ready for PO request flow.</p>
            </article>
            <article class="kpi-card" style="padding: 12px;">
                <p class="kpi-label">Received</p>
                <p class="kpi-value" id="kpi-received" style="font-size: 1.25rem; color: var(--color-success);"><?= esc((string) $receivedOrders) ?></p>
                <p class="kpi-note">Partially or fully received.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm" style="border-bottom: 1px solid var(--color-border); padding-bottom: 12px; margin-bottom: 8px;">
            <h2 style="margin: 0; font-size: 1.25rem;">Purchase Order Queue</h2>
            <p class="muted" style="margin: 4px 0 0 0; font-size: 0.85rem;">Filter by status, issue draft POs, and convert issued POs to PO requests.</p>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
                <input type="text" id="instant-search-input" placeholder="Search by PO number, supplier, or PO ID..." autocomplete="off" style="flex: 1;">
                <button type="button" class="btn btn-outline" id="btn-clear-search">Clear</button>
            </div>
            
            <form class="inline-form" id="server-filter-form" method="get" action="<?= site_url('procurement/purchase-orders') ?>" style="margin: 0;">
                <?php $poStatusLabels = ['draft' => 'Draft', 'issued' => 'Issued', 'partially_received' => 'Partially Received', 'fully_received' => 'Fully Received', 'cancelled' => 'Cancelled']; ?>
                <select id="status" name="status" aria-label="Filter purchase orders by status" style="padding: 6px 12px; font-size: 0.85rem;">
                    <option value="">All Statuses</option>
                    <?php foreach ($poStatusLabels as $option => $label): ?>
                        <option value="<?= esc($option) ?>" <?= (($status ?? '') === $option) ? 'selected' : '' ?>><?= esc($label) ?></option>
                    <?php endforeach ?>
                </select>
                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.85rem;">Filter</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="table" id="po-table" style="table-layout: fixed; width: 100%; min-width: 1050px;">
                <colgroup>
                    <col style="width: 60px;">  <col style="width: 150px;"> <col style="width: 80px;">  <col style="width: 25%;">   <col style="width: 120px;"> <col style="width: 180px;"> <col style="width: 120px;"> <col style="width: 220px;"> </colgroup>
                <thead>
                    <tr>
                        <th class="sortable numeric" data-col="0">ID</th>
                        <th class="sortable" data-col="1">PO Number</th>
                        <th class="sortable numeric" data-col="2">PR ID</th>
                        <th class="sortable" data-col="3">Supplier</th>
                        <th class="sortable date" data-col="4">Order Date</th>
                        <th class="sortable" data-col="5" id="status-header" style="line-height: 1.2;">Status <br><span style="font-size: 0.75rem; opacity: 0.7;">(All)</span></th>
                        <th class="sortable numeric" data-col="6" style="text-align: right;">Total</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (($purchaseOrders ?? []) === []): ?>
                        <tr><td colspan="8" class="empty-state">No purchase orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($purchaseOrders as $order): ?>
                            <tr class="po-row" style="display: none;" data-status="<?= esc(strtolower((string) ($order['status'] ?? ''))) ?>">
                                <td><?= esc((string) $order['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 500; color: var(--color-brand-700);"><?= esc((string) $order['po_number']) ?></td>
                                <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--color-text-muted);">PR-<?= esc((string) $order['purchase_request_id']) ?></td>
                                <td style="font-weight: 500; word-break: break-word;"><?= esc((string) ($order['supplier_name'] ?? '-')) ?></td>
                                <td style="font-size: 0.85rem;"><?= esc((string) $order['order_date']) ?></td>
                                
                                <td>
                                    <?php 
                                        $rawStatus = strtolower((string) ($order['status'] ?? 'draft'));
                                        $sClass = 'status-draft';
                                        if ($rawStatus === 'issued') $sClass = 'status-issued';
                                        if ($rawStatus === 'partially_received') $sClass = 'status-partial';
                                        if ($rawStatus === 'fully_received') $sClass = 'status-full';
                                        if ($rawStatus === 'cancelled') $sClass = 'status-cancelled';
                                        $cleanStatus = ucwords(str_replace('_', ' ', $rawStatus));
                                    ?>
                                    <span class="status-badge <?= $sClass ?>"><?= esc($cleanStatus) ?></span>
                                </td>
                                
                                <td style="text-align: right; font-family: var(--font-mono); font-weight: 600;">
                                    <?= esc(number_format((float) ($order['total_amount'] ?? 0), 2)) ?>
                                </td>
                                
                                <td>
                                    <div class="action-forms-container">
                                        <?php if (($order['status'] ?? '') === 'draft' && $isAdmin): ?>
                                            <form method="post" action="<?= site_url('procurement/purchase-orders/' . $order['id'] . '/issue') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-action-primary">Issue</button>
                                            </form>
                                        <?php endif ?>

                                        <?php if (($order['status'] ?? '') === 'issued' && $isAdmin && ! (bool) ($order['has_open_po_request'] ?? false)): ?>
                                            <form method="post" action="<?= site_url('procurement/po-requests/from-po/' . $order['id']) ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-table btn-action-indigo">PO Req &rarr;</button>
                                            </form>
                                        <?php endif ?>

                                        <?php if (($order['status'] ?? '') === 'issued' && (bool) ($order['has_open_po_request'] ?? false)): ?>
                                            <?php 
                                                // Determine the Action Badge Color based on PO Request Status
                                                $reqStatus = strtolower((string) ($order['po_request_status'] ?? 'pending'));
                                                $badgeClass = 'action-badge-warning'; // Default Yellow for pending/open
                                                if ($reqStatus === 'approved') {
                                                    $badgeClass = 'action-badge-success'; // Clean green outline for approved
                                                }
                                                $cleanReqStatus = strtoupper(str_replace('_', ' ', $reqStatus));
                                            ?>
                                            <span class="<?= $badgeClass ?>">PO REQ: <?= esc($cleanReqStatus) ?></span>
                                        <?php endif ?>
                                        
                                        <?php if (! $isAdmin && in_array((string) ($order['status'] ?? ''), ['draft', 'issued'], true)): ?>
                                            <span class="action-badge-neutral">Read-only</span>
                                        <?php endif ?>

                                        <?php if (!in_array(($order['status'] ?? ''), ['draft', 'issued'], true)): ?>
                                            <span class="muted" style="font-size: 0.85rem; font-weight: 600; padding: 4px 10px;">&mdash;</span>
                                        <?php endif ?>

                                        <a class="btn-link-view" href="<?= site_url('procurement/purchase-orders/' . $order['id']) ?>">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
            <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">Showing records <span id="page-indicator"></span> (Total: <span id="total-indicator"><?= esc((string) $totalOrders) ?></span>)</p>
            <nav aria-label="Pagination"><ul class="ci-pager" id="client-pager"></ul></nav>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- TABLE SORTING & PAGINATION LOGIC ---
        const rowsPerPage = 15; 
        const tbody = document.querySelector('#po-table tbody');
        
        if (tbody && tbody.querySelector('.po-row')) {
            const allRows = Array.from(tbody.querySelectorAll('.po-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const statusHeader = document.getElementById('status-header');
            
            const kpiVisible = document.getElementById('kpi-visible');
            const kpiDraft = document.getElementById('kpi-draft');
            const kpiIssued = document.getElementById('kpi-issued');
            const kpiReceived = document.getElementById('kpi-received');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            const statusCycle = ['All', 'draft', 'issued', 'partially_received', 'fully_received', 'cancelled'];
            let cycleIndex = 0;
            let currentStatusFilter = 'All';

            function toTitleCase(str) { return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '); }

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();
                currentRows = allRows.filter(row => {
                    const poNum = row.children[1].innerText.toLowerCase();
                    const prId = row.children[2].innerText.toLowerCase();
                    const supplier = row.children[3].innerText.toLowerCase();
                    const statusVal = row.getAttribute('data-status');
                    const matchesText = query === '' || poNum.includes(query) || prId.includes(query) || supplier.includes(query);
                    const matchesStatus = currentStatusFilter === 'All' || statusVal === currentStatusFilter;
                    return matchesText && matchesStatus;
                });
                currentRows.forEach(row => tbody.appendChild(row));
                updateKPIs(); showPage(1);
            }

            function updateKPIs() {
                let countDraft = 0, countIssued = 0, countReceived = 0;
                currentRows.forEach(row => {
                    const stat = row.getAttribute('data-status');
                    if (stat === 'draft') countDraft++;
                    if (stat === 'issued') countIssued++;
                    if (stat === 'partially_received' || stat === 'fully_received') countReceived++;
                });
                kpiVisible.innerText = currentRows.length; kpiDraft.innerText = countDraft; kpiIssued.innerText = countIssued; kpiReceived.innerText = countReceived; totalIndicator.innerText = currentRows.length;
            }

            if(searchInput) searchInput.addEventListener('input', applyFilters);
            if(clearBtn) { clearBtn.addEventListener('click', () => { searchInput.value = ''; applyFilters(); }); }

            if(statusHeader) {
                statusHeader.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    cycleIndex = (cycleIndex + 1) % statusCycle.length;
                    currentStatusFilter = statusCycle[cycleIndex];
                    if (currentStatusFilter === 'All') {
                        statusHeader.innerHTML = `Status <br><span style="font-size: 0.75rem; opacity: 0.7;">(All)</span>`;
                    } else {
                        statusHeader.innerHTML = `Status <br><span class="filter-active-text">${toTitleCase(currentStatusFilter)}</span>`;
                    }
                    applyFilters();
                });
            }

            document.querySelectorAll('#po-table th.sortable').forEach(th => {
                if (parseInt(th.getAttribute('data-col')) === 5) return; // Skip Status col

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const isDateCol = th.classList.contains('date');
                    const isAsc = th.classList.contains('asc');
                    const direction = isAsc ? -1 : 1; 
                    
                    document.querySelectorAll('#po-table th.sortable').forEach(header => {
                        if (parseInt(header.getAttribute('data-col')) !== 5) {
                            header.classList.remove('asc', 'desc');
                        }
                    });
                    
                    th.classList.add(isAsc ? 'desc' : 'asc');
                    
                    currentRows.sort((a, b) => {
                        let aText = a.children[colIndex].innerText.trim().replace(/PR-|#|,/g, '');
                        let bText = b.children[colIndex].innerText.trim().replace(/PR-|#|,/g, '');
                        
                        if (isNumericCol) return (parseFloat(aText) - parseFloat(bText)) * direction;
                        if (isDateCol) {
                            let dateA = aText === '' ? 0 : new Date(aText).getTime();
                            let dateB = bText === '' ? 0 : new Date(bText).getTime();
                            return (dateA - dateB) * direction;
                        }
                        return aText.localeCompare(bText) * direction;
                    });
                    
                    currentRows.forEach(row => tbody.appendChild(row));
                    showPage(1);
                });
            });

            let currentPage = 1;
            function showPage(page) {
                currentPage = page;
                const totalRows = currentRows.length;
                const totalPages = Math.ceil(totalRows / rowsPerPage);
                if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
                const startPoint = (currentPage - 1) * rowsPerPage;
                const endPoint = startPoint + rowsPerPage;
                allRows.forEach(row => row.style.display = 'none');
                currentRows.forEach((row, index) => { if (index >= startPoint && index < endPoint) row.style.display = ''; });
                const actualEnd = Math.min(endPoint, totalRows);
                if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;

                if (pagerContainer) {
                    pagerContainer.innerHTML = '';
                    if (totalPages > 1) {
                        let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;
                        let startPage = Math.max(1, currentPage - 2);
                        let endPage = Math.min(totalPages, startPage + 4);
                        if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);
                        if (startPage > 1) { html += `<li><a href="#" data-page="1">1</a></li>`; if (startPage > 2) html += `<li><span class="ellipsis">...</span></li>`; }
                        for (let i = startPage; i <= endPage; i++) { html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`; }
                        if (endPage < totalPages) { if (endPage < totalPages - 1) html += `<li><span class="ellipsis">...</span></li>`; html += `<li><a href="#" data-page="${totalPages}">${totalPages}</a></li>`; }
                        html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">Next &raquo;</a></li>`;
                        pagerContainer.innerHTML = html;
                    }
                }
            }

            if (pagerContainer) {
                pagerContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (!link) return; e.preventDefault();
                    const li = link.parentElement;
                    if (li.classList.contains('disabled') || li.classList.contains('active')) return;
                    showPage(parseInt(link.getAttribute('data-page')));
                });
            }

            showPage(1);
        }
    });
</script>
<?= $this->endSection() ?>