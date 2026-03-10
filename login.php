<?php
session_start(); 
include_once 'connectdb.php'; 

$error_message = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Thu thập và làm sạch dữ liệu chống Hack
    $user_input = mysqli_real_escape_string($conn, trim($_POST['username']));
    $pass_input = $_POST['password'];

    // Lục tìm trong database xem có tài khoản này không
    $sql = "SELECT * FROM users WHERE username = '$user_input'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $account = mysqli_fetch_assoc($result);
        
        // Kiểm tra mật khẩu mã hóa
        if (password_verify($pass_input, $account['password'])) {
            // Cấp thẻ session
            $_SESSION['user_id'] = $account['id'];
            $_SESSION['role'] = $account['role'];
            $_SESSION['username'] = $account['username'];
            
            // Đá về trang chủ
            header("Location: index.php"); 
            exit();
        } else {
            $error_message = "Sai mật khẩu";
        }
    } else {
        $error_message = "Tài khoản không tồn tại";
    }
}

// Xử lý PHP xong mới được in giao diện (để không bị lỗi Headers Already Sent)
include_once 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <title>Đăng nhập BIOLIB</title>
    <style>
        .centered-content { 
            margin: 0 auto; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            flex-direction: column; 
            width: 100%; 
            padding-bottom: 200px; 
            padding-top: 50px; 
        }
        .auth-box { 
            background-color: #fff; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 4px 18px 0 rgb(160, 196, 157); 
            border: 2px solid rgb(160, 196, 157); 
            width: 400px; 
            text-align: center; 
        }
        .input-group { 
            margin-bottom: 20px; 
            text-align: left; 
        }
        .input-group label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
            color: #333; 
        }
        .input { 
            width: 90%; 
            height: 40px; 
            padding: 0 1rem; 
            border: 2px solid transparent; 
            border-radius: 8px; 
            outline: none; 
            background-color: #f3f3f4; 
            transition: 0.3s ease; 
        }
        .input:focus { 
            border-color: rgb(196, 215, 178); 
            background-color: #fff; 
            box-shadow: 0 0 0 4px rgb(247 127 0 / 10%); 
        }
        .button { 
            background-color: rgb(196, 215, 178); 
            color: #000; 
            border: none; 
            border-radius: 8px; 
            width: 100%; 
            height: 45px; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: .3s; 
        }
        .button:hover { 
            background-color: rgb(160, 196, 157); 
            color: #fff; 
        }
        .error { 
            color: red; 
            margin-bottom: 15px; 
            font-style: italic; 
        }
        .register-link { 
            margin-top: 25px; 
            display: block; 
            color: #008bf8; 
            text-decoration: none; 
            font-weight: bold; 
            font-size: 15px;
        }
        .register-link:hover { 
            text-decoration: underline; 
            color: #f77f00; 
        }
    </style>
</head>
<body>
    <div class="centered-content">
        <div class="auth-box">
            <h2>Đăng nhập</h2>
            
            <?php if($error_message != "") echo "<div class='error'>$error_message</div>"; ?>
            
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label>Tên đăng nhập</label>
                    <input class="input" type="text" name="username" required />
                </div>
                <div class="input-group">
                    <label>Mật khẩu</label>
                    <input class="input" type="password" name="password" required />
                </div>
                <button type="submit" class="button">Đăng nhập</button>
            </form>

            <a href="register.php" class="register-link">Chưa có tài khoản? Hãy đăng ký!</a>
        </div>
    </div>
</body>
<?php include_once 'footer.php'; ?>
</html>