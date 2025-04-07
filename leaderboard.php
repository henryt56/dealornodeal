<?php
session_start();
require_once 'users.php';

// Get top players from file storage
$leaderboard = getTopPlayers(10);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Deal or No Deal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="leaderboard-container">
        <h1>TOP WINNERS</h1>
        
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Player</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaderboard as $index => $player): ?>
                    <tr class="<?= $index < 3 ? 'rank-' . ($index + 1) : '' ?>">
                        <td>#<?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($player['username']) ?></td>
                        <td>$<?= number_format($player['highscore']) ?></td>
                        <td><?= date('M j, Y', strtotime($player['created'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="index.php" class="btn back-btn">BACK TO GAME</a>
    </div>
</body>
</html>