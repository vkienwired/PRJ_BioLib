<?php
session_start();
include_once 'connectdb.php';

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Thu thập dữ liệu Cơ bản (Dùng mysqli_real_escape_string chống Hacker)
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username'])); 
    $password = $_POST['password'];

    // 2. Thu thập dữ liệu Học thuật
    $academic_level = mysqli_real_escape_string($conn, $_POST['academic_level']);
    
    // Xử lý thông minh: Nếu user chọn "Khác" thì lấy dữ liệu từ ô text ẩn
    $major = ($_POST['major'] === 'Khác' && !empty($_POST['major_other'])) ? mysqli_real_escape_string($conn, trim($_POST['major_other'])) : mysqli_real_escape_string($conn, $_POST['major']);
    $research_interests = ($_POST['research_interests'] === 'Khác' && !empty($_POST['research_other'])) ? mysqli_real_escape_string($conn, trim($_POST['research_other'])) : mysqli_real_escape_string($conn, $_POST['research_interests']);
    
    $institution = mysqli_real_escape_string($conn, trim($_POST['institution']));
    $academic_statement = mysqli_real_escape_string($conn, trim($_POST['academic_statement']));
    
    // Gom các link profile thành 1 chuỗi để dễ lưu (Đỡ phải tạo 3 cột)
    $profile_links = mysqli_real_escape_string($conn, "GS: " . trim($_POST['gs_link']) . " | ORCID: " . trim($_POST['orcid_link']) . " | RG: " . trim($_POST['rg_link']));
    $publications = mysqli_real_escape_string($conn, trim($_POST['publications']));

    // 3. Kiểm tra xem username hoặc email đã có ai xài chưa
    $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_res = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_res) > 0) {
        $error_msg = "Tên đăng nhập hoặc Email này đã được đăng ký trong hệ thống mất rồi!";
    } else {
        // 4. Mã hóa mật khẩu siêu cấp việt vị (Bcrypt)
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // 5. Lưu vào Database
        $sql = "INSERT INTO users (username, password, role, fullname, email, academic_level, major, institution, research_interests, academic_statement, profile_links, publications) 
                VALUES ('$username', '$pass_hash', 'user', '$fullname', '$email', '$academic_level', '$major', '$institution', '$research_interests', '$academic_statement', '$profile_links', '$publications')";
        
        if (mysqli_query($conn, $sql)) {
            $success_msg = "Đăng ký thành công rực rỡ! Hồ sơ Contributor của cậu đã sẵn sàng.";
        } else {
            $error_msg = "Lỗi hệ thống: " . mysqli_error($conn);
        }
    }
}
include_once 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký Contributor - BIOLIB</title>
    <style>
        .reg-container { 
            width: 750px; 
            margin: 50px auto 150px auto; 
            background: #fff; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            border-top: 5px solid rgb(160, 196, 157); 
        }
        h2 { text-align: center; color: #333; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; font-style: italic; }
        
        .section-title { 
            background: rgb(247, 255, 229); 
            padding: 10px; 
            font-weight: bold; 
            border-left: 4px solid rgb(160, 196, 157); 
            margin-top: 30px; 
            margin-bottom: 15px; 
            font-size: 18px;
        }
        
        .form-group { margin-bottom: 20px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; font-size: 15px; color: #333; }
        
        input[type="text"], input[type="email"], input[type="password"], select, textarea { 
            width: 95%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            font-family: Arial; 
            font-size: 15px;
            background-color: #f9f9f9;
        }
        input:focus, select:focus, textarea:focus {
            border-color: rgb(160, 196, 157);
            background-color: #fff;
            outline: none;
        }

        .hidden-input { display: none; margin-top: 10px; border-color: #f77f00 !important; }
        
        .checkbox-group { margin-bottom: 15px; display: flex; align-items: flex-start; }
        .checkbox-group input { margin-right: 10px; margin-top: 4px; transform: scale(1.2); cursor: pointer; }
        .checkbox-group label { font-weight: normal; font-size: 15px; cursor: pointer; line-height: 1.4; }
        
        .note { font-size: 13px; color: #777; font-style: italic; margin-bottom: 5px; display: block; }
        
        .btn-submit { 
            background-color: rgb(160, 196, 157); 
            color: #000; 
            padding: 15px; 
            border: none; 
            border-radius: 8px; 
            width: 100%; 
            font-size: 18px; 
            font-weight: bold; 
            cursor: pointer; 
            margin-top: 20px; 
            transition: 0.3s;
        }
        .btn-submit:hover { 
            background-color: rgb(196, 215, 178); 
            box-shadow: 0 0 0 4px rgb(247 127 0 / 10%);
        }
        
        .msg { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-weight: bold; }
        .msg.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .msg.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .login-link { display: inline-block; margin-top: 15px; padding: 10px 20px; background-color: #155724; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="reg-container">
        <h2>ĐĂNG KÝ THÀNH VIÊN ĐÓNG GÓP (CONTRIBUTOR)</h2>
        <p class="subtitle">Dành cho các nhà nghiên cứu, giảng viên và sinh viên</p>

        <?php if($error_msg) echo "<div class='msg error'>$error_msg</div>"; ?>
        <?php if($success_msg) { 
            echo "<div class='msg success'>
                    $success_msg<br>
                    <a href='login.php' class='login-link'>Quay lại Đăng nhập ngay</a>
                  </div>"; 
        } else { ?>

        <form action="register.php" method="POST">
            <div class="section-title">I. Thông tin định danh</div>
            <div class="form-group">
                <label>1. Họ và tên (theo giấy tờ chính thức) *</label>
                <input type="text" name="fullname" placeholder="Nguyễn Văn A" required>
            </div>
            <div class="form-group">
                <label>2. Tên đăng nhập (Username) *</label>
                <span class="note">Viết liền không dấu, dùng để đăng nhập hệ thống.</span>
                <input type="text" name="username" placeholder="nguyenvana_vn" required>
            </div>
            <div class="form-group">
                <label>3. Email học thuật hoặc email liên hệ chính thức *</label>
                <span class="note">Khuyến khích sử dụng email thuộc cơ sở đào tạo, Viện nghiên cứu (VD: @edu.vn)</span>
                <input type="email" name="email" placeholder="example@hust.edu.vn" required>
            </div>
            <div class="form-group">
                <label>4. Mật khẩu bảo mật *</label>
                <input type="password" name="password" placeholder="Tối thiểu 6 ký tự" required>
            </div>

            <div class="section-title">II. Thông tin học thuật và chuyên môn</div>
            <div class="form-group">
                <label>5. Trình độ đào tạo / Vị trí học thuật hiện tại *</label>
                <select name="academic_level" required>
                    <option value="">-- Vui lòng chọn --</option>
                    <option value="Sinh viên đại học">Sinh viên đại học (chuyên ngành liên quan)</option>
                    <option value="Học viên cao học">Học viên cao học</option>
                    <option value="Nghiên cứu sinh">Nghiên cứu sinh</option>
                    <option value="Giảng viên">Giảng viên</option>
                    <option value="Nghiên cứu viên">Nghiên cứu viên</option>
                    <option value="Cán bộ phòng thí nghiệm">Cán bộ phòng thí nghiệm</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div class="form-group">
                <label>6. Chuyên ngành chính *</label>
                <select name="major" id="major_select" onchange="toggleOther('major_select', 'major_other')" required>
                    <option value="">-- Chọn chuyên ngành --</option>
                    <option value="Hóa sinh">Hóa sinh</option>
                    <option value="Sinh học phân tử">Sinh học phân tử</option>
                    <option value="Dược lý học">Dược lý học</option>
                    <option value="Hóa dược">Hóa dược</option>
                    <option value="Công nghệ sinh học">Công nghệ sinh học</option>
                    <option value="Y học phân tử">Y học phân tử</option>
                    <option value="Vi sinh y học">Vi sinh y học</option>
                    <option value="Hóa học hữu cơ ứng dụng">Hóa học hữu cơ ứng dụng trong dược học</option>
                    <option value="Khác">Khác (Tự điền)</option>
                </select>
                <input type="text" name="major_other" id="major_other" class="hidden-input" placeholder="Vui lòng nhập chuyên ngành của bạn...">
            </div>
            <div class="form-group">
                <label>7. Cơ sở đào tạo / Đơn vị công tác *</label>
                <span class="note">(Trường đại học, bệnh viện, viện nghiên cứu, doanh nghiệp công nghệ sinh học…)</span>
                <input type="text" name="institution" required>
            </div>
            <div class="form-group">
                <label>8. Lĩnh vực nghiên cứu hoặc quan tâm chuyên sâu *</label>
                <select name="research_interests" id="research_select" onchange="toggleOther('research_select', 'research_other')" required>
                    <option value="">-- Chọn lĩnh vực quan tâm --</option>
                    <option value="Cơ chế enzyme">Cơ chế enzyme</option>
                    <option value="Tương tác protein–ligand">Tương tác protein–ligand</option>
                    <option value="Thiết kế thuốc">Thiết kế thuốc</option>
                    <option value="Chuyển hóa tế bào ung thư">Chuyển hóa tế bào ung thư</option>
                    <option value="Chất chuyển hóa thứ cấp từ thực vật">Chất chuyển hóa thứ cấp từ thực vật Việt Nam</option>
                    <option value="Khác">Khác (Tự điền)</option>
                </select>
                <input type="text" name="research_other" id="research_other" class="hidden-input" placeholder="Vui lòng nhập lĩnh vực của bạn...">
            </div>

            <div class="section-title">III. Hồ sơ học thuật ngắn gọn</div>
            <div class="form-group">
                <label>9. Tóm tắt định hướng học thuật (Academic Statement) *</label>
                <span class="note">Mô tả: Kinh nghiệm nghiên cứu (nếu có), Chủ đề quan tâm, Mong muốn đóng góp (Tối đa 300 từ)</span>
                <textarea name="academic_statement" rows="5" placeholder="Trong môi trường khoa học, cách một người trình bày tư duy cho thấy mức độ nghiêm túc..." required></textarea>
            </div>

            <div class="section-title">IV. Xác minh và minh chứng học thuật</div>
            <div class="form-group">
                <label>10. Liên kết hồ sơ khoa học (Nếu có)</label>
                <input type="text" name="gs_link" placeholder="Link Google Scholar..." style="margin-bottom:8px;">
                <input type="text" name="orcid_link" placeholder="Link hoặc mã số ORCID..." style="margin-bottom:8px;">
                <input type="text" name="rg_link" placeholder="Link ResearchGate...">
            </div>
            <div class="form-group">
                <label>11. Công bố khoa học tiêu biểu (Nếu có)</label>
                <span class="note">Cho phép nhập mã DOI. Đây là cơ sở để hệ thống cấp huy hiệu "Contributor đã xác minh".</span>
                <input type="text" name="publications" placeholder="VD: 10.1038/s41586-020-2012-7">
            </div>

            <div class="section-title">V. Cam kết đạo đức và chuẩn mực khoa học</div>
            <span class="note" style="margin-bottom: 10px; color: red;">* Trong lĩnh vực y sinh, phần này là bắt buộc.</span>
            
            <div class="checkbox-group">
                <input type="checkbox" id="camket1" name="camket1" required>
                <label for="camket1">Tôi cam kết nội dung đóng góp tuân thủ chuẩn mực học thuật, có trích dẫn nguồn đầy đủ.</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="camket2" name="camket2" required>
                <label for="camket2">Tôi cam kết không đăng tải dữ liệu bệnh nhân, thông tin cá nhân nhạy cảm hoặc nội dung vi phạm đạo đức nghiên cứu sinh học.</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="camket3" name="camket3" required>
                <label for="camket3">Tôi hiểu rằng nội dung sẽ được thẩm định trước khi công bố.</label>
            </div>

            <button type="submit" class="btn-submit">NỘP HỒ SƠ CONTRIBUTOR</button>
        </form>
        <?php } ?>
    </div>

    <script>
        function toggleOther(selectId, inputId) {
            var selectBox = document.getElementById(selectId);
            var inputBox = document.getElementById(inputId);
            if (selectBox.value === "Khác") {
                inputBox.style.display = "block";
                inputBox.required = true; // Bắt buộc nhập nếu chọn Khác
                inputBox.focus(); // Tự động đưa con trỏ chuột vào ô
            } else {
                inputBox.style.display = "none";
                inputBox.required = false;
                inputBox.value = ""; // Xóa trắng data cũ nếu đổi ý
            }
        }
    </script>
</body>
<?php include_once 'footer.php'; ?>
</html>