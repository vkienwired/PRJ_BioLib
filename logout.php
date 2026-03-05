<?php
session_start(); // Mở kết nối phiên làm việc hiện tại

// 1. Xóa sạch mọi biến nhớ trong Session (user_id, role, username...)
session_unset();

// 2. Phá hủy hoàn toàn Session đó
session_destroy();

// 3. Đá văng người dùng về lại trang chủ
header("Location: index.php");
exit();
?>