import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Heading,
    Link,
    List,
    BlockQuote,
    Indent,
    IndentBlock,
    Alignment,
    HorizontalLine,
    Table,
    TableToolbar,
    FontSize,
    FontColor,
    RemoveFormat,
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

/* ── Fullscreen icons ─────────────────────────────────── */
const ICON_EXPAND = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
  <path d="M3 3h5v2H5v3H3V3zm9 0h5v5h-2V5h-3V3zM3 12h2v3h3v2H3v-5zm12 3h-3v2h5v-5h-2v3z"/>
</svg>`;
const ICON_COMPRESS = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
  <path d="M8 3v2H6v2H4V3h4zm8 0v4h-2V5h-2V3h4zM4 13h2v2h2v2H4v-4zm10 2v-2h2v4h-4v-2h2z"/>
</svg>`;

/* ── Fullscreen button (injected into toolbar after init) */
function attachFullscreenButton(editor) {
    var editorEl = editor.ui.view.element;
    if (!editorEl) return;

    /* Append inside .ck-toolbar__items so it joins the flex row naturally */
    var items = editorEl.querySelector('.ck-toolbar__items');
    if (!items) return;

    /* Spacer grows to fill available space → pushes button to the right */
    var spacer = document.createElement('span');
    spacer.className = 'ck-fullscreen-spacer';

    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'ck ck-button ck-fullscreen-toggle';
    btn.title = 'Fullscreen';
    btn.setAttribute('aria-label', 'Toggle fullscreen');
    btn.innerHTML = ICON_EXPAND;

    var active = false;

    function toggle() {
        active = !active;
        editorEl.classList.toggle('ck-fullscreen', active);
        document.body.classList.toggle('ck-fullscreen-open', active);
        btn.innerHTML = active ? ICON_COMPRESS : ICON_EXPAND;
        btn.title = active ? 'Exit fullscreen' : 'Fullscreen';
        btn.classList.toggle('ck-on', active);
    }

    btn.addEventListener('click', toggle);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && active) toggle();
    });

    items.appendChild(spacer);
    items.appendChild(btn);
}

/* ── Editor config ────────────────────────────────────── */
const CK_CONFIG = {
    licenseKey: 'GPL',
    plugins: [
        Essentials, Paragraph,
        Bold, Italic, Underline, Strikethrough, RemoveFormat,
        Heading,
        Link,
        List,
        BlockQuote,
        Indent, IndentBlock,
        Alignment,
        HorizontalLine,
        Table, TableToolbar,
        FontSize, FontColor,
    ],
    toolbar: {
        items: [
            'heading', '|',
            'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
            'fontSize', 'fontColor', '|',
            'link', '|',
            'bulletedList', 'numberedList', '|',
            'blockQuote', 'horizontalLine', '|',
            'indent', 'outdent', '|',
            'alignment', '|',
            'insertTable', '|',
            'undo', 'redo',
        ],
        shouldNotGroupWhenFull: false,
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
    },
    link: {
        addTargetToExternalLinks: true,
    },
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
        ],
    },
};

/* ── Init ─────────────────────────────────────────────── */
function initCKEditors() {
    document.querySelectorAll('textarea.ckeditor').forEach(function (el) {
        if (el.dataset.ckInit) return;
        el.dataset.ckInit = '1';

        ClassicEditor.create(el, CK_CONFIG)
            .then(function (editor) {
                /* Sync textarea on form submit */
                var form = el.closest('form');
                if (form) {
                    form.addEventListener('submit', function () {
                        el.value = editor.getData();
                    }, { capture: true });
                }

                /* Inject fullscreen button into toolbar */
                attachFullscreenButton(editor);
            })
            .catch(function (err) {
                console.error('[CKEditor] init failed for #' + (el.id || el.name) + ':', err);
                el.dataset.ckInit = '';
            });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCKEditors);
} else {
    initCKEditors();
}
