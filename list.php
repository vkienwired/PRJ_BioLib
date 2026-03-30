<?php
session_start();
include_once 'connectdb.php';
include_once 'header.php';

// Thiết lập thuật toán phân trang (Pagination)
$limit = 20; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

// Truy vấn tổng số bản ghi hợp lệ để tính toán số lượng trang
$sql_count = "SELECT COUNT(stt) AS total FROM compoundBioLib WHERE status = 'approved'";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

// Truy vấn trích xuất dữ liệu theo giới hạn trang hiện tại
$sql_list = "SELECT stt, name, cid, origin FROM compoundBioLib 
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
        .list-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .list-header h2 {
            color: #2c3e50;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .list-header p {
            color: #555;
            font-size: 16px;
        }
        .compound-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            margin-bottom: 25px;
        }
        .compound-table th, .compound-table td {
            border: 1px solid #dcdcdc;
            padding: 12px;
            text-align: left;
            vertical-align: middle; 
        }
        .compound-table th {
            background-color: #f1f3f5;
            color: #2c3e50;
            font-weight: bold;
            text-align: center;
        }
        .compound-table td {
            text-align: center;
        }
        .compound-table td:nth-child(1),
        .compound-table td:nth-child(4) {
            text-align: left; 
        }
        .compound-table tr:hover {
            background-color: #f8f9fa;
        }
        /* Hiệu ứng mờ dần khi xóa bằng JavaScript */
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }
        
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
            cursor: pointer; /* Bổ sung con trỏ cho thẻ a dùng JavaScript */
        }
        .edit-btn { background-color: #f39c12; }
        .edit-btn:hover { background-color: #d68910; }
        .delete-btn { background-color: #e74c3c; border: none; }
        .delete-btn:hover { background-color: #c0392b; }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #2980b9;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .pagination a.active {
            background-color: #2c3e50;
            color: white;
            border: 1px solid #2c3e50;
        }
        .pagination a:hover:not(.active) {
            background-color: #f1f3f5;
        }
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
                    <tr id="row-<?php echo $row['stt']; ?>"> <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['cid']); ?></td>
                        <td>
                            <?php 
                                $image_path = "img/" . htmlspecialchars($row['cid']) . ".svg";
                            ?>
                            <img src="<?php echo $image_path; ?>" 
                                 alt="Cấu trúc 2D của <?php echo htmlspecialchars($row['name']); ?>" 
                                 class="structure-img"
                                 onerror="this.onerror=null; this.src='img/default_structure.svg'; this.alt='Dữ liệu cấu trúc đang cập nhật';">
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

            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a href="list.php?page=<?php echo $page - 1; ?>">Trước</a>
                <?php endif; ?>

                <?php 
                for ($i = 1; $i <= $total_pages; $i++): 
                ?>
                    <a href="list.php?page=<?php echo $i; ?>" class="<?php if($page == $i) echo 'active'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if($page < $total_pages): ?>
                    <a href="list.php?page=<?php echo $page + 1; ?>">Sau</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <p style="text-align: center; color: #666; font-style: italic; padding: 40px 0;">
                Hệ thống hiện tại chưa ghi nhận dữ liệu hợp chất nào được công bố.
            </p>
        <?php endif; ?>
    </div>

    <script>
        function executeAsyncDelete(id, buttonElement) {
            // Xác nhận thao tác trước khi thực thi
            if (confirm('Cảnh báo: Bạn có chắc chắn muốn xóa hợp chất này? Dữ liệu bị xóa không thể khôi phục.')) {
                
                // Gửi yêu cầu GET ngầm tới delete_compound.php
                fetch('delete_compound.php?id=' + id)
                    .then(response => response.text())
                    .then(data => {
                        // Kiểm tra phản hồi trả về
                        if (data.trim() === 'success') {
                            alert('Hệ thống: Đã xóa thành công hợp chất khỏi cơ sở dữ liệu.');
                            
                            // Hiệu ứng mờ dần và xóa dòng HTML chứa hợp chất
                            let tr = buttonElement.closest('tr');
                            tr.classList.add('fade-out');
                            setTimeout(() => {
                                tr.remove();
                                
                                // (Tùy chọn) Cập nhật lại số lượng tổng trên giao diện
                                let totalElement = document.getElementById('total-count');
                                if (totalElement) {
                                    totalElement.innerText = parseInt(totalElement.innerText) - 1;
                                }
                            }, 500); // Đợi 500ms cho hiệu ứng chạy xong
                            
                        } else {
                            alert('Lỗi hệ thống: Quá trình xóa thất bại. Dữ liệu trả về: ' + data);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi kết nối:', error);
                        alert('Lỗi mạng: Không thể kết nối tới máy chủ để thực hiện thao tác.');
                    });
            }
        }
    </script>
</body>
<?php include_once 'footer.php'; ?>
</html>