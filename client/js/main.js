/*
 * Interações do portfólio ByteBloom.
 * Sem dependências: menu móvel, tema persistente, revelação ao rolar e feedback de links editáveis.
 */

(() => {
  const body = document.body;
  const menuButton = document.querySelector('.menu-toggle');
  const mobileMenu = document.querySelector('.mobile-nav');
  const themeButton = document.querySelector('.theme-toggle');
  const toast = document.querySelector('.toast');
  const currentYear = document.querySelector('#current-year');
  let toastTimeout;

  const showToast = (message) => {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('is-visible');
    window.clearTimeout(toastTimeout);
    toastTimeout = window.setTimeout(() => toast.classList.remove('is-visible'), 3200);
  };

  const closeMenu = () => {
    if (!menuButton || !mobileMenu) return;
    menuButton.classList.remove('is-open');
    mobileMenu.classList.remove('is-open');
    menuButton.setAttribute('aria-expanded', 'false');
    menuButton.setAttribute('aria-label', 'Abrir menu');
  };

  if (menuButton && mobileMenu) {
    menuButton.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.toggle('is-open');
      menuButton.classList.toggle('is-open', isOpen);
      menuButton.setAttribute('aria-expanded', String(isOpen));
      menuButton.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
    });

    mobileMenu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
  }

  const applyTheme = (isLight) => {
    body.classList.toggle('light-mode', isLight);
    if (themeButton) {
      themeButton.setAttribute('aria-pressed', String(isLight));
      themeButton.setAttribute('aria-label', isLight ? 'Ativar tema escuro' : 'Ativar tema claro');
    }
  };

  const savedTheme = window.localStorage.getItem('bytebloom-theme');
  const systemPrefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
  applyTheme(savedTheme ? savedTheme === 'light' : systemPrefersLight);

  themeButton?.addEventListener('click', () => {
    const isLight = !body.classList.contains('light-mode');
    applyTheme(isLight);
    window.localStorage.setItem('bytebloom-theme', isLight ? 'light' : 'dark');
  });

  document.querySelectorAll('[data-placeholder]').forEach((link) => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      showToast('Troque este link pelo endereço do seu projeto.');
    });
  });

  const revealElements = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries, activeObserver) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-visible');
          activeObserver.unobserve(entry.target);
        });
      },
      { threshold: 0.12 }
    );
    revealElements.forEach((element) => observer.observe(element));
  } else {
    revealElements.forEach((element) => element.classList.add('is-visible'));
  }

  if (currentYear) currentYear.textContent = new Date().getFullYear();
})();
