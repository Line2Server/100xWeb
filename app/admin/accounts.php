<?php
require_once '../includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php'); exit;
}

$message = ''; $error = '';
try {
    $db = getDB();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['ban_account'])) {
            $login = sanitize($_POST['login']);
            $stmt = $db->prepare("UPDATE accounts SET accessLevel = -100 WHERE login = ?");
            $stmt->execute([$login]);
            $message = "Conta '$login' banida.";
        }
        if (isset($_POST['unban_account'])) {
            $login = sanitize($_POST['login']);
            $stmt = $db->prepare("UPDATE accounts SET accessLevel = 0 WHERE login = ?");
            $stmt->execute([$login]);
            $message = "Conta '$login' desbanida.";
        }
        if (isset($_POST['reset_password'])) {
            $login = sanitize($_POST['login']);
            $newPass = password_hash('123456', PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE accounts SET password = ? WHERE login = ?");
            $stmt->execute([$newPass, $login]);
            $message = "Senha da conta '$login' resetada para: 123456";
        }
    }

    $search = sanitize($_GET['search'] ?? '');
    $page = max(1, intval($_GET['page'] ?? 1));
    $perPage = 25; $offset = ($page - 1) * $perPage;

    $where = ''; $params = [];
    if ($search) { $where = "WHERE login LIKE ?"; $params = ["%$search%"]; }

    $stmt = $db->prepare("SELECT login, lastactive, created_time, accessLevel, (SELECT COUNT(*) FROM characters WHERE account_name = accounts.login) as char_count FROM accounts $where ORDER BY created_time DESC LIMIT $perPage OFFSET $offset");
    $stmt->execute($params);
    $accounts = $stmt->fetchAll();

    $countStmt = $db->prepare("SELECT COUNT(*) FROM accounts $where");
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();

} catch (Exception $e) { $error = "Erro: " . $e->getMessage(); $accounts = []; $total = 0; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Contas - Admin</title>
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
        <a href="accounts.php" class="nav-item active"><span class="nav-icon">🔑</span> Contas</a>
        <a href="clans.php" class="nav-item"><span class="nav-icon">⚔️</span> Clãs</a>
        <a href="bans.php" class="nav-item"><span class="nav-icon">🚫</span> Banimentos</a>
        <a href="donations.php" class="nav-item"><span class="nav-icon">💰</span> Doações</a>
        <a href="news.php" class="nav-item"><span class="nav-icon">📰</span> Notícias</a>
        <a href="settings.php" class="nav-item"><span class="nav-icon">⚙️</span> Configurações</a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-user"><div class="user-avatar">A</div><div class="user-info"><span class="user-name"><?php echo $_SESSION['admin_user']; ?></span><span class="user-role">Administrador</span></div></div>
        <a href="?logout=1" class="logout-btn"><span>🚪</span> Sair</a>
    </div>
</aside>

<main class="admin-main">
    <header class="admin-header"><h1>Gerenciar Contas</h1><div class="header-actions"><span class="header-time"><?php echo date('d/m/Y H:i'); ?></span></div></header>

    <?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>

    <div class="content-card full-width">
        <div class="search-bar">
            <form method="GET" style="display:flex;gap:12px;width:100%;">
                <input type="text" name="search" placeholder="Buscar por login..." value="<?php echo $search; ?>">
                <button type="submit">🔍 Buscar</button>
                <?php if ($search): ?><a href="accounts.php" class="page-btn">Limpar</a><?php endif; ?>
            </form>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead><tr><th>Login</th><th>Criada</th><th>Último Login</th><th>Personagens</th><th>Status</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php foreach ($accounts as $a): ?>
                    <tr>
                        <td><strong><?php echo sanitize($a['login']); ?></strong></td>
                        <td><?php echo timeAgo($a['created_time']); ?></td>
                        <td><?php echo $a['lastactive'] ? timeAgo($a['lastactive']) : 'Nunca'; ?></td>
                        <td><?php echo $a['char_count']; ?></td>
                        <td><?php echo $a['accessLevel'] < 0 ? '<span style="color:var(--red-glow);">🚫 Banida</span>' : '<span style="color:var(--green-glow);">✅ Ativa</span>'; ?></td>
                        <td>
                            <form method="POST" style="display:inline;gap:4px;">
                                <input type="hidden" name="login" value="<?php echo $a['login']; ?>">
                                <?php if ($a['accessLevel'] >= 0): ?>
                                <button type="submit" name="ban_account" class="page-btn" style="background:rgba(192,57,43,0.1);color:var(--red-glow);" onclick="return confirm('Banir <?php echo $a['login']; ?>?')">Banir</button>
                                <?php else: ?>
                                <button type="submit" name="unban_account" class="page-btn" style="background:rgba(39,174,96,0.1);color:var(--green-glow);">Desbanir</button>
                                <?php endif; ?>
                                <button type="submit" name="reset_password" class="page-btn" style="background:rgba(41,128,185,0.1);color:var(--blue-glow);" onclick="return confirm('Resetar senha de <?php echo $a['login']; ?>?')">Resetar Senha</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($accounts)): ?><tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px;">Nenhuma conta encontrada</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($total > $perPage): 
            $totalPages = ceil($total / $perPage);
            echo '<div class="pagination">';
            if ($page > 1) echo '<a href="?page='.($page-1).'&search='.$search.'" class="page-btn">←</a>';
            for ($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++) {
                echo '<a href="?page='.$i.'&search='.$search.'" class="page-btn '.($i==$page?'active':'').'">'.$i.'</a>';
            }
            if ($page < $totalPages) echo '<a href="?page='.($page+1).'&search='.$search.'" class="page-btn">→</a>';
            echo '</div>';
        endif; ?>
    </div>
</main>
</div>
</body>
</html>
