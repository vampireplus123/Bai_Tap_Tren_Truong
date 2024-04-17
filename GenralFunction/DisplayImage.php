<?php
$servername = "localhost";
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$dbname = "user"; 
if (session_status() == PHP_SESSION_NONE) 
{
    session_start();
}
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Thiết lập chế độ lỗi PDO để ném ngoại lệ
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kiểm tra xem session có tồn tại không
    if(isset($_SESSION['username'])) {
        // Lấy tên người dùng từ session
        $username = $_SESSION['username'];

        // Truy vấn cột Avatar từ bảng user
        $stmt = $conn->prepare("SELECT Avatar FROM user WHERE UserName=:username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $Avatar = "";
        // Kiểm tra xem có kết quả từ truy vấn không
        if ($stmt->rowCount() > 0) {
            // Lấy dữ liệu kết quả từ truy vấn
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Trả về đường dẫn hình ảnh avatar nếu tồn tại
            // echo $row['Avatar'];
            $Avatar = $row['Avatar'];
        } else {
            echo "Không tìm thấy hình ảnh avatar.";
        }
    } else {
        echo "Vui lòng đăng nhập để xem hình ảnh avatar.";
    }
} catch(PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
}
$conn = null;
?>
