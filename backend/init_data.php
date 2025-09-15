<?php
// Script para inicializar dados de exemplo no banco de dados

require_once 'db_config.php';

// Criar usuário administrador padrão
$adminUsername = 'admin';
$adminPassword = hash('sha256', 'admin123'); // Senha: admin123 (criptografada com SHA256)

// Verificar se o usuário admin já existe
$existingAdmin = getUserByUsername($pdo, $adminUsername);
if (!$existingAdmin) {
    addUser($pdo, $adminUsername, $adminPassword);
    echo "Usuário administrador criado com sucesso!\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
} else {
    echo "Usuário administrador já existe.\n";
}

// Adicionar projetos de exemplo
$projects = [
    [
        'title' => 'Sistema de Gerenciamento de Tarefas',
        'description' => 'Um sistema completo para gerenciar tarefas pessoais e profissionais, desenvolvido com PHP e MySQL.',
        'category' => 'individual',
        'github_link' => 'https://github.com/usuario/task-manager',
        'live_link' => 'https://task-manager.exemplo.com'
    ],
    [
        'title' => 'E-commerce Colaborativo',
        'description' => 'Plataforma de e-commerce desenvolvida em equipe usando React e Node.js.',
        'category' => 'collaboration',
        'github_link' => 'https://github.com/equipe/ecommerce',
        'live_link' => 'https://ecommerce.exemplo.com'
    ],
    [
        'title' => 'Sistema Acadêmico',
        'description' => 'Sistema para gerenciamento de notas e frequência desenvolvido como projeto curricular.',
        'category' => 'curricular',
        'github_link' => 'https://github.com/universidade/sistema-academico',
        'live_link' => null
    ]
];

foreach ($projects as $project) {
    $stmt = $pdo->prepare("INSERT INTO projects (title, description, category, github_link, live_link) VALUES (:title, :description, :category, :github_link, :live_link)");
    $stmt->execute($project);
}

echo "Projetos de exemplo adicionados com sucesso!\n";

// Adicionar posts de blog de exemplo
$blogPosts = [
    [
        'title' => 'Minha Jornada no Desenvolvimento Web',
        'content' => 'Este é meu primeiro post sobre como comecei no desenvolvimento web. Aqui compartilho minhas experiências e aprendizados...',
        'image_url' => 'https://via.placeholder.com/400x200',
        'category' => 'pessoal'
    ],
    [
        'title' => 'Dicas de PHP para Iniciantes',
        'content' => 'Neste post, compartilho algumas dicas importantes para quem está começando a programar em PHP...',
        'image_url' => 'https://via.placeholder.com/400x200',
        'category' => 'tutorial'
    ],
    [
        'title' => 'Projeto Open Source: Como Contribuir',
        'content' => 'Guia completo sobre como começar a contribuir para projetos open source...',
        'image_url' => 'https://via.placeholder.com/400x200',
        'category' => 'open-source'
    ]
];

foreach ($blogPosts as $post) {
    addBlogPost($pdo, $post['title'], $post['content'], $post['image_url'], $post['category']);
}

echo "Posts de blog de exemplo adicionados com sucesso!\n";

echo "Inicialização dos dados concluída!\n";
?>

