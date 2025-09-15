<?php
// API segura com autenticação e proteção

require_once 'db_config.php';
require_once 'auth.php';

// Configurar headers de segurança
setSecurityHeaders();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');

// Tratar requisições OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$endpoint = $request[0] ?? '';

// Log da requisição
logSecurityEvent("API_REQUEST", "Method: {$method}, Endpoint: {$endpoint}");

try {
    switch ($endpoint) {
        case 'auth':
            handleAuth($pdo, $method);
            break;
        case 'projects':
            handleProjects($pdo, $method);
            break;
        case 'blog':
            handleBlog($pdo, $method);
            break;
        case 'admin':
            handleAdmin($pdo, $method);
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint não encontrado']);
            break;
    }
} catch (Exception $e) {
    logSecurityEvent("API_ERROR", $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor']);
}

function handleAuth($pdo, $method) {
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }
        
        // Verificar se é login ou verificação 2FA
        if (isset($input['action']) && $input['action'] === 'verify_2fa') {
            $code = sanitizeInput($input['code'] ?? '');
            
            if (empty($code)) {
                http_response_code(400);
                echo json_encode(['error' => 'Código 2FA é obrigatório']);
                return;
            }
            
            $result = verify2FA($code);
            
            if ($result['success']) {
                logSecurityEvent("2FA_SUCCESS", "User verified 2FA");
            } else {
                logSecurityEvent("2FA_FAILED", "Invalid 2FA code");
            }
            
            echo json_encode($result);
            return;
        }
        
        // Login normal
        $username = sanitizeInput($input['username'] ?? '');
        $password = $input['password'] ?? '';
        
        // Validar entrada
        $validation_rules = [
            'username' => ['required' => true, 'min_length' => 3, 'max_length' => 50],
            'password' => ['required' => true, 'min_length' => 6]
        ];
        
        $errors = validateInput($input, $validation_rules);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos', 'details' => $errors]);
            return;
        }
        
        // Verificar tentativas de login
        if (!checkLoginAttempts($username)) {
            logSecurityEvent("LOGIN_BLOCKED", "Too many attempts for user: {$username}");
            http_response_code(429);
            echo json_encode(['error' => 'Muitas tentativas de login. Tente novamente em 15 minutos.']);
            return;
        }
        
        $result = loginUser($pdo, $username, $password);
        
        recordLoginAttempt($username, $result['success']);
        
        if ($result['success']) {
            logSecurityEvent("LOGIN_SUCCESS", "User: {$username}");
        } else {
            logSecurityEvent("LOGIN_FAILED", "User: {$username}");
        }
        
        echo json_encode($result);
        
    } elseif ($method === 'GET') {
        // Verificar status de autenticação
        $status = [
            'authenticated' => isAuthenticated(),
            '2fa_verified' => is2FAActive(),
            'csrf_token' => generateCSRFToken()
        ];
        
        echo json_encode($status);
        
    } elseif ($method === 'DELETE') {
        // Logout
        $result = logoutUser();
        logSecurityEvent("LOGOUT", "User logged out");
        echo json_encode($result);
    }
}

function handleProjects($pdo, $method) {
    if ($method === 'GET') {
        // Projetos são públicos
        $stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($projects);
        
    } else {
        // Operações de escrita requerem autenticação
        requireAdmin();
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Verificar CSRF token
        $csrf_token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $input['csrf_token'] ?? '';
        if (!verifyCSRFToken($csrf_token)) {
            http_response_code(403);
            echo json_encode(['error' => 'Token CSRF inválido']);
            return;
        }
        
        if ($method === 'POST') {
            // Criar projeto
            $validation_rules = [
                'title' => ['required' => true, 'max_length' => 255],
                'description' => ['required' => true],
                'category' => ['required' => true, 'max_length' => 50]
            ];
            
            $errors = validateInput($input, $validation_rules);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados inválidos', 'details' => $errors]);
                return;
            }
            
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, category, github_link, live_link) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([
                sanitizeInput($input['title']),
                sanitizeInput($input['description']),
                sanitizeInput($input['category']),
                sanitizeInput($input['github_link'] ?? ''),
                sanitizeInput($input['live_link'] ?? '')
            ]);
            
            if ($result) {
                logSecurityEvent("PROJECT_CREATED", "Title: " . $input['title']);
                echo json_encode(['success' => true, 'message' => 'Projeto criado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar projeto']);
            }
        }
        // Adicionar outros métodos (PUT, DELETE) conforme necessário
    }
}

function handleBlog($pdo, $method) {
    if ($method === 'GET') {
        // Posts do blog são públicos
        $posts = getAllBlogPosts($pdo);
        echo json_encode($posts);
        
    } else {
        // Operações de escrita requerem autenticação
        requireAdmin();
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Verificar CSRF token
        $csrf_token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $input['csrf_token'] ?? '';
        if (!verifyCSRFToken($csrf_token)) {
            http_response_code(403);
            echo json_encode(['error' => 'Token CSRF inválido']);
            return;
        }
        
        if ($method === 'POST') {
            // Criar post
            $validation_rules = [
                'title' => ['required' => true, 'max_length' => 255],
                'content' => ['required' => true],
                'category' => ['required' => true, 'max_length' => 50]
            ];
            
            $errors = validateInput($input, $validation_rules);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados inválidos', 'details' => $errors]);
                return;
            }
            
            $result = addBlogPost(
                $pdo,
                sanitizeInput($input['title']),
                sanitizeInput($input['content']),
                sanitizeInput($input['image_url'] ?? ''),
                sanitizeInput($input['category'])
            );
            
            if ($result) {
                logSecurityEvent("BLOG_POST_CREATED", "Title: " . $input['title']);
                echo json_encode(['success' => true, 'message' => 'Post criado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar post']);
            }
            
        } elseif ($method === 'PUT') {
            // Atualizar post
            if (!isset($input['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID do post é obrigatório']);
                return;
            }
            
            $validation_rules = [
                'title' => ['required' => true, 'max_length' => 255],
                'content' => ['required' => true],
                'category' => ['required' => true, 'max_length' => 50]
            ];
            
            $errors = validateInput($input, $validation_rules);
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados inválidos', 'details' => $errors]);
                return;
            }
            
            $result = updateBlogPost(
                $pdo,
                (int)$input['id'],
                sanitizeInput($input['title']),
                sanitizeInput($input['content']),
                sanitizeInput($input['image_url'] ?? ''),
                sanitizeInput($input['category'])
            );
            
            if ($result) {
                logSecurityEvent("BLOG_POST_UPDATED", "ID: " . $input['id']);
                echo json_encode(['success' => true, 'message' => 'Post atualizado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar post']);
            }
            
        } elseif ($method === 'DELETE') {
            // Deletar post
            if (!isset($input['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID do post é obrigatório']);
                return;
            }
            
            $result = deleteBlogPost($pdo, (int)$input['id']);
            
            if ($result) {
                logSecurityEvent("BLOG_POST_DELETED", "ID: " . $input['id']);
                echo json_encode(['success' => true, 'message' => 'Post deletado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao deletar post']);
            }
        }
    }
}

function handleAdmin($pdo, $method) {
    requireAdmin();
    
    if ($method === 'GET') {
        // Estatísticas do admin
        $stats = [
            'total_projects' => $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
            'total_blog_posts' => $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn(),
            'recent_posts' => $pdo->query("SELECT title, created_at FROM blog_posts ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC)
        ];
        
        echo json_encode($stats);
    }
}

?>

