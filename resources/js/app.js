import './bootstrap';

import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

Alpine.plugin(Collapse);

window.Alpine = Alpine;

// ── Sidebar Store ──────────────────────────────────────────────
Alpine.store('sidebar', {
    /** Whether the sidebar is in full-width (expanded) mode vs icon-only */
    expanded: true,
    /** Mobile-specific open flag (drawer) */
    mobileOpen: false,

    init() {
        // Default: desktop = expanded, tablet = icon-only
        if (window.innerWidth < 1024) {
            this.expanded = false;
        }
        // Restore persisted preference on tablet/desktop
        const stored = localStorage.getItem('sidebarExpanded');
        if (stored !== null && window.innerWidth >= 768) {
            this.expanded = stored === 'true';
        }
        this._applyClasses();
    },

    toggle() {
        if (window.innerWidth < 768) {
            // Mobile: toggle drawer
            this.mobileOpen = !this.mobileOpen;
        } else {
            // Tablet/Desktop: toggle width
            this.expanded = !this.expanded;
            localStorage.setItem('sidebarExpanded', String(this.expanded));
        }
        this._applyClasses();
    },

    closeMobile() {
        this.mobileOpen = false;
        this._applyClasses();
    },

    /** Applies CSS classes to #app-sidebar, #sidebar-overlay, #page-wrapper, #app-header */
    _applyClasses() {
        const sidebar  = document.getElementById('app-sidebar');
        const overlay  = document.getElementById('sidebar-overlay');
        const wrapper  = document.getElementById('page-wrapper');
        const header   = document.getElementById('app-header');
        if (!sidebar) return;

        const isMobile = window.innerWidth < 768;
        const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;

        if (isMobile) {
            sidebar.classList.toggle('mobile-open', this.mobileOpen);
            if (overlay) overlay.classList.toggle('active', this.mobileOpen);
        } else {
            sidebar.classList.remove('mobile-open');
            if (overlay) overlay.classList.remove('active');

            if (isTablet) {
                sidebar.classList.toggle('sidebar-expanded', this.expanded);
                sidebar.classList.remove('sidebar-collapsed');
                if (wrapper) wrapper.classList.toggle('sidebar-expanded', this.expanded);
                if (wrapper) wrapper.classList.remove('sidebar-collapsed');
                if (header)  header.classList.toggle('sidebar-expanded', this.expanded);
                if (header)  header.classList.remove('sidebar-collapsed');
            } else {
                // Desktop
                sidebar.classList.toggle('sidebar-collapsed', !this.expanded);
                sidebar.classList.remove('sidebar-expanded');
                if (wrapper) wrapper.classList.toggle('sidebar-collapsed', !this.expanded);
                if (wrapper) wrapper.classList.remove('sidebar-expanded');
                if (header)  header.classList.toggle('sidebar-collapsed', !this.expanded);
                if (header)  header.classList.remove('sidebar-expanded');
            }

            // Toggle icon-only mode on the sidebar
            sidebar.classList.toggle('sidebar-icon-only', !this.expanded);
        }
    },

    isIconOnly() {
        return !this.expanded && window.innerWidth >= 768;
    }
});

// ── Accordion helper for sidebar groups ───────────────────────
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebarGroup', (groupKey, defaultOpen = false) => ({
        open: defaultOpen,
        toggle() { this.open = !this.open; },
        isIconOnly() { return Alpine.store('sidebar').isIconOnly(); },
        droprightOpen: false,
        droprightTop: 0,
        droprightLeft: 0,
        showDropright(e) {
            if (this.isIconOnly()) {
                const rect = e.currentTarget.getBoundingClientRect();
                this.droprightTop = rect.top;
                this.droprightLeft = rect.right + 6;
                this.droprightOpen = true;
            }
        },
        hideDropright() {
            this.droprightOpen = false;
        }
    }));
});

Alpine.start();

// ── Re-apply sidebar classes on page load & resize ────────────
document.addEventListener('DOMContentLoaded', () => {
    Alpine.store('sidebar')._applyClasses();

    window.addEventListener('resize', () => {
        Alpine.store('sidebar')._applyClasses();
    });
});
