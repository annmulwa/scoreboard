<?php
// scoreboard_table.php

$conn = new mysqli("localhost", "root", "", "scoring_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "
    SELECT p.id,
        p.name AS participant_name,
        SUM(s.score) AS total_score,
        COUNT(s.judge_id) AS judges_count
    FROM participants p
    LEFT JOIN scores s ON p.id = s.participant_id
    GROUP BY p.id
    ORDER BY total_score DESC
";

$result = $conn->query($query);

echo "
    <tr>
        <th>Participant</th>
        <th>Total Score</th>
        <th>Number of Scores</th>
    </tr>
";

$rank = 1;

while ($row = $result->fetch_assoc()) {
    $participant_id = $row['id'];
    $participant = $row['participant_name'];
    $score = $row['total_score'] ?? 0;
    $judges = $row['judges_count'];

    $rank_class = '';
    if ($rank == 1) {
        $rank_class = 'first-place';
    } elseif ($rank == 2) {
        $rank_class = 'second-place';
    } elseif ($rank == 3) {
        $rank_class = 'third-place';
    }

    echo "<tr class='participant-row $rank_class'>
            <td>$participant</td>
            <td>$score</td>
            <td>$judges</td>
        </tr>";

    $judge_query = $conn->prepare("
        SELECT j.display_name, s.score
        FROM scores s
        JOIN judges j ON s.judge_id = j.id
        WHERE s.participant_id = ?
    ");

    $judge_query->bind_param("i", $participant_id);
    $judge_query->execute();
    $judge_result = $judge_query->get_result();

    while ($judge_row = $judge_result->fetch_assoc()) {
        $judge_name = $judge_row['display_name'];
        $assigned_score = $judge_row['score'];

        echo "<tr class='judge-row'>
                <td>Scored by: $judge_name</td>
                <td colspan='2'>Score: $assigned_score</td>
            </tr>";
    }
    $judge_query->close();

    $rank++;
}

$conn->close();
?>