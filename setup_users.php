<?php
include_once 'connectdb.php';

// 1. Chắc chắn bảng users tồn tại
$sql_create = "CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
mysqli_query($conn, $sql_create);

// 2. XÓA SẠCH các tài khoản cũ bị lỗi (Bước quan trọng nhất đây này!)
mysqli_query($conn, "DELETE FROM `users` WHERE `username` IN ('admin', 'student_user')");

// 3. Tạo mật khẩu mã hóa xịn xò
$pass_hash = password_hash('123456', PASSWORD_DEFAULT);

// 4. Bơm lại tài khoản mới tinh
$sql_admin = "INSERT INTO `users` (`username`, `password`, `role`) VALUES ('admin', '$pass_hash', 'admin')";
$sql_user = "INSERT INTO `users` (`username`, `password`, `role`) VALUES ('student_user', '$pass_hash', 'user')";

if(mysqli_query($conn, $sql_admin) && mysqli_query($conn, $sql_user)){
    echo "<h2 style='color: green;'>✅ Đã reset và tạo mới tài khoản thành công 100%!</h2>";
    echo "Hệ thống đã dọn dẹp tài khoản cũ và bơm lại database với mật khẩu mã hóa siêu xịn:<br>";
    echo "- Tài khoản 1: <b>admin</b> | Mật khẩu: <b>123456</b><br>";
    echo "- Tài khoản 2: <b>student_user</b> | Mật khẩu: <b>123456</b><br><br>";
    echo "<a href='login.php' style='padding: 10px 20px; background: rgb(160, 196, 157); color: #000; text-decoration: none; border-radius: 5px; font-weight: bold;'>Quay lại trang Đăng nhập để test nào!</a>";
} else {
    echo "<h2 style='color: red;'>❌ Có lỗi xảy ra: " . mysqli_error($conn) . "</h2>";
}
?>