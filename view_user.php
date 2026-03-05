<?php
session_start();
include_once 'connectdb.php';

// Khóa an ninh: Chỉ Admin mới được quyền xem hồ sơ người khác
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("<script>alert('Chỉ Admin mới có quyền xem trang này!'); window.location.href='index.php';</script>");
}

if (!isset($_GET['id'])) {
    die("Không tìm thấy người dùng!");
}

$user_id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Tài khoản này không còn tồn tại trong hệ thống.");
}

$user = mysqli_fetch_assoc($result);

include_once 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ Contributor</title>
    <style>
        .profile-container {
            width: 700px;
            margin: 50px auto 100px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-top: 5px solid rgb(160, 196, 157);
        }
        .profile-header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .profile-header h2 { margin: 0; color: #333; }
        .badge {
            display: inline-block;
            background-color: #f77f00;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        .info-group { margin-bottom: 15px; font-size: 16px; line-height: 1.6; }
        .info-label { font-weight: bold; color: #555; display: inline-block; width: 220px; }
        .info-value { color: #000; font-weight: bold; }
        
        .statement-box {
            background-color: #f9f9f9;
            border-left: 4px solid rgb(160, 196, 157);
            padding: 15px;
            margin-top: 10px;
            font-style: italic;
            color: #444;
        }

        .btn-back {
            display: block;
            text-align: center;
            background-color: #666;
            color: white;
            padding: 12px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 30px;
            transition: 0.3s;
        }
        .btn-back:hover { background-color: #333; }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h2>HỒ SƠ CONTRIBUTOR</h2>
            <div class="badge">👤 <?php echo htmlspecialchars($user['fullname'] ? $user['fullname'] : $user['username']); ?></div>
        </div>

        <div class="info-group">
            <span class="info-label">Email liên hệ:</span>
            <span class="info-value"><?php echo htmlspecialchars($user['email'] ? $user['email'] : 'Chưa cập nhật'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Trình độ / Vị trí:</span>
            <span class="info-value"><?php echo htmlspecialchars($user['academic_level'] ? $user['academic_level'] : 'Chưa cập nhật'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Chuyên ngành:</span>
            <span class="info-value"><?php echo htmlspecialchars($user['major'] ? $user['major'] : 'Chưa cập nhật'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Đơn vị công tác:</span>
            <span class="info-value"><?php echo htmlspecialchars($user['institution'] ? $user['institution'] : 'Chưa cập nhật'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Lĩnh vực quan tâm:</span>
            <span class="info-value"><?php echo htmlspecialchars($user['research_interests'] ? $user['research_interests'] : 'Chưa cập nhật'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Liên kết khoa học:</span>
            <span class="info-value" style="color: #008bf8;"><?php echo htmlspecialchars($user['profile_links'] ? $user['profile_links'] : 'Không có'); ?></span>
        </div>
        <div class="info-group">
            <span class="info-label">Công bố tiêu biểu (DOI):</span>
            <span class="info-value"><?php echo htmlspecialchars($user['publications'] ? $user['publications'] : 'Không có'); ?></span>
        </div>

        <div class="info-group" style="margin-top: 25px;">
            <span class="info-label" style="width: 100%;">Tóm tắt định hướng học thuật:</span>
            <div class="statement-box">
                "<?php echo nl2br(htmlspecialchars($user['academic_statement'] ? $user['academic_statement'] : 'Người dùng chưa có chia sẻ nào.')); ?>"
            </div>
        </div>

        <a href="admin_dashboard.php" class="btn-back">⬅ Quay lại Bảng điều khiển</a>
    </div>
</body>
<?php include_once 'footer.php'; ?>
</html>