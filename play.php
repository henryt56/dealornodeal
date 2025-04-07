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

// Process game actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['open_case'])) {
        $openedCaseNumber = (int)$_POST['open_case'];
        $openedValue = openCase($openedCaseNumber);
    }
}

// Check if the game is over (only player's case is left)
if (isGameOver()) {
    // Get the player's case value
    $playerCaseNumber = $_SESSION['player_case'];
    $playerCaseValue = $_SESSION['player_case_value'];
    
    // Redirect to result page with the final amount
    header("Location: result.php?decision=no_deal&amount={$playerCaseValue}");
    exit;
}

// Process game actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['open_case'])) {
        $openedCaseNumber = (int)$_POST['open_case'];
        $openedValue = openCase($openedCaseNumber);
    }
}

// Get current game state
$round = $_SESSION['round'] ?? 0;
$casesNeededThisRound = getCasesForCurrentRound();
$casesRemainingThisRound = getRemainingCasesInRound();
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
            
            <div class="game-status">
                <p>Cases remaining this round: <span class="cases-remaining"><?= $casesRemainingThisRound ?></span></p>
                <p>Then the banker will make an offer</p>
            </div>
            
            <div class="briefcase-grid">
                <?php foreach ($_SESSION['case_grid'] as $index => $case): 
                    $caseNum = $index + 1;
                    $status = $case['status'];
                    $disabled = ($status === 'opened' || $status === 'player' || $casesRemainingThisRound <= 0) ? 'disabled' : '';
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

            <?php if (shouldMakeOffer()): ?>
                <div class="bank-offer-container">
                    <form action="offer.php" method="post">
                        <?php $offer = calculateOffer(); ?>
                        <input type="hidden" name="offer_amount" value="<?= $offer ?>">
                        <button type="submit" class="btn">BANK OFFER: $<?= number_format($offer) ?></button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>