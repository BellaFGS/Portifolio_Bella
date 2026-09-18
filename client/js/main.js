/*
 * Interações do portfólio ByteBloom.
 * Sem dependências: menu móvel, tema persistente, troca de logos, revelação ao rolar e feedback.
 */

(() => {
  const body = document.body;
  const menuButton = document.querySelector('.menu-toggle');
  const mobileMenu = document.querySelector('.mobile-nav');
  const themeButton = document.querySelector('.theme-toggle');
  const toast = document.querySelector('.toast');
  const currentYear = document.querySelector('#current-year');
  let toastTimeout;

  // Elementos das logos/favicon
  const favicon = document.getElementById('favicon');
  const headerLogo = document.getElementById('header-logo');
  const footerLogo = document.getElementById('footer-logo');
  const contactLogo = document.getElementById('contact-logo');

  // Caminhos das imagens
  const darkLogo = './images/logo_allebstrix_sf.png';
  const lightLogo = './images/logo_allebstrix_black_sf.png';

  // Função para atualizar as logos de acordo com o tema
  const updateLogos = (isLight) => {
    const selectedLogo = isLight ? lightLogo : darkLogo;

    if (favicon) favicon.href = selectedLogo;
    if (headerLogo) headerLogo.src = selectedLogo;
    if (footerLogo) footerLogo.src = selectedLogo;
    if (contactLogo) contactLogo.src = selectedLogo;
  };

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

  // Aplica o tema (classe no body + acessibilidade + atualização de logos)
  const applyTheme = (isLight) => {
    body.classList.toggle('light-mode', isLight);
    if (themeButton) {
      themeButton.setAttribute('aria-pressed', String(isLight));
      themeButton.setAttribute('aria-label', isLight ? 'Ativar tema escuro' : 'Ativar tema claro');
    }
    updateLogos(isLight);
  };

  // Carrega preferência salva ou do sistema
  const savedTheme = window.localStorage.getItem('bytebloom-theme');
  const systemPrefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
  const initialIsLight = savedTheme ? savedTheme === 'light' : systemPrefersLight;

  applyTheme(initialIsLight);

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