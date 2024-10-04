<?php
session_start(); 
include 'db_config.php';

if (isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit();
}

$error_message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $username = $_POST['username']; 
    $password = $_POST['password']; 

    $stmt = $conn->prepare("SELECT id, hashed_password FROM users WHERE username=?");
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $result = $stmt->get_result(); 

    if ($result->num_rows === 1) { 
        $user = $result->fetch_assoc(); 

        // Verify the password
        if (password_verify($password, $user['hashed_password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php"); 
            exit();
        } else {
            $error_message = "Invalid username or password."; 
        }
    } else {
        $error_message = "Invalid username or password."; 
    }

    $stmt->close(); 
}

$conn->close(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error_message)): ?> 
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p> 
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
