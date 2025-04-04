<?php
// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID sản phẩm từ URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Không tìm thấy sản phẩm!");
}

// Lấy dữ liệu sản phẩm từ database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    die("Sản phẩm không tồn tại!");
}

// Xử lý cập nhật dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $image = $_POST["image"];

    $update_sql = "UPDATE products SET name=?, price=?, description=?, image=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdssi", $name, $price, $description, $image, $id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Updated product successfully!'); window.location.href='a.php';</script>";
    } else {
        echo "Error update: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
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
        <h2 class="text-center">Update Product</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image (URL)</label>
                <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($product['image']) ?>">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="a.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>