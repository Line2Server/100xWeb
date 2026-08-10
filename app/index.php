<?php
require_once 'includes/config.php';

// Buscar estatísticas do banco
try {
    $db = getDB();

    // Total de contas
    $totalAccounts = $db->query("SELECT COUNT(*) as total FROM accounts")->fetch()['total'];

    // Total de personagens
    $totalChars = $db->query("SELECT COUNT(*) as total FROM characters")->fetch()['total'];

    // Top PvP
    $topPvp = $db->query("
        SELECT c.char_name, c.pvpkills, c.classid, cl.clan_name 
        FROM characters c 
        LEFT JOIN clan_data cl ON c.clanid = cl.clan_id 
        WHERE c.accesslevel = 0 
        ORDER BY c.pvpkills DESC 
        LIMIT 5
    ")->fetchAll();

    // Top PK
    $topPk = $db->query("
        SELECT c.char_name, c.pkkills, c.classid, cl.clan_name 
        FROM characters c 
        LEFT JOIN clan_data cl ON c.clanid = cl.clan_id 
        WHERE c.accesslevel = 0 
        ORDER BY c.pkkills DESC 
        LIMIT 5
    ")->fetchAll();

    // Top Clãs
    $topClans = $db->query("
        SELECT clan_name, clan_level, reputation_score 
        FROM clan_data 
        ORDER BY reputation_score DESC 
        LIMIT 5
    ")->fetchAll();

} catch (Exception $e) {
    $totalAccounts = 0;
    $totalChars = 0;
    $topPvp = [];
    $topPk = [];
    $topClans = [];
}

require_once 'includes/header.php';
?>

<?php require_once 'includes/v01-hero.php'; ?>

<!-- LEGACY HERO SECTION (kept temporarily for backwards-compatible markup) -->
<section class="hero">
    <div class="hero-bg-pattern"></div>
    <div class="hero-content">
        <div class="hero-badge">Servidor Interlude PvP Mid-Rate</div>
        <h1 class="hero-title">
            <span class="line1">ADEN ETERNAL</span>
            <span class="line2">LINEAGE II INTERLUDE</span>
        </h1>
        <p class="hero-desc">
            O melhor servidor PvP Mid-Rate da história. Rates balanceados, economia justa 
            e combate épico te esperam em Aden. Sua jornada começa agora.
        </p>
        <div class="hero-buttons">
            <a href="pages/downloads.php" class="btn btn-primary btn-glow">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Download do Jogo
            </a>
            <a href="<?php echo DISCORD_INVITE; ?>" target="_blank" class="btn btn-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
                Junte-se ao Discord
            </a>
        </div>
    </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-value"><?php echo formatNumber($totalAccounts); ?></div>
            <div class="stat-label">Contas Criadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="stat-value"><?php echo formatNumber($totalChars); ?></div>
            <div class="stat-label">Personagens</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
            </div>
            <div class="stat-value"><?php echo formatNumber(count($topClans)); ?>+</div>
            <div class="stat-label">Clãs Ativos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="stat-value">24/7</div>
            <div class="stat-label">Uptime Garantido</div>
        </div>
    </div>
</div>

<!-- FEATURES SECTION -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Características</span>
            <h2 class="section-title">Por que jogar Aden Eternal?</h2>
            <p class="section-desc">Um servidor pensado para quem ama PvP competitivo e economia balanceada.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">⚔️</div>
                <h3 class="feature-title">PvP Balanceado</h3>
                <p class="feature-desc">Sistema de balance por classe ajustado para competição justa. Todas as classes são viáveis no endgame.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏆</div>
                <h3 class="feature-title">Eventos Automáticos</h3>
                <p class="feature-desc">TvT, CTF, Death Match e Last Man Standing rodando automaticamente com recompensas em Adena e itens exclusivos.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3 class="feature-title">Anti-Cheat HWID</h3>
                <p class="feature-desc">Proteção avançada contra multi-client, bots e cheats. Jogo limpo para todos.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3 class="feature-title">Economia Justa</h3>
                <p class="feature-desc">Adena valiosa, crafting relevante e mercado ativo. Nada de inflação descontrolada.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">Performance Extrema</h3>
                <p class="feature-desc">Engine otimizado com Kotlin Coroutines, Netty e pathfinding avançado. Zero lag em mass PvP.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎁</div>
                <h3 class="feature-title">Sistema de Donate Justo</h3>
                <p class="feature-desc">Itens cosméticos, conveniência e boosts leves. Nada de pay-to-win. Tudo pode ser conquistado in-game.</p>
            </div>
        </div>
    </div>
</section>

<!-- RANKINGS PREVIEW -->
<section class="section rankings-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Rankings</span>
            <h2 class="section-title">Os Guerreiros Mais Fortes</h2>
            <p class="section-desc">Veja quem domina o campo de batalha em Aden.</p>
        </div>

        <div class="rankings-tabs">
            <button class="tab-btn active" data-tab="tab-pvp">Top PvP</button>
            <button class="tab-btn" data-tab="tab-pk">Top PK</button>
            <button class="tab-btn" data-tab="tab-clan">Top Clãs</button>
        </div>

        <!-- Top PvP -->
        <div id="tab-pvp" class="tab-content" style="display: block;">
            <table class="rankings-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jogador</th>
                        <th>Classe</th>
                        <th>Clã</th>
                        <th>PvP Kills</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topPvp as $i => $player): ?>
                    <tr>
                        <td class="rank-pos <?php echo $i < 3 ? 'top'.($i+1) : 'other'; ?>"><?php echo $i + 1; ?></td>
                        <td>
                            <span class="rank-class-icon"><?php echo substr(getClassName($player['classid']), 0, 2); ?></span>
                            <span class="rank-name"><?php echo sanitize($player['char_name']); ?></span>
                        </td>
                        <td><?php echo getClassName($player['classid']); ?></td>
                        <td class="rank-clan"><?php echo $player['clan_name'] ? sanitize($player['clan_name']) : '-'; ?></td>
                        <td class="rank-value"><?php echo formatNumber($player['pvpkills']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($topPvp)): ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px;">Nenhum dado disponível</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Top PK -->
        <div id="tab-pk" class="tab-content" style="display: none;">
            <table class="rankings-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jogador</th>
                        <th>Classe</th>
                        <th>Clã</th>
                        <th>PK Kills</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topPk as $i => $player): ?>
                    <tr>
                        <td class="rank-pos <?php echo $i < 3 ? 'top'.($i+1) : 'other'; ?>"><?php echo $i + 1; ?></td>
                        <td>
                            <span class="rank-class-icon"><?php echo substr(getClassName($player['classid']), 0, 2); ?></span>
                            <span class="rank-name"><?php echo sanitize($player['char_name']); ?></span>
                        </td>
                        <td><?php echo getClassName($player['classid']); ?></td>
                        <td class="rank-clan"><?php echo $player['clan_name'] ? sanitize($player['clan_name']) : '-'; ?></td>
                        <td class="rank-value"><?php echo formatNumber($player['pkkills']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($topPk)): ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px;">Nenhum dado disponível</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Top Clans -->
        <div id="tab-clan" class="tab-content" style="display: none;">
            <table class="rankings-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Clã</th>
                        <th>Level</th>
                        <th>Reputação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topClans as $i => $clan): ?>
                    <tr>
                        <td class="rank-pos <?php echo $i < 3 ? 'top'.($i+1) : 'other'; ?>"><?php echo $i + 1; ?></td>
                        <td><span class="rank-name"><?php echo sanitize($clan['clan_name']); ?></span></td>
                        <td><?php echo $clan['clan_level']; ?></td>
                        <td class="rank-value"><?php echo formatNumber($clan['reputation_score']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($topClans)): ?>
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:40px;">Nenhum dado disponível</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="pages/rankings.php" class="btn btn-secondary">Ver Rankings Completos →</a>
        </div>
    </div>
</section>

<!-- DONATE PREVIEW -->
<section class="section donate-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Apoie o Servidor</span>
            <h2 class="section-title">Pacotes de Doação</h2>
            <p class="section-desc">Contribua com o servidor e receba recompensas exclusivas. 100% do valor é reinvestido.</p>
        </div>

        <div class="donate-grid">
            <div class="donate-card">
                <div class="donate-tier">Bronze</div>
                <div class="donate-price">R$ 10<span>/pacote</span></div>
                <div class="donate-coins">10 Coins + Bônus</div>
                <ul class="donate-features">
                    <li>10 Coins Donate</li>
                    <li>Buff Scrolls x20</li>
                    <li>Teleport Scrolls x10</li>
                    <li class="disabled">VIP Status</li>
                    <li class="disabled">Título Custom</li>
                </ul>
                <a href="pages/donate.php" class="btn btn-secondary" style="width:100%;justify-content:center;">Selecionar</a>
            </div>

            <div class="donate-card">
                <div class="donate-tier">Prata</div>
                <div class="donate-price">R$ 30<span>/pacote</span></div>
                <div class="donate-coins">35 Coins + Bônus</div>
                <ul class="donate-features">
                    <li>35 Coins Donate</li>
                    <li>Armor Set B-Grade</li>
                    <li>Weapon B-Grade</li>
                    <li>VIP Status 3 dias</li>
                    <li class="disabled">Título Custom</li>
                </ul>
                <a href="pages/donate.php" class="btn btn-secondary" style="width:100%;justify-content:center;">Selecionar</a>
            </div>

            <div class="donate-card featured">
                <div class="donate-tier">Ouro</div>
                <div class="donate-price">R$ 50<span>/pacote</span></div>
                <div class="donate-coins">65 Coins + Bônus</div>
                <ul class="donate-features">
                    <li>65 Coins Donate</li>
                    <li>Full Armor A-Grade</li>
                    <li>Weapon A-Grade + SA</li>
                    <li>VIP Status 7 dias</li>
                    <li>Título Colorido</li>
                </ul>
                <a href="pages/donate.php" class="btn btn-primary" style="width:100%;justify-content:center;">Selecionar</a>
            </div>

            <div class="donate-card">
                <div class="donate-tier">Diamante</div>
                <div class="donate-price">R$ 100<span>/pacote</span></div>
                <div class="donate-coins">150 Coins + Bônus</div>
                <ul class="donate-features">
                    <li>150 Coins Donate</li>
                    <li>Full Set S-Grade</li>
                    <li>Weapon S-Grade + SA</li>
                    <li>VIP Status 30 dias</li>
                    <li>Título Custom + Cor</li>
                </ul>
                <a href="pages/donate.php" class="btn btn-secondary" style="width:100%;justify-content:center;">Selecionar</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="section" style="text-align: center; padding: 80px 0;">
    <div class="container">
        <h2 class="section-title" style="margin-bottom: 20px;">Pronto para a Batalha?</h2>
        <p class="section-desc" style="margin-bottom: 30px;">Baixe o cliente, crie sua conta e entre no mundo de Aden. A glória te espera.</p>
        <div class="hero-buttons" style="justify-content: center;">
            <a href="pages/downloads.php" class="btn btn-primary btn-glow">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Baixar Agora
            </a>
            <a href="pages/info.php" class="btn btn-secondary">Como Começar</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
