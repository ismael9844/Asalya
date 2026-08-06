<script>
// Language Toggle (shared header)
window.currentLang = 'en';
(function () {
    const langToggle = document.getElementById('lang-toggle');
    const currentLangSpan = document.getElementById('current-lang');
    if (!langToggle) return;

    window.translatePage = function () {
        document.querySelectorAll('[data-en][data-tr]').forEach(el => {
            el.textContent = el.getAttribute(`data-${window.currentLang}`);
        });
        document.querySelectorAll('[data-en-placeholder][data-tr-placeholder]').forEach(el => {
            el.placeholder = el.getAttribute(`data-${window.currentLang}-placeholder`);
        });
        document.querySelectorAll('option[data-en][data-tr]').forEach(el => {
            el.textContent = el.getAttribute(`data-${window.currentLang}`);
        });
    };

    langToggle.addEventListener('click', () => {
        window.currentLang = window.currentLang === 'en' ? 'tr' : 'en';
        currentLangSpan.textContent = window.currentLang === 'en' ? '🇬🇧 EN' : '🇹🇷 TR';
        window.translatePage();
    });
})();

// Mobile Menu (shared header)
(function () {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (!menuToggle || !mobileMenu) return;
    menuToggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
})();

// Header shadow on scroll (shared header)
(function () {
    const header = document.getElementById('header');
    if (!header) return;
    window.addEventListener('scroll', () => {
        header.classList.toggle('shadow-lg', window.scrollY > 10);
    });
})();

// Scroll to top button (shared footer)
(function () {
    const scrollTopBtn = document.getElementById('scroll-top');
    if (!scrollTopBtn) return;
    window.addEventListener('scroll', () => {
        scrollTopBtn.classList.toggle('opacity-0', window.scrollY <= 500);
        scrollTopBtn.classList.toggle('pointer-events-none', window.scrollY <= 500);
    });
    scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();
</script>
