<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }

        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            overflow-x: auto;
            border: 1px solid #ddd;
        }

        .title {
            margin-bottom: 20px;
        }

        .container {
            padding: 10px 20px;
        }

        .content {
            display: flex;
            gap: 30px;
            align-items: center;
            flex-wrap: wrap;
        }

        .block {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            border-left: 2px solid #000;
            border-top: 2px solid #000;
            width: max-content;
        }

        .cell {
            width: 40px;
            height: 40px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 2px solid #000;
            border-right: 2px solid #000;
            background-color: yellow;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">
            <strong>Bài 4. (thư mục phan1_bai4)</strong>
            <span>Dùng 2 vòng lặp lồng nhau để xuất ra màn hình một bảng (table)</span>
        </div>
        <div class="content">
            <pre><?php
                    highlight_string('<?php
for ($i = 1; $i <= 7; $i++) {
    for ($j = 0; $j <= 6; $j++) {
        echo \'<span class="cell">\';
        echo $i + $j * $i;
        echo \'</span>\';
    }
}
?>');
                    ?></pre>
            <div class="block">
                <?php
                for ($i = 1; $i <= 7; $i++) {
                    for ($j = 0; $j <= 6; $j++) {
                        echo '<span class="cell">';
                        echo $i + $j * $i;
                        echo '</span>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>