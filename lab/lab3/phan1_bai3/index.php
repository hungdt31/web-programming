<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xuất số lẻ từ 0 đến 100</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            padding: 0 10px;
        }

        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            overflow-x: auto;
            border: 1px solid #ddd;
        }

        ul {
            list-style: lower-alpha;
        }

        li::marker {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div>
        <p><strong>Bài 3. (thư mục phan1_bai3)</strong> Viết chương trình PHP xuất ra màn hình tất cả các số lẻ trong khoảng từ 0 đến 100.</p>
        <ul>
            <li>
                <span><strong>Dùng vòng lặp for:</strong></span>
                <pre><?php
                        highlight_string('<?php
for ($i = 1; $i <= 100; $i += 2) {
    echo $i . " ";
}
?>');
                        ?></pre>
                <p><strong>Kết quả:</strong></p>
                <p>
                    <?php
                    for ($i = 1; $i <= 100; $i += 2) {
                        echo $i . " ";
                    }
                    ?>
                </p>
            </li>

            <li>
                <span><strong>Dùng vòng lặp while:</strong></span>
                <pre><?php
                        highlight_string('<?php
$i = 1;
while ($i <= 100) {
    echo $i . " ";
    $i += 2;
}
?>');
                        ?></pre>
                <p><strong>Kết quả:</strong></p>
                <p>
                    <?php
                    $i = 1;
                    while ($i <= 100) {
                        echo $i . " ";
                        $i += 2;
                    }
                    ?>
                </p>
            </li>
        </ul>
    </div>
</body>

</html>