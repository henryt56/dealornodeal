<?php
session_start();
// Check authentication
require_once 'auth_check.php';
require_once 'game-logic.php';

// Initialize game if needed
if (empty($_SESSION['cases'])) {
    initializeGame();
}

// Create the case grid if it doesn't exist yet
if (!isset($_SESSION['case_grid']) || !is_array($_SESSION['case_grid'])) {
    $_SESSION['case_grid'] = [];
    
    for ($i = 0; $i < count($_SESSION['cases']); $i++) {
        $_SESSION['case_grid'][$i] = [
            'value' => $_SESSION['cases'][$i],
            'status' => 'closed' // closed, opened, or player
        ];
    }
}

// Handle player's initial case selection
if (isset($_POST['selected_case'])) {
    $selectedCase = (int)$_POST['selected_case'];
    selectPlayerCase($selectedCase);
}

// Process a "No Deal" decision
if (isset($_POST['decision']) && $_POST['decision'] === 'no_deal') {
    // Clear the waiting flag
    $_SESSION['waiting_for_decision'] = false;
    
    // Advance to the next round
    advanceToNextRound();
}

// Process game actions (opening a case)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['open_case'])) {
    // Only process if we're not waiting for a decision
    if (empty($_SESSION['waiting_for_decision'])) {
        $openedCaseNumber = (int)$_POST['open_case'];
        $openedValue = openCase($openedCaseNumber);
    }
}

// Check if the game is over (only player's case is left)
if (isGameOver()) {
    // Get the player's case value
    $playerCaseNumber = $_SESSION['player_case'] ?? 0;
    $playerCaseValue = $_SESSION['player_case_value'] ?? 0;
    
    // Redirect to result page with the final amount
    header("Location: result.php?decision=no_deal&amount={$playerCaseValue}");
    exit;
}

// Get current game state
$round = $_SESSION['round'] ?? 0;
$casesNeededThisRound = getCasesForCurrentRound();
$casesRemainingThisRound = getRemainingCasesInRound();

// Check if banker should make an offer
if (shouldMakeOffer() && empty($_SESSION['waiting_for_decision'])) {
    $offer = calculateOffer();
    header("Location: offer.php?offer_amount={$offer}");
    exit;
}

// Set a message if waiting for decision
$waitingMessage = '';
if (!empty($_SESSION['waiting_for_decision'])) {
    $waitingMessage = 'You must make a decision on the banker\'s offer before continuing.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Round <?= $round ?> - Deal or No Deal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Top Banner -->
    <div class="game-banner">
        <div class="banner-title">DEAL OR NO DEAL</div>
        <a href="index.php" class="home-button" onclick="return confirm('Are you sure you want to go home? This will reset your game.');">HOME</a>
    </div>
    
    <div class="game-container">
        <!-- Prize Board -->
        <div class="money-board">
            <h3>PRIZE BOARD</h3>
            <?php 
            $values = PRIZE_VALUES;
            sort($values);
            
            // Collect opened values
            $openedValues = [];
            foreach ($_SESSION['case_grid'] ?? [] as $case) {
                if ($case['status'] === 'opened') {
                    $openedValues[] = $case['value'];
                }
            }
            
            foreach ($values as $value): 
                $formattedValue = '$' . number_format($value, $value < 1 ? 2 : 0);
                $eliminated = in_array($value, $openedValues);
                
                // Determine value class
                $valueClass = 'low';
                if ($value >= 1000 && $value < 100000) {
                    $valueClass = 'medium';
                } else if ($value >= 100000) {
                    $valueClass = 'high';
                }
            ?>
                <div class="money-value <?= $valueClass ?> <?= $eliminated ? 'eliminated' : '' ?>">
                    <?= $formattedValue ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Game Area -->
        <div class="game-area">
            <h1>Round <?= $round ?></h1>
            
            <?php if (!empty($waitingMessage)): ?>
                <div class="waiting-message"><?= $waitingMessage ?></div>
            <?php endif; ?>
            
            <div class="game-status">
                <p>Cases remaining this round: <span class="cases-remaining"><?= $casesRemainingThisRound ?></span></p>
                <p>Then the banker will make an offer</p>
            </div>
            
            <div class="briefcase-grid">
                <?php foreach ($_SESSION['case_grid'] as $index => $case): 
                    $caseNum = $index + 1;
                    $status = $case['status'];
                    $disabled = '';
                    
                    // Disable if case is opened, player's case, no cases left this round, or waiting for decision
                    if ($status === 'opened' || $status === 'player' || $casesRemainingThisRound <= 0 || 
                       !empty($_SESSION['waiting_for_decision'])) {
                        $disabled = 'disabled';
                    }
                ?>
                    <form action="play.php" method="post">
                        <input type="hidden" name="open_case" value="<?= $caseNum ?>">
                        <button type="submit" class="case <?= $status ?>" <?= $disabled ?>>
                            <span class="case-number"><?= $caseNum ?></span>
                            <?php if ($status === 'opened'): ?>
                                <span class="case-value">$<?= number_format($case['value'], $case['value'] < 1 ? 2 : 0) ?></span>
                            <?php endif; ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>