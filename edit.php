<?php
// Kết nối đến CSDL
include('connectdb.php');

// Kiểm tra xem ID của mục cần chỉnh sửa đã được truyền từ URL chưa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn dữ liệu từ cơ sở dữ liệu dựa trên ID
    $sql = "SELECT * FROM compoundBioLib WHERE stt=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $cid = $row["cid"];
        $smiles = $row["smiles"];
        $benefit = $row["benefit"];
        $weakness = $row["weakness"];
        $origin = $row["origin"];
        $purpose = $row["purpose"];
        $doi = $row["doi"];

        // Hiển thị biểu mẫu chỉnh sửa
        echo "<!DOCTYPE html>";
        echo "<html lang='en'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Chỉnh sửa chất</title>";
        echo "<style>";
        echo "body {";
        echo "    font-family: Arial, sans-serif;";
        echo "    text-align: center;";
        echo "    margin: 20px;";
        echo "}";
        echo "form {";
        echo "    display: inline-block;";
        echo "    text-align: left;";
        echo "}";
        echo "input[type='text'] {";
        echo "    width: 300px;";
        echo "    padding: 8px;";
        echo "    margin: 5px;";
        echo "}";
        echo "button {";
        echo "    background-color: #4CAF50;";
        echo "    border: none;";
        echo "    color: white;";
        echo "    padding: 10px 20px;";
        echo "    text-align: center;";
        echo "    text-decoration: none;";
        echo "    display: inline-block;";
        echo "    font-size: 16px;";
        echo "    margin-bottom: 10px;";
        echo "    cursor: pointer;";
        echo "}";
        echo "button:hover {";
        echo "    background-color: #45a049;";
        echo "}";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        echo "<h2>Chỉnh sửa chất</h2>";
        echo "<form action='update.php' method='POST'>";
        echo "<input type='hidden' name='id' value='$id'>";
        echo "Tên chất:<br> <input type='text' name='name' value='$name'><br>";
        echo "PubChem CID:<br> <input type='text' name='cid' value='$cid'><br>";
        echo "Chuỗi SMILES: <br> <input type='text' name='smiles' value='$smiles'><br>";
        echo "Lợi ích: <br> <textarea name='benefit' rows='4' cols='50'>$benefit</textarea><br>";
        echo "Hạn chế: <br> <textarea name='weakness' rows='4' cols='50'>$weakness</textarea><br>";
        echo "Nguồn gốc: <br> <textarea name='origin' rows='4' cols='50'>$origin</textarea><br>";
        echo "Mục dích sử dụng: <br> <textarea name='purpose' rows='4' cols='50'>$purpose</textarea><br>";
        echo "Mã số DOI bài báo (nếu có): <br> <textarea  name='doi' rows='4' cols='50'>$doi</textarea><br>";
        echo "<button type='submit'>Lưu chỉnh sửa</button>";
        echo "</form>";
        echo "</body>";
        echo "</html>";
    } else {
        echo "Không tìm thấy mục để chỉnh sửa.";
    }
} else {
    echo "ID không được cung cấp.";
}

// Đóng kết nối
$conn->close();
