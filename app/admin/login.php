<?php
/**
 * Painel Administrativo - Login
 */
require_once '../includes/config.php';

session_start();

// Se já logado, redireciona
if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = sanitize($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    // Credenciais padrão (ALTERE APÓS PRIMEIRO LOGIN!)
    // Hash da senha padrão "admin123" - gere um novo com: password_hash('sua_senha', PASSWORD_BCRYPT)
    $admin_user = 'admin';
    $admin_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // admin123

    if ($user === $admin_user && password_verify($pass, $admin_hash)) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = $user;
        $_SESSION['admin_login_time'] = time();
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Usuário ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SERVER_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #c9a84c; --gold-light: #e8d5a3; --gold-dark: #8b6914;
            --dark: #0a0a0f; --dark-card: #16161f; --dark-border: #1e1e2e;
            --text: #c0c0d0; --text-muted: #6b6b7b; --text-bright: #e8e8f0;
            --red: #c0392b; --green: #27ae60;
            --font-display: 'Cinzel', serif; --font-body: 'Inter', sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--font-body);
            background: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at center, rgba(201,168,76,0.05) 0%, transparent 70%);
            pointer-events: none;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 0 24px;
            position: relative;
            z-index: 1;
        }
        .login-card {
            background: linear-gradient(145deg, var(--dark-card), rgba(22,22,31,0.8));
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            padding: 48px 36px;
            text-align: center;
        }
        .login-logo {
            margin-bottom: 32px;
        }
        .login-logo svg {
            width: 60px;
            height: 60px;
            color: var(--gold);
            filter: drop-shadow(0 0 15px rgba(201,168,76,0.3));
        }
        .login-logo h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            color: var(--gold-light);
            margin-top: 12px;
            letter-spacing: 3px;
        }
        .login-logo p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(10,10,15,0.5);
            border: 1px solid var(--dark-border);
            border-radius: 10px;
            color: var(--text-bright);
            font-family: var(--font-body);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: rgba(201,168,76,0.4);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.05);
        }
        .form-group input::placeholder { color: var(--text-muted); opacity: 0.5; }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--gold-dark), var(--gold));
            border: none;
            border-radius: 10px;
            color: var(--dark);
            font-family: var(--font-body);
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(201,168,76,0.3);
            filter: brightness(1.1);
        }
        .error-msg {
            background: rgba(192,57,43,0.1);
            border: 1px solid rgba(192,57,43,0.2);
            border-radius: 8px;
            padding: 12px;
            color: var(--red);
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 24px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }
        .back-link:hover { color: var(--gold); }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="50,5 95,27.5 95,72.5 50,95 5,72.5 5,27.5"/>
                    <polygon points="50,15 85,32.5 85,67.5 50,85 15,67.5 15,32.5" opacity="0.6"/>
                    <text x="50" y="58" text-anchor="middle" font-size="28" font-weight="bold" fill="currentColor" font-family="Cinzel">E</text>
                </svg>
                <h1>PAINEL ADMIN</h1>
                <p><?php echo SERVER_NAME; ?></p>
            </div>

            <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" placeholder="admin" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="••••••" required>
                </div>
                <button type="submit" class="btn-login">Entrar</button>
            </form>

            <a href="/" class="back-link">← Voltar ao site</a>
        </div>
    </div>
</body>
</html>
