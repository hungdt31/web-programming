<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Product not found!");
}

// Check if product exists
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    die("Product does not exist!");
}

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        echo "<script>alert('Deleted product successfully!'); window.location.href='a.php';</script>";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        label {
            font-weight: bold;
        }
    </style>
    <div class="container mt-5">
        <h2 class="text-center">Delete Product</h2>
        <div class="alert alert-warning" role="alert">
            Are you sure you want to delete the product "<strong><?= htmlspecialchars($product['name']) ?></strong>"? This action cannot be undone!
        </div>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Delete now</button>
            <a href="a.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>