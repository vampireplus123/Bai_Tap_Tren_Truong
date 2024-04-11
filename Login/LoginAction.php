<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$dbname = "user";   // Replace with your database name
// Tạo kết nối đến cơ sở dữ liệu sử dụng PDO
session_start(); // Bắt đầu session
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Thiết lập chế độ báo lỗi để bắt các ngoại lệ PDOException
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin đăng nhập từ biểu mẫu
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn cơ sở dữ liệu để kiểm tra thông tin đăng nhập
    $stmt = $conn->prepare("SELECT ID FROM user WHERE UserName = :username AND Password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Kiểm tra xem có dòng nào trả về từ cơ sở dữ liệu không
    if ($stmt->rowCount() > 0) {
        // Xác thực thành công, lấy user_id từ kết quả truy vấn
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['ID'];
        
        $user_name = $row['UserName'];
        // Lưu user_id vào session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        // Chuyển hướng đến trang chính sau khi đăng nhập thành công
        header("location: /Home/Home.php");
        exit();
    } else {
        // Xác thực không thành công, hiển thị thông báo lỗi hoặc chuyển hướng đến trang đăng nhập lại
        echo "Invalid username or password";
    }
}

// Đóng kết nối
$conn = null;
?>