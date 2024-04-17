<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    // Kết nối đến cơ sở dữ liệu
    $servername = "localhost";
    $username = 'root'; // Replace with your database username
    $password = ''; // Replace with your database password
    $dbname = "user"; 

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Thiết lập chế độ lỗi PDO để ném ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Đường dẫn tới thư mục lưu trữ hình ảnh
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/GenralFunction/images/";
        // Tên file mới (có thể sử dụng tên người dùng hoặc ID của họ)
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        // Đuôi file
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Kiểm tra xem file có phải là hình ảnh hay không
        $check = getimagesize($_FILES["image"]["tmp_name"]);

        if ($check !== false) {
            // Kiểm tra kích thước file (giới hạn 5MB)
            if ($_FILES["image"]["size"] > 5000000) {
                echo "File của bạn quá lớn. Chỉ chấp nhận hình ảnh nhỏ hơn 5MB.";
            } else {
                // Di chuyển file vào thư mục lưu trữ
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Cập nhật đường dẫn hình ảnh vào cột Avatar trong cơ sở dữ liệu
                    $avatar_path = "/GenralFunction/images/" . basename($_FILES["image"]["name"]);
                    // Lấy tên người dùng từ session hoặc từ cơ sở dữ liệu (tuỳ thuộc vào cách bạn thực hiện)
                    $username = $_SESSION['username']; // hoặc $username = "TênNgườiDùng";
                    // Sử dụng prepared statement để tránh injection
                    $stmt = $conn->prepare("UPDATE user SET Avatar=:avatar_path WHERE UserName=:username");
                    $stmt->bindParam(':avatar_path', $avatar_path);
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();
                    header("Location: /Profile/ProfilePage.php");
                } else {
                    echo "Có lỗi xảy ra khi upload file.";
                }
            }
        } else {
            echo "File không phải là hình ảnh.";
        }
    } catch(PDOException $e) {
        echo "Lỗi kết nối: " . $e->getMessage();
    }
    $conn = null;
} else {
    echo "Có lỗi xảy ra khi upload file.";
}
?>
