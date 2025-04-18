<?php
// Set headers for JSON API
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    sendResponse(500, ["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

// Route the request to the appropriate handler
switch ($method) {
    case 'GET':
        if ($endpoint === 'products') {
            getProducts();
        } elseif ($endpoint === 'product' && isset($_GET['id'])) {
            getProduct($_GET['id']);
        } else {
            sendResponse(400, ["error" => "Invalid endpoint"]);
        }
        break;

    case 'POST':
        if ($endpoint === 'products') {
            createProduct();
        } else {
            sendResponse(400, ["error" => "Invalid endpoint"]);
        }
        break;

    case 'PUT':
        if ($endpoint === 'product' && isset($_GET['id'])) {
            updateProduct($_GET['id']);
        } else {
            sendResponse(400, ["error" => "Invalid endpoint"]);
        }
        break;

    case 'DELETE':
        if ($endpoint === 'product' && isset($_GET['id'])) {
            deleteProduct($_GET['id']);
        } else {
            sendResponse(400, ["error" => "Invalid endpoint"]);
        }
        break;

    default:
        sendResponse(405, ["error" => "Method not allowed"]);
        break;
}

// Function to get all products with pagination and search
function getProducts()
{
    global $conn;

    // Pagination parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    if ($page < 1) $page = 1;
    if ($limit < 1) $limit = 5;
    $offset = ($page - 1) * $limit;

    // Search parameter
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $search_query = '';
    $search_params = [];

    if ($search) {
        $search_query = "WHERE name LIKE ? OR description LIKE ?";
        $search_params = ["%$search%", "%$search%"];
    }

    // Count total products
    $count_sql = "SELECT COUNT(*) AS total FROM products $search_query";
    $stmt = $conn->prepare($count_sql);

    if ($search) {
        $stmt->bind_param("ss", $search_params[0], $search_params[1]);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_products = $row['total'];
    $total_pages = ceil($total_products / $limit);

    // Get products
    $sql = "SELECT * FROM products $search_query LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);

    if ($search) {
        $stmt->bind_param("ssii", $search_params[0], $search_params[1], $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $response = [
        "products" => $products,
        "total" => $total_products,
        "current_page" => $page,
        "total_pages" => $total_pages,
        "per_page" => $limit
    ];

    sendResponse(200, $response);
}

// Function to get a single product by ID
function getProduct($id)
{
    global $conn;

    if (!is_numeric($id) || $id <= 0) {
        sendResponse(400, ["error" => "Invalid product ID"]);
        return;
    }

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse(404, ["error" => "Product not found"]);
        return;
    }

    $product = $result->fetch_assoc();
    sendResponse(200, $product);
}

// Function to create a new product
function createProduct()
{
    global $conn;

    // Get JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        sendResponse(400, ["error" => "Invalid JSON data"]);
        return;
    }

    // Validate input
    $validation = validateProductData($data);
    if (!$validation['valid']) {
        sendResponse(400, ["error" => $validation['errors']]);
        return;
    }

    // Insert product
    $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $data['name'], $data['description'], $data['price'], $data['image']);

    if ($stmt->execute()) {
        $new_id = $conn->insert_id;
        sendResponse(201, [
            "message" => "Product created successfully",
            "id" => $new_id
        ]);
    } else {
        sendResponse(500, ["error" => "Failed to create product: " . $stmt->error]);
    }
}

// Function to update an existing product
function updateProduct($id)
{
    global $conn;

    if (!is_numeric($id) || $id <= 0) {
        sendResponse(400, ["error" => "Invalid product ID"]);
        return;
    }

    // Check if product exists
    $check_sql = "SELECT id FROM products WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        sendResponse(404, ["error" => "Product not found"]);
        return;
    }

    // Get JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        sendResponse(400, ["error" => "Invalid JSON data"]);
        return;
    }

    // Validate input
    $validation = validateProductData($data);
    if (!$validation['valid']) {
        sendResponse(400, ["error" => $validation['errors']]);
        return;
    }

    // Update product
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $data['name'], $data['description'], $data['price'], $data['image'], $id);

    if ($stmt->execute()) {
        sendResponse(200, ["message" => "Product updated successfully"]);
    } else {
        sendResponse(500, ["error" => "Failed to update product: " . $stmt->error]);
    }
}

// Function to delete a product
function deleteProduct($id)
{
    global $conn;

    if (!is_numeric($id) || $id <= 0) {
        sendResponse(400, ["error" => "Invalid product ID"]);
        return;
    }

    // Check if product exists
    $check_sql = "SELECT id FROM products WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        sendResponse(404, ["error" => "Product not found"]);
        return;
    }

    // Delete product
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        sendResponse(200, ["message" => "Product deleted successfully"]);
    } else {
        sendResponse(500, ["error" => "Failed to delete product: " . $stmt->error]);
    }
}

// Function to validate product data
function validateProductData($data)
{
    $errors = [];

    // Check required fields
    $required_fields = ['name', 'price'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $errors[] = ucfirst($field) . " is required";
        }
    }

    // If there are missing required fields, return early
    if (!empty($errors)) {
        return ['valid' => false, 'errors' => $errors];
    }

    // Validate name (5 - 40 characters)
    if (strlen($data['name']) < 5 || strlen($data['name']) > 40) {
        $errors[] = "Name must be between 5 and 40 characters";
    }

    // Validate description (max 5000 characters) if provided
    if (isset($data['description']) && strlen($data['description']) > 5000) {
        $errors[] = "Description cannot exceed 5000 characters";
    }

    // Validate price (must be a positive number)
    if (!is_numeric($data['price']) || $data['price'] <= 0) {
        $errors[] = "Price must be a positive number";
    }

    // Validate image (max 255 characters) if provided
    if (isset($data['image']) && strlen($data['image']) > 255) {
        $errors[] = "Image URL cannot exceed 255 characters";
    }

    return ['valid' => empty($errors), 'errors' => $errors];
}

// Function to send JSON response
function sendResponse($status_code, $data)
{
    http_response_code($status_code);
    echo json_encode($data);
    exit;
}
