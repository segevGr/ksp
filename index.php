<?php
declare(strict_types=1);

require_once __DIR__ . '/lib.php';

header('Content-Type: application/json');

// Simple routing: /health or /?user=<name>
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/health') {
    echo json_encode(['status' => 'ok']);
    exit;
}

$user = isset($_GET['user']) ? trim((string)$_GET['user']) : '';

if ($user === '') {
    http_response_code(400);
    echo json_encode(['error' => 'missing user query parameter']);
    exit;
}

try {
    $orders = load_data(__DIR__ . '/data/orders.json');   // returns array of orders
    $stats  = calculate_user_stats($orders, $user);       // expects arrays
    echo json_encode($stats, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'internal error', 'details' => $e->getMessage()]);
}
