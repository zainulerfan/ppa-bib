import './bootstrap';

const sidebar = document.querySelector('#main-sidebar');
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebarClose = document.querySelector('[data-sidebar-close]');

function setSidebarOpen(isOpen) {
    if (!sidebar || !sidebarToggle || !sidebarClose) return;

    sidebar.classList.toggle('is-open', isOpen);
    sidebarClose.classList.toggle('is-visible', isOpen);
    sidebarToggle.setAttribute('aria-expanded', String(isOpen));
    document.body.classList.toggle('sidebar-open', isOpen);
}

sidebarToggle?.addEventListener('click', () => {
    setSidebarOpen(!sidebar?.classList.contains('is-open'));
});

sidebarClose?.addEventListener('click', () => setSidebarOpen(false));

sidebar?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => setSidebarOpen(false));
});

window.addEventListener('resize', () => {
    if (window.innerWidth > 720) setSidebarOpen(false);
});
