<?php
define('CASES_PER_ROUND', [
    1 => 6,  // Round 1: Open 6 cases
    2 => 5,  // Round 2: Open 5 cases
    3 => 4,  // Round 3: Open 4 cases
    4 => 3,  // Round 4: Open 3 cases
    5 => 2,  // Round 5: Open 2 cases
    6 => 1   // Round 6+: Open 1 case
]);
// Set value of cases
define('PRIZE_VALUES', [
    0.01, 1, 5, 10, 25, 50, 75, 100, 200, 300, 400, 500, 750,
    1000, 5000, 10000, 25000, 50000, 75000, 100000, 
    200000, 300000, 400000, 500000, 750000, 1000000
]);

function initializeGame() {
    $_SESSION['cases'] = PRIZE_VALUES;
    shuffle($_SESSION['cases']);
    $_SESSION['remaining_cases'] = array_values($_SESSION['cases']);
    $_SESSION['player_case'] = null;
    $_SESSION['round'] = 1;
    $_SESSION['bank_offers'] = [];
    $_SESSION['opened_cases'] = [];
    $_SESSION['cases_opened_this_round'] = 0;
}

// Set players case
function selectPlayerCase($caseNumber) {
    $index = $caseNumber - 1;
    
    if ($index >= 0 && $index < count($_SESSION['case_grid'])) {
        // Mark this case as the player's case
        $_SESSION['case_grid'][$index]['status'] = 'player';
        
        // Store player's case info
        $_SESSION['player_case'] = $caseNumber;
        $_SESSION['player_case_value'] = $_SESSION['case_grid'][$index]['value'];
        
        // Start first round
        $_SESSION['round'] = 1;
        
        return true;
    }
    
    return false;
}

// Opening selected case
function openCase($caseNumber) {
    $index = $caseNumber - 1;
    
    // Only allow opening if we're in an active round and the case is closed (not player's case or already opened)
    if ($_SESSION['round'] > 0 && 
        isset($_SESSION['case_grid'][$index]) && 
        $_SESSION['case_grid'][$index]['status'] === 'closed') {
        
        // Mark the case as opened
        $_SESSION['case_grid'][$index]['status'] = 'opened';
        $value = $_SESSION['case_grid'][$index]['value'];
        
        // Increment count for this round
        $_SESSION['cases_opened_this_round']++;
        
        // Check if round is complete
        $casesNeededThisRound = CASES_PER_ROUND[$_SESSION['round']] ?? 1;
        
        if ($_SESSION['cases_opened_this_round'] >= $casesNeededThisRound) {
            // Round complete, banker should make an offer
            $_SESSION['should_make_offer'] = true;
            $_SESSION['cases_opened_this_round'] = 0;
        } else {
            $_SESSION['should_make_offer'] = false;
        }
        
        return $value;
    }
    
    return null;
}

// Calculate bankers offer based on what cases we have left
function calculateOffer() {
    $remainingValues = [];
    
    foreach ($_SESSION['case_grid'] as $case) {
        if ($case['status'] === 'closed' || $case['status'] === 'player') {
            $remainingValues[] = $case['value'];
        }
    }
    
    $expectedValue = array_sum($remainingValues) / count($remainingValues);
    
    $roundModifier = 0.3 + ($_SESSION['round'] * 0.1);
    if ($roundModifier > 0.9) $roundModifier = 0.9; // Cap at 90%
    
    $offer = round($expectedValue * $roundModifier);
    
    // Store the offer
    $_SESSION['bank_offers'][$_SESSION['round']] = $offer;
    
    return $offer;
}

// continue to next round
function advanceToNextRound() {
    $_SESSION['round']++;
    $_SESSION['cases_opened_this_round'] = 0;
    $_SESSION['should_make_offer'] = false;
}

// check if game is over
function isGameOver() {
    // Count closed cases (excluding player's case)
    $closedCases = 0;
    foreach ($_SESSION['case_grid'] as $case) {
        if ($case['status'] === 'closed') {
            $closedCases++;
        }
    }
    
    // Game is over if no closed cases remain (only player's case is left)
    return $closedCases === 0;
}

/**
 * Get the number of cases to open in the current round
 */
function getCasesForCurrentRound() {
    return CASES_PER_ROUND[$_SESSION['round']] ?? 1;
}

/**
 * Get remaining cases to open in current round
 */
function getRemainingCasesInRound() {
    $casesNeededThisRound = CASES_PER_ROUND[$_SESSION['round']] ?? 1;
    return $casesNeededThisRound - $_SESSION['cases_opened_this_round'];
}

/**
 * Get all cases with their current status for display
 */
function getCaseGrid() {
    return $_SESSION['case_grid'];
}

/**
 * Should the banker make an offer now?
 */
function shouldMakeOffer() {
    return isset($_SESSION['should_make_offer']) && $_SESSION['should_make_offer'] === true;
}

/**
 * Process the player choosing "Deal"
 */
function acceptDeal($offerAmount) {
    $_SESSION['game_result'] = 'deal';
    $_SESSION['final_amount'] = $offerAmount;
    return $offerAmount;
}

/**
 * Process the player choosing "No Deal" all the way to the end
 */
function finalResultNoDeal() {
    $_SESSION['game_result'] = 'no_deal';
    $_SESSION['final_amount'] = $_SESSION['player_case_value'];
    return $_SESSION['player_case_value'];
}

/**
 * Get values for display on the prize board
 */
function getPrizeBoardValues() {
    $originalValues = PRIZE_VALUES;
    sort($originalValues); // Sort from lowest to highest
    
    // Mark values that have been opened
    $result = [];
    $openedValues = [];
    
    // Collect all opened values
    foreach ($_SESSION['case_grid'] as $case) {
        if ($case['status'] === 'opened') {
            $openedValues[] = $case['value'];
        }
    }
    
    // Create the prize board display
    foreach ($originalValues as $value) {
        $isOpened = in_array($value, $openedValues);
        $result[] = [
            'value' => $value,
            'opened' => $isOpened
        ];
    }
    
    return $result;
}

/**
 * Reset the game to start over
 */
function resetGame() {
    unset($_SESSION['cases']);
    unset($_SESSION['player_case']);
    unset($_SESSION['player_case_value']);
    unset($_SESSION['round']);
    unset($_SESSION['bank_offers']);
    unset($_SESSION['opened_cases']);
    unset($_SESSION['cases_opened_this_round']);
    unset($_SESSION['remaining_cases']);
    unset($_SESSION['should_make_offer']);
    unset($_SESSION['game_result']);
    unset($_SESSION['final_amount']);
}
?>