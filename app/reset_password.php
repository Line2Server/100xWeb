<?php
require_once 'includes/config.php';

$error = '';
$success = '';
$validToken = false;
$token = sanitize($_GET['token'] ?? '');

// Verificar token
try {
    $db = getDB();

    // Criar tabela se não existir
    $db->exec("
        CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            login VARCHAR(45) NOT NULL,
            email VARCHAR(255) NOT NULL,
            token VARCHAR(64) NOT NULL,
            expires_at DATETIME NOT NULL,
            used TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_token (token),
            INDEX idx_login (login)
        )
    ");

    if (!empty($token)) {
        $stmt = $db->prepare("
            SELECT login, email, expires_at, used 
            FROM password_resets 
            WHERE token = ? AND expires_at > NOW() AND used = 0
            LIMIT 1
        ");
        $stmt->execute([$token]);
        $resetData = $stmt->fetch();

        if ($resetData) {
            $validToken = true;
        } else {
            $error = 'Link de recuperação inválido ou expirado. Solicite um novo.';
        }
    } else {
        $error = 'Token não fornecido.';
    }

    // Processar reset
    if ($validToken && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (strlen($password) < 6) {
            $error = 'A senha deve ter pelo menos 6 caracteres.';
        } elseif ($password !== $passwordConfirm) {
            $error = 'As senhas não coincidem.';
        } else {
            // Atualizar senha (SHA1 para compatibilidade com jogo)
            $passHash = sha1($password);
            $stmt = $db->prepare("UPDATE accounts SET password = ? WHERE login = ?");
            $stmt->execute([$passHash, $resetData['login']]);

            // Marcar token como usado
            $stmt = $db->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
            $stmt->execute([$token]);

            // Enviar e-mail de confirmação
            $emailBody = '
                <h2 style="color:#c9a84c;margin-bottom:20px;">Senha Alterada com Sucesso!</h2>
                <p>Olá, <strong>' . htmlspecialchars($resetData['login']) . '</strong>!</p>
                <p>Sua senha em <strong>' . SERVER_NAME . '</strong> foi redefinida com sucesso.</p>
                <p style="margin:16px 0;">Se você não solicitou esta alteração, entre em contato imediatamente com o suporte via Discord.</p>
                <p style="margin-top:20px;"><a href="' . (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/pages/downloads.php" style="display:inline-block;background:linear-gradient(135deg,#8b6914,#c9a84c);color:#0a0a0f;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:700;">🎮 Baixar Jogo</a></p>
            ';
            sendEmail($resetData['email'], 'Senha Alterada - ' . SERVER_NAME, $emailBody);

            $success = "Senha redefinida com sucesso! Você já pode fazer login no jogo com sua nova senha.";
        }
    }
} catch (Exception $e) {
    $error = 'Erro no sistema. Tente novamente.';
}

require_once 'includes/header.php';
?>

<style>
.auth-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 24px;
    position: relative;
}
.auth-container::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at center, rgba(201,168,76,0.05) 0%, transparent 70%);
    pointer-events: none;
}
.auth-card {
    width: 100%;
    max-width: 440px;
    background: linear-gradient(145deg, var(--dark-card), rgba(22,22,31,0.8));
    border: 1px solid var(--dark-border);
    border-radius: 20px;
    padding: 48px 36px;
    position: relative;
    z-index: 1;
}
.auth-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold-dark), var(--gold), var(--gold-dark));
    border-radius: 20px 20px 0 0;
}
.auth-header { text-align: center; margin-bottom: 32px; }
.auth-header h2 { font-family: var(--font-display); font-size: 1.6rem; color: var(--gold-light); margin-bottom: 8px; }
.auth-header p { color: var(--text-muted); font-size: 0.9rem; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 0.8rem; font-weight: 500; color: var(--text-muted); margin-bottom: 8px; letter-spacing: 0.5px; }
.form-group input {
    width: 100%; padding: 14px 16px;
    background: rgba(10,10,15,0.5); border: 1px solid var(--dark-border); border-radius: 10px;
    color: var(--text-bright); font-family: var(--font-body); font-size: 0.95rem;
    transition: all 0.3s ease;
}
.form-group input:focus { outline: none; border-color: rgba(201,168,76,0.4); box-shadow: 0 0 0 3px rgba(201,168,76,0.05); }
.form-group input::placeholder { color: var(--text-muted); opacity: 0.4; }
.btn-auth {
    width: 100%; padding: 14px;
    background: linear-gradient(135deg, var(--gold-dark), var(--gold)); border: none; border-radius: 10px;
    color: var(--dark); font-family: var(--font-body); font-size: 0.9rem; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: all 0.3s; margin-top: 8px;
}
.btn-auth:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(201,168,76,0.3); filter: brightness(1.1); }
.auth-footer { text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--dark-border); }
.auth-footer p { color: var(--text-muted); font-size: 0.85rem; }
.auth-footer a { color: var(--gold); text-decoration: none; font-weight: 600; }
.alert-box { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem; }
.alert-error { background: rgba(192,57,43,0.1); border: 1px solid rgba(192,57,43,0.2); color: var(--red-glow); }
.alert-success { background: rgba(39,174,96,0.1); border: 1px solid rgba(39,174,96,0.2); color: var(--green-glow); }
</style>

<section class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>🔐 Redefinir Senha</h2>
            <p>Crie uma nova senha para sua conta</p>
        </div>

        <?php if ($error): ?>
        <div class="alert-box alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert-box alert-success"><?php echo $success; ?></div>
        <div class="auth-footer">
            <p><a href="pages/downloads.php">🎮 Baixar Jogo e Fazer Login</a></p>
        </div>

        <?php elseif ($validToken): ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="password">Nova Senha (mín. 6 caracteres)</label>
                <input type="password" id="password" name="password" required minlength="6" placeholder="••••••">
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmar Nova Senha</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="6" placeholder="••••••">
            </div>

            <button type="submit" class="btn-auth">Redefinir Senha</button>
        </form>

        <?php endif; ?>

        <?php if (!$success): ?>
        <div class="auth-footer">
            <p><a href="forgot_password.php">Solicitar novo link</a> | <a href="register.php">Criar conta</a></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
