<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền truy cập (Chỉ Admin mới nên dùng tính năng này)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<h2 style='text-align:center; color:red; margin-top:50px;'>Bạn không có quyền truy cập trang này!</h2>");
}

include_once 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kiểm tra trùng lặp dữ liệu - BioLib</title>
    <style>
        .container { width: 95%; margin: 20px auto; font-family: Arial, sans-serif; }
        .duplicate-section { margin-bottom: 50px; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid rgb(160, 196, 157); }
        h3 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: rgb(196, 215, 178); color: #000; padding: 12px; border: 1px solid #ddd; }
        td { padding: 10px; border: 1px solid #ddd; text-align: center; font-size: 14px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-edit { color: #008bf8; font-weight: bold; text-decoration: none; margin-right: 10px; }
        .btn-delete { color: #d9534f; font-weight: bold; text-decoration: none; cursor: pointer; }
        .btn-delete:hover { text-decoration: underline; }
        .badge { background: #ff4d4d; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px; }
    </style>
</head>
<body>

<div class="container">
    <h2 style="text-align: center;">Công cụ rà soát trùng lặp dữ liệu</h2>
    <p style="text-align: center; color: #666;">Hệ thống sẽ liệt kê các hợp chất có tên, SMILES hoặc CID giống nhau để bạn tối ưu hóa cơ sở dữ liệu.</p>

    <?php
    // Hàm hiển thị bảng dữ liệu trùng lặp
    function displayDuplicates($conn, $field, $label) {
        // SQL tìm các giá trị bị lặp lại (loại bỏ giá trị trống/null)
        $sql_find = "SELECT $field, COUNT(*) as count 
                     FROM compoundbiolib 
                     WHERE $field IS NOT NULL AND $field != '' 
                     GROUP BY $field 
                     HAVING COUNT(*) > 1";
        
        $res_find = mysqli_query($conn, $sql_find);

        echo "<div class='duplicate-section'>";
        echo "<h3>Trùng lặp theo: $label</h3>";

        if (mysqli_num_rows($res_find) > 0) {
            echo "<table>";
            echo "<tr>
                    <th>STT Hệ thống</th>
                    <th>Tên hợp chất</th>
                    <th>PubChem CID</th>
                    <th>SMILES</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                  </tr>";

            while ($dup = mysqli_fetch_assoc($res_find)) {
                $val = mysqli_real_escape_string($conn, $dup[$field]);
                
                // Lấy chi tiết các bản ghi bị trùng giá trị này
                $sql_detail = "SELECT * FROM compoundbiolib WHERE $field = '$val' ORDER BY stt ASC";
                $res_detail = mysqli_query($conn, $sql_detail);

                $first = true;
                $num_rows = mysqli_num_rows($res_detail);

                while ($row = mysqli_fetch_assoc($res_detail)) {
                    echo "<tr>";
                    echo "<td>" . $row['stt'] . "</td>";
                    echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['cid']) . "</td>";
                    echo "<td style='max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;'>" . htmlspecialchars($row['smiles']) . "</td>";
                    echo "<td>" . ($row['status'] == 'approved' ? 'Đã duyệt' : 'Chờ duyệt') . "</td>";
                    echo "<td>
                            <a href='edit.php?id=" . $row['stt'] . "' class='btn-edit' target='_blank'>Sửa</a>
                            <a onclick='confirmDelete(" . $row['stt'] . ")' class='btn-delete'>Xóa</a>
                          </td>";
                    echo "</tr>";
                }
                // Dòng ngăn cách giữa các nhóm trùng
                echo "<tr style='background:#eee;'><td colspan='6' style='height:2px; padding:0;'></td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:green;'>🎉 Tuyệt vời! Không có trùng lặp theo $label.</p>";
        }
        echo "</div>";
    }

    // Thực hiện check cho 3 trường quan trọng
    displayDuplicates($conn, 'cid', 'PubChem CID');
    displayDuplicates($conn, 'name', 'Tên hợp chất');
    displayDuplicates($conn, 'smiles', 'Chuỗi SMILES');
    ?>

</div>

<script>
function confirmDelete(id) {
    if (confirm('Cảnh báo: Bạn có chắc chắn muốn XÓA vĩnh viễn hợp chất này không?\nHành động này không thể hoàn tác.')) {
        window.location.href = 'delete_compound.php?id=' + id + '&from=check_duplicates';
    }
}
</script>

<?php include_once 'footer.php'; ?>
</body>
</html>