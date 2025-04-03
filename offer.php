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
    <style>
        .offer-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            text-align: center;
            background: rgba(0,0,0,0.7);
            border-radius: 10px;
            animation: slideIn 0.5s;
        }
        @keyframes slideIn {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .offer-amount {
            font-size: 3rem;
            color: #f8c537;
            margin: 1rem 0;
            animation: pulse 1s infinite alternate;
        }
        .choice-buttons {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 2rem;
        }
        .deal-btn {
            background: #4CAF50;
        }
        .no-deal-btn {
            background: #f44336;
        }
    </style>
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
