<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($login) || empty($email)) {
        $error = 'Preencha todos os campos.';
    } else {
        try {
            $db = getDB();

            // Verificar se login e email existem e correspondem
            $stmt = $db->prepare("SELECT login, email FROM accounts WHERE login = ? AND email = ?");
            $stmt->execute([$login, $email]);
            $account = $stmt->fetch();

            if (!$account) {
                // Verificar se existe apenas o login (sem email na tabela)
                $stmt = $db->prepare("SELECT login FROM accounts WHERE login = ?");
                $stmt->execute([$login]);
                $accountNoEmail = $stmt->fetch();

                if ($accountNoEmail) {
                    $error = 'Esta conta não possui e-mail cadastrado. Entre em contato com o suporte via Discord.';
                } else {
                    $error = 'Login ou e-mail incorretos.';
                }
            } else {
                // Criar tabela de tokens se não existir
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

                // Gerar token
                $token = generateToken(32);
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Salvar token
                $stmt = $db->prepare("INSERT INTO password_resets (login, email, token, expires_at) VALUES (?, ?, ?, ?)");
                $stmt->execute([$login, $email, $token, $expires]);

                // Enviar e-mail
                $resetUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $token;

                $emailBody = '
                    <h2 style="color:#c9a84c;margin-bottom:20px;">Recuperação de Senha</h2>
                    <p>Olá, <strong>' . htmlspecialchars($login) . '</strong>!</p>
                    <p>Recebemos uma solicitação para redefinir sua senha em <strong>' . SERVER_NAME . '</strong>.</p>
                    <p>Clique no botão abaixo para criar uma nova senha. O link expira em <strong>1 hora</strong>.</p>
                    <p style="margin:24px 0;text-align:center;">
                        <a href="' . $resetUrl . '" style="display:inline-block;background:linear-gradient(135deg,#8b6914,#c9a84c);color:#0a0a0f;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:700;font-size:1rem;">🔐 Redefinir Senha</a>
                    </p>
                    <p style="color:#6b6b7b;font-size:0.85rem;">Se você não solicitou esta recuperação, ignore este e-mail.</p>
                    <p style="color:#6b6b7b;font-size:0.85rem;margin-top:8px;">Se o botão não funcionar, copie e cole este link no navegador:<br><code style="background:rgba(201,168,76,0.1);padding:4px 8px;border-radius:4px;color:#c9a84c;word-break:break-all;">' . $resetUrl . '</code></p>
                ';

                if (sendEmail($email, 'Recuperação de Senha - ' . SERVER_NAME, $emailBody)) {
                    $success = "E-mail de recuperação enviado para <strong>" . sanitize($email) . "</strong>. Verifique sua caixa de entrada e spam.";
                } else {
                    $error = 'Erro ao enviar e-mail. Tente novamente mais tarde ou contate o suporte.';
                }
            }
        } catch (Exception $e) {
            $error = 'Erro no sistema. Tente novamente.';
        }
    }
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
.auth-footer a:hover { text-decoration: underline; }
.alert-box { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem; }
.alert-error { background: rgba(192,57,43,0.1); border: 1px solid rgba(192,57,43,0.2); color: var(--red-glow); }
.alert-success { background: rgba(39,174,96,0.1); border: 1px solid rgba(39,174,96,0.2); color: var(--green-glow); }
.info-text { color: var(--text-muted); font-size: 0.85rem; line-height: 1.7; margin-bottom: 20px; padding: 16px; background: rgba(201,168,76,0.03); border-radius: 10px; border: 1px solid rgba(201,168,76,0.08); }
</style>

<section class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>🔐 Recuperar Senha</h2>
            <p>Informe seus dados para receber o link de redefinição</p>
        </div>

        <?php if ($error): ?>
        <div class="alert-box alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert-box alert-success"><?php echo $success; ?></div>
        <?php else: ?>

        <p class="info-text">
            Digite o <strong>login</strong> e <strong>e-mail</strong> cadastrados na sua conta. Enviaremos um link seguro para redefinir sua senha.
        </p>

        <form method="POST" action="">
            <div class="form-group">
                <label for="login">Login da Conta</label>
                <input type="text" id="login" name="login" required placeholder="Seu login no jogo">
            </div>

            <div class="form-group">
                <label for="email">E-mail Cadastrado</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>

            <button type="submit" class="btn-auth">Enviar Link de Recuperação</button>
        </form>

        <?php endif; ?>

        <div class="auth-footer">
            <p>Lembrou a senha? <a href="register.php">Criar conta</a> ou <a href="pages/downloads.php">Baixar jogo</a></p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
