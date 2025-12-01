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

        .button{
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
            /* margin: 20px auto; */
            cursor: pointer;
            flex: 0 0 auto;
            margin-right: 10px;
            margin-left: 5px;
        }

        button:hover {
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
            margin-left: 10px;
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

        table {
            width: 100%;
            background-color: #fff;
        }
        th{
            background-color: rgb(160, 196, 157);
        }
        table a{
            text-decoration: none;
            color: #000;
        }
        td,
        th {
            padding: 5px;
        }

        .svg2d {
            width: 200px;
            height: auto;
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
                <input type="submit" class="button" value="Tìm kiếm"></input>
                <div class="button-container">
                    <input onclick="window.location.href='addnew.php'" type="button"  class="button" value="Thêm chất mới "></input>
                </div>
            </div>
        </form>

        <br>

        <?php
        include_once("connectdb.php");
        $sql_initial = "
        SET @row_number = 0;
        UPDATE compoundBioLib SET new_stt = (@row_number := @row_number + 1) ORDER BY stt;
        UPDATE compoundBioLib SET stt = new_stt;
        ";

        // Thực thi loạt lệnh SQL ban đầu
        if (mysqli_multi_query($conn, $sql_initial)) {
            // Giải phóng tất cả các kết quả của các truy vấn trước đó
            while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            }
            if (isset($_GET['search'])) {
                $search = $_GET['search'];

                $sql = "SELECT * FROM compoundBioLib WHERE name LIKE '%$search%' OR cid LIKE '%$search%' 
                OR benefit LIKE '%$search%' OR origin LIKE '%$search%'";
                if ($result = $conn->query($sql)) {
                    if ($result->num_rows > 0) {
                        echo "<table border='1'>";
                        echo "<tr>
        <th>STT</th>
        <th>Hợp chất</th>
        <th>PubChem CID</th>
        <th>Hình ảnh cấu trúc 2D</th>
        <th>Công dụng</th>
        <th>Nhược điểm</th>
        <th>Nguồn gốc</th>
        <th>Đích sàng lọc</th>
        <th>Tài liệu tham khảo</th>
        <th>Chỉnh sửa</th>
    </tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["stt"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["cid"]) . "</td>";
                            $imageFilename = "./img/" . htmlspecialchars($row["cid"]) . ".svg";
                            if (file_exists($imageFilename)) {
                                echo "<td><img class='svg2d' src='" . htmlspecialchars($imageFilename) . "' alt='Image' /></td>";
                            } else {
                                echo "<td>Không tìm thấy hình ảnh</td>";
                            }
                            echo "<td>" . htmlspecialchars($row["benefit"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["weakness"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["origin"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["purpose"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["doi"]) . "</td>";
                            echo "<td><a href='edit.php?id=" . htmlspecialchars($row["stt"]) . "'>Chỉnh sửa</a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Không tìm thấy kết quả.</p>";
                    }
                } else {
                    echo "<p>Lỗi truy vấn: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Vui lòng nhập từ khóa tìm kiếm.</p>";
            }
        }
        $conn->close();
        ?>
    </div>
</body>
<?php
include_once 'footer.php';
?>

</html>
