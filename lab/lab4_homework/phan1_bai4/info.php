<?php
session_start();

// Check if user is logged in or has valid "Remember Me" cookies
if (!isset($_SESSION['username'])) {
    if (isset($_COOKIE['remember_token']) && isset($_COOKIE['username'])) {
        // In a real app, validate token against database
        $_SESSION['username'] = $_COOKIE['username'];
    } else {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>