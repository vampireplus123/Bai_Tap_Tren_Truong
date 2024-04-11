<?php

// Bạn cần session_start() ở đầu trang nếu chưa có


// Đoạn mã kết nối CSDL và truy vấn dữ liệu
$servername = "localhost";
$username = 'root';
$password = '';
$userDatabase = "user";
$questionDatabase = "questionfield";

try {
    $userDsn = "mysql:host=$servername;dbname=$userDatabase";
    $userPdo = new PDO($userDsn, $username, $password);
    $userPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $questionDsn = "mysql:host=$servername;dbname=$questionDatabase";
    $questionPdo = new PDO($questionDsn, $username, $password);
    $questionPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT user.UserName, questionfield.QuestionName, questionfield.Tag, questionfield.QuestionDetail
            FROM user.user
            INNER JOIN questionfield.questionfield ON user.UserName = questionfield.Publisher
            WHERE user.UserName = :username";

    $stmt = $userPdo->prepare($query);
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $userPdo = null;
    $questionPdo = null;

    $profileName = null;
    $_SESSION['profile_data'] = $result;
// Hiển thị dữ liệu
    // foreach ($result as $row) {
    //     // Gán giá trị cho $profileName nếu nó chưa được gán
    //     if ($profileName === null) {
    //         $profileName = $row['UserName'];
    //     }
    //     // Các đoạn mã hiển thị thông tin khác có thể ở đây
    // }

    // // Kiểm tra xem $profileName có giá trị hay không trước khi hiển thị
    // if ($profileName !== null) {
    //     echo "<h5 class='card-title'>$profileName</h5>";
    // } else {
    //     echo "<h1>Profile Name Not Found</h1>";
    // }
} catch (PDOException $e) {
    // Xử lý lỗi
    echo "Connection failed: " . $e->getMessage();
}

?>
