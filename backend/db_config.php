<?php
// Configuração do banco de dados SQLite

$databaseFile = __DIR__ . "/../database/portfolio.sqlite";

try {
    $pdo = new PDO("sqlite:" . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("PRAGMA foreign_keys = ON;");
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

// Função para criar tabelas se não existirem
function createTables($pdo) {
    // Tabela de usuários (para administrador)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            two_factor_secret TEXT
        );
    ");

    // Tabela de projetos
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT NOT NULL,
            category TEXT NOT NULL,
            github_link TEXT,
            live_link TEXT
        );
    ");

    // Tabela de posts do blog
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS blog_posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            image_url TEXT,
            category TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
}

// Chamada para criar as tabelas
createTables($pdo);

// Funções básicas de acesso ao banco de dados

// Exemplo: Obter um usuário por username
function getUserByUsername($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Exemplo: Adicionar um novo usuário
function addUser($pdo, $username, $passwordHash, $twoFactorSecret = null) {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, two_factor_secret) VALUES (:username, :password, :two_factor_secret)");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $passwordHash);
    $stmt->bindParam(":two_factor_secret", $twoFactorSecret);
    return $stmt->execute();
}

// Exemplo: Obter todos os posts do blog
function getAllBlogPosts($pdo) {
    $stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Exemplo: Adicionar um novo post no blog
function addBlogPost($pdo, $title, $content, $imageUrl, $category) {
    $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content, image_url, category) VALUES (:title, :content, :image_url, :category)");
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":content", $content);
    $stmt->bindParam(":image_url", $imageUrl);
    $stmt->bindParam(":category", $category);
    return $stmt->execute();
}

// Exemplo: Atualizar um post no blog
function updateBlogPost($pdo, $id, $title, $content, $imageUrl, $category) {
    $stmt = $pdo->prepare("UPDATE blog_posts SET title = :title, content = :content, image_url = :image_url, category = :category WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":content", $content);
    $stmt->bindParam(":image_url", $imageUrl);
    $stmt->bindParam(":category", $category);
    return $stmt->execute();
}

// Exemplo: Deletar um post no blog
function deleteBlogPost($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = :id");
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}

?>

