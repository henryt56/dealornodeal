<?php
session_start();
// Check authentication
require_once 'auth_check.php';
require_once 'game-logic.php';
require_once 'users.php';

// Get decision and amount
$decision = $_POST['decision'] ?? $_GET['decision'] ?? '';
$amount = $_POST['amount'] ?? $_GET['amount'] ?? 0;

// Get player's original case value (if they chose "no deal" all the way)
$playerCaseIndex = ($_SESSION['player_case'] ?? 1) - 1;
$playerCaseValue = $_SESSION['cases'][$playerCaseIndex] ?? 0;

// Determine final amount won
$finalAmount = 0;
if ($decision === 'deal') {
    $finalAmount = $amount;
} else {
    $finalAmount = $playerCaseValue;
}

// Update user's high score
if (isset($_SESSION['username'])) {
    updateHighScore($_SESSION['username'], $finalAmount);
}

// Determine result message
$resultMessage = '';
if ($decision === 'deal') {
    $resultMessage = "You accepted the banker's offer of $" . number_format($amount) . "!";
    if ($amount > $playerCaseValue) {
        $resultTitle = "GREAT DEAL!";
        $additionalInfo = "Your original case contained $" . number_format($playerCaseValue) . ". You got a better deal!";
    } else {
        $resultTitle = "DEAL ACCEPTED";
        $additionalInfo = "Your original case contained $" . number_format($playerCaseValue) . ". The banker got a good deal.";
    }
} else {
    $amount = $playerCaseValue;
    $resultTitle = "FINAL RESULT";
    $resultMessage = "You kept your original case until the end!";
    $additionalInfo = "Your case contained $" . number_format($playerCaseValue) . ".";
}

// Store the username before resetting session
$username = $_SESSION['username'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Result - Deal or No Deal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Top Banner -->
    <div class="game-banner">
        <div class="banner-title">DEAL OR NO DEAL</div>
        <a href="index.php" class="home-button">HOME</a>
    </div>
    
    <div class="offer-container">
        <h1><?= $resultTitle ?></h1>
        <p class="result-message"><?= $resultMessage ?></p>
        <div class="offer-amount">$<?= number_format($amount) ?></div>
        <p><?= $additionalInfo ?></p>
    </div>
    
    <!-- Display all case values in a single grid -->
    <div class="case-values-container">
        <h2>All Case Values</h2>
        <div class="case-grid">
            <?php 
            // Get all cases (the original array, not remaining cases)
            $allCases = $_SESSION['cases'] ?? [];
            
            // Display each case with its value
            for ($i = 0; $i < count($allCases); $i++): 
                $caseNum = $i + 1;
                $caseValue = $allCases[$i];
                $isPlayerCase = ($i == $playerCaseIndex);
                $class = $isPlayerCase ? 'selected-case' : '';
            ?>
                <div class="case-result <?= $class ?>">
                    <div class="case-number"><?= $caseNum ?></div>
                    <div class="case-value">$<?= number_format($caseValue, $caseValue < 1 ? 2 : 0) ?></div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    
    <div style="text-align: center; margin: 2rem 0;">
        <a href="index.php" class="btn">HOME</a>
        <a href="selection.php" class="btn">PLAY AGAIN</a>
    </div>
</body>
</html>

<?php
// Reset the game
$_SESSION = array();
session_regenerate_id();
// Keep the user logged in
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $username;
?>