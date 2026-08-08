<?php
/**
 * Shared application configuration.
 * Values are supplied by Docker Compose through the .env file; safe defaults
 * keep the public pages available during an initial installation.
 */

define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'l2jbr');
define('DB_USER', getenv('DB_USER') ?: 'l2jbr');
define('DB_PASS', getenv('DB_PASS') ?: '');

// `SERVER_NAME` is reserved by the web server and resolves to "_" under Nginx.
// Use the L2-prefixed variable to keep the site name stable on every route.
define('SERVER_NAME', getenv('L2_SERVER_NAME') ?: 'Eternal War L2');
define('SERVER_CHRONICLE', getenv('SERVER_CHRONICLE') ?: 'Interlude');
define('SERVER_RATES', getenv('SERVER_RATES') ?: 'x50 XP | x50 SP | x30 Adena | x15 Drop');
define('SERVER_IP', getenv('SERVER_IP') ?: '127.0.0.1');
define('SERVER_PORT', (int) (getenv('SERVER_PORT') ?: 7777));
define('LOGIN_PORT', (int) (getenv('LOGIN_PORT') ?: 2106));
define('SESSION_TIMEOUT', 3600);
define('DISCORD_INVITE', getenv('DISCORD_INVITE') ?: '#');
define('WHATSAPP_GROUP', getenv('WHATSAPP_GROUP') ?: '#');
define('FACEBOOK_URL', getenv('FACEBOOK_URL') ?: '#');
define('YOUTUBE_URL', getenv('YOUTUBE_URL') ?: '#');

function getDB(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME);
    $connection = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $connection;
}

function sanitize($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function formatNumber($number): string
{
    return number_format((float) $number, 0, ',', '.');
}

function generateToken(int $bytes = 32): string
{
    return bin2hex(random_bytes($bytes));
}

function sendEmail(string $to, string $subject, string $html): bool
{
    $from = getenv('SMTP_FROM') ?: 'noreply@localhost';
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $from,
    ];

    return mail($to, $subject, $html, implode("\r\n", $headers));
}
