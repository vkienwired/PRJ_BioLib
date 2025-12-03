<?php
include('connectdb.php'); // Kết nối đến CSDL

// Kiểm tra xem dữ liệu đã được gửi từ form thêm mới hay chưa
if (isset($_POST['name']) && isset($_POST['cid'])) {
    // Lấy dữ liệu từ form
    $name     = $_POST['name'];
    $cid      = $_POST['cid'];
    $smiles   = $_POST['smiles'];
    $benefit  = $_POST['benefit'];
    $weakness = $_POST['weakness'];
    $origin   = $_POST['origin'];
    $purpose  = $_POST['purpose'];
    $doi      = $_POST['doi'];

    if (!empty($name) && !empty($cid)) {

        // 1. Thêm dữ liệu vào CSDL
        $sql = "INSERT INTO compoundBioLib (name, cid, smiles, benefit, weakness, origin, purpose, doi)
                VALUES ('$name', '$cid', '$smiles', '$benefit', '$weakness', '$origin', '$purpose', '$doi')";

        // 2. Nếu có SMILES thì gọi RDKit để vẽ SVG và lưu vào thư mục img
        if (!empty($smiles)) {
            // Đường dẫn python trong env rdkit-env  (theo lệnh `where python` của bạn)
            $pythonPath = 'C:\\Users\\ADMIN\\anaconda3\\envs\\rdkit-env\\python.exe';

            // Đường dẫn đến smiles.py trong project BioLib
            $scriptPath = 'C:\\xampp\\htdocs\\BioLib\\smiles.py';

            // Ghép lệnh: python smiles.py "<SMILES>" "<CID>"
            $cmd = $pythonPath . ' ' . $scriptPath . ' ' .
                   escapeshellarg($smiles) . ' ' . escapeshellarg($cid);

            $output    = [];
            $returnVar = 0;

            // Chạy lệnh, redirect stderr sang stdout để dễ debug
            exec($cmd . " 2>&1", $output, $returnVar);

            // Nếu cần debug thì bỏ comment 3 dòng dưới:
            // file_put_contents('debug_smiles.log',
            //     "CMD: $cmd\nReturn: $returnVar\n" . implode("\n", $output) . "\n\n",
            //     FILE_APPEND
            // );
        }

        // 3. Thực thi truy vấn thêm vào DB
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Thêm hợp chất mới thành công!')</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm hợp chất mới: " . $conn->error . "')</script>";
        }

    } else {
        // Thiếu tên hoặc CID
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin hợp chất!')</script>";
    }
}

// Đóng kết nối
$conn->close();

// Chuyển hướng về trang chủ
echo "<script>
setTimeout(() => {
    window.location.href = 'index.php';
}, 100);
</script>";
?>
