<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        .product-image { max-width: 300px; height: auto; }
    </style>
    <div class="container mt-5">
        <h2 class="text-center">Product Details</h2>
        <div class="card" id="productDetail"></div>
        <a href="list.php" class="btn btn-secondary mt-3">Back to List</a>
    </div>

    <script>
        $(document).ready(function() {
            let urlParams = new URLSearchParams(window.location.search);
            let id = urlParams.get('id');

            if (!id) {
                $('#productDetail').html('<p class="text-danger">Invalid product ID!</p>');
                return;
            }

            $.ajax({
                url: 'fetch_data.php',
                method: 'GET',
                data: { action: 'detail', id: id },
                success: function(response) {
                    let product = JSON.parse(response);
                    if (product.error) {
                        $('#productDetail').html(`<p class="text-danger">${product.error}</p>`);
                    } else {
                        $('#productDetail').html(`
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="${product.image}" class="img-fluid product-image" alt="${product.name}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">${product.name}</h5>
                                        <p class="card-text"><strong>Price:</strong> $${product.price}</p>
                                        <p class="card-text"><strong>Description:</strong> ${product.description}</p>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                }
            });
        });
    </script>
</body>
</html>