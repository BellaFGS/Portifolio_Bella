// ==============================
// MENU MOBILE
// ==============================

const menuToggle = document.querySelector('.menu-toggle');
const siteNav = document.querySelector('.site-nav');

menuToggle?.addEventListener('click', () => {
    const open = siteNav?.classList.toggle('is-open') ?? false;

    menuToggle.setAttribute('aria-expanded', String(open));
    menuToggle.setAttribute(
        'aria-label',
        open ? 'Fechar menu' : 'Abrir menu'
    );
});

document.querySelectorAll('.site-nav a').forEach((link) => {
    link.addEventListener('click', () => {
        siteNav?.classList.remove('is-open');

        menuToggle?.setAttribute('aria-expanded', 'false');
        menuToggle?.setAttribute('aria-label', 'Abrir menu');
    });
});


// ==============================
// FILTRO DE PROJETOS
// ==============================

const filterButtons = document.querySelectorAll('.filter-button');
const projectCards = document.querySelectorAll('.project-card');

filterButtons.forEach((button) => {
    button.addEventListener('click', () => {
        filterButtons.forEach((item) => {
            item.classList.remove('is-active');
        });

        button.classList.add('is-active');

        const filter = button.dataset.filter;

        projectCards.forEach((card) => {
            const visible =
                filter === 'all' ||
                card.dataset.category === filter;

            card.classList.toggle('is-hidden', !visible);
        });
    });
});


// ==============================
// ABAS DA JORNADA
// ==============================

document.querySelectorAll('.journey-tab').forEach((tab) => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.journey-tab').forEach((item) => {
            item.classList.remove('is-active');
        });

        tab.classList.add('is-active');
    });
});


// ==============================
// ANIMAÇÃO DE REVEAL
// ==============================

const observer = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    },
    {
        threshold: 0.12
    }
);

document.querySelectorAll('.reveal').forEach((element) => {
    observer.observe(element);
});


// ==============================
// ANO DO RODAPÉ
// ==============================

const yearElement = document.querySelector('#year');

if (yearElement) {
    yearElement.textContent = new Date().getFullYear();
}


// ==============================
// CARROSSEL DE FERRAMENTAS
// ==============================

const toolsCarousel = document.querySelector('.tools-grid');
const toolsPrevButton = document.querySelector('.tools-arrow-prev');
const toolsNextButton = document.querySelector('.tools-arrow-next');

function updateToolsArrows() {
    if (!toolsCarousel || !toolsPrevButton || !toolsNextButton) {
        return;
    }

    const maxScroll =
        toolsCarousel.scrollWidth - toolsCarousel.clientWidth;

    toolsPrevButton.disabled =
        toolsCarousel.scrollLeft <= 5;

    toolsNextButton.disabled =
        toolsCarousel.scrollLeft >= maxScroll - 5;
}

function scrollTools(direction) {
    if (!toolsCarousel) {
        return;
    }

    const card = toolsCarousel.querySelector('.tool-card');

    if (!card) {
        return;
    }

    const gap = 9;
    const amount = (card.offsetWidth + gap) * 3;

    toolsCarousel.scrollBy({
        left: direction * amount,
        behavior: 'smooth'
    });
}

toolsPrevButton?.addEventListener('click', () => {
    scrollTools(-1);
});

toolsNextButton?.addEventListener('click', () => {
    scrollTools(1);
});

toolsCarousel?.addEventListener('scroll', updateToolsArrows);
window.addEventListener('resize', updateToolsArrows);

updateToolsArrows();


// ==============================
// FILTRO E PAGINAÇÃO DOS CERTIFICADOS
// ==============================

const certificatesList =
    document.getElementById('certificates-list');

if (certificatesList) {

    const allCertificates = Array.from(
        certificatesList.querySelectorAll('.learning-row')
    );

    const yearFilters =
        document.querySelectorAll('.year-filter');

    const pageNumbers =
        document.getElementById('cert-page-numbers');

    const certificatesPrevButton =
        document.getElementById('cert-prev');

    const certificatesNextButton =
        document.getElementById('cert-next');

    const certificatesPerPage = 6;

    let currentPage = 1;
    let selectedYear = 'all';

    let filteredCertificates = [...allCertificates];


    // ------------------------------
    // FILTRAR POR ANO
    // ------------------------------

    function filterCertificates() {

        if (selectedYear === 'all') {
            filteredCertificates = [...allCertificates];
        } else {
            filteredCertificates = allCertificates.filter(
                (certificate) => {
                    const year =
                        certificate.querySelector('time')?.textContent.trim();

                    return year === selectedYear;
                }
            );
        }

        currentPage = 1;

        renderCertificates();
        scrollToCertificates();
    }


    // ------------------------------
    // RENDERIZAR CERTIFICADOS
    // ------------------------------

    function renderCertificates() {

        const start =
            (currentPage - 1) * certificatesPerPage;

        const end =
            start + certificatesPerPage;

        // Esconde todos primeiro
        allCertificates.forEach((certificate) => {
            certificate.style.display = 'none';
        });

        // Mostra somente os certificados da página atual
        filteredCertificates.forEach(
            (certificate, index) => {

                if (index >= start && index < end) {
                    certificate.style.display = '';
                }
            }
        );

        renderPagination();
        updateCertificateButtons();
    }


    // ------------------------------
    // PAGINAÇÃO
    // ------------------------------

    function renderPagination() {

        if (!pageNumbers) {
            return;
        }

        pageNumbers.innerHTML = '';

        const totalPages = Math.ceil(
            filteredCertificates.length /
            certificatesPerPage
        );

        // Se não houver mais de uma página,
        // não precisamos mostrar números.
        if (totalPages <= 1) {
            return;
        }

        for (
            let page = 1;
            page <= totalPages;
            page++
        ) {

            const button =
                document.createElement('button');

            button.type = 'button';
            button.textContent = page;

            button.setAttribute(
                'aria-label',
                `Ir para a página ${page}`
            );

            if (page === currentPage) {
                button.classList.add('active');

                button.setAttribute(
                    'aria-current',
                    'page'
                );
            }

            button.addEventListener('click', () => {

                currentPage = page;

                renderCertificates();
                scrollToCertificates();
            });

            pageNumbers.appendChild(button);
        }
    }


    // ------------------------------
    // BOTÕES ANTERIOR / PRÓXIMO
    // ------------------------------

    function updateCertificateButtons() {

        const totalPages = Math.ceil(
            filteredCertificates.length /
            certificatesPerPage
        );

        if (certificatesPrevButton) {
            certificatesPrevButton.disabled =
                currentPage === 1 ||
                totalPages <= 1;
        }

        if (certificatesNextButton) {
            certificatesNextButton.disabled =
                currentPage === totalPages ||
                totalPages <= 1;
        }
    }


    // ------------------------------
    // SCROLL
    // ------------------------------

    function scrollToCertificates() {

        certificatesList.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }


    // ------------------------------
    // EVENTO DOS FILTROS
    // ------------------------------

    yearFilters.forEach((button) => {

        button.addEventListener('click', () => {

            yearFilters.forEach((item) => {
                item.classList.remove('is-active');
            });

            button.classList.add('is-active');

            selectedYear =
                button.dataset.year;

            filterCertificates();
        });
    });


    // ------------------------------
    // BOTÃO ANTERIOR
    // ------------------------------

    certificatesPrevButton?.addEventListener(
        'click',
        () => {

            if (currentPage > 1) {

                currentPage--;

                renderCertificates();
                scrollToCertificates();
            }
        }
    );


    // ------------------------------
    // BOTÃO PRÓXIMO
    // ------------------------------

    certificatesNextButton?.addEventListener(
        'click',
        () => {

            const totalPages = Math.ceil(
                filteredCertificates.length /
                certificatesPerPage
            );

            if (currentPage < totalPages) {

                currentPage++;

                renderCertificates();
                scrollToCertificates();
            }
        }
    );


    // Renderização inicial
    renderCertificates();
}

const contactEmail = document.querySelector('#contact-email');

if (contactEmail) {
  const email = 'allebstrix.dev@gmail.com';
  const subject = 'Contato pelo portfólio';

  const body = `Olá, Bella!

Encontrei seu portfólio e gostaria de conversar sobre um possível projeto.

Tenho interesse em:
[descreva brevemente sua ideia, necessidade ou projeto]

Fico no aguardo para conversarmos melhor.

Atenciosamente,
[Seu nome]`;

  const gmailUrl =
    'https://mail.google.com/mail/?view=cm&fs=1' +
    `&to=${encodeURIComponent(email)}` +
    `&su=${encodeURIComponent(subject)}` +
    `&body=${encodeURIComponent(body)}`;

  contactEmail.href = gmailUrl;
}

const journeyTabs = document.querySelectorAll('.journey-tab');
const journeyCards = document.querySelectorAll('.journey-card');

if (journeyTabs.length && journeyCards.length) {
  journeyTabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      const category = tab.dataset.category;

      // Atualiza o botão ativo
      journeyTabs.forEach((item) => {
        item.classList.remove('is-active');
      });

      tab.classList.add('is-active');

      // Filtra os cards
      journeyCards.forEach((card) => {
        const cardCategory = card.dataset.category;

        if (category === 'todos' || cardCategory === category) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
}