import $ from 'jquery';
import select2 from 'select2';
import 'select2/dist/css/select2.min.css';
import '../css/select2-theme.css';

select2($);
window.$ = window.jQuery = $;

/* Track open count so we only remove the body class when ALL dropdowns close */
let openCount = 0;

function makeConfig(el) {
    const emptyOption = el.querySelector('option[value=""]');
    const placeholder = emptyOption ? (emptyOption.textContent.trim() || 'Select an option') : null;

    const cfg = {
        width: '100%',
        dropdownParent: $(document.body),
        allowClear: !!placeholder,
    };
    if (placeholder) cfg.placeholder = placeholder;

    if (el.classList.contains('border-red-400') || el.classList.contains('is-invalid')) {
        cfg.containerCssClass = 'select2-error';
    }

    return cfg;
}

function initSelect(el) {
    if (el.dataset.s2Init) return;
    if (el.type === 'hidden') return;
    el.dataset.s2Init = '1';

    const $el = $(el);
    $el.select2(makeConfig(el));

    /* Suppress body overflow-x while dropdown is open */
    $el.on('select2:open', function () {
        openCount++;
        document.documentElement.classList.add('select2-open');
    });
    $el.on('select2:close', function () {
        openCount = Math.max(0, openCount - 1);
        if (openCount === 0) {
            document.documentElement.classList.remove('select2-open');
        }
    });

    /* Alpine.js x-model compatibility */
    $el.on('change.select2', function () {
        el.dispatchEvent(new Event('change', { bubbles: true }));
        el.dispatchEvent(new Event('input',  { bubbles: true }));
    });
}

function initAll() {
    document.querySelectorAll('select:not([data-no-select2]):not([data-s2-init])').forEach(initSelect);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

/* Watch for dynamically added selects (Alpine x-for, modals, etc.) */
document.addEventListener('DOMContentLoaded', function () {
    new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.nodeType !== 1) return;
                const els = node.tagName === 'SELECT'
                    ? [node]
                    : Array.from(node.querySelectorAll('select:not([data-s2-init]):not([data-no-select2])'));
                els.forEach(initSelect);
            });
        });
    }).observe(document.body, { childList: true, subtree: true });
});
