(function (window, document) {
    'use strict';

    function escapeCss(value) {
        if (window.CSS && typeof window.CSS.escape === 'function') {
            return window.CSS.escape(String(value));
        }

        return String(value).replace(/["\\]/g, '\\$&');
    }

    function ensureLiveRegion(politeness) {
        var id = politeness === 'assertive' ? 'app-live-assertive' : 'app-live-polite';
        var region = document.getElementById(id);

        if (region) {
            return region;
        }

        region = document.createElement('div');
        region.id = id;
        region.className = 'visually-hidden';
        region.setAttribute('aria-live', politeness);
        region.setAttribute('aria-atomic', 'true');
        document.body.appendChild(region);

        return region;
    }

    function announce(message, politeness) {
        if (!message) {
            return;
        }

        var region = ensureLiveRegion(politeness === 'assertive' ? 'assertive' : 'polite');
        region.textContent = '';

        window.setTimeout(function () {
            region.textContent = String(message);
        }, 30);
    }

    function appendDescribedBy(control, id) {
        var existing = (control.getAttribute('aria-describedby') || '')
            .split(/\s+/)
            .filter(Boolean);

        if (existing.indexOf(id) !== -1) {
            return;
        }

        existing.push(id);
        control.setAttribute('aria-describedby', existing.join(' '));
    }

    function resolveErrorControl(rawKey) {
        var key = String(rawKey || '').trim();
        if (key === '') {
            return null;
        }

        var parts = key.split('.');
        var baseKey = parts[0];
        var rowIndex = parts.length > 1 && /^\d+$/.test(parts[1]) ? parseInt(parts[1], 10) : null;
        var selectors = [
            '[name="' + escapeCss(key) + '"]',
            '[name="' + escapeCss(key) + '[]"]',
            '[name="' + escapeCss(baseKey) + '"]',
            '[name="' + escapeCss(baseKey) + '[]"]'
        ];
        var controls = Array.from(document.querySelectorAll(selectors.join(',')));

        if (controls.length === 0) {
            return null;
        }

        if (rowIndex !== null && controls[rowIndex]) {
            return controls[rowIndex];
        }

        return controls[0];
    }

    function injectFieldError(control, message, rawKey) {
        if (!(control instanceof HTMLElement) || !message) {
            return;
        }

        if (!control.id) {
            control.id = 'field-' + String(rawKey || 'error')
                .replace(/[^a-z0-9_-]+/gi, '-')
                .replace(/^-+|-+$/g, '')
                .toLowerCase();
        }

        var errorId = control.id + '-error';
        var existing = document.getElementById(errorId);
        var anchor = control.closest('.input-wrapper') || control;
        var container = control.closest('.field') || control.closest('td') || control.parentElement;

        if (!existing) {
            existing = document.createElement('p');
            existing.id = errorId;
            existing.className = 'field-error';
            anchor.insertAdjacentElement('afterend', existing);
        }

        existing.textContent = String(message);
        appendDescribedBy(control, errorId);
        control.setAttribute('aria-invalid', 'true');
        control.classList.add('is-invalid');

        if (container instanceof HTMLElement) {
            container.classList.add('has-error');
        }
    }

    function setupValidationSummary() {
        document.querySelectorAll('[data-validation-summary]').forEach(function (summary) {
            var rawPayload = summary.getAttribute('data-validation-summary') || '{}';
            var messages = {};
            var firstControl = null;

            try {
                messages = JSON.parse(rawPayload);
            } catch (error) {
                messages = {};
            }

            Object.keys(messages).forEach(function (rawKey) {
                var control = resolveErrorControl(rawKey);

                if (!(control instanceof HTMLElement)) {
                    return;
                }

                injectFieldError(control, messages[rawKey], rawKey);

                if (firstControl === null) {
                    firstControl = control;
                }
            });

            if (
                firstControl instanceof HTMLElement
                && (document.activeElement === document.body || document.activeElement === null)
            ) {
                firstControl.focus();
            }
        });
    }

    function setupFormSubmitLock() {
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (event.defaultPrevented) {
                    return;
                }

                if (form.dataset.submitting === 'true') {
                    event.preventDefault();
                    return;
                }

                form.dataset.submitting = 'true';

                form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function (control) {
                    control.disabled = true;
                    control.setAttribute('aria-disabled', 'true');

                    if (
                        control instanceof HTMLButtonElement
                        && control.dataset.loadingLabel
                        && !control.dataset.originalLabel
                    ) {
                        control.dataset.originalLabel = control.innerHTML;
                        control.innerHTML = control.dataset.loadingLabel;
                    }
                });
            });
        });

        window.addEventListener('pageshow', function () {
            document.querySelectorAll('form[data-submitting="true"]').forEach(function (form) {
                delete form.dataset.submitting;

                form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function (control) {
                    control.disabled = false;
                    control.removeAttribute('aria-disabled');

                    if (control instanceof HTMLButtonElement && control.dataset.originalLabel) {
                        control.innerHTML = control.dataset.originalLabel;
                        delete control.dataset.originalLabel;
                    }
                });
            });
        });
    }

    function setupRapidClickLock() {
        var defaultClickLockMs = 800;

        document.addEventListener('click', function (event) {
            var button = event.target.closest('button, input[type="button"], input[type="submit"], input[type="reset"]');
            if (!button || button.dataset.allowMultiClick === 'true') {
                return;
            }

            var lockMs = Number(button.dataset.clickLockMs || defaultClickLockMs);
            var now = Date.now();
            var lastClickAt = Number(button.dataset.lastClickAt || 0);

            if (now - lastClickAt < lockMs) {
                event.preventDefault();
                event.stopPropagation();
                return;
            }

            button.dataset.lastClickAt = String(now);
        }, true);
    }

    function dismissAlert(alert) {
        if (!(alert instanceof HTMLElement) || alert.dataset.dismissing === 'true') {
            return;
        }

        alert.dataset.dismissing = 'true';
        alert.classList.add('alert-dismissing');

        window.setTimeout(function () {
            alert.remove();
        }, 350);
    }

    function setupAlerts() {
        document.querySelectorAll('.alert[data-auto-dismiss]').forEach(function (alert) {
            var duration = parseInt(alert.dataset.autoDismiss || '5000', 10);
            var remaining = Number.isFinite(duration) ? duration : 5000;
            var timerId = null;
            var startedAt = Date.now();

            function startTimer() {
                if (timerId !== null) {
                    window.clearTimeout(timerId);
                }

                startedAt = Date.now();
                timerId = window.setTimeout(function () {
                    dismissAlert(alert);
                }, remaining);
            }

            function pauseTimer() {
                if (timerId === null) {
                    return;
                }

                window.clearTimeout(timerId);
                timerId = null;
                remaining -= Date.now() - startedAt;
            }

            startTimer();
            alert.addEventListener('mouseenter', pauseTimer);
            alert.addEventListener('focusin', pauseTimer);
            alert.addEventListener('mouseleave', startTimer);
            alert.addEventListener('focusout', startTimer);
        });

        document.addEventListener('click', function (event) {
            var closeButton = event.target.closest('.alert-close');
            if (!closeButton) {
                return;
            }

            var alert = closeButton.closest('.alert');
            if (alert) {
                dismissAlert(alert);
            }
        });
    }

    function setupPasswordToggle() {
        document.addEventListener('click', function (event) {
            var button = event.target.closest('[data-pw-toggle]');
            if (!button) {
                return;
            }

            var input = document.getElementById(button.dataset.pwToggle);
            if (!(input instanceof HTMLInputElement)) {
                return;
            }

            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');

            var eyeIcon = button.querySelector('.icon-eye');
            var eyeOff = button.querySelector('.icon-eye-off');

            if (eyeIcon) {
                eyeIcon.style.display = isHidden ? 'none' : '';
            }

            if (eyeOff) {
                eyeOff.style.display = isHidden ? '' : 'none';
            }
        });
    }

    function setupDirtyForms() {
        var forms = Array.from(document.querySelectorAll('form[data-dirty-form]'));
        if (forms.length === 0) {
            return;
        }

        forms.forEach(function (form) {
            var markDirty = function () {
                if (form.dataset.submitting === 'true') {
                    return;
                }

                form.dataset.dirty = 'true';
            };

            var clearDirty = function () {
                delete form.dataset.dirty;
            };

            form.addEventListener('input', markDirty);
            form.addEventListener('change', markDirty);
            form.addEventListener('reset', clearDirty);
            form.addEventListener('submit', clearDirty);
        });

        window.addEventListener('beforeunload', function (event) {
            var hasDirtyForm = forms.some(function (form) {
                return form.dataset.dirty === 'true' && form.dataset.submitting !== 'true';
            });

            if (!hasDirtyForm) {
                return;
            }

            event.preventDefault();
            event.returnValue = '';
        });
    }

    function markFilteredRows(allRows, filteredRows) {
        var visibleRows = new Set(Array.from(filteredRows || []));

        Array.from(allRows || []).forEach(function (row) {
            if (row instanceof HTMLElement) {
                row.dataset.exportVisible = visibleRows.has(row) ? 'true' : 'false';
            }
        });
    }

    function normalizeCsvText(value) {
        return String(value || '').replace(/\s+/g, ' ').trim();
    }

    function csvEscape(value) {
        var text = normalizeCsvText(value);

        if (/[",\r\n]/.test(text)) {
            return '"' + text.replace(/"/g, '""') + '"';
        }

        return text;
    }

    function exportHeaderText(header) {
        var text = normalizeCsvText(header.innerText || header.textContent || '');
        text = text.replace(/\s+\(All\)$/i, '');

        if (/^Status(\s|$)/i.test(text)) {
            return 'Status';
        }

        if (/^Type(\s|$)/i.test(text)) {
            return 'Type';
        }

        return text;
    }

    function parseColumnList(value) {
        return String(value || '')
            .split(',')
            .map(function (item) {
                return parseInt(item.trim(), 10);
            })
            .filter(function (item) {
                return !Number.isNaN(item);
            });
    }

    function includedCsvColumns(link, table) {
        var explicitExclusions = new Set(parseColumnList(link.getAttribute('data-export-exclude-columns')));
        var headers = Array.from(table.querySelectorAll('thead th'));

        return headers
            .map(function (header, index) {
                return {header: header, index: index};
            })
            .filter(function (entry) {
                var label = normalizeCsvText(entry.header.innerText || entry.header.textContent || '').toLowerCase();

                return !explicitExclusions.has(entry.index)
                    && !entry.header.classList.contains('actions')
                    && label !== 'action'
                    && label !== 'actions';
            });
    }

    function resolveExportRows(link, table) {
        var rowSelector = link.getAttribute('data-export-row-selector') || 'tbody tr';
        var rows = Array.from(table.querySelectorAll(rowSelector));
        var hasTrackedRows = rows.some(function (row) {
            return row instanceof HTMLElement && row.dataset.exportVisible !== undefined;
        });

        if (hasTrackedRows) {
            return rows.filter(function (row) {
                return row instanceof HTMLElement && row.dataset.exportVisible === 'true';
            });
        }

        return rows.filter(function (row) {
            return row instanceof HTMLTableRowElement
                && !row.classList.contains('no-records-row')
                && row.cells.length > 0
                && row.offsetParent !== null;
        });
    }

    function filenameSlug(value) {
        return normalizeCsvText(value)
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .slice(0, 48);
    }

    function filterLabel(key) {
        var labels = {
            q: 'search',
            event_name: 'event',
            event_module: 'module',
            event_actor_id: 'actor',
            event_date_from: 'from',
            event_date_to: 'to',
            event_limit: 'limit',
            metric_date_from: 'from',
            metric_date_to: 'to',
            metric_module: 'module',
            actor_id: 'actor',
            date_from: 'from',
            date_to: 'to',
            days: 'days',
            movement_type: 'type',
            overview_days: 'days'
        };

        return labels[key] || key;
    }

    function addFilenameFilter(filters, key, value) {
        var normalizedKey = filenameSlug(filterLabel(key));
        var normalizedValue = filenameSlug(value);

        if (
            normalizedKey === ''
            || normalizedValue === ''
            || normalizedValue === 'all'
            || normalizedValue === 'all-statuses'
            || normalizedValue === 'all-stock-levels'
            || normalizedValue === 'all-levels'
            || normalizedValue === 'all-expiries'
            || normalizedValue === 'all-types'
            || normalizedValue === 'all-low-stock'
        ) {
            return;
        }

        filters.set(normalizedKey, normalizedValue);
    }

    function addUrlFilenameFilters(filters, href) {
        var url = new URL(href, window.location.href);
        var ignored = new Set(['export', 'dataset']);

        url.searchParams.forEach(function (value, key) {
            if (ignored.has(key)) {
                return;
            }

            addFilenameFilter(filters, key, value);
        });
    }

    function controlFilenameKey(control) {
        var key = normalizeCsvText(control.getAttribute('name'));
        if (key !== '') {
            return key;
        }

        var id = normalizeCsvText(control.id);
        if (control.classList.contains('search-input') || id.indexOf('search') !== -1) {
            return 'search';
        }

        if (id.indexOf('stock-status') !== -1) {
            return 'stock';
        }

        if (id.indexOf('expiry-status') !== -1) {
            return 'expiry';
        }

        return id || normalizeCsvText(control.getAttribute('aria-label')) || 'filter';
    }

    function controlFilenameValue(control) {
        if (control instanceof HTMLSelectElement) {
            var option = control.selectedOptions && control.selectedOptions.length > 0
                ? control.selectedOptions[0]
                : null;

            return option ? option.textContent : control.value;
        }

        return control.value;
    }

    function addToolbarFilenameFilters(filters, table) {
        var tableCard = table.closest('.table-card');
        if (!tableCard) {
            return;
        }

        tableCard
            .querySelectorAll('.table-toolbar input:not([type="hidden"]), .table-toolbar select')
            .forEach(function (control) {
                if (!(control instanceof HTMLInputElement) && !(control instanceof HTMLSelectElement)) {
                    return;
                }

                var form = control.closest('form');
                if (form && (form.getAttribute('method') || '').toLowerCase() === 'get') {
                    return;
                }

                addFilenameFilter(filters, controlFilenameKey(control), controlFilenameValue(control));
            });
    }

    function addHeaderFilenameFilters(filters, table) {
        table.querySelectorAll('th .filter-active-text').forEach(function (activeLabel) {
            var value = normalizeCsvText(activeLabel.textContent).replace(/^\(|\)$/g, '');
            if (value === '' || value.toLowerCase() === 'all') {
                return;
            }

            var header = activeLabel.closest('th');
            var headerText = header
                ? normalizeCsvText(header.textContent).replace(normalizeCsvText(activeLabel.textContent), '')
                : '';
            var key = headerText || 'filter';

            addFilenameFilter(filters, key, value);
        });
    }

    function timestampForFilename() {
        var now = new Date();
        var pad = function (value) {
            return String(value).padStart(2, '0');
        };

        return String(now.getFullYear())
            + pad(now.getMonth() + 1)
            + pad(now.getDate())
            + '_'
            + pad(now.getHours())
            + pad(now.getMinutes())
            + pad(now.getSeconds());
    }

    function buildCsvFilename(link, table) {
        var explicitName = normalizeCsvText(link.getAttribute('data-export-filename'));
        var baseName = '';

        if (explicitName !== '') {
            baseName = explicitName.replace(/\.csv$/i, '');
        } else {
            var url = new URL(link.href, window.location.href);
            baseName = url.pathname
                .replace(/\/+$/g, '')
                .split('/')
                .filter(Boolean)
                .pop() || 'filtered_export';
        }

        var filters = new Map();
        var originalHref = link.dataset.originalExportHref || link.href;

        addUrlFilenameFilters(filters, originalHref);

        if (table instanceof HTMLTableElement) {
            addToolbarFilenameFilters(filters, table);
            addHeaderFilenameFilters(filters, table);
        }

        var filterPart = Array.from(filters.entries())
            .map(function (entry) {
                return entry[0] + '-' + entry[1];
            })
            .join('_');

        return [
            filenameSlug(baseName) || 'filtered-export',
            filterPart || 'all',
            timestampForFilename()
        ].join('_') + '.csv';
    }

    function prepareCsvDownload(link, filename, lines) {
        var blob = new Blob([lines.join('\r\n') + '\r\n'], {type: 'text/csv;charset=utf-8'});
        var url = window.URL.createObjectURL(blob);
        var hasStoredState = link.dataset.originalExportHref !== undefined;
        var originalHref = hasStoredState ? link.dataset.originalExportHref : link.href;
        var hadOriginalDownload = hasStoredState
            ? link.dataset.originalExportHadDownload === 'true'
            : link.hasAttribute('download');
        var originalDownload = hasStoredState
            ? (link.dataset.originalExportDownload || '')
            : (link.getAttribute('download') || '');

        if (!hasStoredState) {
            link.dataset.originalExportHref = originalHref;
            link.dataset.originalExportHadDownload = hadOriginalDownload ? 'true' : 'false';
        }

        if (hadOriginalDownload) {
            link.dataset.originalExportDownload = originalDownload;
        }

        link.href = url;
        link.download = filename;

        window.setTimeout(function () {
            link.href = originalHref;

            if (hadOriginalDownload) {
                link.setAttribute('download', originalDownload);
            } else {
                link.removeAttribute('download');
            }

            delete link.dataset.originalExportHref;
            delete link.dataset.originalExportHadDownload;
            delete link.dataset.originalExportDownload;
            window.URL.revokeObjectURL(url);
        }, 1000);
    }

    function setupFilteredCsvExports() {
        document.addEventListener('click', function (event) {
            if (!(event.target instanceof Element)) {
                return;
            }

            var link = event.target.closest('a[data-filtered-csv-export]');
            if (!link || event.defaultPrevented) {
                return;
            }

            var tableSelector = link.getAttribute('data-export-table');
            var table = tableSelector ? document.querySelector(tableSelector) : null;
            if (!(table instanceof HTMLTableElement)) {
                return;
            }

            var columns = includedCsvColumns(link, table);
            if (columns.length === 0) {
                return;
            }

            var rows = resolveExportRows(link, table);
            var lines = [
                columns.map(function (entry) {
                    return csvEscape(exportHeaderText(entry.header));
                }).join(',')
            ];

            rows.forEach(function (row) {
                lines.push(columns.map(function (entry) {
                    var cell = row.cells[entry.index];

                    return csvEscape(cell ? cell.innerText : '');
                }).join(','));
            });

            prepareCsvDownload(link, buildCsvFilename(link, table), lines);
        }, true);
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupFormSubmitLock();
        setupRapidClickLock();
        setupAlerts();
        setupPasswordToggle();
        setupValidationSummary();
        setupDirtyForms();
        setupFilteredCsvExports();
    });

    window.InventoryV2Hci = {
        announce: announce,
        markFilteredRows: markFilteredRows
    };
})(window, document);
