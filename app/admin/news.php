<?php
require_once '../includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php'); exit;
}

$message = ''; $error = '';

// Criar tabela de notícias se não existir
try {
    $db = getDB();
    $db->exec("
        CREATE TABLE IF NOT EXISTS site_news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            category VARCHAR(50) DEFAULT 'geral',
            author VARCHAR(100) DEFAULT 'Admin',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            is_published TINYINT(1) DEFAULT 1
        )
    ");
} catch (Exception $e) { $error = "Erro ao criar tabela: " . $e->getMessage(); }

// Ações
try {
    $db = getDB();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_news'])) {
            $title = sanitize($_POST['title']);
            $content = $_POST['content'];
            $category = sanitize($_POST['category']);
            $stmt = $db->prepare("INSERT INTO site_news (title, content, category, author) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $category, $_SESSION['admin_user']]);
            $message = "Notícia publicada com sucesso!";
        }
        if (isset($_POST['delete_news'])) {
            $id = intval($_POST['id']);
            $stmt = $db->prepare("DELETE FROM site_news WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Notícia removida.";
        }
        if (isset($_POST['toggle_publish'])) {
            $id = intval($_POST['id']);
            $stmt = $db->prepare("UPDATE site_news SET is_published = NOT is_published WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Status atualizado.";
        }
    }

    $news = $db->query("SELECT * FROM site_news ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) { $error = "Erro: " . $e->getMessage(); $news = []; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Notícias - Admin</title>
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
        <a href="news.php" class="nav-item active"><span class="nav-icon">📰</span> Notícias</a>
        <a href="settings.php" class="nav-item"><span class="nav-icon">⚙️</span> Configurações</a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-user"><div class="user-avatar">A</div><div class="user-info"><span class="user-name"><?php echo $_SESSION['admin_user']; ?></span><span class="user-role">Administrador</span></div></div>
        <a href="?logout=1" class="logout-btn"><span>🚪</span> Sair</a>
    </div>
</aside>

<main class="admin-main">
    <header class="admin-header"><h1>Gerenciar Notícias</h1><div class="header-actions"><span class="header-time"><?php echo date('d/m/Y H:i'); ?></span></div></header>

    <?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>

    <!-- Adicionar Notícia -->
    <div class="content-card full-width" style="margin-bottom:24px;">
        <div class="card-header"><h3>➕ Nova Notícia</h3></div>
        <div class="admin-form">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group full">
                        <label>Título</label>
                        <input type="text" name="title" required placeholder="Título da notícia">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="category">
                            <option value="geral">Geral</option>
                            <option value="evento">Evento</option>
                            <option value="atualizacao">Atualização</option>
                            <option value="manutencao">Manutenção</option>
                            <option value="promocao">Promoção</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full">
                        <label>Conteúdo (suporta HTML básico)</label>
                        <textarea name="content" required placeholder="Escreva o conteúdo da notícia..."></textarea>
                    </div>
                </div>
                <button type="submit" name="add_news" class="btn-submit">Publicar Notícia</button>
            </form>
        </div>
    </div>

    <!-- Lista de Notícias -->
    <div class="content-card full-width">
        <div class="card-header"><h3>📰 Notícias Publicadas (<?php echo count($news); ?>)</h3></div>
        <div class="table-container">
            <table class="admin-table">
                <thead><tr><th>ID</th><th>Título</th><th>Categoria</th><th>Autor</th><th>Data</th><th>Status</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php foreach ($news as $n): ?>
                    <tr>
                        <td><?php echo $n['id']; ?></td>
                        <td><strong><?php echo sanitize($n['title']); ?></strong></td>
                        <td><span style="background:rgba(201,168,76,0.1);padding:4px 10px;border-radius:20px;font-size:0.75rem;color:var(--gold);"><?php echo $n['category']; ?></span></td>
                        <td><?php echo sanitize($n['author']); ?></td>
                        <td><?php echo timeAgo($n['created_at']); ?></td>
                        <td><?php echo $n['is_published'] ? '<span style="color:var(--green-glow);">✅ Publicada</span>' : '<span style="color:var(--text-muted);">📝 Rascunho</span>'; ?></td>
                        <td>
                            <form method="POST" style="display:inline;gap:4px;">
                                <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                                <button type="submit" name="toggle_publish" class="page-btn" style="background:rgba(41,128,185,0.1);color:var(--blue-glow);"><?php echo $n['is_published'] ? 'Ocultar' : 'Publicar'; ?></button>
                                <button type="submit" name="delete_news" class="page-btn" style="background:rgba(192,57,43,0.1);color:var(--red-glow);" onclick="return confirm('Remover notícia?')">Remover</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($news)): ?><tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px;">Nenhuma notícia publicada</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</div>
</body>
</html>
