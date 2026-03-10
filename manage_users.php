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
    <title>Quản lý Người dùng - BIOLIB</title>
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
        .dashboard-header { text-align: center; margin-bottom: 30px; }
        .dashboard-header h2 { color: #333; margin-bottom: 5px; }
        .dashboard-header p { color: #666; font-style: italic; }
        
        table { 
            width: 95%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            background-color: #fff; 
            border-radius: 8px;
            overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        th, td { border: 1px solid #eee; padding: 12px; text-align: center; vertical-align: middle; }
        th { background-color: rgb(160, 196, 157); color: #000; font-size: 15px; }
        
        .role-admin { color: #d9534f; font-weight: bold; }
        .role-user { color: #0056b3; font-weight: bold; }

        .action-cell { display: flex; justify-content: center; gap: 10px; }
        
        .btn-view {
            padding: 8px 12px; border-radius: 6px; font-size: 14px; font-weight: bold;
            text-decoration: none; color: #fff; background-color: #0d0c22; border: 1px solid #0d0c22;
            transition: all 0.2s;
        }
        .btn-view:hover { background-color: #fff; color: #0d0c22; box-shadow: 0 4px 12px rgba(13,12,34,0.15); }

        .btn-delete {
            padding: 8px 12px; border-radius: 6px; font-size: 14px; font-weight: bold;
            text-decoration: none; color: #6b7280; background-color: #f3f3f4; border: 1px solid #e5e7eb;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-delete:hover { background-color: #fff; color: #d9534f; border-color: #d9534f; box-shadow: 0 4px 12px rgba(217,83,79,0.15); }

        .fade-out { opacity: 0; transform: translateX(30px); transition: all 0.5s ease-out; }
        
        .nav-links { margin-bottom: 20px; width: 95%; text-align: left; }
        .nav-links a { 
            text-decoration: none; color: #0056b3; font-weight: bold; padding: 10px 15px; 
            border: 1px solid #0056b3; border-radius: 5px; transition: 0.3s;
        }
        .nav-links a:hover { background-color: #0056b3; color: white; }
    </style>
</head>
<body>
    <div class="centered-content">
        <div class="dashboard-header">
            <h2>HỆ THỐNG QUẢN LÝ ĐỊNH DANH</h2>
            <p>Kiểm soát và phân quyền thành viên hệ thống</p>
        </div>

        <div class="nav-links">
            <a href="admin_dashboard.php">⬅ Trở về Bảng xét duyệt dữ liệu</a>
        </div>

        <?php
        $sql = "SELECT id, username, fullname, email, academic_level, role FROM users ORDER BY role ASC, id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>
                    <th style='width: 5%'>ID</th>
                    <th style='width: 15%'>Tên đăng nhập</th>
                    <th style='width: 20%'>Họ và tên</th>
                    <th style='width: 20%'>Email học thuật</th>
                    <th style='width: 15%'>Vị trí chuyên môn</th>
                    <th style='width: 10%'>Phân quyền</th>
                    <th style='width: 15%'>Thao tác</th>
                  </tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr id='user-row-{$row['id']}'>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td><strong>" . htmlspecialchars($row['username']) . "</strong></td>";
                echo "<td>" . htmlspecialchars($row['fullname'] ? $row['fullname'] : 'Chưa cập nhật') . "</td>";
                echo "<td>" . htmlspecialchars($row['email'] ? $row['email'] : 'Chưa cập nhật') . "</td>";
                echo "<td>" . htmlspecialchars($row['academic_level'] ? $row['academic_level'] : 'Chưa cập nhật') . "</td>";
                
                if ($row['role'] === 'admin') {
                    echo "<td class='role-admin'>Quản trị viên</td>";
                } else {
                    echo "<td class='role-user'>Người đóng góp</td>";
                }

                echo "<td>
                        <div class='action-cell'>
                            <a href='view_user.php?id=" . urlencode($row['id']) . "' class='btn-view'>Xem hồ sơ</a>";
                            
                // Không hiển thị nút xóa nếu đây là tài khoản Quản trị viên
                if ($row['role'] !== 'admin') {
                    echo "<button onclick=\"deleteUser({$row['id']})\" class='btn-delete'>Xóa tài khoản</button>";
                }
                
                echo "  </div>
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Không tìm thấy dữ liệu người dùng trên hệ thống.</p>";
        }
        ?>
    </div>

    <script>
        function deleteUser(userId) {
            if (!confirm('Hành động này mang tính vĩnh viễn. Bạn có chắc chắn muốn xóa tài khoản này khỏi hệ thống?')) {
                return;
            }

            fetch('delete_user.php?id=' + userId)
                .then(response => response.text())
                .then(data => {
                    const result = data.trim();
                    if (result === 'success') {
                        const row = document.getElementById('user-row-' + userId);
                        row.classList.add('fade-out');
                        setTimeout(() => row.remove(), 500);
                    } else if (result === 'error_self_delete') {
                        alert('Lỗi: Hệ thống từ chối thao tác tự xóa tài khoản quản trị hiện hành.');
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