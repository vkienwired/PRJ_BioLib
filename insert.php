<?php
session_start();
// 1. Nạp config.php thay cho connectdb.php để dùng cổng 3307 và hằng số Python
require_once 'config.php'; 

// Chốt chặn an ninh: Phải đăng nhập mới được xử lý file này
if (!isset($_SESSION['user_id'])) {
    die("Lỗi: Bạn không có quyền truy cập!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['cid'])) {
    
    // 2. Dọn dẹp dữ liệu chống SQL Injection (Sử dụng biến $conn từ config.php)
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $cid      = mysqli_real_escape_string($conn, $_POST['cid']);
    $smiles   = mysqli_real_escape_string($conn, $_POST['smiles']);
    $benefit  = mysqli_real_escape_string($conn, $_POST['benefit']);
    $weakness = mysqli_real_escape_string($conn, $_POST['weakness']);
    $origin   = mysqli_real_escape_string($conn, $_POST['origin']);
    $purpose  = mysqli_real_escape_string($conn, $_POST['purpose']);
    $doi      = mysqli_real_escape_string($conn, $_POST['doi']);

    // Thông tin phân quyền
    $created_by = $_SESSION['user_id'];
    
    // Phân loại: Admin đăng -> Duyệt luôn (approved). User đăng -> Chờ duyệt (pending)
    $status = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') ? 'approved' : 'pending';

    if (!empty($name) && !empty($cid)) {

        // 3. Thêm dữ liệu vào CSDL
        $sql = "INSERT INTO compoundbioLib (name, cid, smiles, benefit, weakness, origin, purpose, doi, status, created_by)
                VALUES ('$name', '$cid', '$smiles', '$benefit', '$weakness', '$origin', '$purpose', '$doi', '$status', '$created_by')";

        if (mysqli_query($conn, $sql)) {
            
            // 4. Nếu chèn DB thành công và có SMILES, gọi Python vẽ hình
            if (!empty($smiles)) {
                // SỬ DỤNG HẰNG SỐ ĐƯỜNG DẪN ĐÃ KHAI BÁO TRONG config.php
                $pythonPath = PYTHON_PATH;
                $scriptPath = SCRIPT_PATH;

                // Bọc tham số vào escapeshellarg để bảo mật hệ thống
                $cmd = escapeshellcmd($pythonPath) . ' ' . escapeshellarg($scriptPath) . ' ' .
                       escapeshellarg($smiles) . ' ' . escapeshellarg($cid);

                $output = [];
                $returnVar = 0;
                // Thực thi lệnh vẽ hình bằng RDKit
                exec($cmd . " 2>&1", $output, $returnVar);
            }

            // Thông báo tùy theo quyền hạn
            if ($status == 'pending') {
                echo "<script>alert('Cảm ơn cậu! Dữ liệu đã gửi thành công và đang chờ Admin duyệt nhé.');</script>";
            } else {
                echo "<script>alert('Sếp Admin đã thêm dữ liệu mới thành công!');</script>";
            }

        } else {
            // Hiển thị lỗi cụ thể từ MySQL nếu không lưu được
            echo "<script>alert('Ối, có lỗi khi lưu dữ liệu: " . mysqli_error($conn) . "');</script>";
        }
        
    } else {
        echo "<script>alert('Cậu quên nhập Tên hoặc Mã CID rồi!');</script>";
    }
}

// Đóng kết nối
mysqli_close($conn);

// Trở về trang danh sách để cập nhật dữ liệu mới
echo "<script>
setTimeout(() => {
    window.location.href = 'list.php';
}, 100);
</script>";
?>