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
    /* --- V2 DESIGN SYSTEM VARIABLES --- */
    :root {
        --v2-border: #b2e0eb; 
        --v2-title: #00476b;  
        --v2-label: #00668c;  
        --v2-active-bg: #00638a; 
        --v2-text-main: #1e3a8a; /* TRUE Dark Blue */
        --v2-text-muted: #64748b;
    }

    /* --- NO-SCROLL VIEWPORT WRAPPER --- */
    .viewport-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: calc(100vh - 120px); 
        min-height: 640px;
        overflow: hidden;
    }

    /* --- PASTEL KPI CARDS --- */
    .kpi-grid { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); /* 3 columns for 3 KPIs */
        gap: 16px; 
        flex-shrink: 0; 
    }
    
    .kpi-card { 
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        padding: 16px 18px; 
        display: flex; 
        align-items: center; 
        gap: 14px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
    }
    
    .kpi-icon-box {
        width: 46px; height: 46px; 
        border-radius: 10px; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0;
    }

    .icon-total { background: #f1f5f9; color: #475569; }        
    .icon-level { background: #f5f3ff; color: #8b5cf6; } 
    .icon-pr { background: #eff6ff; color: #2563eb; }   

    .kpi-details { display: flex; flex-direction: column; flex: 1; justify-content: center; min-width: 0; }
    
    .kpi-value { font-size: 1.15rem; font-weight: 800; color: var(--v2-title); line-height: 1.2; margin: 0; }
    .kpi-label { font-size: 0.75rem; font-weight: 500; color: var(--v2-text-muted); margin: 0; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.05em; }

    /* --- V2 TABLE CARD --- */
    .table-card {
        background: #ffffff; 
        border: 1px solid var(--v2-border); 
        border-radius: 12px; 
        display: flex;
        flex-direction: column;
        flex: 1; 
        min-height: 0; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 12px 20px; 
        border-bottom: 1px solid var(--v2-border); 
        background: #ffffff; /* Matched to Purchase Requests page */
        flex-shrink: 0;
        flex-wrap: wrap;
    }
    
    .table-toolbar h3 { margin: 0; font-size: 1.05rem; color: var(--v2-title); font-weight: 800; }
    
    /* Toolbar Controls */
    .toolbar-controls { display: flex; gap: 8px; align-items: center; flex: 1; justify-content: flex-end; }
    
    .search-wrap { position: relative; width: 300px; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 14px; height: 14px; }
    .search-input { 
        width: 100%;
        padding: 6px 12px 6px 30px; 
        font-size: 0.85rem; 
        border: 1px solid var(--v2-border); 
        border-radius: 6px; 
        outline: none; 
        color: var(--v2-text-main);
        background: #ffffff;
    }
    .search-input:focus { border-color: var(--v2-label); box-shadow: 0 0 0 3px rgba(0, 102, 140, 0.1); }

    /* Scrollable Table Area */
    .table-scroll-container {
        flex: 1;
        overflow-y: auto; 
        background: #ffffff;
    }

    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th { 
        position: sticky; top: 0; z-index: 10;
        background: #ffffff !important; /* Pure white header */
        padding: 14px 16px; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        font-weight: 800; 
        color: var(--v2-title); 
        border-bottom: 2px solid var(--v2-border); /* 2px distinct separation line */
        text-align: left; 
        letter-spacing: 0.05em; 
        vertical-align: middle;
    }
    .modern-table td { padding: 12px 16px; font-size: 0.85rem; color: var(--v2-text-main); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .modern-table tr:hover td { background: #f8fafc; }

    /* --- SORTABLE HEADERS --- */
    th.sortable { cursor: pointer; padding-right: 18px !important; user-select: none; transition: background 0.2s ease, color 0.2s ease; }
    th.sortable:hover { background-color: #f1f5f9 !important; color: var(--v2-title) !important; }
    th.sortable::after { content: '↕'; position: absolute; right: 6px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; opacity: 0.3; color: var(--v2-title); }
    th.sortable.asc::after { content: '↑'; opacity: 1; color: var(--v2-label); font-weight: bold; }
    th.sortable.desc::after { content: '↓'; opacity: 1; color: var(--v2-label); font-weight: bold; }

    .filter-active-text { color: var(--v2-label); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; display: inline-block; }

    /* --- PAGINATION FOOTER --- */
    .table-footer {
        padding: 10px 20px;
        border-top: 1px solid var(--v2-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
        flex-shrink: 0;
    }
    .ci-pager { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; align-items: center; }
    .ci-pager li a, .ci-pager li span {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; font-size: 0.75rem; min-width: 28px;
        border: 1px solid var(--v2-border); border-radius: 4px;
        background: #ffffff; color: var(--v2-label);
        text-decoration: none; font-weight: 700; transition: all 0.2s ease;
    }
    .ci-pager li a:hover { background: rgba(178, 224, 235, 0.3); border-color: var(--v2-label); }
    .ci-pager li.active a { background: var(--v2-label); color: #ffffff; border-color: var(--v2-label); }
    .ci-pager li.disabled a { opacity: 0.5; background: #f1f5f9; color: var(--v2-text-muted); pointer-events: none; border-color: #cbd5e1; }
    .ci-pager li span.ellipsis { border: none !important; background: transparent !important; padding: 0 4px !important; min-width: auto; color: var(--v2-text-muted); }

    /* --- INLINE APPROVAL INPUTS (V2 Compliant) --- */
    .action-container { display: flex; gap: 6px; align-items: stretch; flex-direction: column; }
    
    .approval-input-group {
        display: inline-flex;
        align-items: stretch;
        border: 1px solid var(--v2-border);
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        height: 32px; 
    }
    .approval-input-group:focus-within { border-color: var(--v2-label); box-shadow: 0 0 0 2px rgba(0, 102, 140, 0.1); }
    
    .approval-comment {
        border: none;
        padding: 4px 10px;
        font-size: 0.75rem;
        width: 150px;
        outline: none;
        background: transparent;
        color: var(--v2-text-main);
    }
    .btn-action {
        border: none;
        color: white;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 4px 14px;
        cursor: pointer;
        transition: background 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .btn-approve { background: var(--v2-label); border-left: 1px solid var(--v2-border); }
    .btn-approve:hover { background: var(--v2-active-bg); }
    
    .btn-reject { background: #fef2f2; color: #ef4444; border-left: 1px solid #fecaca; }
    .btn-reject:hover { background: #fee2e2; color: #dc2626; }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a class="btn btn-outline" href="<?= site_url('procurement/purchase-orders') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">Purchase Orders</a>
<a class="btn btn-outline" href="<?= site_url('procurement/po-requests') ?>" style="padding: 8px 16px; font-weight: 800; font-size: 0.85rem;">PO Requests</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rows = $approvals ?? [];
$totalPending = count($rows);
$level1Pending = count(array_filter($rows, static fn (array $row): bool => (string) ($row['approval_level'] ?? '') === '1'));
$prApprovals = count(array_filter($rows, static fn (array $row): bool => (string) ($row['reference_type'] ?? '') === 'purchase_request'));
?>

<div class="viewport-wrapper">
    
    <div style="flex-shrink: 0;">
        <h2 style="margin:0; font-size: 1.6rem; color: var(--v2-title); font-weight: 900; letter-spacing: -0.02em;">Pending Approvals</h2>
    </div>

    <section style="flex-shrink: 0;">
        <div class="kpi-grid">
            <article class="kpi-card kpi-accent-slate">
                <div class="kpi-icon-box icon-total"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-total"><?= esc((string) $totalPending) ?></span>
                    <span class="kpi-label">Pending Queue</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-violet">
                <div class="kpi-icon-box icon-level"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-l1"><?= esc((string) $level1Pending) ?></span>
                    <span class="kpi-label">Level 1 Submissions</span>
                </div>
            </article>
            <article class="kpi-card kpi-accent-royal">
                <div class="kpi-icon-box icon-pr"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
                <div class="kpi-details">
                    <span class="kpi-value" id="kpi-pr"><?= esc((string) $prApprovals) ?></span>
                    <span class="kpi-label">Purchase Requests</span>
                </div>
            </article>
        </div>
    </section>

    <div class="table-card">
        <div class="table-toolbar">
            <div>
                <h3>Approval Queue</h3>
                <p style="margin: 2px 0 0 0; font-size: 0.75rem; color: var(--v2-text-muted);">Review pending references and submit decisions.</p>
            </div>
            
            <div class="toolbar-controls">
                <div class="search-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="instant-search-input" class="search-input" placeholder="Search by reference type, ID, or PR number..." autocomplete="off">
                </div>
                <button type="button" class="btn btn-outline" id="btn-clear-search" style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 6px;">Clear</button>
            </div>
        </div>

        <div class="table-scroll-container">
            <table class="modern-table" id="approvals-table" style="table-layout: fixed; width: 100%;">
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
                        <tr class="no-records-row">
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--v2-text-muted);">
                                <strong>No pending approvals found.</strong><br>
                                <span style="font-size: 0.8rem;">Your queue is currently empty.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($approvals as $approval): ?>
                            <tr class="approval-row" style="display: none;" data-type="<?= esc(strtolower((string) ($approval['reference_type'] ?? ''))) ?>">
                                <td style="font-weight: 700; color: #94a3b8;"><?= esc((string) $approval['id']) ?></td>
                                <td style="font-family: var(--font-mono); font-weight: 600; color: var(--v2-label); font-size: 0.85rem;">
                                    <span style="font-family: var(--font-sans); font-size: 0.7rem; text-transform: uppercase; display: block; opacity: 0.6; font-weight: 700; color: var(--v2-title);"><?= esc(str_replace('_', ' ', (string)$approval['reference_type'])) ?></span>
                                    #<?= esc((string) $approval['reference_id']) ?>
                                    
                                    <?php $pr = $approval['purchase_request'] ?? null; ?>
                                    <?php if (is_array($pr)): ?>
                                        <details style="margin-top: 6px; font-family: var(--font-sans); font-size: 0.75rem;">
                                            <summary style="cursor: pointer; color: var(--v2-label); font-weight: 800;">View Details</summary>
                                            <div style="margin-top: 8px; line-height: 1.5; color: var(--v2-text-main); background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                                <div style="margin-bottom: 6px;">
                                                    <a href="<?= site_url('procurement/purchase-requests/' . (int) ($approval['reference_id'] ?? 0)) ?>" style="font-weight: 700; color: var(--v2-title); text-decoration: underline;">Open Full Request</a>
                                                </div>
                                                <div><strong>PR:</strong> <?= esc((string) ($pr['pr_number'] ?? '-')) ?></div>
                                                <div><strong>Date:</strong> <?= esc((string) ($pr['request_date'] ?? '-')) ?></div>
                                                <div><strong>Requested By:</strong> <?= esc((string) ($pr['requested_by'] ?? '-')) ?></div>
                                                <?php $items = is_array($pr['items'] ?? null) ? $pr['items'] : []; ?>
                                                <div style="margin-top: 4px;"><strong>Items (<?= esc((string) count($items)) ?>):</strong></div>
                                                <?php if ($items !== []): ?>
                                                    <ul style="margin: 4px 0 0 16px; padding: 0; color: var(--v2-text-muted);">
                                                        <?php foreach (array_slice($items, 0, 4) as $item): ?>
                                                            <li>
                                                                <?= esc((string) ($item['item_name'] ?? '')) ?>
                                                                (<?= esc((string) ((int) round((float) ($item['requested_qty'] ?? 0)))) ?> <?= esc((string) ($item['unit'] ?? 'unit')) ?>)
                                                            </li>
                                                        <?php endforeach ?>
                                                    </ul>
                                                    <?php if (count($items) > 4): ?>
                                                        <div style="margin-top: 4px; font-style: italic; color: #94a3b8;">+<?= esc((string) (count($items) - 4)) ?> more items</div>
                                                    <?php endif ?>
                                                <?php endif ?>
                                            </div>
                                        </details>
                                    <?php endif ?>
                                </td>
                                <td><span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; color: var(--v2-text-main); border: 1px solid #e2e8f0;"><?= esc((string) $approval['approval_level']) ?></span></td>
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
                                                <button type="submit" class="btn-action btn-reject" title="Reject Request">Reject</button>
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
        
        <div class="table-footer">
            <p style="margin: 0; font-size: 0.8rem; font-weight: 600; color: var(--v2-text-muted);">
                Showing records <span id="page-indicator" style="color: var(--v2-title);"></span> (Total: <span id="total-indicator"><?= esc((string) $totalPending) ?></span>)
            </p>
            <nav aria-label="Pagination">
                <ul class="ci-pager" id="client-pager"></ul>
            </nav>
        </div>
    </div>
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
