<?php
function load_users($path) {
    $raw = file_get_contents($path);
    if ($raw === false) {
        return [];
    }
    $data = json_decode($raw, true);
    return $data;
}

function get_active_users($users) {
    $out = [];
    foreach ($users as $u) {
        if ($u['active'] == true) {
            $out[] = $u;
        }
    }
    return $out;
}
