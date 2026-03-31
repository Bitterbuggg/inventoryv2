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

    document.addEventListener('DOMContentLoaded', function () {
        setupFormSubmitLock();
        setupRapidClickLock();
        setupAlerts();
        setupPasswordToggle();
        setupValidationSummary();
        setupDirtyForms();
    });

    window.InventoryV2Hci = {
        announce: announce
    };
})(window, document);
