<?php
session_start();
include_once 'connectdb.php';

// Xác thực quyền Quản trị viên
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Lỗi truy cập: Yêu cầu quyền Quản trị viên hệ thống.'); window.location.href = 'index.php';</script>";
    exit();
}
include_once 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản trị Hệ thống - BIOLIB</title>
    <style>
        .centered-content { 
            margin: 0 auto; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            flex-direction: column; 
            width: 100%; 
            padding-bottom: 100px; 
            padding-top: 30px;
        }
        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .dashboard-header h2 {
            color: #333;
            margin-bottom: 5px;
        }
        .dashboard-header p {
            color: #666;
            font-style: italic;
        }
        table { 
            width: 98%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            background-color: #fff; 
            border-radius: 8px;
            overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        th, td { 
            border: 1px solid #eee; 
            padding: 12px; 
            text-align: center; 
            vertical-align: middle;
        }
        th { 
            background-color: rgb(160, 196, 157); 
            color: #000;
            font-size: 15px;
        }
        .doi-link { color: #008bf8; text-decoration: none; font-weight: bold; }
        .doi-link:hover { text-decoration: underline; color: #f77f00; }
        .user-link { color: #0056b3; font-weight: bold; text-decoration: none; }
        .user-link:hover { text-decoration: underline; color: #d9534f; }

        /* Phân loại bản ghi */
        .status-new { color: #28a745; font-size: 12px; font-weight: bold; display: block; margin-top: 5px; }
        .status-update { color: #d9534f; font-size: 12px; font-weight: bold; display: block; margin-top: 5px; }

        /* Nút thao tác */
        .action-cell {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: center;
        }
        .btn-action {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            border: none;
            font-family: Arial, sans-serif;
        }
        
        .btn-approve { background-color: #0d0c22; color: #fff; border: 1px solid #0d0c22;}
        .btn-approve:hover { background-color: #fff; color: #0d0c22; box-shadow: 0 4px 12px rgba(13, 12, 34, 0.15); transform: translateY(-2px); }

        .btn-reject { background-color: #f3f3f4; color: #6b7280; border: 1px solid #e5e7eb;}
        .btn-reject:hover { background-color: #fff; color: #d9534f; border-color: #d9534f; box-shadow: 0 4px 12px rgba(217, 83, 79, 0.15); transform: translateY(-2px); }

        .fade-out {
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.5s ease-out;
        }
        .text-justify { text-align: justify; padding: 10px 15px; }
    </style>
</head>
<body>
    <div class="centered-content">
        <div class="dashboard-header">
            <h2>BẢNG ĐIỀU KHIỂN QUẢN TRỊ VIÊN</h2>
            <p>Hệ thống xét duyệt dữ liệu chờ xử lý</p>
        </div>
    <div style="width: 98%; text-align: right; margin-bottom: 15px;">
            <a href="manage_users.php" style="text-decoration: none; color: #fff; background-color: #0056b3; padding: 10px 15px; border-radius: 5px; font-weight: bold; font-size: 14px; transition: 0.3s;">
                Quản lý Người dùng hệ thống ➡
            </a>
    </div>

        <?php
        // Truy vấn dữ liệu: Lấy cả bản ghi mới (pending) và bản cập nhật (pending_update)
        $sql = "SELECT c.*, u.id AS user_id, u.fullname, u.username 
                FROM compoundBioLib c 
                LEFT JOIN users u ON c.created_by = u.id 
                WHERE c.status IN ('pending', 'pending_update')";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>
                    <th style='width: 3%'>STT</th>
                    <th style='width: 15%'>Danh pháp hợp chất</th>
                    <th style='width: 8%'>Mã CID</th>
                    <th style='width: 25%'>Hoạt tính sinh học</th>
                    <th style='width: 10%'>Tài liệu (DOI)</th>
                    <th style='width: 15%'>Người đóng góp</th>
                    <th style='width: 12%'>Thao tác</th>
                  </tr>";
            
            $stt = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr id='row-{$row['stt']}'>";
                echo "<td>" . $stt++ . "</td>";
                
                // Nhãn phân loại trạng thái bản ghi
                $status_label = ($row['status'] == 'pending_update') 
                    ? "<span class='status-update'>[Bản cập nhật]</span>" 
                    : "<span class='status-new'>[Bản ghi mới]</span>";
                
                echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong>" . $status_label . "</td>";
                
                echo "<td>" . htmlspecialchars($row['cid']) . "</td>";
                echo "<td class='text-justify'>" . htmlspecialchars($row['benefit']) . "</td>";
                
                $doi = trim($row['doi']);
                if (!empty($doi)) {
                    $doi_link = (strpos($doi, 'http') === 0) ? $doi : 'https://doi.org/' . $doi;
                    echo "<td><a href='" . htmlspecialchars($doi_link) . "' target='_blank' class='doi-link'>Truy cập</a></td>";
                } else {
                    echo "<td><i style='color:#bbb;'>Không có</i></td>";
                }

                $author_name = !empty($row['fullname']) ? $row['fullname'] : $row['username'];
                if ($row['user_id']) {
                    echo "<td><a href='view_user.php?id=" . urlencode($row['user_id']) . "' class='user-link' title='Truy cập hồ sơ học thuật'>" . htmlspecialchars($author_name) . "</a></td>";
                } else {
                    echo "<td><i style='color:#bbb;'>Khách ẩn danh</i></td>";
                }

                echo "<td>
                        <div class='action-cell'>
                            <button onclick=\"processAction('approve.php?id={$row['stt']}', '{$row['stt']}', 'Phê duyệt')\" class='btn-action btn-approve'>Phê duyệt</button>
                            <button onclick=\"processAction('delete_compound.php?id={$row['stt']}', '{$row['stt']}', 'Từ chối')\" class='btn-action btn-reject'>Từ chối</button>
                        </div>
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<h3 style='color: #666; margin-top: 40px; font-weight: normal;'>Hiện tại không có dữ liệu nào cần xét duyệt.</h3>";
        }
        ?>
    </div>

    <script>
        function processAction(url, rowId, actionType) {
            if (actionType === 'Từ chối') {
                if (!confirm('Bạn có chắc chắn muốn từ chối và xóa bản ghi này khỏi hệ thống chờ duyệt?')) {
                    return;
                }
            }

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'success') {
                        const row = document.getElementById('row-' + rowId);
                        row.classList.add('fade-out');
                        
                        setTimeout(() => {
                            row.remove();
                            if (document.querySelectorAll('table tr').length <= 1) {
                                location.reload();
                            }
                        }, 500);
                        
                    } else {
                        alert('Lỗi hệ thống: Không thể xử lý yêu cầu vào lúc này.');
                    }
                })
                .catch(error => {
                    console.error('Lỗi kết nối:', error);
                    alert('Lỗi kết nối máy chủ. Vui lòng kiểm tra lại mạng lưới.');
                });
        }
    </script>
</body>
<?php include_once 'footer.php'; ?>
</html>