<?php

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

    $query = "SELECT questionfield.ID, user.UserName, questionfield.QuestionName, questionfield.Tag, questionfield.QuestionDetail
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
} catch (PDOException $e) {
    // Xử lý lỗi
    echo "Connection failed: " . $e->getMessage();
}

?>
