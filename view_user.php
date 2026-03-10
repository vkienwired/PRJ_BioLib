<?php
session_start();
include_once 'connectdb.php';

// Xác thực quyền truy cập: Chỉ Quản trị viên (Admin) mới được phép xem hồ sơ chi tiết của người dùng khác.
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("<script>alert('Truy cập bị từ chối: Yêu cầu quyền Quản trị viên.'); window.location.href='index.php';</script>");
}

// Kiểm tra tham số định danh người dùng
if (!isset($_GET['id'])) {
    die("Lỗi hệ thống: Không tìm thấy định danh người dùng.");
}

$user_id = mysqli_real_escape_string($conn, $_GET['id']);

// Truy xuất thông tin chi tiết của người dùng từ cơ sở dữ liệu
$sql_user = "SELECT * FROM users WHERE id = '$user_id'";
$result_user = mysqli_query($conn, $sql_user);

if (mysqli_num_rows($result_user) == 0) {
    die("Dữ liệu không hợp lệ: Tài khoản người dùng không tồn tại trên hệ thống.");
}

$user = mysqli_fetch_assoc($result_user);

// Truy xuất danh sách các hợp chất đã được người dùng đóng góp và đã qua kiểm duyệt
$sql_compounds = "SELECT stt, new_stt, name, cid, origin FROM compoundbiolib 
                  WHERE created_by = '$user_id' AND status = 'approved' 
                  ORDER BY stt DESC";
$result_compounds = mysqli_query($conn, $sql_compounds);

include_once 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ Cộng tác viên | BioLib</title>
    <style>
        .profile-container {
            width: 850px;
            margin: 50px auto 100px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 5px solid #2c3e50;
        }
        .profile-header {
            text-align: center;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .profile-header h2 { 
            margin: 0; 
            color: #2c3e50; 
            font-family: Arial, sans-serif; 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        .badge {
            display: inline-block;
            background-color: #34495e;
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 12px;
        }
        .info-group { 
            margin-bottom: 15px; 
            font-size: 15px; 
            line-height: 1.6; 
            color: #333; 
        }
        .info-label { 
            font-weight: bold; 
            color: #555; 
            display: inline-block; 
            width: 240px; 
        }
        .info-value { 
            color: #222; 
            font-weight: 500; 
        }
        
        .statement-box {
            background-color: #f8f9fa;
            border-left: 4px solid #34495e;
            padding: 15px;
            margin-top: 10px;
            font-style: italic;
            color: #444;
            line-height: 1.6;
        }

        .compound-list-section {
            margin-top: 40px;
            border-top: 1px solid #eaeaea;
            padding-top: 25px;
        }
        .compound-list-section h3 {
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .compound-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        .compound-table th, .compound-table td {
            border: 1px solid #dcdcdc;
            padding: 12px;
            text-align: left;
        }
        .compound-table th {
            background-color: #f1f3f5;
            color: #2c3e50;
            font-weight: bold;
        }
        .compound-table tr:hover { background-color: #f8f9fa; }
        .view-link {
            color: #2980b9;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.2s;
        }
        .view-link:hover {
            color: #1a5276;
            text-decoration: underline;
        }

        .btn-back {
            display: inline-block;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 35px;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        .btn-back:hover { background-color: #2c3e50; }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h2>Hồ Sơ Năng Lực Học Thuật</h2>
            <div class="badge">
                <?php echo htmlspecialchars($user['fullname'] ? $user['fullname'] : $user['username']); ?>
            </div>
        </div>

        <div class="info-group">
            <span class="info-label">Thư điện tử (Email):</span>
            <span class="info-value"><?php echo htmlspecialchars($user['email'] ? $user['email'] : 'Chưa cập nhật'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Trình độ học vấn / Chức danh:</span>
            <span class="info-value"><?php echo htmlspecialchars($user['academic_level'] ? $user['academic_level'] : 'Chưa cập nhật'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Chuyên ngành nghiên cứu:</span>
            <span class="info-value"><?php echo htmlspecialchars($user['major'] ? $user['major'] : 'Chưa cập nhật'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Cơ quan công tác:</span>
            <span class="info-value"><?php echo htmlspecialchars($user['institution'] ? $user['institution'] : 'Chưa cập nhật'); ?></span>
        </div>

        <div class="info-group" style="margin-top: 25px;">
            <span class="info-label" style="width: 100%;">Tóm tắt định hướng nghiên cứu:</span>
            <div class="statement-box">
                "<?php echo nl2br(htmlspecialchars($user['academic_statement'] ? $user['academic_statement'] : 'Hệ thống chưa ghi nhận thông tin học thuật từ người dùng này.')); ?>"
            </div>
        </div>

        <div class="compound-list-section">
            <h3>Danh Mục Hợp Chất Đã Đóng Góp (Đã Được Phê Duyệt)</h3>
            <?php if (mysqli_num_rows($result_compounds) > 0): ?>
                <table class="compound-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Danh pháp hợp chất</th>
                            <th>Mã định danh CID</th>
                            <th>Nguồn gốc</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result_compounds)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['new_stt']); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['cid']); ?></td>
                            <td><?php echo htmlspecialchars($row['origin']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo urlencode($row['stt']); ?>" class="view-link">Xem chi tiết</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666; font-style: italic; margin-top: 15px;">Dữ liệu trống: Người dùng chưa có công bố hợp chất nào được hệ thống phê duyệt.</p>
            <?php endif; ?>
        </div>

        <a href="admin_dashboard.php" class="btn-back">Quay lại Bảng Điều Khiển</a>
    </div>
</body>
<?php include_once 'footer.php'; ?>
</html>