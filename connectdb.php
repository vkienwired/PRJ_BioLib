<?php

$servername = "localhost";
$username = "toannt";
$password = "2Gd3pT29";
$dbname = "dna";

// Tạo kết nối
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
if (!$conn->set_charset("utf8mb4")) {
    echo "Lỗi khi thiết lập charset: " . $conn->error;
}
