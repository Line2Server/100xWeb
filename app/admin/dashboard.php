<?php
/**
 * Painel Administrativo - Dashboard
 */
require_once '../includes/config.php';

session_start();

// Verificar login
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit;
}

// Verificar timeout
if (time() - ($_SESSION['admin_login_time'] ?? 0) > SESSION_TIMEOUT) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Atualizar timestamp
$_SESSION['admin_login_time'] = time();

// Buscar estatísticas
try {
    $db = getDB();

    $stats = [
        'accounts' => $db->query("SELECT COUNT(*) FROM accounts")->fetchColumn(),
        'characters' => $db->query("SELECT COUNT(*) FROM characters")->fetchColumn(),
        'online' => $db->query("SELECT COUNT(*) FROM characters WHERE online = 1")->fetchColumn(),
        'clans' => $db->query("SELECT COUNT(*) FROM clan_data")->fetchColumn(),
        'accounts_today' => $db->query("SELECT COUNT(*) FROM accounts WHERE created_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn(),
        'chars_today' => $db->query("SELECT COUNT(*) FROM characters WHERE createtime >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))")->fetchColumn(),
        'banned' => $db->query("SELECT COUNT(*) FROM accounts WHERE accessLevel < 0")->fetchColumn(),
        'top_pvp' => $db->query("SELECT char_name, pvpkills, classid FROM characters WHERE accesslevel = 0 ORDER BY pvpkills DESC LIMIT 10")->fetchAll(),
        'recent_accounts' => $db->query("SELECT login, created_time, lastactive FROM accounts ORDER BY created_time DESC LIMIT 10")->fetchAll(),
        'online_players' => $db->query("
            SELECT c.char_name, c.level, c.classid, c.pvpkills, c.exp, cl.clan_name, a.lastactive
            FROM characters c
            LEFT JOIN clan_data cl ON c.clanid = cl.clan_id
            LEFT JOIN accounts a ON c.account_name = a.login
            WHERE c.online = 1
            ORDER BY c.pvpkills DESC
            LIMIT 20
        ")->fetchAll(),
    ];

} catch (Exception $e) {
    $stats = ['error' => $e->getMessage()];
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - <?php echo SERVER_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <svg viewBox="0 0 100 100" width="32" height="32">
                    <polygon points="50,5 95,27.5 95,72.5 50,95 5,72.5 5,27.5" fill="none" stroke="currentColor" stroke-width="2"/>
                    <text x="50" y="58" text-anchor="middle" font-size="22" font-weight="bold" fill="currentColor" font-family="Cinzel">E</text>
                </svg>
                <div>
                    <span class="brand-name">Admin</span>
                    <span class="brand-sub"><?php echo SERVER_NAME; ?></span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
                <a href="players.php" class="nav-item">
                    <span class="nav-icon">👥</span> Jogadores
                </a>
                <a href="accounts.php" class="nav-item">
                    <span class="nav-icon">🔑</span> Contas
                </a>
                <a href="clans.php" class="nav-item">
                    <span class="nav-icon">⚔️</span> Clãs
                </a>
                <a href="bans.php" class="nav-item">
                    <span class="nav-icon">🚫</span> Banimentos
                </a>
                <a href="donations.php" class="nav-item">
                    <span class="nav-icon">💰</span> Doações
                </a>
                <a href="news.php" class="nav-item">
                    <span class="nav-icon">📰</span> Notícias
                </a>
                <a href="settings.php" class="nav-item">
                    <span class="nav-icon">⚙️</span> Configurações
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-user">
                    <div class="user-avatar">A</div>
                    <div class="user-info">
                        <span class="user-name"><?php echo $_SESSION['admin_user']; ?></span>
                        <span class="user-role">Administrador</span>
                    </div>
                </div>
                <a href="?logout=1" class="logout-btn">
                    <span>🚪</span> Sair
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="header-actions">
                    <span class="server-status-badge <?php echo $stats['online'] > 0 ? 'online' : 'offline'; ?>">
                        <span class="status-dot"></span>
                        <?php echo $stats['online'] > 0 ? 'Servidor Online' : 'Servidor Offline'; ?>
                    </span>
                    <span class="header-time"><?php echo date('d/m/Y H:i'); ?></span>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-row">
                <div class="stat-card admin-stat">
                    <div class="stat-icon-bg" style="background: rgba(201,168,76,0.1); color: var(--gold);">👤</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo number_format($stats['accounts'] ?? 0); ?></span>
                        <span class="stat-label">Total de Contas</span>
                    </div>
                    <span class="stat-trend <?php echo ($stats['accounts_today'] ?? 0) > 0 ? 'up' : ''; ?>">
                        +<?php echo $stats['accounts_today'] ?? 0; ?> hoje
                    </span>
                </div>

                <div class="stat-card admin-stat">
                    <div class="stat-icon-bg" style="background: rgba(41,128,185,0.1); color: var(--blue);">🎮</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo number_format($stats['characters'] ?? 0); ?></span>
                        <span class="stat-label">Personagens</span>
                    </div>
                    <span class="stat-trend <?php echo ($stats['chars_today'] ?? 0) > 0 ? 'up' : ''; ?>">
                        +<?php echo $stats['chars_today'] ?? 0; ?> hoje
                    </span>
                </div>

                <div class="stat-card admin-stat">
                    <div class="stat-icon-bg" style="background: rgba(39,174,96,0.1); color: var(--green);">🟢</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo number_format($stats['online'] ?? 0); ?></span>
                        <span class="stat-label">Online Agora</span>
                    </div>
                    <span class="stat-trend">AO VIVO</span>
                </div>

                <div class="stat-card admin-stat">
                    <div class="stat-icon-bg" style="background: rgba(142,68,173,0.1); color: var(--purple);">🏰</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo number_format($stats['clans'] ?? 0); ?></span>
                        <span class="stat-label">Clãs Ativos</span>
                    </div>
                </div>

                <div class="stat-card admin-stat">
                    <div class="stat-icon-bg" style="background: rgba(192,57,43,0.1); color: var(--red);">🚫</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo number_format($stats['banned'] ?? 0); ?></span>
                        <span class="stat-label">Banimentos</span>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Online Players -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>🟢 Jogadores Online (<?php echo count($stats['online_players'] ?? []); ?>)</h3>
                        <a href="players.php" class="card-link">Ver todos →</a>
                    </div>
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Level</th>
                                    <th>Classe</th>
                                    <th>Clã</th>
                                    <th>PvP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['online_players'] ?? [] as $p): ?>
                                <tr>
                                    <td><strong><?php echo sanitize($p['char_name']); ?></strong></td>
                                    <td><?php echo $p['level']; ?></td>
                                    <td><?php echo getClassName($p['classid']); ?></td>
                                    <td><?php echo $p['clan_name'] ? sanitize($p['clan_name']) : '-'; ?></td>
                                    <td><?php echo number_format($p['pvpkills']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($stats['online_players'])): ?>
                                <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:30px;">Nenhum jogador online</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top PvP -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>🏆 Top PvP</h3>
                        <a href="players.php?tab=pvp" class="card-link">Ver ranking →</a>
                    </div>
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr><th>#</th><th>Jogador</th><th>Classe</th><th>PvP</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['top_pvp'] ?? [] as $i => $p): ?>
                                <tr>
                                    <td class="rank-<?php echo $i+1; ?>"><?php echo $i+1; ?></td>
                                    <td><strong><?php echo sanitize($p['char_name']); ?></strong></td>
                                    <td><?php echo getClassName($p['classid']); ?></td>
                                    <td class="pvp-value"><?php echo number_format($p['pvpkills']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Accounts -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>🆕 Contas Recentes</h3>
                        <a href="accounts.php" class="card-link">Ver todas →</a>
                    </div>
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr><th>Login</th><th>Criada</th><th>Último Login</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['recent_accounts'] ?? [] as $a): ?>
                                <tr>
                                    <td><strong><?php echo sanitize($a['login']); ?></strong></td>
                                    <td><?php echo timeAgo($a['created_time']); ?></td>
                                    <td><?php echo $a['lastactive'] ? timeAgo($a['lastactive']) : 'Nunca'; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>⚡ Ações Rápidas</h3>
                    </div>
                    <div class="quick-actions">
                        <a href="players.php?action=ban" class="action-btn ban">
                            <span>🚫</span>
                            <div>
                                <strong>Banir Jogador</strong>
                                <span>Banir por HWID ou conta</span>
                            </div>
                        </a>
                        <a href="players.php?action=give" class="action-btn give">
                            <span>🎁</span>
                            <div>
                                <strong>Dar Item</strong>
                                <span>Entregar item para jogador</span>
                            </div>
                        </a>
                        <a href="news.php?action=add" class="action-btn news">
                            <span>📰</span>
                            <div>
                                <strong>Nova Notícia</strong>
                                <span>Publicar aviso no site</span>
                            </div>
                        </a>
                        <a href="settings.php?tab=maintenance" class="action-btn maintenance">
                            <span>🔧</span>
                            <div>
                                <strong>Manutenção</strong>
                                <span>Ativar modo manutenção</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    // Auto-refresh a cada 30s
    setInterval(() => {
        fetch('api/refresh.php')
            .then(r => r.json())
            .then(data => {
                if (data.success) location.reload();
            })
            .catch(() => {});
    }, 30000);
    </script>
</body>
</html>
