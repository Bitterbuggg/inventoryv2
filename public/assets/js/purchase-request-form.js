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
        }
    };

    const removeRow = (button) => {
        const row = button.closest('tr');
        if (row) {
            row.remove();
        }
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

            imported += 1;
        });

        window.alert(`Imported ${imported} row(s). Review any unmatched products before saving.`);
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
})();
