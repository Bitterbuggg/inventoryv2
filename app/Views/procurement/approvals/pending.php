<?php

declare(strict_types=1);

$title = 'Pending Approvals - InventoryV2';
$pageTitle = 'Procurement - Pending Approvals';
$pageSubtitle = 'Review and decide submitted approvals in the procurement flow.';
$crumbs = [
    ['label' => 'Purchase Requests', 'url' => site_url('procurement/purchase-requests')],
    ['label' => 'Pending Approvals'],
];
?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('head') ?>
<style>
    /* --- SORTABLE TABLE HEADERS (Aligned with global design) --- */
    th.sortable { 
        cursor: pointer; 
        position: relative; 
        padding-right: 18px !important; 
        user-select: none; 
        transition: background 0.2s ease; 
    }
    th.sortable:hover { background: rgba(0, 0, 0, 0.03) !important; }
    th.sortable::after { 
        content: '↕'; 
        position: absolute; 
        right: 6px; 
        top: 50%; 
        transform: translateY(-50%); 
        font-size: 0.75rem; 
        opacity: 0.3; 
    }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--color-brand-600); font-weight: bold; }
    
    .filter-active-text { color: var(--color-brand-600); font-weight: 800; text-transform: capitalize; font-size: 0.75rem; display: inline-block; }

    /* --- CUSTOM JS PAGER --- */
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li { display: block; }
    .ci-pager li a, .ci-pager li span { 
        display: inline-flex; align-items: center; justify-content: center; 
        padding: 6px 12px; font-size: 0.85rem; min-width: 32px; 
        border: 1px solid var(--color-border-strong); border-radius: var(--radius-sm); 
        background: var(--color-surface); color: var(--color-brand-700); 
        text-decoration: none; font-weight: 600; transition: all 0.2s ease; 
    }
    .ci-pager li a:hover { background: var(--color-brand-100); border-color: var(--color-brand-500); }
    .ci-pager li.active a { background: var(--color-brand-500); color: #ffffff; border-color: var(--color-brand-600); }
    .ci-pager li.disabled a { opacity: 0.5; background: var(--color-surface-alt); color: var(--color-text-muted); pointer-events: none; border-color: var(--color-border); }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--color-text-muted); }

    /* --- HCI COMPLIANT ACTION INPUTS --- */
    .action-container { display: flex; gap: 6px; align-items: stretch; flex-direction: column; }
    
    .approval-input-group {
        display: inline-flex;
        align-items: stretch;
        border: 1px solid var(--color-border-strong);
        border-radius: 4px;
        overflow: hidden;
        background: var(--color-surface);
        transition: border-color 0.2s ease;
        height: 30px; /* Keeps inputs compact and aligned in the table */
    }
    .approval-input-group:focus-within { border-color: var(--color-brand-500); box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.15); }
    
    .approval-comment {
        border: none;
        padding: 4px 8px;
        font-size: 0.75rem;
        width: 140px;
        outline: none;
        background: transparent;
    }
    .btn-action {
        border: none;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 12px;
        cursor: pointer;
        transition: background 0.2s ease;
        text-transform: uppercase;
    }
    .btn-approve { background: var(--color-brand-600); }
    .btn-approve:hover { background: var(--color-brand-700); }
    
    .btn-reject { background: #fee2e2; color: #b91c1c; border-left: 1px solid #fca5a5; }
    .btn-reject:hover { background: #fecaca; color: #991b1b; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>">Purchase Orders</a>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>">PO Requests</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $approvals ?? [];
$totalPending = count($rows);
$level1Pending = count(array_filter($rows, static fn (array $row): bool => (string) ($row['approval_level'] ?? '') === '1'));
$prApprovals = count(array_filter($rows, static fn (array $row): bool => (string) ($row['reference_type'] ?? '') === 'purchase_request'));
?>
<div class="stack-lg">
    <section class="card stack-md">
        <div class="kpi-grid">
            <article class="kpi-card">
                <p class="kpi-label">Pending Queue</p>
                <p class="kpi-value" id="kpi-total"><?= esc((string) $totalPending) ?></p>
                <p class="kpi-note">Approvals requiring a decision.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Level 1</p>
                <p class="kpi-value" id="kpi-l1"><?= esc((string) $level1Pending) ?></p>
                <p class="kpi-note">Initial stage records.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Purchase Requests</p>
                <p class="kpi-value" id="kpi-pr"><?= esc((string) $prApprovals) ?></p>
                <p class="kpi-note">Linked to PR flow.</p>
            </article>
            <article class="kpi-card">
                <p class="kpi-label">Filtered Scope</p>
                <p class="kpi-value" id="kpi-pr-only">PR Only</p>
                <p class="kpi-note">Procurement approvals only.</p>
            </article>
        </div>
    </section>

    <section class="card stack-md">
        <div class="stack-sm" style="border-bottom: 1px solid var(--color-border); padding-bottom: 12px; margin-bottom: 8px;">
            <h2 style="margin: 0; font-size: 1.25rem;">Approval Queue</h2>
            <p class="muted" style="margin: 4px 0 0 0; font-size: 0.85rem;">Review pending references and submit approve/reject decisions.</p>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
                <input type="text" id="instant-search-input" placeholder="Search Reference # or ID..." autocomplete="off" style="flex: 1;">
                <button type="button" class="btn btn-outline" id="btn-clear-search">Clear</button>
            </div>
        </div>

        <div id="full-events-container">
            <div class="table-wrap" style="overflow-x: auto;">
                <table class="table" id="approvals-table" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 5%;">  
                        <col style="width: 30%;">   
                        <col style="width: 8%;">   
                        <col style="width: 12%;">   
                        <col style="width: 45%;">    
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="sortable numeric" data-col="0">ID</th>
                            <th class="sortable" data-col="1" id="ref-header" title="Click to cycle type filters!">
                                Reference <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>
                            </th>
                            <th class="sortable numeric" data-col="2">Level</th>
                            <th class="sortable" data-col="3">Status</th>
                            <th>Decisions & Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (($approvals ?? []) === []): ?>
                            <tr class="no-records-row"><td colspan="5" class="empty-state">No pending approvals found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($approvals as $approval): ?>
                                <tr class="approval-row" style="display: none;" data-type="<?= esc(strtolower((string) ($approval['reference_type'] ?? ''))) ?>">
                                    <td><?= esc((string) $approval['id']) ?></td>
                                    <td style="font-family: var(--font-mono); font-weight: 600; color: var(--color-brand-700); font-size: 0.85rem;">
                                        <span style="font-family: var(--font-sans); font-size: 0.7rem; text-transform: uppercase; display: block; opacity: 0.6; font-weight: 500;"><?= esc(str_replace('_', ' ', (string)$approval['reference_type'])) ?></span>
                                        #<?= esc((string) $approval['reference_id']) ?>
                                        <?php $pr = $approval['purchase_request'] ?? null; ?>
                                        <?php if (is_array($pr)): ?>
                                            <details style="margin-top: 6px; font-family: var(--font-sans); font-size: 0.75rem;">
                                                <summary style="cursor: pointer; color: var(--color-brand-700); font-weight: 600;">View PR Details</summary>
                                                <div style="margin-top: 6px; line-height: 1.45; color: var(--color-text-muted);">
                                                    <div style="margin-bottom: 6px;">
                                                        <a href="<?= site_url('procurement/purchase-requests/' . (int) ($approval['reference_id'] ?? 0)) ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.72rem;">Open Full Request</a>
                                                    </div>
                                                    <div><strong>PR:</strong> <?= esc((string) ($pr['pr_number'] ?? '-')) ?></div>
                                                    <div><strong>Date:</strong> <?= esc((string) ($pr['request_date'] ?? '-')) ?></div>
                                                    <div><strong>Requested By:</strong> <?= esc((string) ($pr['requested_by'] ?? '-')) ?></div>
                                                    <?php $items = is_array($pr['items'] ?? null) ? $pr['items'] : []; ?>
                                                    <div><strong>Items:</strong> <?= esc((string) count($items)) ?></div>
                                                    <?php if ($items !== []): ?>
                                                        <ul style="margin: 6px 0 0 16px; padding: 0;">
                                                            <?php foreach (array_slice($items, 0, 4) as $item): ?>
                                                                <li>
                                                                    <?= esc((string) ($item['item_name'] ?? '')) ?>
                                                                    (<?= esc((string) ((int) round((float) ($item['requested_qty'] ?? 0)))) ?>
                                                                    <?= esc((string) ($item['unit'] ?? 'unit')) ?>)
                                                                </li>
                                                            <?php endforeach ?>
                                                        </ul>
                                                        <?php if (count($items) > 4): ?>
                                                            <div style="margin-top: 4px;">+<?= esc((string) (count($items) - 4)) ?> more items</div>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                </div>
                                            </details>
                                        <?php endif ?>
                                    </td>
                                    <td><span class="badge" style="background: var(--color-surface-alt); font-size: 0.75rem;"><?= esc((string) $approval['approval_level']) ?></span></td>
                                    <td><?= view('components/shared/table_status_badge', ['status' => $approval['decision'] ?? 'pending']) ?></td>
                                    <td>
                                        <div class="action-container">
                                            <form method="post" action="<?= site_url('procurement/approvals/' . $approval['id'] . '/approve') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <div class="approval-input-group">
                                                    <input type="text" name="comments" class="approval-comment" placeholder="Approval note...">
                                                    <button type="submit" class="btn-action btn-approve">Approve</button>
                                                </div>
                                            </form>

                                            <form method="post" action="<?= site_url('procurement/approvals/' . $approval['id'] . '/reject') ?>" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <div class="approval-input-group">
                                                    <input type="text" name="comments" class="approval-comment" placeholder="Rejection reason..." required>
                                                    <button type="submit" class="btn-action btn-reject" title="Reject Request">&times; Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--color-border);">
                <p class="muted" style="margin: 0; font-size: 0.85rem; line-height: 1;">
                    Showing records <span id="page-indicator"></span> (Total: <span id="total-indicator"><?= esc((string) $totalPending) ?></span>)
                </p>
                <nav aria-label="Pagination">
                    <ul class="ci-pager" id="client-pager"></ul>
                </nav>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 15; 
        const tbody = document.querySelector('#approvals-table tbody');
        
        if (tbody && tbody.querySelector('.approval-row')) {
            const allRows = Array.from(tbody.querySelectorAll('.approval-row'));
            let currentRows = [...allRows]; 

            const pagerContainer = document.getElementById('client-pager');
            const pageIndicator = document.getElementById('page-indicator');
            const totalIndicator = document.getElementById('total-indicator');
            const refHeader = document.getElementById('ref-header');
            
            const kpiTotal = document.getElementById('kpi-total');
            const kpiL1 = document.getElementById('kpi-l1');
            const kpiPr = document.getElementById('kpi-pr');
            const kpiPrOnly = document.getElementById('kpi-pr-only');

            const searchInput = document.getElementById('instant-search-input');
            const clearBtn = document.getElementById('btn-clear-search');

            const typeCycle = ['All', 'purchase_request'];
            let cycleIndex = 0;
            let currentTypeFilter = 'All';

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();

                currentRows = allRows.filter(row => {
                    const refContent = row.children[1].innerText.toLowerCase();
                    const typeVal = row.getAttribute('data-type');

                    const matchesText = query === '' || refContent.includes(query);
                    const matchesType = currentTypeFilter === 'All' || typeVal === currentTypeFilter;

                    return matchesText && matchesType;
                });

                currentRows.forEach(row => tbody.appendChild(row));
                updateKPIs();
                showPage(1);
            }

            function updateKPIs() {
                let countL1 = 0, countPR = 0;
                currentRows.forEach(row => {
                    const type = row.getAttribute('data-type');
                    const level = row.children[2].innerText.trim();
                    if (level === '1') countL1++;
                    if (type === 'purchase_request') countPR++;
                });
                
                kpiTotal.innerText = currentRows.length;
                kpiL1.innerText = countL1;
                kpiPr.innerText = countPR;
                if (kpiPrOnly) {
                    kpiPrOnly.innerText = 'PR Only';
                }
                totalIndicator.innerText = currentRows.length;
            }

            if(searchInput) searchInput.addEventListener('input', applyFilters);
            if(clearBtn) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    applyFilters();
                });
            }

            if(refHeader) {
                refHeader.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    cycleIndex = (cycleIndex + 1) % typeCycle.length;
                    currentTypeFilter = typeCycle[cycleIndex];

                    if (currentTypeFilter === 'All') {
                        refHeader.innerHTML = `Reference <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>`;
                    } else {
                        refHeader.innerHTML = `Reference <br><span class="filter-active-text">${currentTypeFilter.replace('_', ' ')}</span>`;
                    }
                    applyFilters();
                });
            }

            document.querySelectorAll('#approvals-table th.sortable').forEach(th => {
                if (th.id === 'ref-header') return; 

                th.addEventListener('click', () => {
                    const colIndex = parseInt(th.getAttribute('data-col'));
                    const isNumericCol = th.classList.contains('numeric');
                    const direction = th.classList.contains('asc') ? -1 : 1; 
                    
                    document.querySelectorAll('#approvals-table th.sortable').forEach(header => {
                        if (header.id !== 'ref-header') header.classList.remove('asc', 'desc');
                    });
                    th.classList.add(direction === 1 ? 'asc' : 'desc');
                    
                    currentRows.sort((a, b) => {
                        let aText = a.children[colIndex].innerText.trim().replace('#', '');
                        let bText = b.children[colIndex].innerText.trim().replace('#', '');
                        if (isNumericCol) return (parseFloat(aText) - parseFloat(bText)) * direction;
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
                currentRows.forEach((row, index) => {
                    if (index >= startPoint && index < endPoint) row.style.display = '';
                });

                const actualEnd = Math.min(endPoint, totalRows);
                if (pageIndicator) pageIndicator.innerText = totalRows === 0 ? '0' : `${startPoint + 1} - ${actualEnd}`;

                if (pagerContainer) {
                    pagerContainer.innerHTML = '';
                    if (totalPages > 1) {
                        let html = `<li class="${currentPage === 1 ? 'disabled' : ''}"><a href="#" data-page="${currentPage - 1}">&laquo; Prev</a></li>`;
                        for (let i = 1; i <= totalPages; i++) {
                            html += `<li class="${i === currentPage ? 'active' : ''}"><a href="#" data-page="${i}">${i}</a></li>`;
                        }
                        html += `<li class="${currentPage === totalPages ? 'disabled' : ''}"><a href="#" data-page="${currentPage + 1}">Next &raquo;</a></li>`;
                        pagerContainer.innerHTML = html;
                    }
                }
            }

            if (pagerContainer) {
                pagerContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (!link) return;
                    e.preventDefault();
                    if (link.parentElement.classList.contains('disabled') || link.parentElement.classList.contains('active')) return;
                    showPage(parseInt(link.getAttribute('data-page')));
                });
            }

            showPage(1);
        }
    });
</script>
<?= $this->endSection() ?>