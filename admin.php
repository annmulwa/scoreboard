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
    $username = $_POST["username"];
    $display_name = $_POST["display_name"];

    if (!empty($username) && !empty($display_name)) {
        $stmt = $conn->prepare("INSERT INTO judges (username, display_name) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $display_name);

        if ($stmt->execute()) {
            $message = "Judge added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Both fields are required.";
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Judge</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="admin-page">
        <h2>Add A New Judge</h2>
        <form method="POST" action="">
            <label>Username:</label><br>
            <input type="text" name="username"><br><br>

            <label>Name:</label><br>
            <input type="text" name="display_name"><br><br>

            <button type="submit">Add Judge</button>
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