<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
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
        <h2 class="text-center mb-4">Products List</h2>

        <!-- Search form -->
        <div class="mb-3 d-flex">
            <input type="text" id="searchInput" class="form-control me-2 w-50" placeholder="Search by name or description...">
            <button id="searchBtn" class="btn btn-primary me-2">Search</button>
            <button id="resetBtn" class="btn btn-secondary">Reset</button>
        </div>

        <div class="mb-3">
            <a href="b.php" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 7a.5.5 0 0 1-.5.5H8v2.5a.5.5 0 0 1-1 0V8.5H4.5a.5.5 0 0 1 0-1H7V4a.5.5 0 0 1 1 0v2.5h2.5a.5.5 0 0 1 .5.5z" />
                </svg>
                Add new product
            </a>
        </div>

        <div class="loader" id="loader"></div>
        <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>

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
            <tbody id="productTableBody">
                <!-- Products will be loaded here -->
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination" id="pagination">
                <!-- Pagination will be loaded here -->
            </ul>
        </nav>
    </div>

    <script>
        $(document).ready(function() {
            let currentPage = 1;
            let searchQuery = '';

            // Load products function
            function loadProducts(page = 1, search = '') {
                $('#loader').show();
                $('#errorMessage').hide();

                $.ajax({
                    url: 'api.php',
                    method: 'GET',
                    data: {
                        endpoint: 'products',
                        page: page,
                        search: search
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#loader').hide();
                        const productTableBody = $('#productTableBody');
                        productTableBody.empty();

                        if (response.products.length === 0) {
                            productTableBody.append('<tr><td colspan="6" class="text-center">No products found.</td></tr>');
                        } else {
                            response.products.forEach(function(product) {
                                productTableBody.append(`
                                    <tr>
                                        <td>${product.id}</td>
                                        <td><img src="${product.image || 'https://via.placeholder.com/100'}" alt="${product.name}" style="width: 100px; height: 100px; object-fit: cover;"></td>
                                        <td>${product.name}</td>
                                        <td>$${parseFloat(product.price).toFixed(2)}</td>
                                        <td>${product.description || ''}</td>
                                        <td>
                                            <a href="c.php?id=${product.id}" class="btn btn-primary btn-sm mb-2">Update</a>
                                            <a href="d.php?id=${product.id}" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                `);
                            });
                        }

                        // Update pagination
                        updatePagination(response);
                    },
                    error: function(xhr, status, error) {
                        $('#loader').hide();
                        let errorMsg = 'Failed to load products';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                errorMsg = response.error;
                            }
                        } catch (e) {
                            console.error('Error parsing error response:', e);
                        }
                        $('#errorMessage').text(errorMsg).show();
                    }
                });
            }

            // Update pagination function
            function updatePagination(data) {
                const pagination = $('#pagination');
                pagination.empty();

                if (data.current_page > 1) {
                    pagination.append(`
                        <li class="page-item">
                            <a class="page-link page-nav" href="#" data-page="${data.current_page - 1}">Previous</a>
                        </li>
                    `);
                }

                for (let i = 1; i <= data.total_pages; i++) {
                    pagination.append(`
                        <li class="page-item ${i === data.current_page ? 'active' : ''}">
                            <a class="page-link page-number" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                }

                if (data.current_page < data.total_pages) {
                    pagination.append(`
                        <li class="page-item">
                            <a class="page-link page-nav" href="#" data-page="${data.current_page + 1}">Next</a>
                        </li>
                    `);
                }
            }

            // Initial load
            loadProducts();

            // Search button click
            $('#searchBtn').click(function() {
                searchQuery = $('#searchInput').val();
                currentPage = 1;
                loadProducts(currentPage, searchQuery);
            });

            // Reset button click
            $('#resetBtn').click(function() {
                $('#searchInput').val('');
                searchQuery = '';
                currentPage = 1;
                loadProducts(currentPage, searchQuery);
            });

            // Pagination click
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                currentPage = $(this).data('page');
                loadProducts(currentPage, searchQuery);
            });
        });
    </script>
</body>

</html>