<?php
include 'game-logic.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_case'])) {
        $_SESSION['player_case'] = $_POST['selected_case'];
    }
    if (isset($_POST['open_case'])) {
        $opened_value = openCase($_POST['open_case']);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Round <?= $_SESSION['round'] ?></h2>
    <div class="briefcase-grid">
        <?php foreach ($_SESSION['remaining'] as $index => $value): ?>
            <form action="play.php" method="post">
                <input type="hidden" name="open_case" value="<?= $index ?>">
                <button type="submit" class="briefcase">
                    <span><?= $index + 1 ?></span>
                </button>
            </form>
        <?php endforeach; ?>
    </div>
    <?php if (count($_SESSION['remaining']) % 5 === 0): ?>
        <?php $offer = calculateOffer(); ?>
        <form action="offer.php" method="post">
            <input type="hidden" name="offer" value="<?= $offer ?>">
            <button type="submit">BANK OFFER: $<?= number_format($offer) ?></button>
        </form>
    <?php endif; ?>
</body>
</html