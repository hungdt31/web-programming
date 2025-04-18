<?php
session_start();

// Clear session
session_unset();
session_destroy();

// Clear "Remember Me" cookies
setcookie("remember_token", "", time() - 3600, "/");
setcookie("username", "", time() - 3600, "/");

// Redirect to login
header("Location: login.php");
exit();
?>