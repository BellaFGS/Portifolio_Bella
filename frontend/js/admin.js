// JavaScript para Dashboard Administrativo

let csrfToken = '';
let blogPosts = [];
let projects = [];

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    loadCSRFToken();
    loadStats();
    loadBlogPosts();
    loadProjects();
    initializeNavigation();
    initializeForms();
});

// Carregar token CSRF
async function loadCSRFToken() {
    try {
        const response = await fetch('backend/secure_api.php/auth');
        const data = await response.json();
        csrfToken = data.csrf_token;
    } catch (error) {
        console.error('Erro ao carregar token CSRF:', error);
    }
}

// Carregar estatísticas
async function loadStats() {
    try {
        const response = await fetch('backend/secure_api.php/admin', {
            headers: {
                'X-CSRF-Token': csrfToken
            }
        });
        
        if (response.ok) {
            const stats = await response.json();
            renderStats(stats);
        }
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
}

// Renderizar estatísticas
function renderStats(stats) {
    const statsGrid = document.getElementById('statsGrid');
    statsGrid.innerHTML = `
        <div class="stat-card">
            <div class="stat-number">${stats.total_projects}</div>
            <div class="stat-label">Projetos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.total_blog_posts}</div>
            <div class="stat-label">Posts do Blog</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.recent_posts.length}</div>
            <div class="stat-label">Posts Recentes</div>
        </div>
    `;
    
    // Renderizar atividades recentes
    const recentActivity = document.getElementById('recentActivity');
    recentActivity.innerHTML = `
        <h3>📈 Posts Recentes</h3>
        <ul>
            ${stats.recent_posts.map(post => `
                <li>${post.title} - ${new Date(post.created_at).toLocaleDateString('pt-BR')}</li>
            `).join('')}
        </ul>
    `;
}

// Navegação entre seções
function initializeNavigation() {
    const navButtons = document.querySelectorAll('.nav-btn');
    const sections = document.querySelectorAll('.admin-section');
    
    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetSection = this.getAttribute('data-section');
            
            // Remover classe active de todos os botões e seções
            navButtons.forEach(btn => btn.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            // Adicionar classe active ao botão e seção clicados
            this.classList.add('active');
            document.getElementById(targetSection).classList.add('active');
        });
    });
}

// Inicializar formulários
function initializeForms() {
    // Formulário do blog
    document.getElementById('blogForm').addEventListener('submit', handleBlogSubmit);
    
    // Formulário de projetos
    document.getElementById('projectForm').addEventListener('submit', handleProjectSubmit);
    
    // Formulário de alteração de senha
    document.getElementById('changePasswordForm').addEventListener('submit', handlePasswordChange);
}

// Carregar posts do blog
async function loadBlogPosts() {
    try {
        const response = await fetch('backend/secure_api.php/blog');
        if (response.ok) {
            blogPosts = await response.json();
            renderBlogPosts();
            populateBlogCategories();
        }
    } catch (error) {
        console.error('Erro ao carregar posts do blog:', error);
        showNotification('Erro ao carregar posts do blog', 'error');
    }
}

// Renderizar posts do blog
function renderBlogPosts(posts = blogPosts) {
    const tbody = document.getElementById('blogTableBody');
    tbody.innerHTML = '';
    
    posts.forEach(post => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${post.id}</td>
            <td>${post.title}</td>
            <td>${post.category}</td>
            <td>${new Date(post.created_at).toLocaleDateString('pt-BR')}</td>
            <td>
                <button class="btn btn-secondary" onclick="editBlogPost(${post.id})">✏️ Editar</button>
                <button class="btn btn-danger" onclick="deleteBlogPost(${post.id})">🗑️ Deletar</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Popular categorias do blog
function populateBlogCategories() {
    const select = document.getElementById('blogCategoryFilter');
    const categories = [...new Set(blogPosts.map(post => post.category))];
    
    // Limpar opções existentes (exceto a primeira)
    while (select.children.length > 1) {
        select.removeChild(select.lastChild);
    }
    
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category.charAt(0).toUpperCase() + category.slice(1);
        select.appendChild(option);
    });
}

// Filtrar posts do blog
function filterBlogPosts() {
    const searchTerm = document.getElementById('blogSearchFilter').value.toLowerCase();
    const selectedCategory = document.getElementById('blogCategoryFilter').value;
    
    const filteredPosts = blogPosts.filter(post => {
        const matchesSearch = post.title.toLowerCase().includes(searchTerm) || 
                            post.content.toLowerCase().includes(searchTerm);
        const matchesCategory = !selectedCategory || post.category === selectedCategory;
        
        return matchesSearch && matchesCategory;
    });
    
    renderBlogPosts(filteredPosts);
}

// Abrir modal do blog
function openBlogModal(post = null) {
    const modal = document.getElementById('blogModal');
    const title = document.getElementById('blogModalTitle');
    const form = document.getElementById('blogForm');
    
    if (post) {
        title.textContent = 'Editar Post';
        document.getElementById('blogPostId').value = post.id;
        document.getElementById('blogTitle').value = post.title;
        document.getElementById('blogCategory').value = post.category;
        document.getElementById('blogImageUrl').value = post.image_url || '';
        document.getElementById('blogContent').value = post.content;
    } else {
        title.textContent = 'Novo Post';
        form.reset();
        document.getElementById('blogPostId').value = '';
    }
    
    modal.style.display = 'block';
}

// Fechar modal do blog
function closeBlogModal() {
    document.getElementById('blogModal').style.display = 'none';
}

// Editar post do blog
function editBlogPost(id) {
    const post = blogPosts.find(p => p.id === id);
    if (post) {
        openBlogModal(post);
    }
}

// Deletar post do blog
async function deleteBlogPost(id) {
    if (!confirm('Tem certeza que deseja deletar este post?')) {
        return;
    }
    
    try {
        const response = await fetch('backend/secure_api.php/blog', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({ id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Post deletado com sucesso!', 'success');
            loadBlogPosts();
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        console.error('Erro ao deletar post:', error);
        showNotification('Erro ao deletar post', 'error');
    }
}

// Manipular envio do formulário do blog
async function handleBlogSubmit(e) {
    e.preventDefault();
    
    const postId = document.getElementById('blogPostId').value;
    const title = document.getElementById('blogTitle').value;
    const category = document.getElementById('blogCategory').value;
    const imageUrl = document.getElementById('blogImageUrl').value;
    const content = document.getElementById('blogContent').value;
    
    const method = postId ? 'PUT' : 'POST';
    const data = {
        title,
        category,
        image_url: imageUrl,
        content,
        csrf_token: csrfToken
    };
    
    if (postId) {
        data.id = parseInt(postId);
    }
    
    try {
        const response = await fetch('backend/secure_api.php/blog', {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            closeBlogModal();
            loadBlogPosts();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Erro ao salvar post:', error);
        showNotification('Erro ao salvar post', 'error');
    }
}

// Carregar projetos
async function loadProjects() {
    try {
        const response = await fetch('backend/secure_api.php/projects');
        if (response.ok) {
            projects = await response.json();
            renderProjects();
        }
    } catch (error) {
        console.error('Erro ao carregar projetos:', error);
        showNotification('Erro ao carregar projetos', 'error');
    }
}

// Renderizar projetos
function renderProjects() {
    const tbody = document.getElementById('projectsTableBody');
    tbody.innerHTML = '';
    
    projects.forEach(project => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${project.id}</td>
            <td>${project.title}</td>
            <td>${project.category}</td>
            <td>${project.github_link ? '<a href="' + project.github_link + '" target="_blank">GitHub</a>' : '-'}</td>
            <td>${project.live_link ? '<a href="' + project.live_link + '" target="_blank">Ver</a>' : '-'}</td>
            <td>
                <button class="btn btn-secondary" onclick="editProject(${project.id})">✏️ Editar</button>
                <button class="btn btn-danger" onclick="deleteProject(${project.id})">🗑️ Deletar</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Abrir modal de projetos
function openProjectModal(project = null) {
    const modal = document.getElementById('projectModal');
    const title = document.getElementById('projectModalTitle');
    const form = document.getElementById('projectForm');
    
    if (project) {
        title.textContent = 'Editar Projeto';
        document.getElementById('projectId').value = project.id;
        document.getElementById('projectTitle').value = project.title;
        document.getElementById('projectCategory').value = project.category;
        document.getElementById('projectDescription').value = project.description;
        document.getElementById('projectGithub').value = project.github_link || '';
        document.getElementById('projectLive').value = project.live_link || '';
    } else {
        title.textContent = 'Novo Projeto';
        form.reset();
        document.getElementById('projectId').value = '';
    }
    
    modal.style.display = 'block';
}

// Fechar modal de projetos
function closeProjectModal() {
    document.getElementById('projectModal').style.display = 'none';
}

// Editar projeto
function editProject(id) {
    const project = projects.find(p => p.id === id);
    if (project) {
        openProjectModal(project);
    }
}

// Deletar projeto
async function deleteProject(id) {
    if (!confirm('Tem certeza que deseja deletar este projeto?')) {
        return;
    }
    
    // Implementar delete de projeto (similar ao blog)
    showNotification('Funcionalidade de deletar projeto será implementada', 'info');
}

// Manipular envio do formulário de projetos
async function handleProjectSubmit(e) {
    e.preventDefault();
    
    const projectId = document.getElementById('projectId').value;
    const title = document.getElementById('projectTitle').value;
    const category = document.getElementById('projectCategory').value;
    const description = document.getElementById('projectDescription').value;
    const githubLink = document.getElementById('projectGithub').value;
    const liveLink = document.getElementById('projectLive').value;
    
    const data = {
        title,
        category,
        description,
        github_link: githubLink,
        live_link: liveLink,
        csrf_token: csrfToken
    };
    
    try {
        const response = await fetch('backend/secure_api.php/projects', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            closeProjectModal();
            loadProjects();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Erro ao salvar projeto:', error);
        showNotification('Erro ao salvar projeto', 'error');
    }
}

// Manipular alteração de senha
async function handlePasswordChange(e) {
    e.preventDefault();
    
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        showNotification('As senhas não coincidem', 'error');
        return;
    }
    
    if (newPassword.length < 8) {
        showNotification('A nova senha deve ter pelo menos 8 caracteres', 'error');
        return;
    }
    
    // Implementar alteração de senha
    showNotification('Funcionalidade de alteração de senha será implementada', 'info');
}

// Download de backup
function downloadBackup() {
    showNotification('Funcionalidade de backup será implementada', 'info');
}

// Logout
async function logout() {
    if (!confirm('Tem certeza que deseja sair?')) {
        return;
    }
    
    try {
        const response = await fetch('backend/secure_api.php/auth', {
            method: 'DELETE'
        });
        
        if (response.ok) {
            window.location.href = 'admin_login.php';
        }
    } catch (error) {
        console.error('Erro ao fazer logout:', error);
        window.location.href = 'admin_login.php';
    }
}

// Sistema de notificações
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Adicionar CSS para notificações se não existir
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
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
                max-width: 300px;
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
    }
    
    document.body.appendChild(notification);
    
    // Remover após 4 segundos
    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease-out reverse';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 4000);
}

// Fechar modais ao clicar fora
window.addEventListener('click', function(event) {
    const blogModal = document.getElementById('blogModal');
    const projectModal = document.getElementById('projectModal');
    
    if (event.target === blogModal) {
        closeBlogModal();
    }
    
    if (event.target === projectModal) {
        closeProjectModal();
    }
});

console.log('🎮 Dashboard administrativo carregado com sucesso! ✨');

