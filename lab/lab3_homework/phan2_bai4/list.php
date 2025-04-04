<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <style>
        * { font-family: 'Poppins', sans-serif; }
    </style>
    <div class="container mt-5">
        <h2 class="text-center">Product List</h2>

        <!-- Search Form -->
        <form id="searchForm" class="mb-3 d-flex">
            <input type="text" id="searchInput" name="search" class="form-control me-2 w-50" placeholder="Search by name or description...">
            <button type="submit" class="btn btn-primary me-2">Search</button>
            <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
        </form>

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
            <tbody id="productTable"></tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>

        <!-- Modal for Product Details -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Product Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentPage = 1;

            // Load products
            function loadProducts(page, search = '') {
                $.ajax({
                    url: 'fetch_data.php',
                    method: 'GET',
                    data: { action: 'list', page: page, search: search },
                    success: function(response) {
                        let data = response;
                        let tbody = $('#productTable');
                        tbody.empty();

                        if (data.products.length === 0) {
                            tbody.append('<tr><td colspan="6" class="text-center">No products found.</td></tr>');
                        } else {
                            data.products.forEach(product => {
                                tbody.append(`
                                    <tr>
                                        <td>${product.id}</td>
                                        <td><img src="${product.image}" alt="${product.name}" style="width: 100px; height: 100px;"></td>
                                        <td><a href="#" class="view-detail" data-id="${product.id}">${product.name}</a></td>
                                        <td>${product.price}</td>
                                        <td>${product.description}</td>
                                        <td><button class="btn btn-info view-detail" data-id="${product.id}">View Details</button></td>
                                    </tr>
                                `);
                            });
                        }

                        // Update pagination
                        let pagination = $('#pagination');
                        pagination.empty();
                        if (data.current_page > 1) {
                            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">Previous</a></li>`);
                        }
                        for (let i = 1; i <= data.total_pages; i++) {
                            pagination.append(`<li class="page-item ${i === data.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`);
                        }
                        if (data.current_page < data.total_pages) {
                            pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">Next</a></li>`);
                        }
                    }
                });
            }

            // Initial load
            loadProducts(currentPage);

            // Search form submit
            $('#searchForm').submit(function(e) {
                e.preventDefault();
                currentPage = 1;
                loadProducts(currentPage, $('#searchInput').val());
            });

            // Reset button
            $('#resetBtn').click(function() {
                $('#searchInput').val('');
                currentPage = 1;
                loadProducts(currentPage);
            });

            // Pagination click
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                currentPage = $(this).data('page');
                loadProducts(currentPage, $('#searchInput').val());
            });

            // View detail click
            $(document).on('click', '.view-detail', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url: 'fetch_data.php',
                    method: 'GET',
                    data: { action: 'detail', id: id },
                    success: function(response) {
                        let product = response;
                        if (product.error) {
                            alert(product.error);
                        } else {
                            $('#modalBody').html(`
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="${product.image}" class="img-fluid" alt="${product.name}">
                                    </div>
                                    <div class="col-md-8">
                                        <h5>${product.name}</h5>
                                        <p><strong>Price:</strong> $${product.price}</p>
                                        <p><strong>Description:</strong> ${product.description}</p>
                                    </div>
                                </div>
                            `);
                            $('#detailModal').modal('show');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>