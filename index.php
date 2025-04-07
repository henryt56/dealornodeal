<?php 
session_start(); 

// Check if user is logged in
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$username = $_SESSION['username'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deal or No Deal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="home-screen">
        <h1 class="game-title">DEAL OR NO DEAL</h1>
        
        <?php if ($logged_in): ?>
            <p class="welcome-message">Welcome, <?= htmlspecialchars($username) ?>!</p>
        <?php endif; ?>
        
        <div class="btn-container">
            <?php if ($logged_in): ?>
                <a href="selection.php" class="btn pulse">START GAME</a>
                <a href="how-to-play.php" class="btn">HOW TO PLAY</a>
                <a href="leaderboard.php" class="btn">LEADERBOARD</a>
                <a href="logout.php" class="btn">LOGOUT</a>
            <?php else: ?>
                <a href="login.php" class="btn pulse">LOGIN</a>
                <a href="register.php" class="btn">REGISTER</a>
                <a href="how-to-play.php" class="btn">HOW TO PLAY</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>