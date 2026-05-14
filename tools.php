<?php
session_start();
require_once 'config.php';
include 'header.php';

// Khởi tạo biến
$message = "";
$upload_success = false;
$uploaded_filename = "";

// Xử lý khi người dùng nhấn nút Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['protein_file'])) {
    $target_dir = "uploads/proteins/"; 
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["protein_file"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name; 
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file_type != "pdb") {
        $message = "<div class='alert error'>Lỗi: Chỉ chấp nhận tệp định dạng .pdb!</div>";
    } else {
        if (move_uploaded_file($_FILES["protein_file"]["tmp_name"], $target_file)) {
            $message = "<div class='alert success'>Tải tệp <b>$file_name</b> thành công! Vui lòng chọn bước tiếp theo phía dưới.</div>";
            $upload_success = true;
            $uploaded_filename = $file_name;
        } else {
            $message = "<div class='alert error'>Có lỗi xảy ra trong quá trình tải tệp lên.</div>";
        }
    }
}
?>

<style>
    .container {
        max-width: 900px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .upload-section {
        border: 2px dashed rgb(160, 196, 157);
        padding: 40px;
        text-align: center;
        border-radius: 8px;
        background: #f9fff0;
        margin-bottom: 30px;
    }

    .upload-section input[type="file"] {
        display: none;
    }

    .custom-file-upload {
        display: inline-block;
        padding: 12px 24px;
        cursor: pointer;
        background: rgb(160, 196, 157);
        color: #fff;
        border-radius: 5px;
        font-weight: bold;
        transition: 0.3s;
    }

    .custom-file-upload:hover {
        background: #6a9c68;
    }

    .btn-submit {
        margin-top: 15px;
        padding: 10px 30px;
        background: #2d5a27;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .action-sections {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .action-card {
        flex: 1;
        background: #fff;
        border: 1px solid rgb(160, 196, 157);
        border-radius: 8px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
    }

    .action-card h3 {
        color: #2d5a27;
        margin-top: 0;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        font-size: 20px;
    }

    .search-box {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        margin-bottom: 15px;
        justify-content: center;
    }

    .search-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .btn-search {
        padding: 10px 15px;
        background: rgb(160, 196, 157);
        color: #000;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-search:hover {
        background: #8ab887;
    }

    .search-results {
        border: 1px solid #eee;
        border-radius: 5px;
        max-height: 200px;
        overflow-y: auto;
        text-align: left;
        margin-bottom: 15px;
        display: none;
    }

    .compound-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px;
        border-bottom: 1px solid #f1f1f1;
        cursor: pointer;
        transition: 0.2s;
    }

    .compound-item:hover {
        background-color: #f9fff0;
    }

    .compound-item.selected {
        background-color: #e2f0d9;
        border: 2px solid #2d5a27;
        border-radius: 5px;
    }

    .btn-action {
        padding: 10px 25px;
        background: #2d5a27;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
        margin-top: auto; 
    }

    .btn-action:hover:not(:disabled) {
        background: #1e3c1a;
    }

    .btn-action:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .warning-text {
        color: #999;
        font-size: 14px;
        font-style: italic;
        margin-top: 10px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .instruction-section {
        background: #f1f1f1;
        padding: 25px;
        border-radius: 8px;
        line-height: 1.8;
    }

    .instruction-text {
        color: #444;
        text-align: justify;
        font-size: 16px;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
    }
    .success { background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; }
    .error { background-color: #f2dede; color: #a94442; border: 1px solid #ebccd1; }
</style>

<div class="container">
    <?php echo $message; ?>

    <div class="upload-section">
        <form action="tools.php" method="post" enctype="multipart/form-data">
            <p>Chọn tệp Protein của bạn để bắt đầu phân tích</p>
            <label for="protein-file" class="custom-file-upload">
                <span class="material-symbols-outlined" style="vertical-align: middle;">upload_file</span>
                Chọn tệp .PDB
            </label>
            <input id="protein-file" name="protein_file" type="file" accept=".pdb" onchange="updateFileName()"/>
            <div id="file-name" style="margin-top: 10px; font-style: italic; color: #666;">Chưa có tệp nào được chọn</div>
            <button type="submit" class="btn-submit">Tải lên và Tiếp tục</button>
        </form>
    </div>

    <?php if ($upload_success): ?>
    <div class="action-sections">
        <div class="action-card">
            <h3>Dự đoán ái lực liên kết</h3>
            <p style="font-size: 15px; color: #555;">Tìm kiếm hợp chất trong CSDL để dự đoán với protein <b><?php echo $uploaded_filename; ?></b></p>
            
            <div class="search-box">
                <input type="text" id="compound-keyword" class="search-input" placeholder="Nhập tên hợp chất, CID...">
                <button type="button" class="btn-search" onclick="searchCompound()">Tìm kiếm</button>
            </div>

            <div id="search-results-area" class="search-results"></div>

            <button class="btn-action" id="btn-start-docking" disabled>Bắt đầu</button>
        </div>

        <div class="action-card">
            <h3>Sàng lọc ảo</h3>
            <p style="font-size: 15px; color: #555;">Tìm kiếm các hợp chất có ái lực mạnh nhất trong hệ thống.</p>
            <div class="warning-text">
                Xin thông cảm vì CPU của web server khá yếu: Dual CPU Intel(R) Xeon(R) E5-2630 v4 @ 2.20GHz, 10 cores, 20 luồng nên thời gian xử lý có thể mất khá lâu.
            </div>
            <button class="btn-action">Sàng lọc ảo</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="instruction-section">
        <div class="instruction-text">
            Công cụ này cung cấp giải pháp tính toán mô phỏng nhằm đánh giá năng lượng liên kết giữa các hợp chất trong cơ sở dữ liệu BioLib và cấu trúc protein đích (target protein). Bằng việc tích hợp các thuật toán Học sâu, cụ thể là Mạng nơ-ron tích chập (Convolutional Neural Network - CNN), kết hợp cùng mô hình Động lực học phân tử (Molecular Dynamics), hệ thống cho phép dự đoán chính xác ái lực liên kết (binding affinity) và mô phỏng tương tác giữa phối tử (ligand) và thụ thể. Đồng thời, công cụ còn được trang bị tính năng Sàng lọc ảo (Virtual Screening), hỗ trợ tự động phân tích và đề xuất các hợp chất tiềm năng nhất trong BioLib thể hiện ái lực liên kết mạnh với protein mục tiêu.
        </div>
    </div>
</div>

<script>
    function updateFileName() {
        var input = document.getElementById('protein-file');
        var output = document.getElementById('file-name');
        if (input.files.length > 0) {
            output.innerHTML = "Tệp đã chọn: <b>" + input.files[0].name + "</b>";
        }
    }

    // Hàm gọi API tìm kiếm
    function searchCompound() {
        var keyword = document.getElementById('compound-keyword').value;
        var resultsArea = document.getElementById('search-results-area');
        var btnStart = document.getElementById('btn-start-docking');

        if(keyword.trim() === '') {
            alert('Vui lòng nhập từ khóa!');
            return;
        }

        // Hiện vùng kết quả và báo đang tìm
        resultsArea.style.display = 'block';
        resultsArea.innerHTML = '<div style="padding: 15px; text-align: center; color: #666;">Đang tìm kiếm...</div>';
        btnStart.disabled = true; // Khóa nút bắt đầu

        // Gọi tới file PHP API 
        fetch('api_search_compound.php?keyword=' + encodeURIComponent(keyword))
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    let html = '';
                    data.data.forEach(item => {
                        // Gọi ảnh đúng theo CID giống như cách bạn dùng ở list.php
                        let imgPath = 'img/' + item.cid + '.svg';
                        
                        html += `
                            <div class="compound-item" onclick="selectCompound(this)">
                                <img src="${imgPath}" alt="Structure" style="width: 45px; height: 45px; object-fit: contain;" onerror="this.src='img/default_structure.svg'">
                                <div>
                                    <strong>${item.name}</strong><br>
                                    <span style="font-size: 13px; color: #666;">CID: ${item.cid}</span>
                                </div>
                            </div>
                        `;
                    });
                    resultsArea.innerHTML = html;
                } else if(data.status === 'empty') {
                    // Nếu không có hợp chất trong cơ sở dữ liệu
                    resultsArea.innerHTML = '<div style="padding: 15px; text-align: center; color: #e74c3c; font-weight: bold;">Không có hợp chất này!</div>';
                }
            })
            .catch(error => {
                resultsArea.innerHTML = '<div style="padding: 15px; text-align: center; color: red;">Lỗi kết nối cơ sở dữ liệu!</div>';
                console.error('Error:', error);
            });
    }

    // JS Xử lý khi người dùng click chọn 1 hợp chất trong danh sách kết quả
    function selectCompound(element) {
        var items = document.getElementsByClassName('compound-item');
        for(var i=0; i<items.length; i++){
            items[i].classList.remove('selected');
        }

        // Thêm viền xanh vào hợp chất được click
        element.classList.add('selected');

        // Mở khóa nút Bắt đầu
        document.getElementById('btn-start-docking').disabled = false;
    }
</script>

<?php include 'footer.php'; ?>