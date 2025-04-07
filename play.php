<?php
session_start();

// Include the game logic FIRST
require_once 'game-logic.php';

// Initialize game if needed
if (empty($_SESSION['cases'])) {
    initializeGame();
}

// Process game actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['open_case'])) {
        openCase($_POST['open_case']);
    }
}

// Get current game state
$round = $_SESSION['round'] ?? 1;
$max_cases = CASES_PER_ROUND[$round] ?? end(CASES_PER_ROUND);
$cases_remaining = $max_cases - ($_SESSION['cases_opened_this_round'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Round <?= $round ?> - Deal or No Deal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="game-container">
        <h1>Round <?= $round ?></h1>
        <div class="round-info">
            Cases remaining this round: <span class="cases-remaining"><?= $cases_remaining ?></span>
        </div>
        
        <div class="briefcase-grid">
            <?php foreach ($_SESSION['remaining_cases'] as $index => $case): ?>
                <form action="play.php" method="post">
                    <input type="hidden" name="open_case" value="<?= $index ?>">
                    <button type="submit" class="case" <?= ($cases_remaining <= 0) ? 'disabled' : '' ?>>
                        <span class="case-number"><?= $index + 1 ?></span>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>

        <?php if (shouldMakeOffer($round)): ?>
            <div class="bank-offer-container">
                <form action="offer.php" method="post">
                    <?php $offer = calculateOffer(); ?>
                    <input type="hidden" name="offer_amount" value="<?= $offer ?>">
                    <button type="submit" class="btn">BANK OFFER: $<?= number_format($offer) ?></button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>