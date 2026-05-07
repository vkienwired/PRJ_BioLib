<?php
session_start();
// ĐÃ SỬA: Nạp file cấu hình chuẩn để kết nối đúng cổng 3307
require_once 'config.php';
include_once 'header.php';

// --- 1. THUẬT TOÁN PHÂN TRANG (Giữ nguyên logic của cậu) ---
$limit = 20; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

// Truy vấn tổng số bản ghi đã phê duyệt
$sql_count = "SELECT COUNT(stt) AS total FROM compoundbioLib WHERE status = 'approved'";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

// --- 2. TRUY VẤN DỮ LIỆU ---
$sql_list = "SELECT stt, name, cid, origin FROM compoundbioLib 
             WHERE status = 'approved' 
             ORDER BY stt DESC 
             LIMIT $start_from, $limit";
$result_list = mysqli_query($conn, $sql_list);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Mục Hợp Chất | BioLib</title>
    <style>
        /* GIỮ NGUYÊN TOÀN BỘ CSS CỦA CẬU - KHÔNG CẮT XÉN */
        .list-container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 5px solid #2c3e50;
        }
        .list-header { text-align: center; margin-bottom: 30px; }
        .list-header h2 {
            color: #2c3e50;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .list-header p { color: #555; font-size: 16px; }
        .compound-table { width: 100%; border-collapse: collapse; font-size: 15px; margin-bottom: 25px; }
        .compound-table th, .compound-table td {
            border: 1px solid #dcdcdc;
            padding: 12px;
            text-align: left;
            vertical-align: middle; 
        }
        .compound-table th { background-color: #f1f3f5; color: #2c3e50; font-weight: bold; text-align: center; }
        .compound-table td { text-align: center; }
        .compound-table td:nth-child(1), .compound-table td:nth-child(4) { text-align: left; }
        .compound-table tr:hover { background-color: #f8f9fa; }
        .fade-out { opacity: 0; transition: opacity 0.5s ease-out; }
        .structure-img {
            max-width: 140px;
            max-height: 140px;
            object-fit: contain;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #eee;
            border-radius: 4px;
        }
        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 3px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
            color: white;
            transition: background-color 0.3s;
            cursor: pointer;
        }
        .edit-btn { background-color: #f39c12; }
        .delete-btn { background-color: #e74c3c; border: none; }
        .pagination { display: flex; justify-content: center; align-items: center; margin-top: 20px; }
        .pagination a {
            color: #2980b9;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
            font-weight: bold;
        }
        .pagination a.active { background-color: #2c3e50; color: white; border: 1px solid #2c3e50; }
    </style>
</head>
<body>
    <div class="list-container">
        <div class="list-header">
            <h2>Danh Mục Cơ Sở Dữ Liệu Hợp Chất</h2>
            <p>Tổng số hợp chất đã được hệ thống phê duyệt: <strong><span id="total-count"><?php echo $total_records; ?></span></strong></p>
        </div>

        <?php if (mysqli_num_rows($result_list) > 0): ?>
            <table class="compound-table">
                <thead>
                    <tr>
                        <?php $is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; ?>
                        <th style="width: <?php echo $is_admin ? '25%' : '30%'; ?>;">Danh pháp hợp chất</th>
                        <th style="width: 15%;">Mã định danh CID</th>
                        <th style="width: <?php echo $is_admin ? '20%' : '25%'; ?>;">Cấu trúc 2D</th>
                        <th style="width: <?php echo $is_admin ? '25%' : '30%'; ?>;">Nguồn gốc</th>
                        <?php if ($is_admin): ?>
                            <th style="width: 15%;">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result_list)): ?>
                    <tr id="row-<?php echo $row['stt']; ?>"> 
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['cid']); ?></td>
                        <td>
                            <?php 
                                // Load ảnh tĩnh từ thư mục img/ dựa trên mã CID
                                $image_path = "img/" . htmlspecialchars($row['cid']) . ".svg";
                            ?>
                            <img src="<?php echo $image_path; ?>?v=<?php echo time(); ?>" 
                                 alt="Cấu trúc 2D" 
                                 class="structure-img"
                                 onerror="this.onerror=null; this.src='img/default_structure.svg';">
                        </td>
                        <td><?php echo htmlspecialchars($row['origin']); ?></td>
                        <?php if ($is_admin): ?>
                            <td>
                                <a href="edit.php?id=<?php echo $row['stt']; ?>" class="action-btn edit-btn">Sửa</a>
                                <a onclick="executeAsyncDelete(<?php echo $row['stt']; ?>, this)" class="action-btn delete-btn">Xóa</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="list.php?page=<?php echo $i; ?>" class="<?php if($page == $i) echo 'active'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>

        <?php else: ?>
            <p style="text-align: center; color: #666; font-style: italic; padding: 40px 0;">
                Hệ thống hiện tại chưa ghi nhận dữ liệu hợp chất nào được công bố.
            </p>
        <?php endif; ?>
    </div>

    <script>
        /* GIỮ NGUYÊN TOÀN BỘ JAVASCRIPT CỦA CẬU */
        function executeAsyncDelete(id, buttonElement) {
            if (confirm('Cảnh báo: Bạn có chắc chắn muốn xóa hợp chất này?')) {
                fetch('delete_compound.php?id=' + id)
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {
                            let tr = buttonElement.closest('tr');
                            tr.classList.add('fade-out');
                            setTimeout(() => {
                                tr.remove();
                                let totalElement = document.getElementById('total-count');
                                if (totalElement) totalElement.innerText = parseInt(totalElement.innerText) - 1;
                            }, 500);
                        } else {
                            alert('Lỗi: ' + data);
                        }
                    });
            }
        }
    </script>
</body>
<?php include_once 'footer.php'; ?>
</html>