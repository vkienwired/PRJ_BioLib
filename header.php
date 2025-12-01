<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIOLIB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        .logo {
            position: absolute;
            top: 10;
            left: 0;
            width: 100px;
            height: 100px;
            margin: 10px;
        }

        body {
            font-family: "Arial";
            font-size: 18px;
            margin: 0;
            height: 100vh;
            background: rgb(247, 255, 229);
            color: #000;
            
        }

        :root {
            --color-primary: rgb(247, 255, 229);
        }

        ul {
            margin: 0 auto;
            padding: 0;
            list-style: none;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content:flex-end;
            padding: 0px 20px 0;
            margin-top: 0px;
            top: 100px;
        }

        nav> :is(h1, span.material-symbols-outlined) {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;

        }

        a {
            cursor: pointer;
            text-align: right;
            display: block;
        }

        .menu {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: stretch;
            height: 70px;
        }

        .menu li {
            display: inline-block;
            width: 120px;
            color: #999;
            transition: all 0.3s ease-in-out;
        }


        .menu>li:hover>a {
            color: #999;
        }

        .menu>li:hover>a::before {
            visibility: visible;
            scale: 1 1;
        }

        .menu>li a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 7px;
            font-size: 16px;
            text-decoration: none;
            position: relative;
            height: 100%;
            transition: 0.3s;
            color: #000;
            font-size: 20px;
        }

        .menu>li>a::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 3px;
            bottom: 3px;
            left: 0px;
            background-color: var(--color-primary);
            transition: all 0.2s ease-in-out;
            scale: 0 0;
            visibility: hidden;
        }

        .header1 {
            position: relative;
            background-color: rgb(160, 196, 157);
            text-align: center;
            top: 0;
            width: 100%;
            height: 180px;
            padding: 10px 20px;
        }

        .header2 h1 {
            font-size: 30px;
        }

        .header2 {
            height: 100px;
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <div class="header1">
        <img src="img/LOGO BIOLIB//BIOLIB-02.png" alt="Logo" class="logo">
        <div class="header2">
            <h1>BioLib</h1>
            <h3>CỔNG THÔNG TIN HỢP CHẤT SINH HỌC VIỆT NAM</h3>
        </div>
        <nav>
            <ul class="menu">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="search.php">Tìm kiếm</a></li>
                <li><a href="addnew.php">Thêm mới</a></li>
                <li><a href="aboutus.php">Thông tin</a></li>
                <li><a href="contact.php">Liên hệ</a></li>
            </ul>
        </nav>
    </div>
</body>

</html>