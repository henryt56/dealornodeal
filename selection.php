<?php 
session_start();
// Check authentication
require_once 'auth_check.php';

include 'game-logic.php';

// Reset any previous game
unset($_SESSION['cases']);
unset($_SESSION['case_grid']);
unset($_SESSION['player_case']);
unset($_SESSION['round']);
unset($_SESSION['cases_opened_this_round']);
unset($_SESSION['should_make_offer']);

// Initialize a new game
initializeGame();

// Create case grid
$_SESSION['case_grid'] = [];
for ($i = 0; $i < count($_SESSION['cases']); $i++) {
    $_SESSION['case_grid'][$i] = [
        'value' => $_SESSION['cases'][$i],
        'status' => 'closed'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Briefcase</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="selection-container">
        <h1>SELECT YOUR BRIEFCASE</h1>
        <div class="briefcase-grid">
            <?php for ($i = 1; $i <= count($_SESSION['case_grid']); $i++): ?>
                <form action="play.php" method="post">
                    <input type="hidden" name="selected_case" value="<?= $i ?>">
                    <button type="submit" class="briefcase">
                        <span class="briefcase-number"><?= $i ?></span>
                    </button>
                </form>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>