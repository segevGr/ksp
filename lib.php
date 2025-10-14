<?php
declare(strict_types=1);

/**
 * Loads JSON orders data from file.
 * Each order: {"user": "alice", "item": "strings", "amount": 19.99}
 * 
 * BUG is here (non-trivial, logical): investigate JSON → PHP structure vs. array access below.
 */
function load_data(string $path): array {
    if (!is_file($path)) {
        throw new RuntimeException("data file not found: $path");
    }
    $json = file_get_contents($path);
    if ($json === false) {
        throw new RuntimeException("failed reading data file: $path");
    }

    // Intentionally buggy line:
    // Returns objects, but the rest of the code treats items like arrays.
    $data = json_decode($json, true); // <— your fix should make the structure consistent with usage below

    if (!is_array($data)) {
        throw new RuntimeException('invalid data format');
    }
    return $data;
}

/**
 * Calculate per-user stats.
 * Returns: ["user" => string, "count" => int, "total" => float]
 */
function calculate_user_stats(array $orders, string $user): array {
    $count = 0;
    $total = 0.0;

    foreach ($orders as $o) {
        // Using array-style access on each order element:
        if (!isset($o['user']) || !isset($o['amount'])) {
            // robust guard
            continue;
        }
        if (strcasecmp($o['user'], $user) === 0) {
            $count++;
            // amount might be numeric or numeric-string, be liberal but safe
            $amt = is_numeric($o['amount']) ? (float)$o['amount'] : 0.0;
            $total += $amt;
        }
    }

    return [
        'user'  => $user,
        'count' => $count,
        'total' => round($total, 2),
    ];
}
