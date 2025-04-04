<?php
$list_product = array();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Số sản phẩm hiển thị trên mỗi trang
$products_per_page = 5;

// Xác định trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_query = '';
if ($search) {
    // Sử dụng prepared statement để tránh SQL injection
    $search_query = "WHERE name LIKE ? OR description LIKE ?";
}

// Lấy tổng số sản phẩm sau khi lọc tìm kiếm
$total_sql = "SELECT COUNT(*) AS total FROM products $search_query";
$stmt = $conn->prepare($total_sql);
if ($search) {
    $search_param = "%$search%";
    $stmt->bind_param("ss", $search_param, $search_param);
}
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];

// Tính tổng số trang
$total_pages = ceil($total_products / $products_per_page);

// Tính toán OFFSET
$offset = ($page - 1) * $products_per_page;

// Lấy sản phẩm theo trang và tìm kiếm
$sql = "SELECT * FROM products $search_query LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($search) {
    $stmt->bind_param("ssii", $search_param, $search_param, $products_per_page, $offset);
} else {
    $stmt->bind_param("ii", $products_per_page, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $list_product[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <div class="container mt-5">
        <h2 class="text-center">Read Products</h2>

        <!-- Search form -->
        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2 w-50" placeholder="Search by name or description...">
            <button type="submit" class="btn btn-primary me-2">Search</button>
            <a href="?" class="btn btn-secondary">Reset</a>
        </form>
        <div class="mb-3">
            <a href="b.php" class="btn btn-info">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 7a.5.5 0 0 1-.5.5H8v2.5a.5.5 0 0 1-1 0V8.5H4.5a.5.5 0 0 1 0-1H7V4a.5.5 0 0 1 1 0v2.5h2.5a.5.5 0 0 1 .5.5z"/>
                </svg>
                Add new product
            </a>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($list_product)) { ?>
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($list_product as $product) { ?>
                        <tr>
                            <td><?= htmlspecialchars($product['id']) ?></td>
                            <td><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100px; height: 100px;"></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['price']) ?></td>
                            <td><?= htmlspecialchars($product['description']) ?></td>
                            <td>
                                <a href="c.php?id=<?= $product['id'] ?>" class="btn btn-primary mb-2">Update</a>
                                <a href="d.php?id=<?= $product['id'] ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php if ($page > 1) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                <?php } ?>

                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>

                <?php if ($page < $total_pages) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</body>

</html>