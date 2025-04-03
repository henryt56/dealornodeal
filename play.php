<?php
session_start();

// Include the game logic FIRST
require_once 'includes/game-logic.php';

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
    <style>
        .game-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 1rem;
        }
        .briefcase-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .case {
            background: url('assets/images/briefcase.jpg') center/contain no-repeat;
            width: 120px;
            height: 120px;
            margin: 0 auto;
            transition: all 0.5s;
            position: relative;
        }
        .case:hover {
            transform: scale(1.1);
        }
        .case.opened {
            background: url('assets/images/openbriefcase.png') center/contain no-repeat;
            transform: rotateY(180deg) scale(1.1);
            cursor: default;
        }
        .case-number {
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .round-info {
            text-align: center;
            margin: 1rem 0;
            font-size: 1.2rem;
        }
        .cases-remaining {
            color: #f8c537;
            font-weight: bold;
        }
    </style>
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
