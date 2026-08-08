<?php
/**
 * API de Status do Servidor - JSON
 * Retorna informações em tempo real do servidor L2
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');

require_once '../includes/config.php';

$response = [
    'server_name' => SERVER_NAME,
    'chronicle' => SERVER_CHRONICLE,
    'rates' => SERVER_RATES,
    'ip' => SERVER_IP,
    'port_game' => SERVER_PORT,
    'port_login' => LOGIN_PORT,
    'timestamp' => time(),
    'datetime' => date('Y-m-d H:i:s')
];

try {
    $db = getDB();

    // Ping no GameServer
    $socket = @fsockopen(SERVER_IP, SERVER_PORT, $errno, $errstr, 2);
    $response['status'] = $socket ? 'online' : 'offline';
    if ($socket) fclose($socket);

    // Jogadores online
    $stmt = $db->query("SELECT COUNT(*) as total FROM characters WHERE online = 1");
    $response['players_online'] = (int)$stmt->fetch()['total'];

    // Total contas
    $stmt = $db->query("SELECT COUNT(*) as total FROM accounts");
    $response['total_accounts'] = (int)$stmt->fetch()['total'];

    // Total personagens
    $stmt = $db->query("SELECT COUNT(*) as total FROM characters");
    $response['total_characters'] = (int)$stmt->fetch()['total'];

    // Total clãs
    $stmt = $db->query("SELECT COUNT(*) as total FROM clan_data");
    $response['total_clans'] = (int)$stmt->fetch()['total'];

    // Top PvP (preview)
    $stmt = $db->query("
        SELECT char_name, pvpkills, classid, level 
        FROM characters 
        WHERE accesslevel = 0 
        ORDER BY pvpkills DESC 
        LIMIT 5
    ");
    $response['top_pvp'] = $stmt->fetchAll();

    // Top Clãs (preview)
    $stmt = $db->query("
        SELECT clan_name, reputation_score, clan_level 
        FROM clan_data 
        ORDER BY reputation_score DESC 
        LIMIT 5
    ");
    $response['top_clans'] = $stmt->fetchAll();

    // Contas criadas hoje
    $stmt = $db->query("
        SELECT COUNT(*) as total FROM accounts 
        WHERE created_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ");
    $response['accounts_today'] = (int)$stmt->fetch()['total'];

    // Personagens criados hoje
    $stmt = $db->query("
        SELECT COUNT(*) as total FROM characters 
        WHERE createtime >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))
    ");
    $response['characters_today'] = (int)$stmt->fetch()['total'];

    // Máximo online (histórico)
    $stmt = $db->query("
        SELECT MAX(online_count) as max FROM (
            SELECT COUNT(*) as online_count 
            FROM characters 
            WHERE online = 1 
            GROUP BY HOUR(lastAccess)
        ) t
    ");
    $maxOnline = $stmt->fetch()['max'];
    $response['max_online'] = $maxOnline ? (int)$maxOnline : $response['players_online'];

    $response['success'] = true;

} catch (Exception $e) {
    $response['status'] = 'offline';
    $response['players_online'] = 0;
    $response['error'] = 'Database connection failed: ' . $e->getMessage();
    $response['success'] = false;
}

echo json_encode($response, JSON_PRETTY_PRINT);
