<?php
session_start();

// Check if already logged in
if (isset($_SESSION['username'])) {
    header("Location: info.php");
    exit();
}

// Hardcoded credentials for simplicity (in real apps, use a database)
$valid_username = "admin";
$valid_password = "123456";

$error = "";

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;

        // Handle "Remember Me"
        if (isset($_POST['remember'])) {
            $token = bin2hex(random_bytes(16)); // Generate random token
            setcookie("remember_token", $token, time() + (30 * 24 * 60 * 60), "/"); // 30 days
            setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/"); // 30 days
            // In a real app, store token in database
        }

        header("Location: info.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="remember"> Remember me</label>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Test Credentials: admin/123456</p>
    </div>
</body>

</html>