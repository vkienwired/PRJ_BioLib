<?php
session_start();
include_once 'connectdb.php';

// Xác thực quyền Quản trị viên
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("error_permission");
}

if (isset($_GET['id'])) {
    $target_id = mysqli_real_escape_string($conn, $_GET['id']);
    $current_admin_id = $_SESSION['user_id'];

    // Cơ chế an toàn: Ngăn chặn Quản trị viên tự xóa tài khoản của chính mình
    if ($target_id == $current_admin_id) {
        die("error_self_delete");
    }

    // Thực thi lệnh xóa bản ghi người dùng
    $sql = "DELETE FROM users WHERE id = '$target_id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error_query";
    }
} else {
    echo "error_missing_id";
}
?>