<?php

session_start();

// Kiểm tra xem có dữ liệu được gửi từ form hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối đến cơ sở dữ liệu
    $servername = "localhost";
    $username = 'root'; // Thay thế bằng tên người dùng cơ sở dữ liệu của bạn
    $password = ''; // Thay thế bằng mật khẩu cơ sở dữ liệu của bạn
    $dbname = "questionfield"; // Thay thế bằng tên cơ sở dữ liệu của bạn
    
    try {
        // Tạo kết nối đến cơ sở dữ liệu sử dụng PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        // Thiết lập chế độ báo lỗi và ngoại lệ cho kết nối
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Lấy dữ liệu từ form
        $questionName = $_POST['question_name'];
        $tag = $_POST['tag'];
        // Giả sử bạn muốn sử dụng tên người dùng "Anonymous" làm Publisher
        $publisher = $_SESSION['username']; 
        $questionDetail = $_POST['question_detail'];

        // Tạo truy vấn để chèn dữ liệu vào bảng
        $sql = "INSERT INTO questionfield (QuestionName, Tag, Publisher, QuestionDetail)
                VALUES (:questionName, :tag, :publisher, :questionDetail)";
        
        // Chuẩn bị truy vấn
        $stmt = $conn->prepare($sql);
        
        // Bind các giá trị vào các tham số của truy vấn
        $stmt->bindParam(':questionName', $questionName);
        $stmt->bindParam(':tag', $tag);
        $stmt->bindParam(':publisher', $publisher);
        $stmt->bindParam(':questionDetail', $questionDetail);
        
        // Thực thi truy vấn
        $stmt->execute();

        // Thiết lập thông báo thành công và chuyển hướng người dùng về trang Home.php
        $_SESSION['success_message'] = "Câu hỏi đã được gửi thành công.";
        header("Location: /Home/Home.php");
    } catch(PDOException $e) {
        // Xử lý lỗi nếu có
        echo "Error: " . $e->getMessage();
    }

    // Đóng kết nối đến cơ sở dữ liệu
    $conn = null;
}
?>
