<?php
session_start();
include('connectdb.php'); 

// Chốt chặn an ninh: Phải đăng nhập mới được xử lý file này
if (!isset($_SESSION['user_id'])) {
    die("Lỗi: Bạn không có quyền truy cập!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['cid'])) {
    
    // 1. Dọn dẹp dữ liệu chống SQL Injection
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
    
    // Nếu là Admin đăng -> Duyệt luôn. Nếu là User đăng -> Chờ duyệt (pending)
    $status = ($_SESSION['role'] == 'admin') ? 'approved' : 'pending';

    if (!empty($name) && !empty($cid)) {

        // 2. Thêm dữ liệu vào CSDL (Bao gồm cả status và created_by)
        $sql = "INSERT INTO compoundBioLib (name, cid, smiles, benefit, weakness, origin, purpose, doi, status, created_by)
                VALUES ('$name', '$cid', '$smiles', '$benefit', '$weakness', '$origin', '$purpose', '$doi', '$status', '$created_by')";

        if (mysqli_query($conn, $sql)) {
            // 3. Nếu chèn DB thành công và có SMILES, gọi Python vẽ hình
            if (!empty($_POST['smiles'])) {
                // Đường dẫn Python và Script (cậu nhớ kiểm tra lại đường dẫn này cho khớp với máy cậu nhé)
                $pythonPath = 'C:\\Users\\ADMIN\\anaconda3\\envs\\rdkit-env\\python.exe';
                $scriptPath = 'C:\\xampp\\htdocs\\BioLib\\smiles.py';

                // Bọc $_POST['smiles'] vào escapeshellarg để Python hiểu chuỗi dài
                $cmd = escapeshellcmd($pythonPath) . ' ' . escapeshellarg($scriptPath) . ' ' .
                       escapeshellarg($_POST['smiles']) . ' ' . escapeshellarg($_POST['cid']);

                $output = [];
                $returnVar = 0;
                exec($cmd . " 2>&1", $output, $returnVar);
            }

            // Thông báo tùy theo quyền
            if ($status == 'pending') {
                echo "<script>alert('Cảm ơn cậu! Dữ liệu đã gửi thành công và đang chờ Admin duyệt nhé.');</script>";
            } else {
                echo "<script>alert('Sếp Admin đã thêm dữ liệu mới thành công!');</script>";
            }

        } else {
            echo "<script>alert('Ối, có lỗi khi lưu dữ liệu: " . mysqli_error($conn) . "');</script>";
        }
        
    } else {
        echo "<script>alert('Cậu quên nhập Tên hoặc Mã CID rồi!');</script>";
    }
}

// Đóng kết nối
mysqli_close($conn);

// Trở về trang chủ
echo "<script>
setTimeout(() => {
    window.location.href = 'index.php';
}, 100);
</script>";
?>
