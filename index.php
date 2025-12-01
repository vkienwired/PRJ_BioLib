<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thư viện hợp chất sinh học Việt Nam</title>
    <style>
        .group {
            display: inline-flex;
            line-height: 28px;
            align-items: center;
            position: relative;
            width: 500px;
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
            width: 100px;
            height: 45px;
            transition: .3s;
            border-color: rgb(160, 196, 157);
            border-width: thick;
            /* margin: 20px auto; */
            cursor: pointer;
            margin-left: 10px;
        }

        .button:hover,
        .card-button:hover {
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
            padding-bottom: 200px;
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

        .card {
            width: 600px;
            height: 400px;
            border-radius: 20px;
            background: #f5f5f5;
            position: relative;
            padding: 1.8rem;
            border: 2px solid rgb(160, 196, 157);
            transition: 0.5s ease-out;
            overflow: visible;
            margin-top: 30px;

            /* display: inline-flex;
            line-height: 28px;
            align-items: center; */
        }

        .card-details {
            color: black;
            height: 100%;
            gap: .5em;
            display: grid;
            place-content: center;
        }

        .card-button {
            transform: translate(-50%, 125%);
            width: 60%;
            border-radius: 1rem;
            border: none;
            background-color: rgb(196, 215, 178);
            color: #000;
            font-size: 1rem;
            padding: .5rem 1rem;
            position: absolute;
            left: 50%;
            bottom: 0;
            opacity: 0;
            transition: 0.3s ease-out;
        }

        .text-body {
            color: rgb(134, 134, 134);
            text-align: justify;
            text-indent: 2em;
            margin: 0;
            padding: 0;
        }

        .text-title {
            font-size: 1.5em;
            font-weight: bold;
            margin: 0 auto;
        }

        /*Hover*/
        .card:hover {
            border-color: #008bf8;
            box-shadow: 0 4px 18px 0 rgb(160, 196, 157);
        }

        .card:hover .card-button {
            transform: translate(-50%, 50%);
            opacity: 1;
        }

        .logo-card {
            width: 80px;
            height: auto;
            margin: 0 auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<?php
include_once 'header.php';
?>

<body>
    <div class="centered-content">
        <h2>Tìm kiếm hợp chất sinh học</h2><br>
        <form action="process_search.php" method="GET">
            <div class="group">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="icon">
                    <g>
                        <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                    </g>
                </svg>
                <input class="input" type="search" name="search" placeholder="Search" />
                <input type="submit" class="button" value="Tìm kiếm"></input>
            </div>
        </form>
        <div class="card">
            <div class="card-details">
                <img src="img/LOGO BIOLIB//BIOLIB-01.png" alt="Logo" class="logo-card">
                <p class="text-title">BioLib là gì?</p>
                <p class="text-body"> BioLib là cổng thông tin về hợp chất sinh học của các chất có trong các bài
                    thuốc dân gian Việt Nam hay những hợp chất có nguồn gốc từ thực vật thiên nhiên. Cổng
                    thông tin cung cấp cho người dùng những thông tin cần thiết cơ bản về những hợp chất này. Người dùng
                    có thể tìm kiếm theo tên hợp chất, mã PubChem CID của hợp chất, lợi ích hay nguồn gốc của chúng.
                    Ngoài ra bạn có thể thêm các chất mới để cùng làm đa dạng hóa cơ sở dữ liệu này. Vui lòng kiểm tra
                    và đóng góp ý kiến với chúng tôi nếu bạn có ý kiến đóng góp cho cơ sở dữ liệu và trang web.</p>
            </div>
            <button onclick="window.location.href='aboutus.php'" class="card-button">More info</button>
        </div>
    </div>

</body>
<?php
include_once 'footer.php';
?>

</html>
