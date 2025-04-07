<?php
session_start();
$offer = $_POST['offer_amount'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Offer - Deal or No Deal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="offer-container">
        <h2>THE BANK OFFERS YOU</h2>
        <div class="offer-amount">$<?= number_format($offer) ?></div>
        
        <div class="choice-buttons">
            <form action="result.php" method="post">
                <input type="hidden" name="decision" value="deal">
                <input type="hidden" name="amount" value="<?= $offer ?>">
                <button type="submit" class="btn deal-btn">DEAL</button>
            </form>
            
            <form action="play.php" method="post">
                <button type="submit" class="btn no-deal-btn">NO DEAL</button>
            </form>
        </div>
    </div>
</body>
</html>