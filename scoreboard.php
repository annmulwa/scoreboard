<?php

// connect to the database using external config
include 'config.php';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoreboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="scoreboard-page">
    <h2>Live Scoreboard</h2>
    <table>
        <tbody id="scoreboard-body">
            <?php include 'scoreboard_table.php'; ?>
        </tbody>
    </table>
    <script>
        function refreshScoreboard() {
            fetch('scoreboard_table.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('scoreboard-body').innerHTML = html;
                });
        }

        setInterval(refreshScoreboard, 30000); // refresh every 30 seconds
    </script>
</body>

</html>