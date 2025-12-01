<?php
include('connectdb.php'); // Kết nối đến CSDL
# $command="conda install -y  -n IntelPython310_gmxMMPBSA ipython";
# shell_exec($command);
#$command = "conda run -n IntelPython310_gmxMMPBSA python3.10 ./smiles.py ". $smiles ." ". $cid . " 2>&1";
#exec($command,$output,$return_var);
#echo "Command: $command<br>";
#echo "Return var: $return_var<br>";
#echo "Output:\n" . implode("<br>", $output) . "<br>";

#exit(0);
// Kiểm tra xem dữ liệu đã được gửi từ biểu mẫu thêm mới hay không
if (
    isset($_POST['name']) &&
    isset($_POST['cid'])
) {
    $name = $_POST['name'];
    $cid = $_POST['cid'];
    $smiles = $_POST["smiles"];
    $benefit = $_POST["benefit"];
    $weakness = $_POST["weakness"];
    $origin = $_POST["origin"];
    $purpose = $_POST["purpose"];
    $doi = $_POST["doi"];
    if (!empty($name) && !empty($cid)) {
        // Truy vấn để thêm dữ liệu vào cơ sở dữ liệu
        $sql= "INSERT INTO compoundBioLib (name, cid, smiles, benefit, weakness, origin, purpose, doi)
    VALUES ('$name', '$cid', '$smiles', '$benefit', '$weakness', '$origin', '$purpose', '$doi')";
        // Vẽ SVG và lưu vào thư mục img
        // Chạy tập lệnh Python và lấy dữ liệu SVG
	$command = "conda run -n IntelPython310_gmxMMPBSA python3.10 smiles.py ". escapeshellarg($smiles) ." " .escapeshellarg($cid) . " 2>&1";
	exec($command,$output,$return_var);
	/*echo "Command: $command<br>";
	echo "Return var: $return_var<br>";
	echo "Output:\n" . implode("\n", $output) . "\n";

	if ($return_var!==0){;
		echo "Lỗi khi thực thi script:\n";
		foreach ($output as $line) {
        		echo $line . "\n";}
		} else{
			echo "thuc thi thanh cong". implode("\n".$output);
		}*/
        // Thực thi truy vấn
        if ($conn->query($sql) === TRUE) {
            // Thêm dữ liệu thành công
		echo "<script>alert('Thêm hợp chất mới thành công!')</script>";
        } else {
            // Lỗi khi thêm dữ liệu
		echo "<script>alert('Lỗi khi thêm hợp chất mới: " . $conn->error . "')</script>";
        }
    } else {
        // Thiếu dữ liệu
	echo "<script>alert('Vui lòng nhập đầy đủ thông tin hợp chất!')</script>";
    }
}

// Đóng kết nối
$conn->close();
// Chuyển hướng về trang chủ
echo "<script>
setTimeout(() => {
window.location.href = 'index.php';
}, 100);
</script>";
