<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Play - Deal or No Deal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="instructions-container">
        <h1>HOW TO PLAY</h1>
        
        <div class="instructions-list">
            <ol>
                <li><strong>Select your briefcase:</strong> Choose one from 25 briefcases to keep until the end.</li>
                <li><strong>Open other briefcases:</strong> Each round, open a set number of briefcases to reveal their values.</li>
                <li><strong>Receive bank offers:</strong> After each set of opened cases, the bank will make an offer to buy your briefcase.</li>
                <li><strong>Decide:</strong> Choose "Deal" to accept the offer and end the game, or "No Deal" to continue playing.</li>
                <li><strong>Final round:</strong> If you refuse all offers, you'll win whatever is in your original briefcase.</li>
                <li><strong>Winning:</strong> Try to get the highest possible amount!</li>
            </ol>
        </div>
        
        <a href="index.php" class="btn back-btn">BACK TO GAME</a>
    </div>
</body>
</html>
