<?php
require_once 'database.php';

if (!isset($_GET['id'])) {
    header("Location: a.php");
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    
    header("Location: a.php");
    exit();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?> 