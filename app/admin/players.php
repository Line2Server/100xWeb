<?php
require_once '../includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php'); exit;
}

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

try {
    $db = getDB();

    // Ações
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['ban_player'])) {
            $charName = sanitize($_POST['char_name']);
            $reason = sanitize($_POST['reason']);
            $duration = intval($_POST['duration']);
            $stmt = $db->prepare("UPDATE characters SET accesslevel = -1 WHERE char_name = ?");
            $stmt->execute([$charName]);
            $message = "Jogador '$charName' banido com sucesso.";
        }
        if (isset($_POST['unban_player'])) {
            $charName = sanitize($_POST['char_name']);
            $stmt = $db->prepare("UPDATE characters SET accesslevel = 0 WHERE char_name = ?");
            $stmt->execute([$charName]);
            $message = "Jogador '$charName' desbanido com sucesso.";
        }
        if (isset($_POST['give_item'])) {
            $charName = sanitize($_POST['char_name']);
            $itemId = intval($_POST['item_id']);
            $count = intval($_POST['count']);
            $stmt = $db->prepare("SELECT obj_Id FROM characters WHERE char_name = ?");
            $stmt->execute([$charName]);
            $char = $stmt->fetch();
            if ($char) {
                $stmt = $db->prepare("INSERT INTO items (owner_id, item_id, count, enchant_level, loc) VALUES (?, ?, ?, 0, 'INVENTORY')");
                $stmt->execute([$char['obj_Id'], $itemId, $count]);
                $message = "Item ID $itemId (x$count) entregue para '$charName'.";
            } else {
                $error = "Personagem não encontrado.";
            }
        }
    }

    // Buscar jogadores
    $search = sanitize($_GET['search'] ?? '');
    $page = max(1, intval($_GET['page'] ?? 1));
    $perPage = 25;
    $offset = ($page - 1) * $perPage;

    $where = '';
    $params = [];
    if ($search) {
        $where = "WHERE c.char_name LIKE ? OR a.login LIKE ?";
        $params = ["%$search%", "%$search%"];
    }

    $stmt = $db->prepare("
        SELECT c.*, a.login, a.lastactive, a.accesslevel as acc_level, cl.clan_name
        FROM characters c
        LEFT JOIN accounts a ON c.account_name = a.login
        LEFT JOIN clan_data cl ON c.clanid = cl.clan_id
        $where
        ORDER BY c.pvpkills DESC
        LIMIT $perPage OFFSET $offset
    ");
    $stmt->execute($params);
    $players = $stmt->fetchAll();

    $countStmt = $db->prepare("SELECT COUNT(*) FROM characters c LEFT JOIN accounts a ON c.account_name = a.login $where");
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();

} catch (Exception $e) {
    $error = "Erro: " . $e->getMessage();
    $players = [];
    $total = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Jogadores - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-layout">
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <svg viewBox="0 0 100 100" width="32" height="32"><polygon points="50,5 95,27.5 95,72.5 50,95 5,72.5 5,27.5" fill="none" stroke="currentColor" stroke-width="2"/><text x="50" y="58" text-anchor="middle" font-size="22" font-weight="bold" fill="currentColor" font-family="Cinzel">E</text></svg>
        <div><span class="brand-name">Admin</span><span class="brand-sub"><?php echo SERVER_NAME; ?></span></div>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
        <a href="players.php" class="nav-item active"><span class="nav-icon">👥</span> Jogadores</a>
        <a href="accounts.php" class="nav-item"><span class="nav-icon">🔑</span> Contas</a>
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
    <header class="admin-header"><h1>Gerenciar Jogadores</h1><div class="header-actions"><span class="header-time"><?php echo date('d/m/Y H:i'); ?></span></div></header>

    <?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>

    <div class="content-card full-width">
        <div class="search-bar">
            <form method="GET" style="display:flex;gap:12px;width:100%;">
                <input type="text" name="search" placeholder="Buscar por nome ou conta..." value="<?php echo $search; ?>">
                <button type="submit">🔍 Buscar</button>
                <?php if ($search): ?><a href="players.php" class="page-btn">Limpar</a><?php endif; ?>
            </form>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead><tr><th>Nome</th><th>Conta</th><th>Level</th><th>Classe</th><th>Clã</th><th>PvP</th><th>PK</th><th>Online</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php foreach ($players as $p): ?>
                    <tr>
                        <td><strong><?php echo sanitize($p['char_name']); ?></strong></td>
                        <td><?php echo sanitize($p['login']); ?></td>
                        <td><?php echo $p['level']; ?></td>
                        <td><?php echo getClassName($p['classid']); ?></td>
                        <td><?php echo $p['clan_name'] ? sanitize($p['clan_name']) : '-'; ?></td>
                        <td class="pvp-value"><?php echo number_format($p['pvpkills']); ?></td>
                        <td style="color:var(--red-glow);"><?php echo number_format($p['pkkills']); ?></td>
                        <td><?php echo $p['online'] ? '<span style="color:var(--green-glow);">🟢 Online</span>' : '<span style="color:var(--text-muted);">⚪ Offline</span>'; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="char_name" value="<?php echo $p['char_name']; ?>">
                                <?php if ($p['accesslevel'] >= 0): ?>
                                <button type="submit" name="ban_player" class="page-btn" style="background:rgba(192,57,43,0.1);color:var(--red-glow);border-color:rgba(192,57,43,0.2);" onclick="return confirm('Banir <?php echo $p['char_name']; ?>?')">Banir</button>
                                <?php else: ?>
                                <button type="submit" name="unban_player" class="page-btn" style="background:rgba(39,174,96,0.1);color:var(--green-glow);border-color:rgba(39,174,96,0.2);">Desbanir</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($players)): ?><tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:40px;">Nenhum jogador encontrado</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($total > $perPage): 
            $totalPages = ceil($total / $perPage);
            echo '<div class="pagination">';
            if ($page > 1) echo '<a href="?page='.($page-1).'&search='.$search.'" class="page-btn">← Anterior</a>';
            for ($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++) {
                echo '<a href="?page='.$i.'&search='.$search.'" class="page-btn '.($i==$page?'active':'').'">'.$i.'</a>';
            }
            if ($page < $totalPages) echo '<a href="?page='.($page+1).'&search='.$search.'" class="page-btn">Próximo →</a>';
            echo '</div>';
        endif; ?>
    </div>

    <!-- Dar Item -->
    <div class="content-card full-width" style="margin-top:24px;">
        <div class="card-header"><h3>🎁 Dar Item para Jogador</h3></div>
        <div class="admin-form">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nome do Personagem</label>
                        <input type="text" name="char_name" required placeholder="Ex: DarkKnight">
                    </div>
                    <div class="form-group">
                        <label>ID do Item</label>
                        <input type="number" name="item_id" required placeholder="Ex: 57 (Adena)">
                    </div>
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="number" name="count" required value="1" min="1">
                    </div>
                    <div class="form-group" style="display:flex;align-items:flex-end;">
                        <button type="submit" name="give_item" class="btn-submit">Entregar Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
</div>
</body>
</html>
