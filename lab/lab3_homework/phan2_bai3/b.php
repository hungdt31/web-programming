<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <div class="container mt-5">
        <a href="a.php" class="btn btn-light mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 7a.5.5 0 0 1-.5.5H6.707l1.646 1.646a.5.5 0 0 1-.708.708l-2.5-2.5a.5.5 0 0 1 0-.708l2.5-2.5a.5.5 0 1 1 .708.708L6.707 8H11a.5.5 0 0 1 .5.5z"/>
            </svg>
            Return
        </a>
        <h2>Add Product</h2>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = $_POST['price'] ?? 0;
            $image = trim($_POST['image'] ?? '');

            $errors = [];

            // Validate name (5 - 40 characters)
            if (strlen($name) < 5 || strlen($name) > 40) {
                $errors[] = "Name must be between 5 and 40 characters.";
            }

            // Validate description (max 5000 characters)
            if (strlen($description) > 5000) {
                $errors[] = "Description cannot exceed 5000 characters.";
            }

            // Validate price (must be a positive number)
            if (!is_numeric($price) || $price <= 0) {
                $errors[] = "Price must be a positive number.";
            }

            // Validate image (max 255 characters)
            if (strlen($image) > 255) {
                $errors[] = "Image URL cannot exceed 255 characters.";
            }

            if (empty($errors)) {
                // Connect to database
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "shop";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Use prepared statement
                $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                // Hàm bind_param() giúp gán giá trị từ PHP vào các dấu ? trong câu lệnh SQL. Cấu trúc:
                // bind_param("kiểu_dữ_liệu", biến1, biến2, biến3, ...)
                // s: chuôing, d: số thực, i: số nguyên
                // Chú ý: Các biến phải được khai báo trước khi gọi hàm bind_param()
                $stmt->bind_param("ssds", $name, $description, $price, $image);

                if ($stmt->execute()) {
                    header("Location: a.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
                }

                // Close connection
                $stmt->close();
                $conn->close();
            } else {
                echo "<div class='alert alert-danger'><ul>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul></div>";
            }
        }
        ?>


        <form class="mt-3" action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required minlength="5" maxlength="40">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="5000"></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required min="0.01">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image URL</label>
                <input type="url" class="form-control" id="image" name="image" maxlength="255">
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</body>

</html>