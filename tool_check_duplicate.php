<?php
// tool_check_duplicate.php
include 'header.php';
include 'connectdb.php';

echo "<div class='container mt-5'>";
echo "<h2>🛠 Tool Kiểm Tra Hợp Chất Trùng Lặp</h2>";

// Truy vấn tìm các SMILES bị trùng
$sql = "SELECT smiles, COUNT(*) as count, GROUP_CONCAT(name SEPARATOR ' | ') as names, GROUP_CONCAT(id) as ids
        FROM compound
        WHERE smiles IS NOT NULL AND smiles != ''
        GROUP BY smiles 
        HAVING COUNT(*) > 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='alert alert-danger'>Phát hiện có " . $result->num_rows . " nhóm hợp chất bị trùng mã SMILES!</div>";
    echo "<table class='table table-hover table-bordered shadow-sm'>";
    echo "<thead class='table-dark'>
            <tr>
                <th>Mã SMILES</th>
                <th>Số lượng trùng</th>
                <th>Các tên được đặt</th>
                <th>IDs liên quan</th>
                <th>Hành động</th>
            </tr>
          </thead><tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><code>" . htmlspecialchars($row['smiles']) . "</code></td>";
        echo "<td><span class='badge bg-danger'>" . $row['count'] . "</span></td>";
        echo "<td>" . $row['names'] . "</td>";
        echo "<td>" . $row['ids'] . "</td>";
        echo "<td><a href='list.php?search=" . urlencode($row['smiles']) . "' class='btn btn-sm btn-primary'>Xem & Sửa</a></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-success mt-4'>🎉 Tuyệt vời! Cơ sở dữ liệu sạch sẽ, không có hợp chất nào trùng lặp.</div>";
}

echo "</div>";
include 'footer.php';
?>