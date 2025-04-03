<?php
session_start();

// Initialize game cases (shuffled)
if (!isset($_SESSION['cases'])) {
    $cases = [0.01, 1, 5, 10, 25, 50, 75, 100, 200, 300, 400, 500, 750, 
              1000, 5000, 10000, 25000, 50000, 75000, 100000, 200000, 300000, 400000, 500000, 750000, 1000000];
    shuffle($cases);
    $_SESSION['cases'] = $cases;
    $_SESSION['remaining'] = $cases;
    $_SESSION['player_case'] = null;
    $_SESSION['round'] = 1;
    $_SESSION['bank_offers'] = [];
}

// Calculate bank offer (PHP-based)
function calculateOffer() {
    $remaining = $_SESSION['remaining'];
    $average = array_sum($remaining) / count($remaining);
    return round($average * (0.7 + (mt_rand(0, 30) / 100)));
}

// Open a case 
function openCase($index) {
    if (isset($_SESSION['remaining'][$index])) {
        $value = $_SESSION['remaining'][$index];
        unset($_SESSION['remaining'][$index]);
        $_SESSION['remaining'] = array_values($_SESSION['remaining']); 
        return $value;
    }
    return null;
}

// Leaderboard 
function updateLeaderboard($name, $amount) {
    $entry = "$name, $amount\n";
    file_put_contents('leaderboard.txt', $entry, FILE_APPEND);
}
?>
