<?php
// Página inicial do portfólio
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Portfólio Interativo</title>
    <link rel="stylesheet" href="frontend/css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#home">Início</a></li>
                <li><a href="#about">Sobre Mim</a></li>
                <li><a href="#projects">Projetos</a></li>
                <li><a href="#blog">Blog</a></li>
                <li><a href="admin_login.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="home" class="hero-section">
            <h1 id="welcome-message"></h1>
            <div class="social-links">
                <a href="#" target="_blank">GitHub</a>
                <a href="#" target="_blank">Instagram</a>
                <a href="#" target="_blank">LinkedIn</a>
            </div>
            <div class="projects-summary">
                <h2>Meus Projetos Principais</h2>
                <div class="project-card">
                    <h3>Projeto Exemplo 1</h3>
                    <p>Este é um resumo curto do projeto 1. <span class="long-text">Aqui vai o texto mais longo que será ocultado inicialmente.</span></p>
                    <button class="read-more-btn">Ver mais</button>
                </div>
                <div class="project-card">
                    <h3>Projeto Exemplo 2</h3>
                    <p>Este é um resumo curto do projeto 2. <span class="long-text">Aqui vai o texto mais longo que será ocultado inicialmente.</span></p>
                    <button class="read-more-btn">Ver mais</button>
                </div>
            </div>
        </section>

        <section id="about" class="about-section">
            <h2>Sobre Mim</h2>
            <div class="about-content">
                <h3>Experiências de Trabalho</h3>
                <p>Desenvolvedor Web na Empresa X (2020-2023)</p>
                <h3>Experiências Extracurriculares</h3>
                <p>Voluntário em projeto open-source</p>
                <h3>Profissão</h3>
                <p>Desenvolvedor Fullstack</p>
                <h3>Hobbies</h3>
                <ul>
                    <li>Jogar videogames</li>
                    <li>Ler livros de fantasia</li>
                    <li>Cuidar dos meus gatos</li>
                </ul>
                <h3>Certificados</h3>
                <p>Certificado em Desenvolvimento Web Avançado</p>
                <h3>Skills e Habilidades</h3>
                <div class="skills-graph">
                    <!-- Gráfico de habilidades será gerado com JS/CSS -->
                    <div class="skill-item">
                        <span class="skill-name">HTML/CSS</span>
                        <div class="skill-bar"><div class="skill-level" style="width: 90%;"></div></div>
                    </div>
                    <div class="skill-item">
                        <span class="skill-name">JavaScript</span>
                        <div class="skill-bar"><div class="skill-level" style="width: 80%;"></div></div>
                    </div>
                    <div class="skill-item">
                        <span class="skill-name">PHP</span>
                        <div class="skill-bar"><div class="skill-level" style="width: 75%;"></div></div>
                    </div>
                    <div class="skill-item">
                        <span class="skill-name">SQL</span>
                        <div class="skill-bar"><div class="skill-level" style="width: 70%;"></div></div>
                    </div>
                </div>
                <h3>Escolaridade</h3>
                <p>Bacharel em Ciência da Computação - Universidade Y</p>
                <div class="resume-buttons">
                    <button>Visualizar Currículo</button>
                    <button>Baixar Currículo</button>
                </div>
            </div>
        </section>

        <section id="projects" class="projects-section">
            <h2>Meus Projetos</h2>
            <div class="project-filters">
                <input type="text" id="project-search" placeholder="Buscar projeto...">
                <select id="project-category">
                    <option value="all">Todas as Categorias</option>
                    <option value="individual">Individual</option>
                    <option value="collaboration">Em Colaboração</option>
                    <option value="curricular">Curriculares</option>
                </select>
            </div>
            <div class="projects-grid">
                <!-- Projetos serão carregados aqui -->
                <div class="project-item" data-category="individual">
                    <h3>Projeto Individual 1</h3>
                    <p>Descrição do projeto individual.</p>
                </div>
                <div class="project-item" data-category="collaboration">
                    <h3>Projeto Colaborativo 1</h3>
                    <p>Descrição do projeto colaborativo.</p>
                </div>
            </div>
        </section>

        <section id="blog" class="blog-section">
            <h2>Blog</h2>
            <div class="blog-filters">
                <input type="text" id="blog-search" placeholder="Buscar post...">
                <select id="blog-category">
                    <option value="all">Todas as Categorias</option>
                    <!-- Categorias do blog serão carregadas dinamicamente -->
                </select>
                <input type="date" id="blog-date-filter">
            </div>
            <div class="blog-posts">
                <!-- Posts do blog serão carregados aqui -->
                <div class="blog-post">
                    <img src="https://via.placeholder.com/150" alt="">
                    <h3>Título do Post 1</h3>
                    <p>Descrição curta do post 1.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Meu Portfólio. Todos os direitos reservados.</p>
    </footer>

    <audio id="background-music" loop>
        <!-- Adicione sua música de fundo aqui -->
        <source src="" type="audio/mpeg">
    </audio>
    <div class="music-controls">
        <button id="play-music">Play</button>
        <button id="pause-music">Pause</button>
        <input type="range" id="volume-control" min="0" max="1" step="0.1" value="0.5">
    </div>

    <script src="frontend/js/script.js"></script>
</body>
</html>


