<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $email = trim($_POST['email'] ?? '');

    // Validações
    if (strlen($login) < 4 || strlen($login) > 16) {
        $error = 'O login deve ter entre 4 e 16 caracteres.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        $error = 'O login deve conter apenas letras, números e underscores.';
    } elseif (strlen($password) < 6) {
        $error = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'As senhas não coincidem.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'E-mail inválido.';
    } else {
        try {
            $db = getDB();

            // Verificar se login já existe
            $stmt = $db->prepare("SELECT login FROM accounts WHERE login = ?");
            $stmt->execute([$login]);
            if ($stmt->fetch()) {
                $error = 'Este login já está em uso.';
            } else {
                // Verificar se e-mail já existe
                $stmt = $db->prepare("SELECT login FROM accounts WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Este e-mail já está cadastrado.';
                } else {
                    // Verificar se coluna email existe na tabela accounts
                    $columns = $db->query("SHOW COLUMNS FROM accounts LIKE 'email'")->fetchAll();

                    // Hash da senha (BR Project usa SHA1 ou bcrypt dependendo da config)
                    // Vamos usar SHA1 para compatibilidade com o jogo
                    $passHash = sha1($password);

                    if (empty($columns)) {
                        // Sem coluna email - inserir sem email
                        $stmt = $db->prepare("INSERT INTO accounts (login, password, lastactive, accessLevel) VALUES (?, ?, NOW(), 0)");
                        $stmt->execute([$login, $passHash]);
                    } else {
                        // Com coluna email
                        $stmt = $db->prepare("INSERT INTO accounts (login, password, email, lastactive, accessLevel) VALUES (?, ?, ?, NOW(), 0)");
                        $stmt->execute([$login, $passHash, $email]);
                    }

                    $success = "Conta criada com sucesso! Você já pode fazer login no jogo com o login <strong>$login</strong>.";

                    // Enviar e-mail de boas-vindas
                    $emailBody = '
                        <h2 style="color:#c9a84c;margin-bottom:20px;">Bem-vindo a ' . SERVER_NAME . '!</h2>
                        <p>Sua conta foi criada com sucesso. Aqui estão seus dados:</p>
                        <div style="background:rgba(201,168,76,0.05);border:1px solid rgba(201,168,76,0.15);border-radius:10px;padding:20px;margin:20px 0;">
                            <p style="margin:0;"><strong>Login:</strong> ' . htmlspecialchars($login) . '</p>
                            <p style="margin:8px 0 0;"><strong>Servidor:</strong> ' . SERVER_IP . ':' . SERVER_PORT . '</p>
                        </div>
                        <p>Baixe o cliente em nosso site e comece sua jornada em Aden!</p>
                        <p style="margin-top:20px;"><a href="' . (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/pages/downloads.php" style="display:inline-block;background:linear-gradient(135deg,#8b6914,#c9a84c);color:#0a0a0f;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:700;">📥 Baixar Jogo</a></p>
                    ';
                    sendEmail($email, 'Bem-vindo a ' . SERVER_NAME . '!', $emailBody);
                }
            }
        } catch (Exception $e) {
            $error = 'Erro ao criar conta. Tente novamente.';
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
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold-dark), var(--gold), var(--gold-dark));
    border-radius: 20px 20px 0 0;
}
.auth-header {
    text-align: center;
    margin-bottom: 32px;
}
.auth-header h2 {
    font-family: var(--font-display);
    font-size: 1.6rem;
    color: var(--gold-light);
    margin-bottom: 8px;
}
.auth-header p {
    color: var(--text-muted);
    font-size: 0.9rem;
}
.form-group {
    margin-bottom: 20px;
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
.form-group input::placeholder { color: var(--text-muted); opacity: 0.4; }
.password-strength {
    height: 4px;
    background: var(--dark-border);
    border-radius: 2px;
    margin-top: 8px;
    overflow: hidden;
}
.password-strength-bar {
    height: 100%;
    width: 0;
    border-radius: 2px;
    transition: all 0.3s;
}
.password-strength-bar.weak { width: 33%; background: var(--red); }
.password-strength-bar.medium { width: 66%; background: var(--gold); }
.password-strength-bar.strong { width: 100%; background: var(--green-glow); }
.password-hint {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 6px;
}
.btn-auth {
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
    transition: all 0.3s;
    margin-top: 8px;
}
.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(201,168,76,0.3);
    filter: brightness(1.1);
}
.auth-footer {
    text-align: center;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--dark-border);
}
.auth-footer p {
    color: var(--text-muted);
    font-size: 0.85rem;
}
.auth-footer a {
    color: var(--gold);
    text-decoration: none;
    font-weight: 600;
}
.auth-footer a:hover { text-decoration: underline; }
.alert-box {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 0.9rem;
}
.alert-error {
    background: rgba(192,57,43,0.1);
    border: 1px solid rgba(192,57,43,0.2);
    color: var(--red-glow);
}
.alert-success {
    background: rgba(39,174,96,0.1);
    border: 1px solid rgba(39,174,96,0.2);
    color: var(--green-glow);
}
</style>

<section class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Criar Conta</h2>
            <p>Registre-se e comece sua jornada em Aden</p>
        </div>

        <?php if ($error): ?>
        <div class="alert-box alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert-box alert-success"><?php echo $success; ?></div>
        <?php else: ?>

        <form method="POST" action="" id="registerForm">
            <div class="form-group">
                <label for="login">Login (4-16 caracteres)</label>
                <input type="text" id="login" name="login" required minlength="4" maxlength="16" 
                       pattern="[a-zA-Z0-9_]+" placeholder="Ex: DarkKnight_99"
                       value="<?php echo isset($_POST['login']) ? sanitize($_POST['login']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com"
                       value="<?php echo isset($_POST['email']) ? sanitize($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Senha (mín. 6 caracteres)</label>
                <input type="password" id="password" name="password" required minlength="6" placeholder="••••••">
                <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                <p class="password-hint" id="strengthHint">Digite uma senha forte</p>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmar Senha</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="6" placeholder="••••••">
            </div>

            <button type="submit" class="btn-auth">Criar Conta</button>
        </form>

        <?php endif; ?>

        <div class="auth-footer">
            <p>Já tem uma conta? <a href="pages/downloads.php">Baixe o jogo e faça login</a></p>
            <p style="margin-top:8px;"><a href="forgot_password.php">Esqueceu a senha?</a></p>
        </div>
    </div>
</section>

<script>
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('strengthBar');
    const hint = document.getElementById('strengthHint');
    let strength = 0;

    if (val.length >= 6) strength++;
    if (val.length >= 10) strength++;
    if (/[A-Z]/.test(val)) strength++;
    if (/[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;

    bar.className = 'password-strength-bar';
    if (strength <= 2) {
        bar.classList.add('weak');
        hint.textContent = 'Senha fraca - adicione números e caracteres especiais';
        hint.style.color = 'var(--red-glow)';
    } else if (strength <= 4) {
        bar.classList.add('medium');
        hint.textContent = 'Senha média - boa, mas pode ser melhor';
        hint.style.color = 'var(--gold)';
    } else {
        bar.classList.add('strong');
        hint.textContent = 'Senha forte - excelente!';
        hint.style.color = 'var(--green-glow)';
    }
});

document.getElementById('registerForm').addEventListener('submit', function(e) {
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    if (pass !== confirm) {
        e.preventDefault();
        alert('As senhas não coincidem!');
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
