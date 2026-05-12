<?php
// File: api_generate_3d.php
require_once 'config.php';

// Báo cho trình duyệt biết dữ liệu trả về là JSON
header('Content-Type: application/json');

if (isset($_POST['cid'])) {
    // Ép kiểu số nguyên để phòng chống SQL Injection hoàn toàn
    $cid = intval($_POST['cid']); 

    // 1. Kiểm tra "Kho chứa" (Caching) trong thư mục img/
    $sdf_file = __DIR__ . "/img/" . $cid . ".sdf";
    $sdf_url = "img/" . $cid . ".sdf";

    if (file_exists($sdf_file)) {
        // Hàng đã có sẵn, trả về luôn không cần chạy Python!
        echo json_encode(['status' => 'success', 'url' => $sdf_url]);
        exit;
    }

    // 2. Chưa có file, truy vấn DB lấy SMILES
    // Dùng Prepared Statement để an toàn tuyệt đối
    $stmt = $conn->prepare("SELECT smiles FROM compoundbiolib WHERE cid = ? LIMIT 1");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $smiles = trim($row['smiles']);

        if (empty($smiles)) {
             echo json_encode(['status' => 'error', 'message' => 'Hợp chất này chưa có dữ liệu SMILES để dựng 3D.']);
             exit;
        }

        // 3. Gọi Python RDKit ra làm việc
        $python_script = __DIR__ . "/generate_3d.py";
        
        // Bọc biến lại để chống Command Injection
        $safe_smiles = escapeshellarg($smiles);
        $safe_script = escapeshellarg($python_script);
        $safe_output = escapeshellarg($sdf_file);

        // Ghép lệnh và chạy (sử dụng PYTHON_PATH khai báo trong config.php)
        $command = PYTHON_PATH . " $safe_script $safe_smiles $safe_output 2>&1";
        $output = shell_exec($command);

        // 4. Trả kết quả về cho giao diện
        if (file_exists($sdf_file)) {
            echo json_encode(['status' => 'success', 'url' => $sdf_url]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi từ RDKit: ' . $output]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy hợp chất trong CSDL.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu CID truyền vào.']);
}
?>