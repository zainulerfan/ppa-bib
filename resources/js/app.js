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

document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const passwordInput = document.getElementById(toggle.getAttribute('aria-controls'));

        if (!(passwordInput instanceof HTMLInputElement)) return;

        const isVisible = passwordInput.type === 'password';
        passwordInput.type = isVisible ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', String(isVisible));
        toggle.setAttribute('aria-label', isVisible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    });
});
