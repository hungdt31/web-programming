<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông điệp</title>
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
                    <h2 class="text-center text-primary">Nhập số nguyên dương</h2>
                    <form method="post" action="">
                        <div class="input-group">
                            <input type="number" name="target" class="form-control" id="target" required>
                        </div>
                        <div class="text-center d-flex justify-content-center align-items-center mt-3 gap-3">
                            <button type="button" class="btn btn-secondary" onclick="generateRandom()">Random số</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $x = $_POST["target"];
                        if (!filter_var($x, FILTER_VALIDATE_INT) || $x <= 0) {
                            echo '<div class="mt-3 text-danger text-center fw-bold">Vui lòng nhập số nguyên dương!</div>';
                        } else {
                            $y = $x % 5;
                            echo '<div class="mt-3 text-center fw-bold">';
                            switch ($y):
                                case 0:
                                    echo "Hello";
                                    break;
                                case 1:
                                    echo "How are you?";
                                    break;
                                case 2:
                                    echo "I'm doing well, thank you";
                                    break;
                                case 3:
                                    echo "See you later";
                                    break;
                                case 4:
                                    echo "Good-bye";
                                    break;
                            endswitch;
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function generateRandom() {
            let randomNumber = Math.floor(Math.random() * 100) + 1; // Tạo số từ 1 đến 100
            document.getElementById('target').value = randomNumber;
        }
    </script>

</body>

</html>