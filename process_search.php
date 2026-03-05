<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu dữ liệu - BIOLIB</title>
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
            font-family: Arial, sans-serif;
            background-color: rgb(196, 215, 178);
            color: #0d0c22;
            border: none;
            border-radius: 8px;
            width: 130px;
            height: 45px;
            transition: .3s;
            border-color: rgb(160, 196, 157);
            border-width: thick;
            cursor: pointer;
            flex: 0 0 auto;
            margin-right: 10px;
            margin-left: 5px;
            font-weight: bold;
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
            width: 98%;
            background-color: #fff;
            border-collapse: collapse;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        th {
            background-color: rgb(160, 196, 157);
            color: #000;
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        table a {
            text-decoration: none;
            color: #008bf8;
            font-weight: bold;
        }

        table a:hover {
            color: #f77f00;
            text-decoration: underline;
        }

        .svg2d {
            width: 180px;
            height: auto;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
</head>
<?php
include_once 'header.php';
?>

<body>
    <div class="centered-content">
        <h2 style="color: #333; margin-bottom: 20px;">Tra cứu Cơ sở dữ liệu Hợp chất Sinh học</h2>
        <form action="process_search.php" method="GET">
            <div class="group">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="icon">
                    <g>
                        <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                    </g>
                </svg>
                <input class="input" type="search" name="search" placeholder="Nhập danh pháp, mã PubChem CID..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <input type="submit" class="button" value="Tìm kiếm"></input>
                <div class="button-container">
                    <input onclick="window.location.href='addnew.php'" type="button" class="button" value="Thêm dữ liệu"></input>
                </div>
            </div>
        </form>

        <br>

        <?php
        include_once("connectdb.php");

        if (isset($_GET['search']) && trim($_GET['search']) !== '') {
            $search = trim($_GET['search']);
            $search_safe = mysqli_real_escape_string($conn, $search);

            // Truy vấn dữ liệu đã được hệ thống phê duyệt
            $sql = "SELECT * FROM compoundBioLib 
                    WHERE status = 'approved' 
                      AND (name LIKE '%$search_safe%' 
                           OR cid LIKE '%$search_safe%' 
                           OR benefit LIKE '%$search_safe%' 
                           OR origin LIKE '%$search_safe%')";
            
            $result = mysqli_query($conn, $sql);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<table>";
                    echo "<tr>
                            <th style='width: 4%'>STT</th>
                            <th style='width: 10%'>Danh pháp</th>
                            <th style='width: 8%'>PubChem CID</th>
                            <th style='width: 15%'>Cấu trúc 2D</th>
                            <th style='width: 16%'>Hoạt tính sinh học</th>
                            <th style='width: 14%'>Hạn chế</th>
                            <th style='width: 10%'>Nguồn gốc</th>
                            <th style='width: 10%'>Đích tác dụng</th>
                            <th style='width: 7%'>Tài liệu (DOI)</th>
                            <th style='width: 6%'>Tùy chỉnh</th>
                        </tr>";
                    
                    $stt_hienthi = 1;

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $stt_hienthi++ . "</td>";
                        echo "<td><strong>" . htmlspecialchars($row["name"]) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row["cid"]) . "</td>";
                        
                        $imageFilename = "./img/" . htmlspecialchars($row["cid"]) . ".svg";
                        if (file_exists($imageFilename)) {
                            echo "<td><img class='svg2d' src='" . htmlspecialchars($imageFilename) . "' alt='Cấu trúc 2D của " . htmlspecialchars($row["name"]) . "' /></td>";
                        } else {
                            echo "<td><i style='color:#999;'>Chưa cập nhật</i></td>";
                        }
                        
                        echo "<td class='text-justify'>" . htmlspecialchars($row["benefit"]) . "</td>";
                        echo "<td class='text-justify'>" . htmlspecialchars($row["weakness"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["origin"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["purpose"]) . "</td>";
                        
                        $doi = trim($row['doi']);
                        if (!empty($doi)) {
                            $doi_link = (strpos($doi, 'http') === 0) ? $doi : 'https://doi.org/' . $doi;
                            echo "<td><a href='" . htmlspecialchars($doi_link) . "' target='_blank'>Truy cập</a></td>";
                        } else {
                            echo "<td><i style='color:#999;'>Không có</i></td>";
                        }
                        
                        // Phân quyền hiển thị: Kiểm tra trạng thái đăng nhập để cấp quyền chỉnh sửa
                        if (isset($_SESSION['user_id'])) {
                            echo "<td><a href='edit.php?id=" . urlencode($row["stt"]) . "'>Chỉnh sửa</a></td>";
                        } else {
                            echo "<td><span style='color:#999; font-size: 14px;' title='Yêu cầu đăng nhập để thực hiện chức năng này'>Khóa</span></td>";
                        }
                        
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<h3 style='color: #d9534f; margin-top: 30px;'>Không tìm thấy dữ liệu phù hợp với từ khóa: \"" . htmlspecialchars($search) . "\"</h3>";
                }
            } else {
                echo "<p style='color: red;'>Đã xảy ra lỗi hệ thống trong quá trình truy vấn: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style='color: #666; margin-top: 20px;'>Vui lòng nhập từ khóa để bắt đầu tra cứu.</p>";
        }
        
        mysqli_close($conn);
        ?>
    </div>
</body>
<?php
include_once 'footer.php';
?>
</html>