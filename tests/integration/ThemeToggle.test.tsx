import { describe, it, expect, beforeEach } from 'vitest';

describe('Testes de Integração - Interações do Portfólio', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <button class="menu-toggle" type="button" aria-expanded="false" aria-label="Abrir menu"><span></span></button>
      <nav class="site-nav">
        <a href="#sobre">Sobre</a>
      </nav>

      <button class="filter-button is-active" data-filter="all">Todos</button>
      <button class="filter-button" data-filter="web">Web</button>

      <div class="project-card" data-category="web"></div>
      <div class="project-card" data-category="game"></div>

      <button class="journey-tab is-active">Trabalho</button>
      <button class="journey-tab">Por fora</button>

      <span id="year"></span>
    `;

    const menuToggle = document.querySelector<HTMLButtonElement>('.menu-toggle');
    const siteNav = document.querySelector<HTMLElement>('.site-nav');

    menuToggle?.addEventListener('click', () => {
      const open = siteNav?.classList.toggle('is-open');
      menuToggle.setAttribute('aria-expanded', String(open));
      menuToggle.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
    });

    const filterButtons = document.querySelectorAll<HTMLButtonElement>('.filter-button');
    const projectCards = document.querySelectorAll<HTMLElement>('.project-card');

    filterButtons.forEach((button) => {
      button.addEventListener('click', () => {
        filterButtons.forEach((item) => item.classList.remove('is-active'));
        button.classList.add('is-active');
        const filter = button.dataset.filter;
        projectCards.forEach((card) => {
          const visible = filter === 'all' || card.dataset.category === filter;
          card.classList.toggle('is-hidden', !visible);
        });
      });
    });

    const yearElement = document.querySelector<HTMLElement>('#year');
    if (yearElement) {
      yearElement.textContent = new Date().getFullYear().toString();
    }
  });

  it('deve abrir e fechar o menu mobile ao clicar no botão de menu', () => {
    const menuToggle = document.querySelector<HTMLButtonElement>('.menu-toggle');
    const siteNav = document.querySelector<HTMLElement>('.site-nav');

    menuToggle?.click();
    expect(siteNav?.classList.contains('is-open')).toBe(true);
    expect(menuToggle?.getAttribute('aria-expanded')).toBe('true');

    menuToggle?.click();
    expect(siteNav?.classList.contains('is-open')).toBe(false);
    expect(menuToggle?.getAttribute('aria-expanded')).toBe('false');
  });

  it('deve ocultar e exibir cards ao clicar nos botões de filtro', () => {
    const filterButtons = document.querySelectorAll<HTMLButtonElement>('.filter-button');
    const webBtn = filterButtons[1];
    const cards = document.querySelectorAll<HTMLElement>('.project-card');

    webBtn?.click();

    expect(cards[0]?.classList.contains('is-hidden')).toBe(false);
    expect(cards[1]?.classList.contains('is-hidden')).toBe(true);
  });

  it('deve preencher o elemento #year com o ano atual', () => {
    const yearElement = document.querySelector<HTMLElement>('#year');
    expect(yearElement?.textContent).toBe(new Date().getFullYear().toString());
  });
});