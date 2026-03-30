(function (window, document) {
    'use strict';

    function toTitleCase(value) {
        return String(value)
            .split('_')
            .map(function (part) {
                return part.charAt(0).toUpperCase() + part.slice(1);
            })
            .join(' ');
    }

    function numericValue(value) {
        var normalized = String(value).replace(/[^0-9.-]+/g, '');
        var parsed = parseFloat(normalized);

        return Number.isFinite(parsed) ? parsed : 0;
    }

    function buildPagerHtml(currentPage, totalPages, mode) {
        if (totalPages <= 1) {
            return '';
        }

        var html = '<li class="' + (currentPage === 1 ? 'disabled' : '') + '"><a href="#" data-page="' + (currentPage - 1) + '">&laquo; Prev</a></li>';

        if (mode === 'full') {
            for (var fullPage = 1; fullPage <= totalPages; fullPage++) {
                html += '<li class="' + (fullPage === currentPage ? 'active' : '') + '"><a href="#" data-page="' + fullPage + '">' + fullPage + '</a></li>';
            }

            html += '<li class="' + (currentPage === totalPages ? 'disabled' : '') + '"><a href="#" data-page="' + (currentPage + 1) + '">Next &raquo;</a></li>';
            return html;
        }

        for (var page = 1; page <= totalPages; page++) {
            html += '<li class="' + (page === currentPage ? 'active' : '') + '"><a href="#" data-page="' + page + '">' + page + '</a></li>';
        }

        html += '<li class="' + (currentPage === totalPages ? 'disabled' : '') + '"><a href="#" data-page="' + (currentPage + 1) + '">Next &raquo;</a></li>';

        return html;
    }

    function init(config) {
        var table = document.querySelector(config.tableSelector);
        if (!table) {
            return;
        }

        var tbody = table.querySelector('tbody');
        if (!tbody) {
            return;
        }

        var allRows = Array.from(tbody.querySelectorAll(config.rowSelector));
        if (allRows.length === 0) {
            return;
        }

        var rowsPerPage = config.rowsPerPage || 15;
        var pagerMode = config.pagerMode || 'windowed';
        var pagerContainer = config.pagerSelector ? document.querySelector(config.pagerSelector) : null;
        var pageIndicator = config.pageIndicatorSelector ? document.querySelector(config.pageIndicatorSelector) : null;
        var totalIndicator = config.totalIndicatorSelector ? document.querySelector(config.totalIndicatorSelector) : null;
        var searchInput = config.searchInputSelector ? document.querySelector(config.searchInputSelector) : null;
        var clearButton = config.clearButtonSelector ? document.querySelector(config.clearButtonSelector) : null;
        var filterConfig = config.filter || null;
        var filterHeader = filterConfig && filterConfig.headerSelector ? document.querySelector(filterConfig.headerSelector) : null;
        var state = {
            currentPage: 1,
            currentFilter: filterConfig && Array.isArray(filterConfig.values) && filterConfig.values.length > 0
                ? filterConfig.values[0]
                : 'All',
            visibleRows: allRows.slice()
        };

        function filterLabel(value) {
            if (value === 'All') {
                return 'All';
            }

            if (filterConfig && typeof filterConfig.labelFor === 'function') {
                return filterConfig.labelFor(value);
            }

            return toTitleCase(value);
        }

        function renderFilterHeader() {
            if (!filterHeader || !filterConfig) {
                return;
            }

            var label = filterLabel(state.currentFilter);
            var title = filterConfig.title || 'Status';

            if (typeof filterConfig.renderHeader === 'function') {
                filterConfig.renderHeader(filterHeader, state.currentFilter, label);
                return;
            }

            if (state.currentFilter === 'All') {
                filterHeader.innerHTML = title + ' <span class="filter-active-text" style="font-weight: normal; opacity: 0.7;">(All)</span>';
                return;
            }

            filterHeader.innerHTML = title + ' <br><span class="filter-active-text">' + label + '</span>';
        }

        function matchesSearch(row, query) {
            if (query === '') {
                return true;
            }

            if (typeof config.searchMatcher === 'function') {
                return config.searchMatcher(row, query);
            }

            return row.innerText.toLowerCase().indexOf(query) !== -1;
        }

        function matchesFilter(row) {
            if (!filterConfig || state.currentFilter === 'All') {
                return true;
            }

            return (row.getAttribute(filterConfig.attribute) || '') === state.currentFilter;
        }

        function updateKpis() {
            if (typeof config.updateKpis === 'function') {
                config.updateKpis(state.visibleRows, {
                    totalIndicator: totalIndicator
                });
            }

            if (totalIndicator) {
                totalIndicator.textContent = String(state.visibleRows.length);
            }
        }

        function showPage(page) {
            state.currentPage = page;

            var totalRows = state.visibleRows.length;
            var totalPages = Math.ceil(totalRows / rowsPerPage);

            if (state.currentPage > totalPages && totalPages > 0) {
                state.currentPage = totalPages;
            }

            var startPoint = (state.currentPage - 1) * rowsPerPage;
            var endPoint = startPoint + rowsPerPage;

            allRows.forEach(function (row) {
                row.style.display = 'none';
            });

            state.visibleRows.forEach(function (row, index) {
                if (index >= startPoint && index < endPoint) {
                    row.style.display = '';
                }
            });

            if (pageIndicator) {
                var actualEnd = Math.min(endPoint, totalRows);
                pageIndicator.textContent = totalRows === 0 ? '0' : String(startPoint + 1) + ' - ' + String(actualEnd);
            }

            if (pagerContainer) {
                pagerContainer.innerHTML = buildPagerHtml(state.currentPage, totalPages, pagerMode);
            }
        }

        function applyFilters() {
            var query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            state.visibleRows = allRows.filter(function (row) {
                return matchesSearch(row, query) && matchesFilter(row);
            });

            state.visibleRows.forEach(function (row) {
                tbody.appendChild(row);
            });

            updateKpis();
            showPage(1);
        }

        function sortValue(row, colIndex, header) {
            if (typeof config.sortValue === 'function') {
                return config.sortValue(row, colIndex, header);
            }

            return row.children[colIndex] ? row.children[colIndex].innerText.trim() : '';
        }

        function compareRows(rowA, rowB, colIndex, header, direction) {
            var valueA = sortValue(rowA, colIndex, header);
            var valueB = sortValue(rowB, colIndex, header);

            if (typeof valueA === 'number' || typeof valueB === 'number') {
                return ((Number(valueA) || 0) - (Number(valueB) || 0)) * direction;
            }

            if (header.classList.contains('numeric')) {
                return (numericValue(valueA) - numericValue(valueB)) * direction;
            }

            if (header.classList.contains('date')) {
                return ((Date.parse(String(valueA)) || 0) - (Date.parse(String(valueB)) || 0)) * direction;
            }

            return String(valueA).localeCompare(String(valueB)) * direction;
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                if (searchInput) {
                    searchInput.value = '';
                }

                applyFilters();
            });
        }

        if (filterHeader && filterConfig && Array.isArray(filterConfig.values) && filterConfig.values.length > 0) {
            filterHeader.addEventListener('click', function (event) {
                event.stopPropagation();

                var currentIndex = filterConfig.values.indexOf(state.currentFilter);
                var nextIndex = (currentIndex + 1) % filterConfig.values.length;
                state.currentFilter = filterConfig.values[nextIndex];

                renderFilterHeader();
                applyFilters();
            });
        }

        table.querySelectorAll('th.sortable').forEach(function (header) {
            if (filterHeader && header === filterHeader) {
                return;
            }

            header.addEventListener('click', function () {
                var colIndex = parseInt(header.getAttribute('data-col') || '', 10);
                if (Number.isNaN(colIndex)) {
                    return;
                }

                var isAsc = header.classList.contains('asc');
                var direction = isAsc ? -1 : 1;

                table.querySelectorAll('th.sortable').forEach(function (otherHeader) {
                    if (otherHeader !== filterHeader) {
                        otherHeader.classList.remove('asc', 'desc');
                    }
                });

                header.classList.add(isAsc ? 'desc' : 'asc');

                state.visibleRows.sort(function (rowA, rowB) {
                    return compareRows(rowA, rowB, colIndex, header, direction);
                });

                state.visibleRows.forEach(function (row) {
                    tbody.appendChild(row);
                });

                showPage(1);
            });
        });

        if (pagerContainer) {
            pagerContainer.addEventListener('click', function (event) {
                var link = event.target.closest('a');
                if (!link) {
                    return;
                }

                event.preventDefault();

                var item = link.parentElement;
                if (item && (item.classList.contains('disabled') || item.classList.contains('active'))) {
                    return;
                }

                var page = parseInt(link.getAttribute('data-page') || '', 10);
                if (!Number.isNaN(page)) {
                    showPage(page);
                }
            });
        }

        renderFilterHeader();
        updateKpis();
        showPage(1);
    }

    window.InventoryV2ProcurementQueue = {
        init: init
    };
})(window, document);
