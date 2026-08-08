<?php
require_once __DIR__ . '/config.php';

// Verificar status do servidor
function checkServerStatus() {
    $socket = @fsockopen(SERVER_IP, SERVER_PORT, $errno, $errstr, 2);
    if ($socket) {
        fclose($socket);
        return ['online' => true, 'players' => getOnlineCount()];
    }
    return ['online' => false, 'players' => 0];
}

function getOnlineCount() {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT COUNT(*) as total FROM characters WHERE online = 1");
        return $stmt->fetch()['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

$serverStatus = checkServerStatus();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SERVER_NAME; ?> - <?php echo SERVER_CHRONICLE; ?> PvP Server</title>
    <meta name="description" content="<?php echo SERVER_NAME; ?> - Melhor servidor <?php echo SERVER_CHRONICLE; ?> PvP Mid-Rate. <?php echo SERVER_RATES; ?>">
    <meta name="keywords" content="Lineage 2, L2, Interlude, PvP, Mid-Rate, Servidor BR, MMORPG">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css?v=3">
</head>
<body>
    <!-- Particles Background -->
    <div id="particles"></div>

    <!-- Server Status Bar -->
    <div class="status-bar">
        <div class="container">
            <div class="status-left">
                <span class="status-indicator <?php echo $serverStatus['online'] ? 'online' : 'offline'; ?>">
                    <span class="pulse"></span>
                    <?php echo $serverStatus['online'] ? 'ONLINE' : 'OFFLINE'; ?>
                </span>
                <span class="separator">|</span>
                <span class="players-count">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <?php echo formatNumber($serverStatus['players']); ?> Online
                </span>
                <span class="separator">|</span>
                <span class="rates-info"><?php echo SERVER_RATES; ?></span>
            </div>
            <div class="status-right">
                <a href="<?php echo DISCORD_INVITE; ?>" target="_blank" class="social-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg>
                    Discord
                </a>
                <a href="<?php echo WHATSAPP_GROUP; ?>" target="_blank" class="social-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-bg"></div>
        <div class="container nav-container">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 100 100" width="40" height="40">
                        <polygon points="50,5 95,27.5 95,72.5 50,95 5,72.5 5,27.5" fill="none" stroke="currentColor" stroke-width="2"/>
                        <polygon points="50,15 85,32.5 85,67.5 50,85 15,67.5 15,32.5" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.6"/>
                        <text x="50" y="58" text-anchor="middle" font-size="28" font-weight="bold" fill="currentColor" font-family="Cinzel">E</text>
                    </svg>
                </div>
                <div class="logo-text">
                    <span class="logo-main">ETERNAL</span>
                    <span class="logo-sub">WAR</span>
                </div>
            </a>

            <button class="mobile-toggle" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>

            <ul class="nav-menu">
                <li><a href="/" class="nav-link active">Início</a></li>
                <li><a href="/pages/rankings.php" class="nav-link">Rankings</a></li>
                <li><a href="/pages/donate.php" class="nav-link">Donate</a></li>
                <li><a href="/pages/downloads.php" class="nav-link">Downloads</a></li>
                <li><a href="/pages/info.php" class="nav-link">Informações</a></li>
                <li><a href="/pages/roadmap.php" class="nav-link">Roadmap</a></li>
                <li><a href="<?php echo DISCORD_INVITE; ?>" target="_blank" class="nav-link nav-cta">Discord</a></li>
                <li><a href="/register.php" class="nav-link" style="color:var(--green-glow);">📝 Registrar</a></li>
            </ul>
        </div>
    </nav>
