<?php
include 'game-logic.php';
$offer = $_POST['offer'];
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>BANK OFFER: $<?= number_format($offer) ?></h2>
    <form action="result.php" method="post">
        <input type="hidden" name="choice" value="deal">
        <input type="hidden" name="amount" value="<?= $offer ?>">
        <button type="submit">DEAL</button>
    </form>
    <form action="play.php" method="post">
        <button type="submit">NO DEAL</button>
    </form>
</body>
</html>