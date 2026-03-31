(function () {
    const root = document.querySelector('[data-purchase-request-form]');
    if (!root) {
        return;
    }

    const itemsBody = root.querySelector('[data-pr-items-body]');
    const rowTemplate = root.querySelector('[data-pr-item-row-template]');
    const addRowButton = root.querySelector('[data-pr-add-row]');
    const csvTrigger = root.querySelector('[data-pr-csv-trigger]');
    const csvFileInput = root.querySelector('[data-pr-csv-file]');
    const configNode = root.querySelector('[data-pr-form-config]');
    const feedbackNode = root.querySelector('[data-pr-feedback]');
    const rowCountNode = root.querySelector('[data-pr-row-count]');
    const requestDateInput = root.querySelector('#request_date');
    const neededDateInput = root.querySelector('#needed_date');

    if (!itemsBody || !rowTemplate) {
        return;
    }

    const config = (() => {
        if (!configNode) {
            return { catalogProducts: [], allowCsvImport: false };
        }

        try {
            return JSON.parse(configNode.textContent || '{}');
        } catch (error) {
            return { catalogProducts: [], allowCsvImport: false };
        }
    })();

    const setFeedback = (message) => {
        if (feedbackNode instanceof HTMLElement) {
            feedbackNode.textContent = message || '';
        }

        if (message && window.InventoryV2Hci && typeof window.InventoryV2Hci.announce === 'function') {
            window.InventoryV2Hci.announce(message);
        }
    };

    const syncDateBounds = () => {
        if (!(requestDateInput instanceof HTMLInputElement) || !(neededDateInput instanceof HTMLInputElement)) {
            return;
        }

        neededDateInput.min = requestDateInput.value || '';

        if (neededDateInput.value && requestDateInput.value && neededDateInput.value < requestDateInput.value) {
            neededDateInput.value = requestDateInput.value;
            setFeedback('Needed date was adjusted to match the request date.');
        }
    };

    const refreshRowMetadata = () => {
        const rows = Array.from(itemsBody.querySelectorAll('tr'));

        rows.forEach((row, index) => {
            const rowNumber = index + 1;
            const productSelect = row.querySelector('[data-pr-product-select]');
            const qtyInput = row.querySelector('input[name="requested_qty[]"]');
            const costInput = row.querySelector('input[name="estimated_unit_cost[]"]');
            const notesInput = row.querySelector('input[name="notes[]"]');
            const removeButton = row.querySelector('[data-pr-remove-row]');

            if (productSelect instanceof HTMLElement) {
                productSelect.setAttribute('aria-label', 'Product for line ' + rowNumber);
            }

            if (qtyInput instanceof HTMLElement) {
                qtyInput.setAttribute('aria-label', 'Requested quantity for line ' + rowNumber);
            }

            if (costInput instanceof HTMLElement) {
                costInput.setAttribute('aria-label', 'Estimated unit cost for line ' + rowNumber);
            }

            if (notesInput instanceof HTMLElement) {
                notesInput.setAttribute('aria-label', 'Notes for line ' + rowNumber);
            }

            if (removeButton instanceof HTMLElement) {
                removeButton.setAttribute('aria-label', 'Remove line ' + rowNumber);
            }
        });

        if (rowCountNode instanceof HTMLElement) {
            rowCountNode.textContent = rows.length + ' line item' + (rows.length === 1 ? '' : 's') + ' ready';
        }
    };

    const syncRow = (row) => {
        const select = row.querySelector('[data-pr-product-select]');
        const unitInput = row.querySelector('[data-pr-unit-display]');
        if (!select || !unitInput) {
            return;
        }

        const selected = select.options[select.selectedIndex];
        unitInput.value = selected ? (selected.dataset.unit || '') : '';
    };

    const addRow = () => {
        itemsBody.appendChild(rowTemplate.content.cloneNode(true));
        const newRow = itemsBody.lastElementChild;
        if (newRow instanceof HTMLElement) {
            syncRow(newRow);
            refreshRowMetadata();

            const firstField = newRow.querySelector('[data-pr-product-select]');
            if (firstField instanceof HTMLElement) {
                firstField.focus();
            }
        }
    };

    const removeRow = (button) => {
        const row = button.closest('tr');
        if (row) {
            row.remove();
        }

        if (!itemsBody.querySelector('tr')) {
            addRow();
            setFeedback('At least one empty line item row is kept for faster entry.');
            return;
        }

        refreshRowMetadata();
        setFeedback('Line item removed.');
    };

    const findProductByName = (name) => {
        const needle = name.trim().toLowerCase();
        return (config.catalogProducts || []).find((product) => {
            return String(product.name || '').trim().toLowerCase() === needle;
        }) || null;
    };

    const importCsv = (text) => {
        const rows = text.split(/\r?\n/).filter(Boolean);
        if (rows.length <= 1) {
            return;
        }

        itemsBody.querySelectorAll('tr').forEach((row) => {
            const select = row.querySelector('[data-pr-product-select]');
            if (select && select.value === '') {
                row.remove();
            }
        });

        let imported = 0;
        let unmatched = 0;

        rows.slice(1).forEach((line) => {
            const cols = line.split(',');
            if (!cols[0] || cols[0].trim() === '') {
                return;
            }

            addRow();
            const row = itemsBody.lastElementChild;
            if (!(row instanceof HTMLElement)) {
                return;
            }

            const product = findProductByName(cols[0]);
            const qtyIndex = cols.length >= 5 ? 2 : 1;
            const costIndex = cols.length >= 5 ? 3 : 2;
            const notesIndex = cols.length >= 5 ? 4 : 3;

            const productSelect = row.querySelector('[data-pr-product-select]');
            const qtyInput = row.querySelector('input[name="requested_qty[]"]');
            const costInput = row.querySelector('input[name="estimated_unit_cost[]"]');
            const notesInput = row.querySelector('input[name="notes[]"]');

            if (product && productSelect instanceof HTMLSelectElement) {
                productSelect.value = String(product.id);
            }

            syncRow(row);

            if (qtyInput instanceof HTMLInputElement) {
                qtyInput.value = String(parseInt(cols[qtyIndex] || '0', 10) || '');
            }

            if (costInput instanceof HTMLInputElement) {
                costInput.value = (cols[costIndex] || '').trim();
            }

            if (notesInput instanceof HTMLInputElement) {
                notesInput.value = product
                    ? (cols[notesIndex] || '').trim()
                    : 'Unmatched product: ' + cols[0].trim();
            }

            if (!product) {
                unmatched += 1;
            }

            imported += 1;
        });

        if (!itemsBody.querySelector('tr')) {
            addRow();
        }

        refreshRowMetadata();

        if (imported === 0) {
            setFeedback('No rows were imported from the selected CSV file.');
            return;
        }

        setFeedback(
            'Imported ' + imported + ' row(s).' + (unmatched > 0 ? ' ' + unmatched + ' product name(s) need review.' : ' Review quantities and costs before saving.')
        );
    };

    root.addEventListener('change', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLElement)) {
            return;
        }

        if (target.matches('[data-pr-product-select]')) {
            const row = target.closest('tr');
            if (row instanceof HTMLElement) {
                syncRow(row);
            }
        }
    });

    root.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLElement)) {
            return;
        }

        const removeButton = target.closest('[data-pr-remove-row]');
        if (removeButton instanceof HTMLElement) {
            removeRow(removeButton);
            return;
        }

        if (target.closest('[data-pr-add-row]')) {
            addRow();
            return;
        }

        if (target.closest('[data-pr-csv-trigger]') && csvFileInput instanceof HTMLInputElement) {
            csvFileInput.click();
        }
    });

    if (config.allowCsvImport && csvFileInput instanceof HTMLInputElement && csvTrigger instanceof HTMLElement) {
        csvFileInput.addEventListener('change', (event) => {
            const input = event.target;
            if (!(input instanceof HTMLInputElement) || !input.files || input.files.length === 0) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (loadEvent) => {
                importCsv(String(loadEvent.target?.result || ''));
                input.value = '';
            };
            reader.readAsText(input.files[0]);
        });
    }

    itemsBody.querySelectorAll('tr').forEach((row) => {
        if (row instanceof HTMLElement) {
            syncRow(row);
        }
    });

    if (requestDateInput instanceof HTMLInputElement) {
        requestDateInput.addEventListener('change', syncDateBounds);
    }

    syncDateBounds();
    refreshRowMetadata();
})();
