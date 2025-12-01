<?php
// Include database connection details
include('connectdb.php');

// Kiểm tra kết nối cơ sở dữ liệu
if ($conn->connect_error) {
    http_response_code(500);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Chuẩn bị và ràng buộc
    $stmt = $conn->prepare("INSERT INTO contactBioLib (email, message) VALUES (?, ?)");
    if ($stmt === false) {
        http_response_code(500);
        exit;
    }
    $stmt->bind_param("ss", $email, $message);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        http_response_code(200);
        echo "<script>alert('Cảm ơn ý kiến đóng góp của bạn, chúng tôi sẽ có câu trả lời sớm nhất!')</script>";
    } else {
        http_response_code(500);
    }

    // Đóng câu lệnh và kết nối
    $stmt->close();
    $conn->close();
}
// Chuyển hướng về trang chủ
echo "<script>
setTimeout(() => {
window.location.href = 'index.php';
}, 1000);
</script>";

?>
