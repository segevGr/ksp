<?php
require_once __DIR__ . '/logic.php';
header('Content-Type: application/json');
$users = load_users(__DIR__ . '/data/users.json');
$active = get_active_users($users);
echo json_encode([
    'active_count' => count($active),
    'sample' => $active
]);
