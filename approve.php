<?php
session_start();
// 1. Nạp file cấu hình dùng chung (cổng 3307)
require_once 'config.php';

// Xác thực quyền Quản trị viên
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    die("error"); 
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Truy xuất thông tin bản ghi đang được bấm duyệt (đồng bộ tên bảng compoundbiolib)
    $res = mysqli_query($conn, "SELECT * FROM compoundbiolib WHERE stt = '$id'");
    
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        
        // --- PHÂN NHÁNH 1: Xử lý Bản cập nhật (Trường hợp User sửa chất cũ) ---
        if ($row['status'] == 'pending_update' && !empty($row['edit_of'])) {
            
            $orig_id = mysqli_real_escape_string($conn, $row['edit_of']); // ID chất gốc (ví dụ: 124)
            $name    = mysqli_real_escape_string($conn, $row['name']);
            $cid     = mysqli_real_escape_string($conn, $row['cid']);
            $smiles  = mysqli_real_escape_string($conn, $row['smiles']);
            $benefit = mysqli_real_escape_string($conn, $row['benefit']);
            $weakness= mysqli_real_escape_string($conn, $row['weakness']);
            $origin  = mysqli_real_escape_string($conn, $row['origin']);
            $purpose = mysqli_real_escape_string($conn, $row['purpose']);
            $doi     = mysqli_real_escape_string($conn, $row['doi']);
            
            // 1. Thực thi lệnh UPDATE để ghi đè dữ liệu mới vào dòng gốc
            $update_sql = "UPDATE compoundbiolib 
                           SET name='$name', cid='$cid', smiles='$smiles', benefit='$benefit', 
                               weakness='$weakness', origin='$origin', purpose='$purpose', doi='$doi',
                               status='approved'
                           WHERE stt = '$orig_id'";
            
            if (mysqli_query($conn, $update_sql)) {
                // 2. Xóa bản nháp (dòng pending_update) sau khi đã "nhập xác" xong
                mysqli_query($conn, "DELETE FROM compoundbiolib WHERE stt = '$id'"); 
                echo "success";
            } else {
                echo "error";
            }
            
        } else {
            // --- PHÂN NHÁNH 2: Xử lý Bản ghi mới hoàn toàn (Trường hợp User thêm chất mới) ---
            $sql = "UPDATE compoundbiolib SET status = 'approved' WHERE stt = '$id'";
            if (mysqli_query($conn, $sql)) {
                echo "success";
            } else {
                echo "error";
            }
        }
    } else {
        echo "error";
    }
} else {
    echo "error";
}

mysqli_close($conn);
?>