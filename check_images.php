<?php

require_once 'connectdb.php';

echo "<h2>Kiểm tra các hợp chất chưa có ảnh 2D</h2>";

$sql = "SELECT stt, name, cid, doi FROM compoundBioLib ORDER BY stt";
$result = mysqli_query($conn, $sql);

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>
        <th>STT</th>
        <th>Tên hợp chất</th>
        <th>PubChem CID</th>
        <th>DOI</th>
        <th>File ảnh</th>
        <th>Trạng thái</th>
      </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $cid = $row['cid'];
    $fileRelative = "img/" . $cid . ".svg";
    $filePath = __DIR__ . "/" . $fileRelative;

    // check file ảnh tồn tại không
    $hasImage = file_exists($filePath);

    if (!$hasImage) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['stt']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['cid']) . "</td>";
        echo "<td>" . htmlspecialchars($row['doi']) . "</td>";
        echo "<td>" . htmlspecialchars($fileRelative) . "</td>";
        echo "<td style='color:red;font-weight:bold'>MISSING</td>";
        echo "</tr>";
    }
}

echo "</table>";

mysqli_close($conn);
?>
