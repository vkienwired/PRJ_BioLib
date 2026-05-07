<?php
session_start();
// 1. Nạp file cấu hình chung thay vì connectdb.php để dùng đúng cổng 3307
require_once 'config.php';

// Xác thực quyền Admin: Chỗ này cực quan trọng để tránh bị phá hoại CSDL
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("error"); // Trả về error để Javascript ở Dashboard xử lý
}

if (isset($_GET['id'])) {
    // 2. Làm sạch ID để chống SQL Injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // 3. Thực hiện lệnh xóa
    // Tớ giữ nguyên câu lệnh của cậu vì nó sẽ xóa sạch bản ghi dựa trên 'stt' 
    // bất kể là đang pending hay approved.
    $sql = "DELETE FROM compoundbiolib WHERE stt = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        // Trả về đúng chữ "success" để Ajax ở Dashboard biết mà ẩn dòng đó đi
        echo "success"; 
    } else {
        echo "error: " . mysqli_error($conn);
    }
} else {
    echo "error_no_id";
}

// Đóng kết nối
mysqli_close($conn);
?>