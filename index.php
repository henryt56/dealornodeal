<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deal or No Deal</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .home-screen {
            text-align: center;
            animation: fadeIn 1s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .game-title {
            font-size: 4rem;
            margin: 2rem 0;
            text-shadow: 0 0 20px #f8c537;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 300px;
            margin: 3rem auto;
        }
        .btn {
            padding: 15px 30px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="home-screen">
        <h1 class="game-title">DEAL OR NO DEAL</h1>
        <div class="btn-container">
            <a href="selection.php" class="btn pulse">START GAME</a>
            <a href="how-to-play.php" class="btn">HOW TO PLAY</a>
            <a href="leaderboard.php" class="btn">LEADERBOARD</a>
        </div>
    </div>
</body>
</html>
