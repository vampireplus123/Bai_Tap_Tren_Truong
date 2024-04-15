<?php
$servername = "localhost";
$username = 'root';
$password = '';
$database = "questionfield";

// Thực hiện kết nối đến cơ sở dữ liệu
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Thiết lập chế độ lỗi PDO để báo cáo các lỗi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Nếu có lỗi khi kết nối đến cơ sở dữ liệu, hiển thị thông báo lỗi
    echo "Kết nối đến cơ sở dữ liệu thất bại: " . $e->getMessage();
    exit(); // Dừng việc thực thi kịp thời
}

// Kiểm tra xem phương thức là POST và tồn tại dữ liệu câu hỏi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_question'])) {
    // Lấy ID của câu hỏi từ biểu mẫu
    $question_id = $_POST['question_id'];

    // Truy vấn để lấy thông tin về câu hỏi dựa trên ID
    $query = "SELECT * FROM questionfield WHERE ID = :question_id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $statement->execute();
    $question = $statement->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem câu hỏi có tồn tại không
    if (!$question) {
        echo "Question not found.";
        exit(); // Dừng việc thực thi nếu không tìm thấy câu hỏi
    }

    // Lấy thông tin câu hỏi từ kết quả truy vấn
    $question_name = $question['QuestionName'];
    // Tương tự, bạn cần lấy các thông tin khác của câu hỏi (nếu có) để hiển thị trong biểu mẫu chỉnh sửa
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_question'])) {
    // Nếu dữ liệu được gửi từ biểu mẫu cập nhật
    // Lấy ID và tên câu hỏi từ biểu mẫu
    $question_id = $_POST['question_id'];
    $question_name = $_POST['question_name'];

    // Truy vấn cập nhật câu hỏi trong cơ sở dữ liệu
    $update_query = "UPDATE questionfield SET QuestionName = :question_name WHERE ID = :question_id";
    $update_statement = $pdo->prepare($update_query);
    $update_statement->bindParam(':question_name', $question_name, PDO::PARAM_STR);
    $update_statement->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $update_statement->execute();

    // Chuyển hướng người dùng về trang chính sau khi cập nhật
    header("Location: ProfilePage.php");
    exit();
} else {
    // Nếu không có dữ liệu post hoặc không tồn tại ID câu hỏi, chuyển hướng về trang chính
    header("Location: ProfilePage.php");
    exit(); // Dừng việc thực thi kịp thời
}
?>

<!-- Hiển thị biểu mẫu chỉnh sửa câu hỏi -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <!-- Thêm các liên kết cần thiết cho giao diện -->
</head>
<body>
    <h2>Edit Question</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
        <label for="question_name">Question Name:</label><br>
        <input type="text" id="question_name" name="question_name" value="<?php echo $question_name; ?>"><br>
        <!-- Tương tự, bạn có thể thêm các trường cho các thông tin khác của câu hỏi -->
        <br>
        <input type="submit" name="update_question" value="Save Changes">
    </form>
</body>
</html>
