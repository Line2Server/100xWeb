<?php
require_once '../includes/config.php';

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 25;
$offset = ($page - 1) * $perPage;

$tab = isset($_GET['tab']) ? sanitize($_GET['tab']) : 'pvp';

try {
    $db = getDB();

    // Top PvP
    $stmt = $db->prepare("
        SELECT c.char_name, c.pvpkills, c.pkkills, c.classid, c.level, c.online,
               cl.clan_name, c.exp 
        FROM characters c 
        LEFT JOIN clan_data cl ON c.clanid = cl.clan_id 
        WHERE c.accesslevel = 0 
        ORDER BY c.pvpkills DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $pvpData = $stmt->fetchAll();

    $pvpTotal = $db->query("SELECT COUNT(*) as total FROM characters WHERE accesslevel = 0")->fetch()['total'];

    // Top PK
    $stmt = $db->prepare("
        SELECT c.char_name, c.pkkills, c.pvpkills, c.classid, c.level, c.online,
               cl.clan_name, c.exp 
        FROM characters c 
        LEFT JOIN clan_data cl ON c.clanid = cl.clan_id 
        WHERE c.accesslevel = 0 
        ORDER BY c.pkkills DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $pkData = $stmt->fetchAll();

    // Top Clãs
    $stmt = $db->prepare("
        SELECT clan_name, clan_level, reputation_score, hasCastle, ally_name 
        FROM clan_data 
        ORDER BY reputation_score DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $clanData = $stmt->fetchAll();

    $clanTotal = $db->query("SELECT COUNT(*) as total FROM clan_data")->fetch()['total'];

    // Top Online Time
    $stmt = $db->prepare("
        SELECT c.char_name, c.classid, c.level, cl.clan_name,
               TIMESTAMPDIFF(HOUR, c.lastAccess, NOW()) as offline_hours
        FROM characters c 
        LEFT JOIN clan_data cl ON c.clanid = cl.clan_id 
        WHERE c.accesslevel = 0 
        ORDER BY c.onlinetime DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $onlineData = $stmt->fetchAll();

} catch (Exception $e) {
    $pvpData = $pkData = $clanData = $onlineData = [];
    $pvpTotal = $clanTotal = 0;
}

function renderPagination($total, $perPage, $currentPage, $tab) {
    $totalPages = ceil($total / $perPage);
    if ($totalPages <= 1) return '';

    $html = '<div class="pagination">';

    if ($currentPage > 1) {
        $html .= '<a href="?tab='.$tab.'&page='.($currentPage-1).'" class="page-btn">← Anterior</a>';
    }

    for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) {
        $active = $i == $currentPage ? 'active' : '';
        $html .= '<a href="?tab='.$tab.'&page='.$i.'" class="page-btn '.$active.'">'.$i.'</a>';
    }

    if ($currentPage < $totalPages) {
        $html .= '<a href="?tab='.$tab.'&page='.($currentPage+1).'" class="page-btn">Próximo →</a>';
    }

    $html .= '</div>';
    return $html;
}

require_once '../includes/header.php';
?>

<style>
.pagination { display: flex; justify-content: center; gap: 8px; margin-top: 30px; flex-wrap: wrap; }
.page-btn { padding: 8px 16px; background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.85rem; transition: var(--transition); }
.page-btn:hover, .page-btn.active { background: rgba(201,168,76,0.1); border-color: rgba(201,168,76,0.3); color: var(--gold); }
.online-indicator { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: var(--green-glow); box-shadow: 0 0 6px var(--green-glow); margin-right: 6px; }
.online-indicator.offline { background: var(--text-muted); box-shadow: none; }
.rankings-table th { cursor: pointer; user-select: none; }
.rankings-table th:hover { color: var(--gold-light); }
</style>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Hall da Fama</span>
            <h2 class="section-title">Rankings do Servidor</h2>
            <p class="section-desc">Os guerreiros mais lendários de Aden. Sua posição te espera.</p>
        </div>

        <div class="rankings-tabs">
            <button class="tab-btn <?php echo $tab == 'pvp' ? 'active' : ''; ?>" onclick="location.href='?tab=pvp'">Top PvP</button>
            <button class="tab-btn <?php echo $tab == 'pk' ? 'active' : ''; ?>" onclick="location.href='?tab=pk'">Top PK</button>
            <button class="tab-btn <?php echo $tab == 'clan' ? 'active' : ''; ?>" onclick="location.href='?tab=clan'">Top Clãs</button>
            <button class="tab-btn <?php echo $tab == 'online' ? 'active' : ''; ?>" onclick="location.href='?tab=online'">Mais Ativos</button>
        </div>

        <?php if ($tab == 'pvp'): ?>
        <table class="rankings-table">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>Jogador</th>
                    <th>Classe</th>
                    <th>Level</th>
                    <th>Clã</th>
                    <th style="text-align:right;">PvP Kills</th>
                    <th style="text-align:right;">PK Kills</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pvpData as $i => $p): 
                    $rank = $offset + $i + 1;
                ?>
                <tr>
                    <td class="rank-pos <?php echo $rank <= 3 ? 'top'.$rank : 'other'; ?>"><?php echo $rank; ?></td>
                    <td>
                        <span class="rank-class-icon"><?php echo substr(getClassName($p['classid']), 0, 2); ?></span>
                        <span class="rank-name"><?php echo sanitize($p['char_name']); ?></span>
                        <?php if ($p['online']): ?><span class="online-indicator" title="Online"></span><?php endif; ?>
                    </td>
                    <td><?php echo getClassName($p['classid']); ?></td>
                    <td><?php echo $p['level']; ?></td>
                    <td class="rank-clan"><?php echo $p['clan_name'] ? sanitize($p['clan_name']) : '-'; ?></td>
                    <td class="rank-value" style="text-align:right;"><?php echo formatNumber($p['pvpkills']); ?></td>
                    <td style="text-align:right;color:var(--red-glow);"><?php echo formatNumber($p['pkkills']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($pvpData)): ?>
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:60px;">Nenhum jogador encontrado</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo renderPagination($pvpTotal, $perPage, $page, 'pvp'); ?>

        <?php elseif ($tab == 'pk'): ?>
        <table class="rankings-table">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>Jogador</th>
                    <th>Classe</th>
                    <th>Level</th>
                    <th>Clã</th>
                    <th style="text-align:right;">PK Kills</th>
                    <th style="text-align:right;">PvP Kills</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pkData as $i => $p): 
                    $rank = $offset + $i + 1;
                ?>
                <tr>
                    <td class="rank-pos <?php echo $rank <= 3 ? 'top'.$rank : 'other'; ?>"><?php echo $rank; ?></td>
                    <td>
                        <span class="rank-class-icon"><?php echo substr(getClassName($p['classid']), 0, 2); ?></span>
                        <span class="rank-name"><?php echo sanitize($p['char_name']); ?></span>
                        <?php if ($p['online']): ?><span class="online-indicator" title="Online"></span><?php endif; ?>
                    </td>
                    <td><?php echo getClassName($p['classid']); ?></td>
                    <td><?php echo $p['level']; ?></td>
                    <td class="rank-clan"><?php echo $p['clan_name'] ? sanitize($p['clan_name']) : '-'; ?></td>
                    <td class="rank-value" style="text-align:right;color:var(--red-glow);"><?php echo formatNumber($p['pkkills']); ?></td>
                    <td style="text-align:right;"><?php echo formatNumber($p['pvpkills']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($pkData)): ?>
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:60px;">Nenhum jogador encontrado</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo renderPagination($pvpTotal, $perPage, $page, 'pk'); ?>

        <?php elseif ($tab == 'clan'): ?>
        <table class="rankings-table">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>Clã</th>
                    <th>Level</th>
                    <th>Castelo</th>
                    <th>Aliança</th>
                    <th style="text-align:right;">Reputação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clanData as $i => $c): 
                    $rank = $offset + $i + 1;
                ?>
                <tr>
                    <td class="rank-pos <?php echo $rank <= 3 ? 'top'.$rank : 'other'; ?>"><?php echo $rank; ?></td>
                    <td><span class="rank-name"><?php echo sanitize($c['clan_name']); ?></span></td>
                    <td><?php echo $c['clan_level']; ?></td>
                    <td><?php echo $c['hasCastle'] > 0 ? '🏰 Sim' : '-'; ?></td>
                    <td class="rank-clan"><?php echo $c['ally_name'] ? sanitize($c['ally_name']) : '-'; ?></td>
                    <td class="rank-value" style="text-align:right;"><?php echo formatNumber($c['reputation_score']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($clanData)): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:60px;">Nenhum clã encontrado</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo renderPagination($clanTotal, $perPage, $page, 'clan'); ?>

        <?php elseif ($tab == 'online'): ?>
        <table class="rankings-table">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>Jogador</th>
                    <th>Classe</th>
                    <th>Level</th>
                    <th>Clã</th>
                    <th style="text-align:right;">Último Login</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($onlineData as $i => $p): 
                    $rank = $offset + $i + 1;
                ?>
                <tr>
                    <td class="rank-pos <?php echo $rank <= 3 ? 'top'.$rank : 'other'; ?>"><?php echo $rank; ?></td>
                    <td>
                        <span class="rank-class-icon"><?php echo substr(getClassName($p['classid']), 0, 2); ?></span>
                        <span class="rank-name"><?php echo sanitize($p['char_name']); ?></span>
                    </td>
                    <td><?php echo getClassName($p['classid']); ?></td>
                    <td><?php echo $p['level']; ?></td>
                    <td class="rank-clan"><?php echo $p['clan_name'] ? sanitize($p['clan_name']) : '-'; ?></td>
                    <td style="text-align:right;color:var(--text-muted);"><?php echo $p['offline_hours'] ? $p['offline_hours'].'h atrás' : 'Online'; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($onlineData)): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:60px;">Nenhum dado disponível</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
