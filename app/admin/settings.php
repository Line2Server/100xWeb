<?php
require_once '../includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php'); exit;
}

$message = ''; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $admin_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    if (!password_verify($current, $admin_hash)) {
        $error = "Senha atual incorreta.";
    } elseif (strlen($new) < 6) {
        $error = "Nova senha deve ter pelo menos 6 caracteres.";
    } elseif ($new !== $confirm) {
        $error = "Senhas não coincidem.";
    } else {
        $newHash = password_hash($new, PASSWORD_BCRYPT);
        $message = "Senha alterada! Novo hash: <code style='background:rgba(201,168,76,0.1);padding:4px 8px;border-radius:4px;color:var(--gold);font-size:0.8rem;'>$newHash</code><br><small style='color:var(--text-muted);'>Copie este hash para o arquivo login.php</small>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Configurações - Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css"></head>
<body>
<div class="admin-layout">
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <svg viewBox="0 0 100 100" width="32" height="32"><polygon points="50,5 95,27.5 95,72.5 50,95 5,72.5 5,27.5" fill="none" stroke="currentColor" stroke-width="2"/><text x="50" y="58" text-anchor="middle" font-size="22" font-weight="bold" fill="currentColor" font-family="Cinzel">E</text></svg>
        <div><span class="brand-name">Admin</span><span class="brand-sub"><?php echo SERVER_NAME; ?></span></div>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
        <a href="players.php" class="nav-item"><span class="nav-icon">👥</span> Jogadores</a>
        <a href="accounts.php" class="nav-item"><span class="nav-icon">🔑</span> Contas</a>
        <a href="clans.php" class="nav-item"><span class="nav-icon">⚔️</span> Clãs</a>
        <a href="bans.php" class="nav-item"><span class="nav-icon">🚫</span> Banimentos</a>
        <a href="donations.php" class="nav-item"><span class="nav-icon">💰</span> Doações</a>
        <a href="news.php" class="nav-item"><span class="nav-icon">📰</span> Notícias</a>
        <a href="settings.php" class="nav-item active"><span class="nav-icon">⚙️</span> Configurações</a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-user"><div class="user-avatar">A</div><div class="user-info"><span class="user-name"><?php echo $_SESSION['admin_user']; ?></span><span class="user-role">Administrador</span></div></div>
        <a href="?logout=1" class="logout-btn"><span>🚪</span> Sair</a>
    </div>
</aside>

<main class="admin-main">
    <header class="admin-header"><h1>Configurações</h1><div class="header-actions"><span class="header-time"><?php echo date('d/m/Y H:i'); ?></span></div></header>

    <?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>

    <div class="content-grid">
        <!-- Alterar Senha -->
        <div class="content-card">
            <div class="card-header"><h3>🔐 Alterar Senha Admin</h3></div>
            <div class="admin-form">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group full">
                            <label>Senha Atual</label>
                            <input type="password" name="current_password" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nova Senha</label>
                            <input type="password" name="new_password" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Confirmar Nova Senha</label>
                            <input type="password" name="confirm_password" required minlength="6">
                        </div>
                    </div>
                    <button type="submit" name="change_password" class="btn-submit">Alterar Senha</button>
                </form>
            </div>
        </div>

        <!-- Info do Sistema -->
        <div class="content-card">
            <div class="card-header"><h3>ℹ️ Informações do Sistema</h3></div>
            <div class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Versão PHP</label>
                        <input type="text" value="<?php echo phpversion(); ?>" readonly style="opacity:0.6;">
                    </div>
                    <div class="form-group">
                        <label>Servidor Web</label>
                        <input type="text" value="<?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido'; ?>" readonly style="opacity:0.6;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>IP do Servidor</label>
                        <input type="text" value="<?php echo SERVER_IP; ?>" readonly style="opacity:0.6;">
                    </div>
                    <div class="form-group">
                        <label>Porta Game</label>
                        <input type="text" value="<?php echo SERVER_PORT; ?>" readonly style="opacity:0.6;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full">
                        <label>Config File</label>
                        <input type="text" value="includes/config.php" readonly style="opacity:0.6;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Links Úteis -->
        <div class="content-card full-width">
            <div class="card-header"><h3>🔗 Links Úteis</h3></div>
            <div class="quick-actions">
                <a href="../" target="_blank" class="action-btn">
                    <span>🌐</span><div><strong>Site Principal</strong><span>Visualizar site público</span></div>
                </a>
                <a href="../api/status.php" target="_blank" class="action-btn">
                    <span>📡</span><div><strong>API Status</strong><span>Ver JSON de status</span></div>
                </a>
                <a href="../pages/rankings.php" target="_blank" class="action-btn">
                    <span>🏆</span><div><strong>Rankings</strong><span>Ver rankings públicos</span></div>
                </a>
            </div>
        </div>
    </div>
</main>
</div>
</body>
</html>
