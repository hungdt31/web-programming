<?php
require_once 'database.php';

if (!isset($_GET['id'])) {
    header("Location: a.php");
    exit();
}

$id = $_GET['id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate input
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $image = $_POST['image'] ?? '';

        // Validate name (2-40 characters)
        if (strlen($name) < 2 || strlen($name) > 40) {
            throw new Exception("Name must be between 2 and 40 characters");
        }

        // Validate description (max 3000 characters)
        if (strlen($description) > 3000) {
            throw new Exception("Description must not exceed 3000 characters");
        }

        // Validate price (must be numeric)
        if (!is_numeric($price)) {
            throw new Exception("Price must be a number");
        }

        // Validate image URL (max 255 characters)
        if (strlen($image) > 255) {
            throw new Exception("Image URL must not exceed 255 characters");
        }

        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $image, $id]);

        header("Location: a.php");
        exit();
    }

    // Get existing product data
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception("Product not found");
    }
} catch(Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Product</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($product)): ?>
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Name (2-40 characters)</label>
                    <input type="text" class="form-control" id="name" name="name" required 
                           minlength="2" maxlength="40" value="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description (max 3000 characters)</label>
                    <textarea class="form-control" id="description" name="description" 
                              maxlength="3000"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" 
                           required value="<?php echo htmlspecialchars($product['price']); ?>">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image URL (max 255 characters)</label>
                    <input type="url" class="form-control" id="image" name="image" 
                           maxlength="255" value="<?php echo htmlspecialchars($product['image']); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="a.php" class="btn btn-secondary">Back to List</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html> 