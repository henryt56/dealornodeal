<?php 
session_start();
include 'game-logic.php';

if (!isset($_SESSION['cases'])) {
    initializeGame();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Briefcase</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="selection-container">
        <h1>SELECT YOUR BRIEFCASE</h1>
        <div class="briefcase-grid">
            <?php for ($i = 1; $i <= 25; $i++): ?>
                <form action="play.php" method="post">
                    <input type="hidden" name="selected_case" value="<?= $i ?>">
                    <button type="submit" class="briefcase" data-number="<?= $i ?>"></button>
                </form>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>