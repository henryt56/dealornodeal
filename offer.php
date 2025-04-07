<?php
session_start();
// Check authentication
require_once 'auth_check.php';
require_once 'game-logic.php';

$offer = $_POST['offer_amount'] ?? $_GET['offer_amount'] ?? 0;

// Set a flag in session to block case selection until decision is made
$_SESSION['waiting_for_decision'] = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Offer - Deal or No Deal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="offer-page">
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
            <!-- Offer Overlay -->
            <div class="offer-overlay">
                <div class="offer-content">
                    <h2>THE BANK OFFERS YOU</h2>
                    <div class="offer-amount">$<?= number_format($offer) ?></div>
                    
                    <div class="choice-buttons">
                        <form action="result.php" method="post">
                            <input type="hidden" name="decision" value="deal">
                            <input type="hidden" name="amount" value="<?= $offer ?>">
                            <button type="submit" class="btn deal-btn">DEAL</button>
                        </form>
                        
                        <form action="play.php" method="post">
                            <input type="hidden" name="decision" value="no_deal">
                            <input type="hidden" name="offer_amount" value="<?= $offer ?>">
                            <button type="submit" class="btn no-deal-btn">NO DEAL</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <h1>Round <?= $_SESSION['round'] ?? 1 ?></h1>
            
            <div class="game-status">
                <p>The banker has made you an offer!</p>
                <p>Review the prize board and make your decision.</p>
                <p>Your case: <span class="player-case-number">#<?= $_SESSION['player_case'] ?></span></p>
            </div>
            
            <div class="briefcase-grid">
                <?php foreach ($_SESSION['case_grid'] as $index => $case): 
                    $caseNum = $index + 1;
                    $status = $case['status'];
                    $disabled = 'disabled'; // All cases are disabled during offer
                ?>
                    <div class="case <?= $status ?>" <?= $disabled ?>>
                        <span class="case-number"><?= $caseNum ?></span>
                        <?php if ($status === 'opened'): ?>
                            <span class="case-value">$<?= number_format($case['value'], $case['value'] < 1 ? 2 : 0) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>