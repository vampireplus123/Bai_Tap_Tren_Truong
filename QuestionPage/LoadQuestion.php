<?php
        $servername = "localhost";
        $username = 'root'; // Replace with your database username
        $password = ''; // Replace with your database password
        $dbname = "questionfield";   // Replace with your database name
        $conn = new mysqli($servername, $username, $password, $dbname);

        try {
            // Kết nối đến cơ sở dữ liệu
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Thiết lập chế độ lỗi
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Lấy ID của câu hỏi từ URL
            if (isset($_GET['id'])) {
                $question_id = $_GET['id'];
                // Truy vấn cơ sở dữ liệu để lấy thông tin của câu hỏi từ bảng 'questionfield'
                $question_query = "SELECT * FROM questionfield WHERE ID = :id";
                $question_statement = $conn->prepare($question_query);
                $question_statement->bindParam(':id', $question_id);
                $question_statement->execute();
                $question_result = $question_statement->fetch(PDO::FETCH_ASSOC);
            }
        } catch(PDOException $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
        }
?>