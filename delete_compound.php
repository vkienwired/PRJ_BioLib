<?php
session_start();
include_once 'connectdb.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Lỗi quyền truy cập!");
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "DELETE FROM compoundBioLib WHERE stt = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "success"; // Chỉ in ra chữ success
    } else {
        echo "error";
    }
}
?>