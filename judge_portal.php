<?php

// connect to the database
$conn = new mysqli("localhost", "root", "", "scoring_system");

// check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// handle form submission
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judge_username = $_POST["judge_username"];
    $participant_name = $_POST["participant_name"];
    $score = $_POST["score"];

    // get judge id
    $judge_query = $conn->prepare("SELECT id FROM judges WHERE username = ? ");
    $judge_query->bind_param("s", $judge_username);
    $judge_query->execute();
    $judge_result = $judge_query->get_result();
    $judge_row = $judge_result->fetch_assoc();
    $judge_id = $judge_row["id"];

    // get participant id
    $participant_query = $conn->prepare("SELECT id FROM participants WHERE name = ? ");
    $participant_query->bind_param("s", $participant_name);
    $participant_query->execute();
    $participant_result = $participant_query->get_result();
    $participant_row = $participant_result->fetch_assoc();
    $participant_id = $participant_row["id"];

    // insert the score
    $stmt = $conn->prepare("INSERT INTO scores (judge_id, participant_id, score) VALUES (?, ?, ?) ");
    $stmt->bind_param("iii", $judge_id, $participant_id, $score);

    if ($stmt->execute()) {
        $message = "Score submitted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Judge Scoring</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="judge-portal-page">
        <h2>Submit Score</h2>
        <form method="POST" action="">
            <label>Judge:</label><br>
            <select name="judge_username" required>
                <option value="">-- Select Judge --</option>
                <?php
                $result = $conn->query("SELECT username, display_name FROM judges");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row["username"]}'>{$row["display_name"]} ({$row["username"]})</option>";
                }
                ?>
            </select><br><br>
            <label>Participant:</label><br>
            <select name="participant_name" required>
                <option value="">-- Select Participant --</option>
                <?php
                $result = $conn->query("SELECT name FROM participants");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row["name"]}'>{$row["name"]}</option>";
                }
                ?>
            </select><br><br>
            <label>Score (0 - 100):</label><br>
            <input type="number" name="score" min="0" max="100" required><br><br>
            <button type="submit">Submit Score</button>
        </form>
        <p id="flash-message"><?php echo $message; ?></p>
        
        <script>
            // hide the flash message after 3 seconds
            setTimeout(() => {
                const msg = document.getElementById('flash-message');
                if (msg) {
                    msg.style.transition = 'opacity 0.5s ease';
                    msg.style.opacity = '0';
                    setTimeout(() => msg.style.display = 'none', 500);
                }
            }, 3000);
        </script>
    </body>
</html>