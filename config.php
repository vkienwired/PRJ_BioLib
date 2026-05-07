<?php
/**
 * File cấu hình BioLib - Giữ nguyên cấu trúc SQL gốc
 */

// --- 1. Cấu hình CSDL ---
$db_host = "127.0.0.1"; // Dùng IP để tránh lỗi plugin xác thực
$db_user = "root";
$db_pass = "";
$db_name = "dnakien";       // Tên database theo file dnakien.sql của cậu
$db_port = 3307;        // Cổng cậu đang dùng trên XAMPP[cite: 1, 2]

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Đảm bảo không lỗi font tiếng Việt[cite: 1]
mysqli_set_charset($conn, "utf8mb4");

// --- 2. Đường dẫn Python (Anaconda) ---
// Cậu nhớ kiểm tra lại đường dẫn này trên máy xem chuẩn chưa nhé
define('PYTHON_PATH', 'C:\\Users\\ADMIN\\anaconda3\\envs\\rdkit-env\\python.exe');
define('SCRIPT_PATH', 'C:\\xampp\\htdocs\\BioLib\\smiles.py');
?>