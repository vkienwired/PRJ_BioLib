<?php
session_start();
require_once 'config.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$msg_type = "";

// Xử lý khi người dùng bấm nút Đổi mật khẩu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // 1. Kiểm tra tính hợp lệ của form
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $message = "Vui lòng nhập đầy đủ các trường!";
        $msg_type = "error";
    } elseif ($new_password !== $confirm_password) {
        $message = "Mật khẩu mới và Nhập lại mật khẩu không khớp!";
        $msg_type = "error";
    } elseif (strlen($new_password) < 6) {
        $message = "Mật khẩu mới phải có ít nhất 6 ký tự!";
        $msg_type = "error";
    } else {
        // 2. Truy vấn lấy mật khẩu hiện tại trong CSDL
        // Giả sử bảng của cậu tên là `users` và cột khóa chính là `id`
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $hashed_password_db = $row['password'];

            // 3. Kiểm tra mật khẩu cũ có đúng không
            // Hỗ trợ cả 2 trường hợp: Mật khẩu cũ mã hóa bằng password_hash hoặc md5
            if (password_verify($old_password, $hashed_password_db) || md5($old_password) === $hashed_password_db || $old_password === $hashed_password_db) {
                
                // 4. Mã hóa mật khẩu mới (Chuẩn bảo mật cao nhất của PHP)
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // 5. Cập nhật vào Database
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    $message = "Đổi mật khẩu thành công!";
                    $msg_type = "success";
                } else {
                    $message = "Đã xảy ra lỗi khi cập nhật: " . $conn->error;
                    $msg_type = "error";
                }
                $update_stmt->close();
            } else {
                $message = "Mật khẩu hiện tại không chính xác!";
                $msg_type = "error";
            }
        } else {
            $message = "Không tìm thấy thông tin tài khoản!";
            $msg_type = "error";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu - BioLib</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f4; margin: 0; }
        .centered-content { display: flex; align-items: center; justify-content: center; min-height: 80vh; flex-direction: column; }
        .form-container { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.05); 
            width: 400px; 
            border-top: 5px solid rgb(160, 196, 157); 
        }
        .form-container h2 { text-align: center; color: #333; margin-top: 0; margin-bottom: 25px; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 8px; color: #555; font-weight: bold; font-size: 14px; }
        .input { 
            width: 100%; 
            height: 45px; 
            padding: 0 15px; 
            border: 2px solid #e0e0e0; 
            border-radius: 8px; 
            outline: none; 
            box-sizing: border-box; 
            transition: 0.3s;
            font-size: 15px;
        }
        .input:focus { border-color: rgb(160, 196, 157); box-shadow: 0 0 0 4px rgb(247 127 0 / 10%); }
        .button { 
            width: 100%; 
            height: 45px; 
            background-color: rgb(196, 215, 178); 
            color: #0d0c22; 
            border: 2px solid rgb(160, 196, 157); 
            border-radius: 8px; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 10px;
        }
        .button:hover { background-color: rgb(160, 196, 157); color: #fff; }
        .msg { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; }
        .msg.error { background-color: #fde8e8; color: #d9534f; border: 1px solid #f5c6c6; }
        .msg.success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #008bf8; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; color: #f77f00; }
    </style>
</head>
<body>

<?php include_once 'header.php'; ?>

<div class="centered-content">
    <div class="form-container">
        <h2>Đổi Mật Khẩu</h2>

        <?php if (!empty($message)): ?>
            <div class="msg <?php echo $msg_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="change_password.php" method="POST">
            <div class="input-group">
                <label for="old_password">Mật khẩu hiện tại</label>
                <input type="password" id="old_password" name="old_password" class="input" required placeholder="Nhập mật khẩu cũ...">
            </div>

            <div class="input-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" id="new_password" name="new_password" class="input" required placeholder="Nhập mật khẩu mới...">
            </div>

            <div class="input-group">
                <label for="confirm_password">Nhập lại mật khẩu mới</label>
                <input type="password" id="confirm_password" name="confirm_password" class="input" required placeholder="Xác nhận lại mật khẩu...">
            </div>

            <button type="submit" class="button">Cập nhật mật khẩu</button>
        </form>

        <a href="index.php" class="back-link">⬅ Quay lại trang chủ</a>
    </div>
</div>

<?php include_once 'footer.php'; ?>

</body>
</html>