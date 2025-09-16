<?php
session_start();

// Verificar se está logado e com 2FA verificado
// if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in'] || 
//     !isset($_SESSION['2fa_verified']) || !$_SESSION['2fa_verified']) {
//     header('Location: admin_login.php');
//     exit;
// }
?>
<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Administrativo - Portfólio</title>
        <link rel="stylesheet" href="frontend/css/style.css">
        <link rel="stylesheet" href="frontend/css/dashboard.css">
    </head>
    <body>
        <div class="admin-container">
            <div class="admin-header">
                <div>
                    <h1>🎮 Dashboard Administrativo</h1>
                    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
                </div>
                <div>
                    <a href="index.php" class="btn btn-secondary">Ver Site</a>
                    <button class="logout-btn" onclick="logout()">Sair</button>
                </div>
            </div>
            
            <div class="stats-grid" id="statsGrid">
                <!-- Estatísticas serão carregadas aqui -->
            </div>
            
            <div class="admin-nav">
                <button class="nav-btn active" data-section="dashboard">Dashboard</button>
                <button class="nav-btn" data-section="blog">Gerenciar Blog</button>
                <button class="nav-btn" data-section="projects">Gerenciar Projetos</button>
                <button class="nav-btn" data-section="settings">Configurações</button>
            </div>
            
            <!-- Dashboard -->
            <div id="dashboard" class="admin-section active">
                <h2>📊 Visão Geral</h2>
                <div id="recentActivity">
                    <!-- Atividades recentes serão carregadas aqui -->
                </div>
            </div>
            
            <!-- Gerenciar Blog -->
            <div id="blog" class="admin-section">
                <h2>📝 Gerenciar Blog</h2>
                
                <button class="btn" onclick="openBlogModal()">➕ Novo Post</button>
                
                <div class="form-grid">
                    <div class="form-group">
                        <input type="text" id="blogSearchFilter" placeholder="Buscar posts..." onkeyup="filterBlogPosts()">
                    </div>
                    <div class="form-group">
                        <select id="blogCategoryFilter" onchange="filterBlogPosts()">
                            <option value="">Todas as categorias</option>
                        </select>
                    </div>
                </div>
                
                <table class="data-table" id="blogTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Categoria</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="blogTableBody">
                        <!-- Posts serão carregados aqui -->
                    </tbody>
                </table>
            </div>
            
            <!-- Gerenciar Projetos -->
            <div id="projects" class="admin-section">
                <h2>🚀 Gerenciar Projetos</h2>
                
                <button class="btn" onclick="openProjectModal()">➕ Novo Projeto</button>
                
                <table class="data-table" id="projectsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Categoria</th>
                            <th>GitHub</th>
                            <th>Live</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTableBody">
                        <!-- Projetos serão carregados aqui -->
                    </tbody>
                </table>
            </div>
            
            <!-- Configurações -->
            <div id="settings" class="admin-section">
                <h2>⚙️ Configurações</h2>
                
                <div class="form-grid">
                    <div>
                        <h3>Alterar Senha</h3>
                        <form id="changePasswordForm">
                            <div class="form-group">
                                <label>Senha Atual:</label>
                                <input type="password" id="currentPassword" required>
                            </div>
                            <div class="form-group">
                                <label>Nova Senha:</label>
                                <input type="password" id="newPassword" required>
                            </div>
                            <div class="form-group">
                                <label>Confirmar Nova Senha:</label>
                                <input type="password" id="confirmPassword" required>
                            </div>
                            <button type="submit" class="btn">Alterar Senha</button>
                        </form>
                    </div>
                    
                    <div>
                        <h3>Backup do Sistema</h3>
                        <p>Faça backup dos seus dados regularmente.</p>
                        <button class="btn btn-secondary" onclick="downloadBackup()">📥 Download Backup</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal para Blog -->
        <div id="blogModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeBlogModal()">&times;</span>
                <h2 id="blogModalTitle">Novo Post</h2>
                <form id="blogForm">
                    <input type="hidden" id="blogPostId">
                    <div class="form-group">
                        <label>Título:</label>
                        <input type="text" id="blogTitle" required>
                    </div>
                    <div class="form-group">
                        <label>Categoria:</label>
                        <input type="text" id="blogCategory" required>
                    </div>
                    <div class="form-group">
                        <label>URL da Imagem:</label>
                        <input type="url" id="blogImageUrl">
                    </div>
                    <div class="form-group">
                        <label>Conteúdo:</label>
                        <textarea id="blogContent" required></textarea>
                    </div>
                    <button type="submit" class="btn">Salvar</button>
                    <button type="button" class="btn btn-secondary" onclick="closeBlogModal()">Cancelar</button>
                </form>
            </div>
        </div>
        
        <!-- Modal para Projetos -->
        <div id="projectModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeProjectModal()">&times;</span>
                <h2 id="projectModalTitle">Novo Projeto</h2>
                <form id="projectForm">
                    <input type="hidden" id="projectId">
                    <div class="form-group">
                        <label>Título:</label>
                        <input type="text" id="projectTitle" required>
                    </div>
                    <div class="form-group">
                        <label>Categoria:</label>
                        <select id="projectCategory" required>
                            <option value="individual">Individual</option>
                            <option value="collaboration">Em Colaboração</option>
                            <option value="curricular">Curriculares</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Descrição:</label>
                        <textarea id="projectDescription" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Link do GitHub:</label>
                        <input type="url" id="projectGithub">
                    </div>
                    <div class="form-group">
                        <label>Link do Projeto:</label>
                        <input type="url" id="projectLive">
                    </div>
                    <button type="submit" class="btn">Salvar</button>
                    <button type="button" class="btn btn-secondary" onclick="closeProjectModal()">Cancelar</button>
                </form>
            </div>
        </div>
        
        <script src="frontend/js/admin.js"></script>
    </body>
</html>

