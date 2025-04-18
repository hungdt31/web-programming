<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
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

        <h2 class="mb-4">Update Product</h2>

        <div id="errorContainer" class="alert alert-danger" style="display: none;"></div>
        <div id="successMessage" class="alert alert-success" style="display: none;">Product updated successfully!</div>
        <div id="productNotFound" class="alert alert-warning" style="display: none;">Product not found or invalid ID.</div>

        <div class="loader" id="loader"></div>

        <form id="updateProductForm" class="needs-validation" novalidate style="display: none;">
            <input type="hidden" id="productId">

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required minlength="5" maxlength="40">
                <div class="invalid-feedback">
                    Name must be between 5 and 40 characters.
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" maxlength="5000"></textarea>
                <div class="invalid-feedback">
                    Description cannot exceed 5000 characters.
                </div>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0.01" required>
                <div class="invalid-feedback">
                    Price must be a positive number.
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image URL</label>
                <input type="url" class="form-control" id="image" name="image" maxlength="255">
                <div class="invalid-feedback">
                    Please enter a valid URL (max 255 characters).
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="a.php" class="btn btn-secondary">Cancel</a>
        </form>
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

            $('#productId').val(productId);

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

                        // Populate form with product data
                        $('#name').val(product.name);
                        $('#description').val(product.description);
                        $('#price').val(product.price);
                        $('#image').val(product.image);

                        // Show form
                        $('#updateProductForm').show();
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

            // Form submission
            $('#updateProductForm').submit(function(e) {
                e.preventDefault();

                const form = $(this)[0];
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    $(form).addClass('was-validated');
                    return;
                }

                // Get form data
                const productData = {
                    name: $('#name').val().trim(),
                    description: $('#description').val().trim(),
                    price: parseFloat($('#price').val()),
                    image: $('#image').val().trim()
                };

                // Show loader and hide messages
                $('#loader').show();
                $('#errorContainer').hide();
                $('#successMessage').hide();

                // Send API request
                $.ajax({
                    url: `api.php?endpoint=product&id=${productId}`,
                    method: 'PUT',
                    data: JSON.stringify(productData),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(response) {
                        $('#loader').hide();
                        $('#successMessage').show();

                        // Redirect after a delay
                        setTimeout(function() {
                            window.location.href = 'a.php';
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        $('#loader').hide();
                        let errorMsg = 'Failed to update product. Please try again.';

                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                if (Array.isArray(response.error)) {
                                    errorMsg = '<ul>';
                                    response.error.forEach(function(err) {
                                        errorMsg += `<li>${err}</li>`;
                                    });
                                    errorMsg += '</ul>';
                                } else {
                                    errorMsg = response.error;
                                }
                            }
                        } catch (e) {
                            console.error('Error parsing error response:', e);
                        }

                        $('#errorContainer').html(errorMsg).show();
                    }
                });
            });
        });
    </script>
</body>

</html>