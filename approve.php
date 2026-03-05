<?php
session_start();
include_once 'connectdb.php';

// Xác thực quyền Quản trị viên từ hệ thống
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    die("error"); 
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Truy xuất thông tin bản ghi đang chờ duyệt
    $res = mysqli_query($conn, "SELECT * FROM compoundBioLib WHERE stt = '$id'");
    
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        
        // Phân nhánh logic: Xử lý Bản cập nhật (Shadow Copy)
        if ($row['status'] == 'pending_update' && !empty($row['edit_of'])) {
            
            $orig_id = mysqli_real_escape_string($conn, $row['edit_of']);
            $name    = mysqli_real_escape_string($conn, $row['name']);
            $cid     = mysqli_real_escape_string($conn, $row['cid']);
            $smiles  = mysqli_real_escape_string($conn, $row['smiles']);
            $benefit = mysqli_real_escape_string($conn, $row['benefit']);
            $weakness= mysqli_real_escape_string($conn, $row['weakness']);
            $origin  = mysqli_real_escape_string($conn, $row['origin']);
            $purpose = mysqli_real_escape_string($conn, $row['purpose']);
            $doi     = mysqli_real_escape_string($conn, $row['doi']);
            
            // Thực thi lệnh ghi đè dữ liệu lên bản ghi gốc
            $update_sql = "UPDATE compoundBioLib 
                           SET name='$name', cid='$cid', smiles='$smiles', benefit='$benefit', 
                               weakness='$weakness', origin='$origin', purpose='$purpose', doi='$doi' 
                           WHERE stt = '$orig_id'";
            
            if (mysqli_query($conn, $update_sql)) {
                // Xóa bản nháp sau khi đã hợp nhất dữ liệu thành công
                mysqli_query($conn, "DELETE FROM compoundBioLib WHERE stt = '$id'"); 
                echo "success";
            } else {
                echo "error";
            }
            
        } else {
            // Phân nhánh logic: Xử lý Bản ghi hoàn toàn mới
            $sql = "UPDATE compoundBioLib SET status = 'approved' WHERE stt = '$id'";
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
?>