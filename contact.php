<?php
// Biến để lưu trữ thông tin liên hệ
$contact_info = array(

    "address" => " Đại học Khoa học Tự nhiên, Đại học Quốc gia Hà Nội, 334 Nguyễn Trãi, Thanh Xuân, Hà Nội, Việt Nam",
    "website" => "https://mscskeylab.hus.vnu.edu.vn/",
    "contact_form" => "contact_form.php"
);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thư viện hợp chất sinh học Việt Nam</title>
    <style>
        .container1 {
            width: 700px;
            height: 400px;
        }

        .map {
            width: 100%;
            height: auto;
            margin: 10px auto 10px 10px;

            border: 1px solid #c7c7c7;
            margin-bottom: 15px;

        }

        .website {
            color: #000;
            text-decoration: none;
            text-align: left;
            cursor: pointer;

        }

        h2 {
            text-align: center;
        }

        .form-container {
            width: 400px;
            background: rgb(196, 215, 178);
            border: 2px solid transparent;
            padding: 32px 24px;
            font-size: 14px;
            font-family: arial;
            color: white;
            display: flex;
            flex-direction: column;
            gap: 20px;
            box-sizing: border-box;
            border-radius: 16px;
            margin-right: 10px;
            margin-left: 100px;
            padding-bottom: 50px;
        }

        .form-container button:active {
            scale: 0.95;
        }

        .form-container .form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-container .form-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .form-container .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #717171;
            font-weight: 600;
            font-size: 12px;
        }

        .form-container .form-group input {
            width: 90%;
            padding: 12px 16px;
            border-radius: 8px;
            color: #313131;
            font-family: inherit;
            background-color: rgb(247, 255, 229);
            border: 1px solid #414141;
        }

        .form-container .form-group textarea {
            width: 90%;
            padding: 12px 16px;
            border-radius: 8px;
            resize: none;
            color: #313131;
            height: 96px;
            border: 1px solid #414141;
            background-color: rgb(247, 255, 229);
            font-family: inherit;
        }

        .form-container .form-group input::placeholder {
            opacity: 0.5;
        }

        .form-container .form-group input:focus {
            outline: none;
            border-color: #e81cff;
        }

        .form-container .form-group textarea:focus {
            outline: none;
            border-color: #e81cff;
        }

        .form-container .form-submit-btn {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            align-self: flex-start;
            font-family: inherit;
            color: #000;
            font-weight: 600;
            width: 40%;
            background: rgb(247, 255, 229);
            border: 1px solid #414141;
            padding: 12px 16px;
            font-size: inherit;
            gap: 8px;
            margin-top: 8px;
            cursor: pointer;
            border-radius: 6px;
        }

        .form-container .form-submit-btn:hover {
            background-color: rgb(160, 196, 157);
            border-color: #fff;
            color: #fff;
        }

        .contact-container {
            display: flex;
            justify-content: center;
            padding-bottom: 100px;
        }

        #ul-contact {
            display: inline-block;
            margin-right: 20px;
            margin-top: 10px;
            margin-left: 10px;
            text-align: left;
        }
    </Style>
</head>

<?php
include_once 'header.php';
?>

<body>
    <h2>Liên hệ với chúng tôi:</h2>
    <div class="contact-container">
        <div class="container1">
            <h4>Phòng thí nghiệm Trọng điểm Khoa học tính toán đa tỉ lệ cho các Hệ phức hợp (VNU Key Laboratory for Multiscale Simulation of Complex Systems)</h4>
            <ul id="ul-contact">
                <li>Địa chỉ: <?php echo $contact_info['address']; ?></li>
                <li><a href="<?php echo $contact_info['website']; ?>" class="website">Website:<?php echo $contact_info['website']; ?></a></li>
            </ul><br>
            <div class="map" id="map">
                <iframe width="100%" height="175" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.9229048098523!2d105.80550057476837!3d20.995727288913272!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135add3018563bb%3A0x70bf956e9956d3f0!2zMzM0IE5ndXnhu4VuIFRyw6NpLCBUaGFuaCBYdcOibiBUcnVuZywgVGhhbmggWHXDom4sIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1702995394971!5m2!1svi!2s"></iframe><br><small><a href="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.9229048098523!2d105.80550057476837!3d20.995727288913272!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135add3018563bb%3A0x70bf956e9956d3f0!2zMzM0IE5ndXnhu4VuIFRyw6NpLCBUaGFuaCBYdcOibiBUcnVuZywgVGhhbmggWHXDom4sIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1702995394971!5m2!1svi!2s" style="color:#666;text-align:left;font-size:12px"></a></small>
            </div>
        </div>
        <div class="form-container">
            <form class="form" action="process_contact.php" method="post" name="form">
                <div class="form-group">
                    <label for="email">Email của bạn</label>
                    <input type="email" id="email" name="email" required="">
                </div>
                <div class="form-group">
                    <label for="textarea">Chúng tôi có thể giúp gì cho bạn?</label>
                    <textarea name="message" id="message" rows="10" cols="50" required="">          </textarea>
                </div>
                <button class="form-submit-btn" type="submit" name="submit">Gửi</button>
            </form>
        </div>
    </div>
</body>
<?php
include_once 'footer.php';
?>

</html>