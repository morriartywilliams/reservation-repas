import './bootstrap';

// Vanilla JS for Madrassati UI components
document.addEventListener('DOMContentLoaded', () => {
    // Mobile sidebar toggle
    const sidebarToggleBtn = document.getElementById('sidebar-toggle');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileSidebarBackdrop = document.getElementById('mobile-sidebar-backdrop');
    const mobileSidebarClose = document.getElementById('mobile-sidebar-close');

    if (sidebarToggleBtn && mobileSidebar) {
        const toggleSidebar = (show) => {
            if (show) {
                mobileSidebar.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                mobileSidebar.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        };

        sidebarToggleBtn.addEventListener('click', () => toggleSidebar(true));
        if (mobileSidebarClose) mobileSidebarClose.addEventListener('click', () => toggleSidebar(false));
        if (mobileSidebarBackdrop) mobileSidebarBackdrop.addEventListener('click', () => toggleSidebar(false));
    }

    // Modal helpers
    window.openConfirmModal = (options) => {
        const modal = document.getElementById('confirm-action-modal');
        if (!modal) return;

        const titleElem = document.getElementById('modal-title');
        const messageElem = document.getElementById('modal-message');
        const confirmBtn = document.getElementById('modal-confirm-button');
        const formElem = document.getElementById('modal-confirm-form');

        if (titleElem) titleElem.textContent = options.title || 'Confirmer l\'action';
        if (messageElem) messageElem.textContent = options.message || 'Êtes-vous sûr de vouloir continuer ?';
        if (confirmBtn) {
            confirmBtn.textContent = options.confirmText || 'Confirmer';
            confirmBtn.className = options.buttonClass || 'inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:text-sm';
        }

        if (formElem && options.action) {
            formElem.action = options.action;
            const methodInput = formElem.querySelector('input[name="_method"]');
            if (methodInput && options.method) {
                methodInput.value = options.method;
            }
        }

        modal.classList.remove('hidden');
    };

    window.closeConfirmModal = () => {
        const modal = document.getElementById('confirm-action-modal');
        if (modal) modal.classList.add('hidden');
    };
});
