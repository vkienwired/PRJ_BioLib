<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    
    // Tìm kiếm tương đối theo tên hoặc cid (Chỉ lấy hợp chất đã approved)
    $sql = "SELECT name, cid FROM compoundbiolib 
            WHERE (name LIKE '%$keyword%' OR cid LIKE '%$keyword%') 
            AND status = 'approved' 
            LIMIT 10"; // Giới hạn 10 kết quả cho nhẹ web
            
    $result = mysqli_query($conn, $sql);
    $compounds = array();
    
    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $compounds[] = $row;
        }
        // Trả về JSON nếu có kết quả
        echo json_encode(['status' => 'success', 'data' => $compounds]);
    } else {
        // Trả về empty nếu không tìm thấy
        echo json_encode(['status' => 'empty']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing keyword']);
}
?>