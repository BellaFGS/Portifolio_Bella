// Variáveis globais
let projectsData = [];
let blogPostsData = [];

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {

    initializeReadMoreButtons();
    initializeMusicControls();
    initializeButterflyEffect();
    initializeSkillsAnimation();
    initializeSmoothScrolling();
});

// Efeito de digitação para mensagem de boas-vindas


// Funcionalidade "Ver mais" para textos longos
function initializeReadMoreButtons() {
    document.querySelectorAll('.read-more-btn').forEach(button => {
        button.addEventListener('click', function() {
            const projectCard = this.closest('.project-card');
            const longText = projectCard.querySelector('.long-text');
            
            if (longText.style.display === 'none' || longText.style.display === '') {
                longText.style.display = 'block';
                longText.style.animation = 'fadeIn 0.5s ease-in-out';
                this.innerHTML = '<span>Ver menos</span>';
            } else {
                longText.style.display = 'none';
                this.innerHTML = '<span>Ver mais</span>';
            }
        });
    });
    
    // Adicionar CSS para animação fadeIn
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
}


// Efeito de borboletas ao clicar na tela
function initializeButterflyEffect() {
    document.addEventListener('click', function(e) {
        // Não criar borboletas em elementos interativos
        if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' || e.target.tagName === 'INPUT') {
            return;
        }
        
        createButterfly(e.pageX, e.pageY);
    });
}

function createButterfly(x, y) {
    const butterfly = document.createElement('div');
    butterfly.className = 'butterfly';
    butterfly.style.left = x + 'px';
    butterfly.style.top = y + 'px';
    
    // Adicionar variação na animação
    const randomDirection = Math.random() * 360;
    const randomDuration = 2 + Math.random() * 2; // 2-4 segundos
    
    butterfly.style.transform = `rotate(${randomDirection}deg)`;
    butterfly.style.animationDuration = `${randomDuration}s`;
    
    document.body.appendChild(butterfly);
    
    // Remover após a animação
    setTimeout(() => {
        if (butterfly.parentNode) {
            butterfly.remove();
        }
    }, randomDuration * 1000);
}

// Animação das barras de skills
function initializeSkillsAnimation() {
    const skillBars = document.querySelectorAll('.skill-level');
    
    // Observador para animar quando a seção estiver visível
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const skillBar = entry.target;
                const width = skillBar.style.width;
                skillBar.style.width = '0%';
                setTimeout(() => {
                    skillBar.style.width = width;
                }, 100);
            }
        });
    }, { threshold: 0.5 });
    
    skillBars.forEach(bar => observer.observe(bar));
}

// Renderizar projetos
function renderProjects(projects) {
    const projectsGrid = document.querySelector('.projects-grid');
    if (!projectsGrid) return;
    
    projectsGrid.innerHTML = '';
    
    projects.forEach(project => {
        const projectElement = document.createElement('div');
        projectElement.className = 'project-item';
        projectElement.setAttribute('data-category', project.category);
        
        projectElement.innerHTML = `
            <h3>${project.title}</h3>
            <p>${project.description}</p>
            <div class="project-links">
                ${project.github_link ? `<a href="${project.github_link}" target="_blank" class="project-link">GitHub</a>` : ''}
                ${project.live_link ? `<a href="${project.live_link}" target="_blank" class="project-link">Ver Projeto</a>` : ''}
            </div>
        `;
        
        projectsGrid.appendChild(projectElement);
    });
    
    // Adicionar CSS para links dos projetos
    const style = document.createElement('style');
    style.textContent = `
        .project-links {
            margin-top: 1rem;
        }
        .project-link {
            display: inline-block;
            padding: 8px 15px;
            margin: 5px;
            background: var(--amarelo);
            color: var(--azul-escuro);
            text-decoration: none;
            border-radius: 15px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .project-link:hover {
            background: var(--vermelho);
            color: var(--branco);
            transform: scale(1.05);
        }
    `;
    document.head.appendChild(style);
}

// Renderizar posts do blog
function renderBlogPosts(posts) {
    const blogPosts = document.querySelector('.blog-posts');
    if (!blogPosts) return;
    
    blogPosts.innerHTML = '';
    
    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'blog-post';
        postElement.setAttribute('data-category', post.category);
        postElement.setAttribute('data-date', post.created_at);
        
        const postDate = new Date(post.created_at).toLocaleDateString('pt-BR');
        
        postElement.innerHTML = `
            <img src="${post.image_url || 'https://via.placeholder.com/400x200'}" alt="${post.title}">
            <h3>${post.title}</h3>
            <p>${post.content.substring(0, 150)}...</p>
            <div class="post-meta">
                <span class="post-category">${post.category}</span>
                <span class="post-date">${postDate}</span>
            </div>
        `;
        
        blogPosts.appendChild(postElement);
    });
    
    // Adicionar CSS para meta informações do post
    const style = document.createElement('style');
    style.textContent = `
        .post-meta {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }
        .post-category {
            background: var(--azul-claro);
            color: var(--branco);
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
        }
        .post-date {
            color: var(--amarelo);
        }
    `;
    document.head.appendChild(style);
}




// Scroll suave para navegação
function initializeSmoothScrolling() {
    document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Sistema de notificações
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Adicionar CSS para notificações
    const style = document.createElement('style');
    style.textContent = `
        .notification {
            position: fixed;
            top: 100px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            color: var(--branco);
            font-weight: bold;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
        }
        .notification-info {
            background: var(--azul-claro);
        }
        .notification-error {
            background: var(--vermelho);
        }
        .notification-success {
            background: var(--amarelo);
            color: var(--azul-escuro);
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);
    
    document.body.appendChild(notification);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease-out reverse';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

console.log('🎮 Portfólio interativo carregado com sucesso! ✨');


