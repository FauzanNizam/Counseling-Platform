document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.getElementById('menuButton');
    const backdrop = document.getElementById('navBackdrop');
    const sidebarLinks = document.querySelectorAll('#sidebar a');
    if (!menuButton || !backdrop) return;

    function closeMenu() {
        document.body.classList.remove('menu-open');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Buka menu');
    }

    menuButton.addEventListener('click', () => {
        const isOpen = document.body.classList.toggle('menu-open');
        menuButton.setAttribute('aria-expanded', String(isOpen));
        menuButton.setAttribute('aria-label', isOpen ? 'Tutup menu' : 'Buka menu');
    });
    backdrop.addEventListener('click', closeMenu);
    sidebarLinks.forEach(link => link.addEventListener('click', closeMenu));
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) closeMenu();
    });
});
