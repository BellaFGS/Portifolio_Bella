<?php
session_start();

// Se já estiver logado, redirecionar para admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: admin_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - Portfólio</title>
    <link rel="stylesheet" href="frontend/css/style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-form {
            background: rgba(37, 30, 82, 0.9);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .login-form h2 {
            text-align: center;
            color: var(--amarelo);
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--branco);
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--azul-claro);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--branco);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--amarelo);
            box-shadow: 0 0 10px rgba(199, 161, 98, 0.3);
        }
        
        .login-btn {
            width: 100%;
            background: linear-gradient(45deg, var(--amarelo), var(--vermelho));
            color: var(--branco);
            border: none;
            padding: 15px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(195, 70, 75, 0.4);
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-link a {
            color: var(--azul-claro);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .back-link a:hover {
            color: var(--amarelo);
        }
        
        .error-message {
            background: rgba(195, 70, 75, 0.2);
            color: var(--vermelho);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid var(--vermelho);
        }
        
        .success-message {
            background: rgba(73, 119, 157, 0.2);
            color: var(--azul-claro);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid var(--azul-claro);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form class="login-form" id="loginForm">
            <h2>🔐 Login Administrativo</h2>
            
            <div id="message-container"></div>
            
            <div class="form-group">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">Entrar</button>
            
            <div class="back-link">
                <a href="index.php">← Voltar ao Portfólio</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const messageContainer = document.getElementById('message-container');
            
            try {
                const response = await fetch('backend/secure_api.php/auth', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username, password })
                });
                
                const data = await response.json();
                
                if (data.success && data.requires_2fa) {
                    messageContainer.innerHTML = '<div class="success-message">Login realizado! Código 2FA: ' + data.code + '</div>';
                    show2FAForm();
                } else if (data.success) {
                    messageContainer.innerHTML = '<div class="success-message">Login realizado com sucesso! Redirecionando...</div>';
                    setTimeout(() => {
                        window.location.href = 'admin_dashboard.php';
                    }, 1500);
                } else {
                    messageContainer.innerHTML = '<div class="error-message">' + data.message + '</div>';
                }
            } catch (error) {
                messageContainer.innerHTML = '<div class="error-message">Erro de conexão. Tente novamente.</div>';
            }
        });
        
        function show2FAForm() {
            const loginForm = document.getElementById('loginForm');
            loginForm.innerHTML = `
                <h2>🔐 Verificação 2FA</h2>
                <div id="message-container-2fa"></div>
                <div class="form-group">
                    <label for="twofa-code">Código 2FA:</label>
                    <input type="text" id="twofa-code" name="twofa-code" required maxlength="6" placeholder="000000">
                </div>
                <button type="submit" class="login-btn">Verificar</button>
                <div class="back-link">
                    <a href="admin_login.php">← Voltar ao Login</a>
                </div>
            `;
            
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const code = document.getElementById('twofa-code').value;
                const messageContainer = document.getElementById('message-container-2fa');
                
                try {
                    const response = await fetch('backend/secure_api.php/auth', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ action: 'verify_2fa', code })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        messageContainer.innerHTML = '<div class="success-message">2FA verificado! Redirecionando...</div>';
                        setTimeout(() => {
                            window.location.href = 'admin_dashboard.php';
                        }, 1500);
                    } else {
                        messageContainer.innerHTML = '<div class="error-message">' + data.message + '</div>';
                    }
                } catch (error) {
                    messageContainer.innerHTML = '<div class="error-message">Erro de conexão. Tente novamente.</div>';
                }
            });
        }
    </script>
</body>
</html>

