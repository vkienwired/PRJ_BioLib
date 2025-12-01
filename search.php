<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm</title>
    <style>
        .group {
            display: inline-flex;
            line-height: 28px;
            align-items: center;
            position: relative;
            width: 700px;
        }

        .input {
            width: 33.33%;
            height: 40px;
            line-height: 28px;
            padding: 0 1rem;
            padding-left: 2.5rem;
            border: 2px solid transparent;
            border-radius: 8px;
            outline: none;
            background-color: #f3f3f4;
            color: #0d0c22;
            transition: 0.3s ease;
            margin-right: 10px;
            flex-basis: 200px;
            flex: 1;
        }

        .input::placeholder {
            color: #9e9ea7;
        }

        .input:focus,
        input:hover {
            outline: none;
            border-color: rgb(196, 215, 178);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgb(247 127 0 / 10%);
        }

        .icon {
            position: absolute;
            left: 1rem;
            fill: #9e9ea7;
            width: 1rem;
            height: 1rem;
        }

        .button {
            font-family: arial;
            background-color: rgb(196, 215, 178);
            color: #0d0c22;
            border: none;
            border-radius: 8px;
            width: 120px;
            height: 45px;
            transition: .3s;
            border-color: rgb(160, 196, 157);
            border-width: thick;

            display: flex;
            justify-content: center;
            align-items: center;

            cursor: pointer;
            flex: 0 0 auto;
            margin-right: 10px;
            margin-left: 10px;
        }

        .button:hover {
            background-color: rgb(160, 196, 157);
            box-shadow: 0 0 0 5px rgb(225, 236, 200);
            color: #fff;
        }

        @media (max-width: 768px) {
            .input {
                width: 50%;
            }
        }

        @media (max-width: 480px) {
            .input {
                width: 100%;
            }
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin: 0 auto;
        }

        .centered-content {
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 100%;
            padding-bottom: 300px;
        }

        table {
            width: 100%;
        }
    </style>
</head>
<?php
include_once 'header.php';
?>

<body>
    <div class="centered-content">
        <h2>Tìm kiếm hợp chất sinh học</h2>
        <form action="process_search.php" method="GET">
            <div class="group">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="icon">
                    <g>
                        <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                    </g>
                </svg>
                <input class="input" type="search" name="search" placeholder="Search" />
                <div class="button-container"> 
                    <input type="submit" class="button" value="Tìm kiếm"></input>
                    <input onclick="window.location.href='addnew.php'" type="button" class="button" value="Thêm mới"></input>
                </div>
            </div>
        </form>

        <br>
        <table border="1" align="center" cellspacing="0" cellpadding="5" width="850px">
            <tr>
                <th style="width : 3%">STT</th>
                <th style="width : 5%">Hợp chất</th>
                <th style="width : 7%">PubChem CID</th>
                <th style="width : 15%">Hình ảnh cấu trúc 2D</th>
                <th style="width : 15%">Lợi ích</th>
                <th style="width : 15%">Hạn chế</th>
                <th style="width : 7%">Nguồn gốc</th>
                <th style="width : 23%">Mục dích sử dụng</th>
                <th style="width : 5%">Tài liệu tham khảo</th>
                <th style="width : 5%">Chỉnh sửa</th>
            </tr>
        </table>
        <!-- Hiển thị kết quả tìm kiếm -->

    </div>
</body>
<?php
include_once 'footer.php';
?>

</html>