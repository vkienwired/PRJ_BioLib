<?php
require_once 'connectdb.php';

// ĐƯỜNG DẪN PYTHON TRONG ENV RDKit
$pythonPath = 'C:\\Users\\ADMIN\\anaconda3\\envs\\rdkit-env\\python.exe';

// ĐƯỜNG DẪN TỚI smiles.py
$scriptPath = 'C:\\xampp\\htdocs\\BioLib\\smiles.py';

// Thư mục img nằm trong project
$imgDir = __DIR__ . DIRECTORY_SEPARATOR . 'img';

if (!is_dir($imgDir)) {
    mkdir($imgDir, 0777, true);
}

echo "<h2>Tạo ảnh 2D cho các hợp chất chưa có SVG</h2>";

$sql = "SELECT stt, name, cid, smiles FROM compoundBioLib ORDER BY stt";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Lỗi query: " . mysqli_error($conn));
}

$created = 0;
$skipped = 0;

echo "<pre>";

while ($row = mysqli_fetch_assoc($result)) {
    $cid    = trim($row['cid']);
    $smiles = trim($row['smiles']);
    $name   = $row['name'];

    if ($cid === '' || $smiles === '') {
        echo "BỎ QUA (thiếu cid/smiles): STT {$row['stt']} - {$name}\n";
        $skipped++;
        continue;
    }

    $filePath = $imgDir . DIRECTORY_SEPARATOR . $cid . ".svg";

    if (file_exists($filePath)) {
        echo "ĐÃ CÓ ẢNH: CID {$cid} - {$name}\n";
        $skipped++;
        continue;
    }

    // Ghép lệnh: python smiles.py "<smiles>" "<cid>"
    $cmd = $pythonPath . ' ' . $scriptPath . ' ' .
           escapeshellarg($smiles) . ' ' . escapeshellarg($cid);

    $output = [];
    $returnVar = 0;
    exec($cmd . " 2>&1", $output, $returnVar);

    if ($returnVar === 0 && file_exists($filePath)) {
        echo "✅ TẠO ẢNH OK: CID {$cid} - {$name}\n";
        $created++;
    } else {
        echo "❌ LỖI TẠO ẢNH: CID {$cid} - {$name}\n";
        echo "   CMD: $cmd\n";
        echo "   Output:\n   " . implode("\n   ", $output) . "\n";
    }
}

echo "\n-----------------------------\n";
echo "Tổng số ảnh mới tạo: {$created}\n";
echo "Số dòng bỏ qua (đã có ảnh / thiếu dữ liệu): {$skipped}\n";
echo "</pre>";

mysqli_close($conn);
?>
