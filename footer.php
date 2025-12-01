<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding-bottom: 50px;
            /* Để tạo khoảng trống dưới cùng cho footer */
            min-height: 100vh;
            /* Đảm bảo chiều cao của trang ít nhất bằng chiều cao của màn hình */
            position: relative;
        }

        .footer {
            position: absolute;
            margin-bottom: 0;
            width: 100%;
            height: 120px;
            /* Chiều cao của footer */
            background-color: rgb(160, 196, 157);
            color: #000;
            text-align: center;
            line-height: 50px;
            /* Căn giữa nội dung trong footer theo chiều dọc */
        }

        .footer-menu {
            width: 400px;
        }

        .footer-menu ul {
            list-style-type: none;
            /* Loại bỏ dấu đầu dòng của danh sách */
            padding: 0;
            margin: 0;
        }
        
        
    </style>
</head>

<body>
    <footer class="footer">
            <ul class="menu">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="search.php">Tìm kiếm</a></li>
                <li><a href="addnew.php">Thêm mới</a></li>
                <li><a href="aboutus.php">Thông tin</a></li>
                <li><a href="contact.php">Liên hệ</a></li>
            </ul>
        <span> © 2024 All rights reserved </span>
    </footer>
</body>

</html>