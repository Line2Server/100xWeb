<?php
/**
 * API de Refresh para o Dashboard Admin
 */
header('Content-Type: application/json');
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    $db = getDB();
    $data = [
        'online' => (int)$db->query("SELECT COUNT(*) FROM characters WHERE online = 1")->fetchColumn(),
        'accounts' => (int)$db->query("SELECT COUNT(*) FROM accounts")->fetchColumn(),
        'characters' => (int)$db->query("SELECT COUNT(*) FROM characters")->fetchColumn(),
        'clans' => (int)$db->query("SELECT COUNT(*) FROM clan_data")->fetchColumn(),
        'banned' => (int)$db->query("SELECT COUNT(*) FROM accounts WHERE accessLevel < 0")->fetchColumn(),
        'success' => true,
        'timestamp' => time()
    ];
    echo json_encode($data);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
