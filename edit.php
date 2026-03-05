<?php
session_start();
include_once 'connectdb.php';

// Xác thực quyền truy cập
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để thực hiện chức năng chỉnh sửa dữ liệu.'); window.location.href='login.php';</script>";
    exit();
}

// Kiểm tra tham số định danh
if (!isset($_GET['id'])) {
    die("<script>alert('Lỗi: Không tìm thấy định danh hợp chất.'); window.location.href='index.php';</script>");
}
$id = mysqli_real_escape_string($conn, $_GET['id']);

// Truy xuất dữ liệu gốc để hiển thị trên form
$sql_old = "SELECT * FROM compoundBioLib WHERE stt = '$id'";
$res_old = mysqli_query($conn, $sql_old);
$old_data = mysqli_fetch_assoc($res_old);

if (!$old_data) {
    die("Dữ liệu hợp chất không tồn tại trên hệ thống.");
}

// Xử lý dữ liệu cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = mysqli_real_escape_string($conn, trim($_POST['name']));
    $cid      = mysqli_real_escape_string($conn, trim($_POST['cid']));
    $smiles   = mysqli_real_escape_string($conn, trim($_POST['smiles']));
    $benefit  = mysqli_real_escape_string($conn, trim($_POST['benefit']));
    $weakness = mysqli_real_escape_string($conn, trim($_POST['weakness']));
    $origin   = mysqli_real_escape_string($conn, trim($_POST['origin']));
    $purpose  = mysqli_real_escape_string($conn, trim($_POST['purpose']));
    $doi      = mysqli_real_escape_string($conn, trim($_POST['doi']));
    $created_by = $_SESSION['user_id'];

    if ($_SESSION['role'] == 'admin') {
        // Quản trị viên cập nhật trực tiếp (Không cần tạo bản nháp)
        $update_sql = "UPDATE compoundBioLib SET name='$name', cid='$cid', smiles='$smiles', benefit='$benefit', weakness='$weakness', origin='$origin', purpose='$purpose', doi='$doi' WHERE stt='$id'";
        
        if (mysqli_query($conn, $update_sql)) {
            echo "<script>alert('Dữ liệu đã được cập nhật trực tiếp vào hệ thống.'); window.location.href='search.php';</script>";
        } else {
            $error_msg = mysqli_real_escape_string($conn, mysqli_error($conn));
            echo "<script>alert('Lỗi truy vấn SQL: " . $error_msg . "'); window.history.back();</script>";
        }
    } else {
        // Người dùng (Contributor) đề xuất chỉnh sửa -> Tạo bản nháp (Shadow Copy)
        $insert_sql = "INSERT INTO compoundBioLib (name, cid, smiles, benefit, weakness, origin, purpose, doi, status, created_by, edit_of)
                       VALUES ('$name', '$cid', '$smiles', '$benefit', '$weakness', '$origin', '$purpose', '$doi', 'pending_update', '$created_by', '$id')";
        
        // Cơ chế kiểm tra lỗi nghiêm ngặt
        if (mysqli_query($conn, $insert_sql)) {
            echo "<script>alert('Bản đề xuất chỉnh sửa đã được ghi nhận và đang chờ xét duyệt. Phiên bản gốc vẫn sẽ được duy trì hiển thị cho đến khi quá trình xét duyệt hoàn tất.'); window.location.href='search.php';</script>";
        } else {
            // Hiển thị mã lỗi cụ thể để phục vụ công tác gỡ lỗi (debugging)
            $error_msg = mysqli_real_escape_string($conn, mysqli_error($conn));
            echo "<script>alert('Lỗi hệ thống trong quá trình ghi dữ liệu: " . $error_msg . "'); window.history.back();</script>";
        }
    }
    exit();
}
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật dữ liệu - BIOLIB</title>
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
        .form-container { 
            width: 700px; 
            background: #fff; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            border-top: 5px solid rgb(160, 196, 157); 
        }
        h2 { color: #333; margin-bottom: 5px; text-align: center; }
        .subtitle { text-align: center; color: #666; font-style: italic; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { font-weight: bold; display: block; margin-bottom: 8px; font-size: 15px; color: #333; }
        input[type="text"], textarea { 
            width: 95%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            font-family: Arial, sans-serif; 
            font-size: 15px; 
            background-color: #f9f9f9; 
        }
        input:focus, textarea:focus { 
            border-color: rgb(160, 196, 157); 
            background-color: #fff; 
            outline: none; 
        }
        .button { 
            background-color: rgb(196, 215, 178); 
            color: #000; 
            padding: 15px; 
            border: none; 
            border-radius: 8px; 
            width: 100%; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            margin-top: 10px; 
            transition: 0.3s; 
        }
        .button:hover { 
            background-color: rgb(160, 196, 157); 
            box-shadow: 0 0 0 4px rgb(247 127 0 / 10%); 
        }
    </style>
</head>
<body>
    <div class="centered-content">
        <div class="form-container">
            <h2>CẬP NHẬT DỮ LIỆU HỢP CHẤT</h2>
            <p class="subtitle">Đang thực hiện chỉnh sửa dữ liệu cho: <b><?php echo htmlspecialchars($old_data['name']); ?></b></p>

            <form action="edit.php?id=<?php echo $id; ?>" method="post">
                <div class="form-group">
                    <label>Danh pháp hợp chất *</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($old_data['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Mã PubChem CID *</label>
                    <input type="text" name="cid" value="<?php echo htmlspecialchars($old_data['cid']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Chuỗi định dạng SMILES</label>
                    <input type="text" name="smiles" value="<?php echo htmlspecialchars($old_data['smiles']); ?>">
                </div>

                <div class="form-group">
                    <label>Hoạt tính sinh học</label>
                    <textarea name="benefit" rows="4"><?php echo htmlspecialchars($old_data['benefit']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Hạn chế / Tác dụng phụ</label>
                    <textarea name="weakness" rows="4"><?php echo htmlspecialchars($old_data['weakness']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Nguồn gốc</label>
                    <textarea name="origin" rows="4"><?php echo htmlspecialchars($old_data['origin']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Đích tác dụng</label>
                    <textarea name="purpose" rows="4"><?php echo htmlspecialchars($old_data['purpose']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Tài liệu tham khảo (DOI)</label>
                    <input type="text" name="doi" value="<?php echo htmlspecialchars($old_data['doi']); ?>">
                </div>

                <button type="submit" class="button">GỬI BẢN ĐỀ XUẤT CHỈNH SỬA</button>
            </form>
        </div>
    </div>
</body>
<?php include_once 'footer.php'; ?>
</html>