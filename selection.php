<?php 
session_start();
include 'includes/game-logic.php';

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
    <style>
        .selection-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 1rem;
        }
        .briefcase-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .briefcase {
            background: url('briefcase.jpg') center/contain no-repeat;
            width: 120px;
            height: 120px;
            margin: 0 auto;
            transition: transform 0.3s;
            position: relative;
        }
        .briefcase:hover {
            transform: scale(1.1);
        }
        .briefcase::after {
            content: attr(data-number);
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }
    </style>
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
