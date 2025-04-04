<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Xử lý yêu cầu
$action = $_GET['action'] ?? '';

if ($action == 'list') {
    $products_per_page = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $search_query = $search ? "WHERE name LIKE ? OR description LIKE ?" : "";

    // Tổng số sản phẩm
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
    $total_pages = ceil($total_products / $products_per_page);

    // Lấy danh sách sản phẩm
    $offset = ($page - 1) * $products_per_page;
    $sql = "SELECT * FROM products $search_query LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($search) {
        $stmt->bind_param("ssii", $search_param, $search_param, $products_per_page, $offset);
    } else {
        $stmt->bind_param("ii", $products_per_page, $offset);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode([
        'products' => $products,
        'total_pages' => $total_pages,
        'current_page' => $page
    ]);
} elseif ($action == 'detail') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        echo json_encode(['error' => 'Invalid product ID']);
        exit;
    }

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
}

$conn->close();
?>