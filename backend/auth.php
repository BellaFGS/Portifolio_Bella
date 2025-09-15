<?php
// Sistema de autenticação com 2FA

require_once 'db_config.php';

// Função para gerar código 2FA
function generate2FACode() {
    return sprintf('%06d', mt_rand(0, 999999));
}

// Função para verificar se o usuário está autenticado
function isAuthenticated() {
    session_start();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Função para verificar se o 2FA está ativo
function is2FAActive() {
    session_start();
    return isset($_SESSION['2fa_verified']) && $_SESSION['2fa_verified'] === true;
}

// Função para fazer login (primeira etapa)
function loginUser($pdo, $username, $password) {
    $user = getUserByUsername($pdo, $username);
    
    if ($user && $user['password'] === hash('sha256', $password)) {
        session_start();
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['2fa_verified'] = false;
        
        // Gerar código 2FA
        $code = generate2FACode();
        $_SESSION['2fa_code'] = $code;
        $_SESSION['2fa_expires'] = time() + 300; // 5 minutos
        
        // Em produção, você enviaria este código por email/SMS
        // Por simplicidade, vamos apenas retornar o código
        return [
            'success' => true,
            'requires_2fa' => true,
            'code' => $code, // Em produção, remover esta linha
            'message' => 'Código 2FA gerado. Verifique seu email.'
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Credenciais inválidas'
    ];
}

// Função para verificar código 2FA
function verify2FA($code) {
    session_start();
    
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        return [
            'success' => false,
            'message' => 'Usuário não autenticado'
        ];
    }
    
    if (!isset($_SESSION['2fa_code']) || !isset($_SESSION['2fa_expires'])) {
        return [
            'success' => false,
            'message' => 'Código 2FA não encontrado'
        ];
    }
    
    if (time() > $_SESSION['2fa_expires']) {
        unset($_SESSION['2fa_code']);
        unset($_SESSION['2fa_expires']);
        return [
            'success' => false,
            'message' => 'Código 2FA expirado'
        ];
    }
    
    if ($code === $_SESSION['2fa_code']) {
        $_SESSION['2fa_verified'] = true;
        unset($_SESSION['2fa_code']);
        unset($_SESSION['2fa_expires']);
        
        return [
            'success' => true,
            'message' => 'Autenticação 2FA concluída com sucesso'
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Código 2FA inválido'
    ];
}

// Função para fazer logout
function logoutUser() {
    session_start();
    session_destroy();
    return [
        'success' => true,
        'message' => 'Logout realizado com sucesso'
    ];
}

// Função para verificar permissões administrativas
function requireAdmin() {
    if (!isAuthenticated() || !is2FAActive()) {
        http_response_code(401);
        echo json_encode(['error' => 'Acesso negado. Autenticação necessária.']);
        exit;
    }
}

// Função para gerar token CSRF
function generateCSRFToken() {
    session_start();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Função para verificar token CSRF
function verifyCSRFToken($token) {
    session_start();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Função para sanitizar entrada
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Função para validar senha forte
function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'A senha deve ter pelo menos 8 caracteres';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'A senha deve conter pelo menos uma letra maiúscula';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'A senha deve conter pelo menos uma letra minúscula';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'A senha deve conter pelo menos um número';
    }
    
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'A senha deve conter pelo menos um caractere especial';
    }
    
    return $errors;
}

// Função para hash seguro de senha
function hashPassword($password) {
    return hash('sha256', $password . 'salt_secreto_portfolio');
}

// Função para verificar tentativas de login
function checkLoginAttempts($username) {
    session_start();
    
    $key = 'login_attempts_' . $username;
    $attempts = $_SESSION[$key] ?? 0;
    $last_attempt = $_SESSION[$key . '_time'] ?? 0;
    
    // Reset após 15 minutos
    if (time() - $last_attempt > 900) {
        unset($_SESSION[$key]);
        unset($_SESSION[$key . '_time']);
        return true;
    }
    
    // Máximo 5 tentativas
    return $attempts < 5;
}

// Função para registrar tentativa de login
function recordLoginAttempt($username, $success = false) {
    session_start();
    
    $key = 'login_attempts_' . $username;
    
    if ($success) {
        unset($_SESSION[$key]);
        unset($_SESSION[$key . '_time']);
    } else {
        $_SESSION[$key] = ($_SESSION[$key] ?? 0) + 1;
        $_SESSION[$key . '_time'] = time();
    }
}

// Função para log de segurança
function logSecurityEvent($event, $details = '') {
    $logFile = __DIR__ . '/../logs/security.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $logEntry = "[{$timestamp}] {$event} - IP: {$ip} - User-Agent: {$userAgent} - Details: {$details}\n";
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Middleware de segurança para headers
function setSecurityHeaders() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\'; style-src \'self\' \'unsafe-inline\'; img-src \'self\' data: https:;');
}

// Função para validar entrada de dados
function validateInput($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? null;
        
        if (isset($rule['required']) && $rule['required'] && empty($value)) {
            $errors[$field] = "O campo {$field} é obrigatório";
            continue;
        }
        
        if (!empty($value)) {
            if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
                $errors[$field] = "O campo {$field} deve ter pelo menos {$rule['min_length']} caracteres";
            }
            
            if (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
                $errors[$field] = "O campo {$field} deve ter no máximo {$rule['max_length']} caracteres";
            }
            
            if (isset($rule['pattern']) && !preg_match($rule['pattern'], $value)) {
                $errors[$field] = "O campo {$field} tem formato inválido";
            }
        }
    }
    
    return $errors;
}

?>

