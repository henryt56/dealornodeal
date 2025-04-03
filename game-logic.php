<?php
// Define game constants at the very top
define('CASES_PER_ROUND', [
    1 => 6,  // Round 1: Open 6 cases
    2 => 5,  // Round 2: Open 5 cases
    3 => 4,  // Round 3: Open 4 cases
    4 => 3,  // Round 4: Open 3 cases
    5 => 2   // Round 5+: Open 2 cases
]);

define('PRIZE_VALUES', [
    0.01, 1, 5, 10, 25, 50, 75, 100, 200, 300, 400, 500, 750,
    1000, 5000, 10000, 25000, 50000, 75000, 100000, 
    200000, 300000, 400000, 500000, 750000, 1000000
]);

function initializeGame() {
    $_SESSION['cases'] = PRIZE_VALUES;
    shuffle($_SESSION['cases']);
    $_SESSION['remaining_cases'] = $_SESSION['cases'];
    $_SESSION['player_case'] = null;
    $_SESSION['round'] = 1;
    $_SESSION['bank_offers'] = [];
    $_SESSION['opened_cases'] = [];
    $_SESSION['cases_opened_this_round'] = 0;
}


function openCase($index) {
    if (isset($_SESSION['remaining_cases'][$index])) {
        $value = $_SESSION['remaining_cases'][$index];
        $_SESSION['opened_cases'][] = $value;
        unset($_SESSION['remaining_cases'][$index]);
        $_SESSION['remaining_cases'] = array_values($_SESSION['remaining_cases']);
        $_SESSION['cases_opened_this_round']++;
        
        // Check if round is complete
        $max_cases = CASES_PER_ROUND[$_SESSION['round']] ?? 2;
        if ($_SESSION['cases_opened_this_round'] >= $max_cases) {
            $_SESSION['round']++;
            $_SESSION['cases_opened_this_round'] = 0;
        }
    }
}

function calculateOffer() {
    $average = array_sum($_SESSION['remaining_cases']) / count($_SESSION['remaining_cases']);
    $round_modifier = 0.5 + ($_SESSION['round'] * 0.1);
    return round($average * $round_modifier);
}

function shouldMakeOffer($round) {
    return ($_SESSION['cases_opened_this_round'] > 0 && 
           ($_SESSION['cases_opened_this_round'] % CASES_PER_ROUND[$round] == 0));
}
?>
