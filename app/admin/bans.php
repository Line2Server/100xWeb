<?php
require_once '../includes/config.php';
session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Banimentos - Admin</title>
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
        <a href="bans.php" class="nav-item active"><span class="nav-icon">🚫</span> Banimentos</a>
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
    <header class="admin-header"><h1>Banimentos</h1><div class="header-actions"><span class="header-time"><?php echo date('d/m/Y H:i'); ?></span></div></header>
    <div class="content-card full-width">
        <div style="padding:60px;text-align:center;color:var(--text-muted);">
            <div style="font-size:3rem;margin-bottom:16px;">🚫</div>
            <h3 style="color:var(--text-bright);margin-bottom:8px;font-family:var(--font-display);">Banimentos</h3>
            <p>Esta funcionalidade está em desenvolvimento. Use o Dashboard para gerenciar o servidor.</p>
            <a href="dashboard.php" class="btn-submit" style="display:inline-block;margin-top:20px;text-decoration:none;">Voltar ao Dashboard</a>
        </div>
    </div>
</main>
</div>
</body>
</html>
