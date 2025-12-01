<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm hợp chất mới</title>
    <style>
        button {
            font-family: arial;
            background-color: rgb(196, 215, 178);
            color: #000;
            border: none;
            border-radius: 8px;
            width: 100px;
            height: 45px;
            transition: .3s;
            border-color: rgb(160, 196, 157);
            border-width: thick;
            flex: 0 0 auto;
            cursor: pointer;
        }

        button:hover {
            background-color: rgb(160, 196, 157);
            box-shadow: 0 0 0 5px rgb(225, 236, 200);
            color: #fff;
        }

        .centered-content {
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 100%;
            padding-bottom: 100px;
        }
        input{
            width: 500px;
            height: 30px;
        }
        textarea{
            width: 500px;
        }
    </style>
</head>

<body>
    <?php
    include("header.php");
    ?>
    <div class="centered-content">
        <h1>Thêm hợp chất mới</h1>

        <form action="insert.php" method="post">
            <label for="name">Tên hợp chất:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="cid">Mã hợp chất (cid):</label><br>
            <input type="text" id="cid" name="cid" required><br><br>

            <label for="smiles">Chuỗi SMILES:</label><br>
            <input type="text" id="smiles" name="smiles"><br><br>

            <label for="benefit">Lợi ích:</label><br>
            <textarea id="benefit" name="benefit" rows="5"></textarea><br><br>

            <label for="weakness">Hạn chế:</label><br>
            <textarea id="weakness" name="weakness" rows="5"></textarea><br><br>

            <label for="origin">Nguồn gốc:</label><br>
            <textarea type="text" id="origin" name="origin" rows="5"></textarea><br><br>

            <label for="purpose">Mục đích sử dụng:</label><br>
            <textarea id="purpose" name="purpose" rows="5"></textarea><br><br>
            <label for="doi">Mã số DOI bài báo (nếu có):</label><br>
            <input type="text" id="doi" name="doi"><br><br>

            <button type="submit" class="button">Thêm</button>
        </form>
    </div>
</body>
<?php
include_once 'footer.php';
?>
</html>
