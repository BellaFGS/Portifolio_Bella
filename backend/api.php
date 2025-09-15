<?php
// API para comunicação entre frontend e backend

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$endpoint = $request[0] ?? '';

switch ($endpoint) {
    case 'projects':
        handleProjects($pdo, $method);
        break;
    case 'blog':
        handleBlog($pdo, $method);
        break;
    case 'auth':
        handleAuth($pdo, $method);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint não encontrado']);
        break;
}

function handleProjects($pdo, $method) {
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($projects);
    }
}

function handleBlog($pdo, $method) {
    if ($method === 'GET') {
        $posts = getAllBlogPosts($pdo);
        echo json_encode($posts);
    } elseif ($method === 'POST') {
        // Verificar autenticação aqui
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['title'], $input['content'], $input['category'])) {
            $result = addBlogPost($pdo, $input['title'], $input['content'], $input['image_url'] ?? '', $input['category']);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Post criado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar post']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos']);
        }
    } elseif ($method === 'PUT') {
        // Atualizar post
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['id'], $input['title'], $input['content'], $input['category'])) {
            $result = updateBlogPost($pdo, $input['id'], $input['title'], $input['content'], $input['image_url'] ?? '', $input['category']);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Post atualizado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar post']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos']);
        }
    } elseif ($method === 'DELETE') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['id'])) {
            $result = deleteBlogPost($pdo, $input['id']);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Post deletado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao deletar post']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID não fornecido']);
        }
    }
}

function handleAuth($pdo, $method) {
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['username'], $input['password'])) {
            $user = getUserByUsername($pdo, $input['username']);
            
            if ($user && $user['password'] === hash('sha256', $input['password'])) {
                session_start();
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Credenciais inválidas']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Username e password são obrigatórios']);
        }
    } elseif ($method === 'GET') {
        session_start();
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
            echo json_encode(['authenticated' => true]);
        } else {
            echo json_encode(['authenticated' => false]);
        }
    }
}
?>

