<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Máy tính đơn giản</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-light">


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6"> <!-- Chiếm 100% trên mobile, 50% trên lg -->
                <div class="card shadow-lg p-4">
                    <h2 class="text-center text-primary">Máy tính đơn giản</h2>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label" for="x">Nhập số thứ nhất:</label>
                            <input type="number" name="x" class="form-control" id="x" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="y">Nhập số thứ hai:</label>
                            <input type="number" name="y" class="form-control" id="y" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Tính toán</button>
                        </div>
                    </form>

                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $x = $_POST["x"];
                        $y = $_POST["y"];

                        echo "<div class='mt-4 p-3 bg-white border rounded'>";
                        echo "<h4 class='text-success border-bottom pb-2'>Kết quả:</h4>";
                        echo "<p><strong>$x + $y =</strong> " . ($x + $y) . "</p>";
                        echo "<p><strong>$x - $y =</strong> " . ($x - $y) . "</p>";
                        echo "<p><strong>$x * $y =</strong> " . ($x * $y) . "</p>";

                        if ($y != 0) {
                            echo "<p><strong>$x / $y =</strong> " . number_format($x / $y, 5) . "</p>";
                            echo "<p><strong>$x % $y =</strong> " . ($x % $y) . "</p>";
                        } else {
                            echo "<p class='text-danger'>Không thể chia cho 0!</p>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>