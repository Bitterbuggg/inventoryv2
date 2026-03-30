(function () {
    function getHeaderAlignment(headerCell) {
        const headerText = headerCell.textContent.replace(/\s+/g, ' ').trim().toLowerCase();
        const inlineAlign = (headerCell.style.textAlign || '').trim().toLowerCase();
        const isActions = headerCell.classList.contains('actions') || /^(action|actions)$/.test(headerText);
        const isCenter = headerCell.classList.contains('center') || inlineAlign === 'center' || headerText === 'rank';
        const isNumeric = !isActions && (headerCell.classList.contains('numeric') || inlineAlign === 'right');

        return {
            isActions,
            isCenter,
            isNumeric,
        };
    }

    function applyAlignmentToTable(table) {
        if (!table.tHead || table.tHead.rows.length === 0) {
            return;
        }

        const headerCells = Array.from(table.tHead.rows[0].cells);
        const alignments = headerCells.map(getHeaderAlignment);

        Array.from(table.tBodies).forEach((tbody) => {
            Array.from(tbody.rows).forEach((row) => {
                Array.from(row.cells).forEach((cell) => {
                    cell.classList.remove('is-actions', 'is-center', 'is-numeric', 'table-empty');
                });

                if (Array.from(row.cells).some((cell) => cell.colSpan > 1)) {
                    Array.from(row.cells)
                        .filter((cell) => cell.colSpan > 1)
                        .forEach((cell) => cell.classList.add('table-empty'));
                    return;
                }

                alignments.forEach((alignment, index) => {
                    const cell = row.cells[index];
                    if (!cell) {
                        return;
                    }

                    if (alignment.isActions) {
                        cell.classList.add('is-actions');
                    } else if (alignment.isCenter) {
                        cell.classList.add('is-center');
                    } else if (alignment.isNumeric) {
                        cell.classList.add('is-numeric');
                    }
                });
            });
        });
    }

    function applyTableAlignment(root) {
        const scope = root instanceof Element || root instanceof Document ? root : document;
        scope.querySelectorAll('table.modern-table, table.table').forEach(applyAlignmentToTable);
    }

    window.applyTableAlignment = applyTableAlignment;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            applyTableAlignment(document);
        });
    } else {
        applyTableAlignment(document);
    }
})();
