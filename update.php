<?php
include('connectdb.php'); // Kết nối đến CSDL

// Kiểm tra xem dữ liệu đã được gửi từ biểu mẫu để cập nhật thông tin hay không
if (
    isset($_POST['name'], $_POST['cid'], $_POST['smiles'], $_POST['benefit'], $_POST['weakness'], $_POST['origin'], $_POST['purpose'], $_POST['doi'])
) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $cid = mysqli_real_escape_string($conn, $_POST['cid']);
    $smiles = mysqli_real_escape_string($conn, $_POST['smiles']);
    $benefit = mysqli_real_escape_string($conn, $_POST['benefit']);
    $weakness = mysqli_real_escape_string($conn, $_POST['weakness']);
    $origin = mysqli_real_escape_string($conn, $_POST['origin']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
    $doi = mysqli_real_escape_string($conn, $_POST['doi']);

    // Truy vấn để cập nhật dữ liệu vào cơ sở dữ liệu
    $sql = "UPDATE compoundBioLib 
            SET name='$name', smiles='$smiles', benefit='$benefit', weakness='$weakness', origin='$origin', purpose='$purpose', doi='$doi' 
            WHERE cid='$cid'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cập nhật dữ liệu thành công!')</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật dữ liệu: " . $conn->error . "')</script>";
    }
} else {
    echo "<script>alert('Vui lòng nhập đầy đủ thông tin hợp chất!')</script>";
}

// Đóng kết nối
$conn->close();

echo "<script>
setTimeout(() => {
window.location.href = 'index.php';
}, 1000);
</script>";
?>
