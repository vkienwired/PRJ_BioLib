<?php
session_start();
// 1. Nạp config để dùng cổng 3307 và biến $conn
require_once 'config.php'; 

// Chốt chặn: Phải đăng nhập mới được thực hiện
if (!isset($_SESSION['user_id'])) {
    die("Lỗi: Bạn không có quyền truy cập!");
}

if (isset($_POST['name'], $_POST['cid'])) {
    // 2. Làm sạch dữ liệu chống SQL Injection
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $cid      = mysqli_real_escape_string($conn, $_POST['cid']); 
    $smiles   = mysqli_real_escape_string($conn, $_POST['smiles']);
    $benefit  = mysqli_real_escape_string($conn, $_POST['benefit']);
    $weakness = mysqli_real_escape_string($conn, $_POST['weakness']);
    $origin   = mysqli_real_escape_string($conn, $_POST['origin']);
    $purpose  = mysqli_real_escape_string($conn, $_POST['purpose']);
    $doi      = mysqli_real_escape_string($conn, $_POST['doi']);
    
    $user_id = $_SESSION['user_id'];
    // Ép kiểu role về chữ thường để so sánh chuẩn xác
    $role = isset($_SESSION['role']) ? strtolower($_SESSION['role']) : 'user';

    // --- PHÂN NHÁNH XỬ LÝ ---

    if ($role === 'admin') {
        // TRƯỜNG HỢP 1: ADMIN SỬA -> Cập nhật trực tiếp vào bản ghi gốc
        // Tên bảng đồng bộ là compoundbiolib (viết thường theo SQL)
        $sql = "UPDATE compoundbiolib 
                SET name='$name', smiles='$smiles', benefit='$benefit', weakness='$weakness', 
                    origin='$origin', purpose='$purpose', doi='$doi', status='approved' 
                WHERE cid='$cid' AND (status='approved' OR status='' OR status IS NULL)";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Sếp Admin đã cập nhật dữ liệu trực tiếp thành công!'); window.location.href='list.php';</script>";
        } else {
            echo "<script>alert('Lỗi cập nhật Admin: " . mysqli_error($conn) . "'); window.history.back();</script>";
        }

    } else {
        // TRƯỜNG HỢP 2: USER SỬA -> Tạo bản nháp (Shadow Copy) chờ duyệt
        
        // BƯỚC A: Tìm STT của bản gốc để làm tham chiếu cho cột edit_of
        $find_sql = "SELECT stt FROM compoundbiolib WHERE cid='$cid' AND (status='approved' OR status='' OR status IS NULL) LIMIT 1";
        $find_res = mysqli_query($conn, $find_sql);
        
        if ($find_res && mysqli_num_rows($find_res) > 0) {
            $orig_row = mysqli_fetch_assoc($find_res);
            $orig_stt = $orig_row['stt']; 

            // BƯỚC B: Chèn một dòng MỚI hoàn toàn vào bảng với status='pending_update'
            $insert_sql = "INSERT INTO compoundbiolib (name, cid, smiles, benefit, weakness, origin, purpose, doi, status, created_by, edit_of) 
                           VALUES ('$name', '$cid', '$smiles', '$benefit', '$weakness', '$origin', '$purpose', '$doi', 'pending_update', '$user_id', '$orig_stt')";
                           
            if (mysqli_query($conn, $insert_sql)) {
                echo "<script>alert('Thay đổi đã được gửi dưới dạng bản nháp. Vui lòng chờ Admin phê duyệt.'); window.location.href='list.php';</script>";
            } else {
                echo "<script>alert('Lỗi tạo bản nháp: " . mysqli_error($conn) . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Lỗi: Không tìm thấy hợp chất gốc đang hoạt động để sửa!'); window.history.back();</script>";
        }
    }
} else {
    echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
}

mysqli_close($conn);
?>