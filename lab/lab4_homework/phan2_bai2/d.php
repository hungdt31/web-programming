<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .loader {
            display: none;
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="mb-3">
            <a href="a.php" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Back to Products
            </a>
        </div>

        <h2 class="mb-4">Delete Product</h2>

        <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>
        <div id="successMessage" class="alert alert-success" style="display: none;">Product deleted successfully!</div>
        <div id="productNotFound" class="alert alert-warning" style="display: none;">Product not found or invalid ID.</div>

        <div class="loader" id="loader"></div>

        <div id="productDetails" style="display: none;">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Warning!</h4>
                <p>Are you sure you want to delete the product "<strong id="productName"></strong>"?</p>
                <p class="mb-0">This action cannot be undone.</p>
            </div>

            <div class="card mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img id="productImage" src="" class="img-fluid rounded-start product-image" alt="Product image">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title" id="productTitle"></h5>
                            <p class="card-text"><strong>Price:</strong> $<span id="productPrice"></span></p>
                            <p class="card-text" id="productDescription"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button id="confirmDelete" class="btn btn-danger me-2">Yes, Delete This Product</button>
                <a href="a.php" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Get product ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            const productId = urlParams.get('id');

            if (!productId) {
                $('#productNotFound').text('No product ID provided.').show();
                return;
            }

            // Load product data
            function loadProduct() {
                $('#loader').show();

                $.ajax({
                    url: 'api.php',
                    method: 'GET',
                    data: {
                        endpoint: 'product',
                        id: productId
                    },
                    dataType: 'json',
                    success: function(product) {
                        $('#loader').hide();

                        if (product.error) {
                            $('#productNotFound').text(product.error).show();
                            return;
                        }

                        // Populate product details
                        $('#productName').text(product.name);
                        $('#productTitle').text(product.name);
                        $('#productPrice').text(parseFloat(product.price).toFixed(2));
                        $('#productDescription').text(product.description || 'No description available');
                        $('#productImage').attr('src', product.image || 'https://via.placeholder.com/300');

                        // Show product details
                        $('#productDetails').show();
                    },
                    error: function(xhr, status, error) {
                        $('#loader').hide();
                        let errorMsg = 'Failed to load product data.';

                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                errorMsg = response.error;
                            }
                        } catch (e) {
                            console.error('Error parsing error response:', e);
                        }

                        $('#productNotFound').text(errorMsg).show();
                    }
                });
            }

            // Initial load
            loadProduct();

            // Delete confirmation
            $('#confirmDelete').click(function() {
                $('#loader').show();
                $('#errorContainer').hide();
                $('#successMessage').hide();

                $.ajax({
                    url: `api.php?endpoint=product&id=${productId}`,
                    method: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        $('#loader').hide();
                        $('#productDetails').hide();
                        $('#successMessage').show();

                        // Redirect after a delay
                        setTimeout(function() {
                            window.location.href = 'a.php';
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        $('#loader').hide();
                        let errorMsg = 'Failed to delete product. Please try again.';

                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                errorMsg = response.error;
                            }
                        } catch (e) {
                            console.error('Error parsing error response:', e);
                        }

                        $('#errorContainer').text(errorMsg).show();
                    }
                });
            });
        });
    </script>
</body>

</html>