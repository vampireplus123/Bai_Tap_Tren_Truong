<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Thông tin kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = 'root'; // Thay đổi thành tên người dùng của bạn
$password = ''; // Thay đổi thành mật khẩu của bạn
$dbname = "user"; // Thay đổi thành tên cơ sở dữ liệu của bạn

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kiểm tra xem người dùng đã gửi file lên chưa
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
        // Kiểm tra xem có phiên đăng nhập hay không
        if (isset($_SESSION['username'])) {
            // Lấy tên người dùng từ phiên đăng nhập
            $username = $_SESSION['username'];

            // Đường dẫn thư mục để lưu trữ hình ảnh
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Kiểm tra kích thước của hình ảnh
            if ($_FILES["image"]["size"] > 500000) {
                echo "Hình ảnh quá lớn.";
                $uploadOk = 0;
            }

            // Cho phép chỉ một số định dạng hình ảnh cụ thể
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Chỉ chấp nhận các tệp JPG, JPEG, PNG & GIF.";
                $uploadOk = 0;
            }

            // Kiểm tra nếu $uploadOk không được đặt thành 0 sau tất cả các kiểm tra
            if ($uploadOk == 0) {
                echo "Hình ảnh của bạn không được tải lên.";
            } else {
                // Di chuyển hình ảnh vào thư mục được chỉ định
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    echo "Tệp " . htmlspecialchars(basename($_FILES["image"]["name"])) . " đã được tải lên thành công.";

                    // Lưu đường dẫn vào cơ sở dữ liệu
                    $avatarPath = "images/" . basename($_FILES["image"]["name"]);

                    // Chuẩn bị và thực thi truy vấn để cập nhật đường dẫn hình ảnh trong cơ sở dữ liệu
                    $stmt = $conn->prepare("UPDATE user SET Avatar = :avatarPath WHERE UserName = :username");
                    $stmt->bindParam(':avatarPath', $avatarPath);
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $AvatarProfile = $avatarPath;
                } else {
                    echo "Đã xảy ra lỗi khi tải lên hình ảnh.";
                }
            }
        } else {
            echo "Bạn phải đăng nhập trước khi tải lên hình ảnh.";
        }
    }
} catch(PDOException $e) {
    echo "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
}

// Đóng kết nối
$conn = null;
?>