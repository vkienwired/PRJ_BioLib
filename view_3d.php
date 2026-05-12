<?php
// File: view_3d.php
if (!isset($_GET['cid'])) {
    die("<h2 style='text-align:center; font-family:Arial; margin-top:50px;'>⚠️ Thiếu mã hợp chất (CID). Vui lòng truy cập từ danh sách tìm kiếm!</h2>");
}
$cid = intval($_GET['cid']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mô hình 3D - BioLib</title>
    <script src="https://3Dmol.csb.pitt.edu/build/3Dmol-min.js"></script>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f3f3f4; 
            margin: 0; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
        }
        
        .header-title {
            color: #333;
            margin-bottom: 20px;
        }

        .viewer-container { 
            width: 90%; 
            max-width: 800px; 
            height: 500px; 
            background: #fff; 
            border-radius: 12px; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.1); 
            position: relative; 
            overflow: hidden; 
            border: 3px solid rgb(160, 196, 157); 
        }
        
        #loading-screen { 
            position: absolute; 
            top: 0; left: 0; right: 0; bottom: 0; 
            background: rgba(255,255,255,0.95); 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            z-index: 10; 
        }
        
        .spinner { 
            border: 5px solid rgba(0, 0, 0, 0.05); 
            width: 50px; 
            height: 50px; 
            border-radius: 50%; 
            border-left-color: rgb(160, 196, 157); 
            animation: spin 1s linear infinite; 
            margin-bottom: 15px; 
        }
        
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        #mol-viewer { width: 100%; height: 100%; }
        
        .controls {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }

        .btn { 
            padding: 10px 20px; 
            background: rgb(196, 215, 178); 
            color: #0d0c22; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: bold; 
            border: 2px solid rgb(160, 196, 157); 
            transition: 0.3s; 
            cursor: pointer;
            font-size: 15px;
        }
        
        .btn:hover { 
            background: rgb(160, 196, 157); 
            color: #fff; 
            box-shadow: 0 0 0 4px rgb(225, 236, 200);
        }
    </style>
</head>
<body>

    <h2 class="header-title">Khảo sát không gian 3D Hợp chất (CID: <?php echo $cid; ?>)</h2>

    <div class="viewer-container">
        <div id="loading-screen">
            <div class="spinner"></div>
            <h3 style="color:#555;">Đang nhờ RDKit tính toán cấu trúc 3D... Đợi chút nhé!</h3>
        </div>
        
        <div id="mol-viewer"></div>
    </div>

    <div class="controls">
        <button class="btn" onclick="viewer.zoomTo()">Căn giữa mô hình</button>
        <button class="btn" onclick="window.close();" style="background:#e0e0e0; border-color:#ccc;">⬅ Đóng cửa sổ</button>
    </div>

    <script>
        const cid = <?php echo $cid; ?>;
        const loadingScreen = document.getElementById('loading-screen');
        const viewerDiv = document.getElementById('mol-viewer');
        
        // Khởi tạo không gian 3Dmol
        let viewer = $3Dmol.createViewer(viewerDiv, {backgroundColor: "white"});

        // Giao tiếp với API PHP bằng Fetch API (AJAX)
        fetch('api_generate_3d.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'cid=' + cid
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Thành công thì tải file .sdf lên để vẽ
                fetch(data.url)
                    .then(res => res.text())
                    .then(sdfData => {
                        viewer.addModel(sdfData, "sdf");
                        
                        // Cài đặt style: Dạng que (stick) chuẩn Hóa tin học
                        viewer.setStyle({}, {stick: {colorscheme: 'Jmol', radius: 0.15}, sphere: {scale: 0.25}});
                        viewer.zoomTo();
                        viewer.render();
                        
                        // Render xong thì ẩn màn hình chờ đi
                        loadingScreen.style.display = 'none';
                    });
            } else {
                loadingScreen.innerHTML = `<h3 style="color:#d9534f;">Lỗi: ${data.message}</h3>`;
            }
        })
        .catch(error => {
            console.error(error);
            loadingScreen.innerHTML = `<h3 style="color:#d9534f;">Lỗi kết nối đến máy chủ!</h3>`;
        });
    </script>
</body>
</html>