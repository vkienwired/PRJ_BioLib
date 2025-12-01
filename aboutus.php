<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thư viện hợp chất sinh học Việt Nam</title>
    <style>
        .information {
            text-align: center;
            padding-bottom: 100px;
        }

        .information p {
            text-align: justify;
            text-indent: 2em;
            margin: 10px 50px 20px 50px;
            padding: 0;
        }

        .list {
            list-style-type: disc;
            padding: 0;
            margin: 0;
            display: block;
            padding-left: 50px;
        }

        .list li {
            display: list-item;
            margin: 5px 50px 10px 50px;
            text-align: left;
        }
    </style>
</head>
<?php
include_once 'header.php';
?>

<body>

    <div class="information">
        <h4>BioLib là gì?</h4>
        <p>
            Cổng thông tin hợp chất sinh học Việt Nam là một nguồn tài nguyên trực
            tuyến toàn diện dành cho các nhà khoa học, nhà nghiên cứu, sinh viên và
            công chúng quan tâm đến lĩnh vực hóa học sinh học tại Việt Nam. Cổng thông
            tin cung cấp thông tin về các hợp chất sinh học được tìm thấy trong các
            nguồn tài nguyên tự nhiên của Việt Nam, cũng như các ứng dụng tiềm năng của
            chúng trong các lĩnh vực y học, nông nghiệp và công nghiệp... BioLib là nơi lưu trữ
            cũng như bảo tồn những giá trị kiến thức, nghiên cứu, thông tin được đóng góp và chọn lọc.
        </p>
        <h4>Mục đích hướng tới của BioLib:</h4>
        <ul class="list">
            <li>Cung cấp thông tin về hợp chất sinh học Việt Nam.</li>
            <li>Khuyến khích nghiên cứu và phát triển các hợp chất sinh học có tiềm năng ứng dụng.</li>
            <li>Nâng cao nhận thức cộng đồng, chia sẻ thông tin về tầm quan trọng và ứng dụng của hợp chất sinh học.</li>
            <li>Nơi lưu trữ, bảo tồn kho tàng kiến thức về sinh học, hóa học, y học.</li>
        </ul>
        <h4>Các tính năng của Cổng thông tin hợp chất sinh học Việt Nam:</h4>
        <ul class="list">
            <li>Cơ sở dữ liệu các hợp chât sinh học: cung cấp các thông tin của hợp chất về cấu trúc, mã số PubChem CID,
                lợi ích, hạn chế, nguồn gốc của chúng.</li>
            <li>Công cụ tìm kiếm: cho phép người dùng dễ dàng tìm kiếm các hợp chất dựa trên tên, mã số hợp chất CID,
                lợi ích, nguồn gốc của chúng.</li>
            <li>Cơ sở dữ liệu mở: cho phép người dùng đóng góp, chỉnh sửa thông tin hợp chất, cùng nhau xây dựng cơ sở dữ liệu chung.</li>
        </ul>
        <h4>Các thông tin về hợp chất được cung cấp:</h4>
        <ul class="list">
            <li>Hợp chất: Tên khoa học của hợp chất sinh học.</li>
            <li>PubChem CID: : là mã một số duy nhất được gán cho mỗi hợp chất hóa học trong cơ sở dữ liệu PubChem. Nó giống như một
                mã số nhận dạng giúp phân biệt các hợp chất khác nhau một cách dễ dàng và chính xác.</li>
            <li>Hình ảnh cấu trúc 2D: Là hình ảnh mô phỏng 2D được tạo ra từ mã Canonical SMILES của hợp chất. Trong đó Canonical SMILES 
                (viết tắt của Canonical Simplified Molecular Input Line Entry Specification) là một cách biểu diễn duy nhất và chuẩn hóa của 
                cấu trúc phân tử sử dụng chuỗi ký tự ASCII ngắn. Nó được xây dựng dựa trên SMILES (Simplified Molecular Input Line Entry Specification) 
                là một định dạng phổ biến để mô tả cấu trúc phân tử. </li>
            <li>Lợi ích: những ưu điểm mà hợp chất có tiềm năng để ứng dụng trong các ngành y tế, nông nghiệp, lâm nghiệp,…</li>
            <li>Hạn chế: những nhược điểm hợp chất có thể gây ra cho con người nếu sử dụng.</li>
            <li>Nguồn gốc: Nguồn gốc có thể tìm thấy hợp chất có thể là thực vật, vi sinh vật, hay được con người tổng hợp.</li>
            <li>Mục đích sử dụng: Mục tiêu nghiên cứu, tiềm năng của hợp chất.</li>
            <li>Tài liệu tham khảo: Nguồn tài liệu tham khảo của nghiên cứu về hợp chất, sẽ được lưu bằng mã DOI của bài nghiên cứu.</li>    
        </ul>
    </div>

</body>
<?php
include_once 'footer.php';
?>

</html>