<?php
// File: process_3d.php
require_once 'config.php'; // Gọi config để lấy PYTHON_PATH của cậu

if (isset($_POST['smiles'])) {
    $smiles = trim($_POST['smiles']);
    
    // Tên file python mới tạo ở Bước 1
    $python_script = __DIR__ . "/generate_3d.py"; 

    // BẢO MẬT: Bọc chuỗi SMILES lại để chống Command Injection
    $safe_smiles = escapeshellarg($smiles);
    $safe_script = escapeshellarg($python_script);

    // Lệnh CMD gọi Python
    $command = PYTHON_PATH . " $safe_script $safe_smiles 2>&1";

    // Kích hoạt phép thuật
    $output = shell_exec($command);

    // Trả kết quả (chuỗi MOL block) về cho giao diện
    echo $output;
} else {
    echo "Lỗi: Chưa có dữ liệu SMILES.";
}
?>