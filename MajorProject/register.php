<?php
session_start();
include 'db_config.php';

$registrationSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $raw_password = $_POST["password"];
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT); // Hash the password

    $stmt = $conn->prepare("INSERT INTO users (username, raw_password, hashed_password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $raw_password, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $conn->insert_id;
        $registrationSuccess = true;
        header("Refresh: 2; URL=index.php");
    } else {
        echo "Oops! Error: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showSuccessMessage() {
            alert("Registration successful! Welcome aboard!");
        }
    </script>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>

    <?php if ($registrationSuccess): ?>
        <script>
            showSuccessMessage();
        </script>
    <?php endif; ?>
</body>
</html>
