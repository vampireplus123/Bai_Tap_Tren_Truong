<?php

$servername = "localhost";
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$dbname = "questionfield";   // Replace with your database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $question_id = $_GET['id'];
        $question_query = "SELECT * FROM questionfield WHERE ID = :id";
        $question_statement = $conn->prepare($question_query);
        $question_statement->bindParam(':id', $question_id);
        $question_statement->execute();
        $question_result = $question_statement->fetch(PDO::FETCH_ASSOC);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $question_id = $_POST['ID'];
        $comment_content = $_POST['comment_content'];

        $sql = "INSERT INTO comment (ID, Comment) VALUES (:question_id, :comment_content)";
        $insert_statement = $conn->prepare($sql);
        $insert_statement->bindParam(':question_id', $question_id);
        $insert_statement->bindParam(':comment_content', $comment_content);
        $insert_statement->execute();

        echo "Comment successfully added.";

        $comment_query = "SELECT Comment FROM comment WHERE ID = :id";
        $comment_statement = $conn->prepare($comment_query);
        $comment_statement->bindParam(':question_id', $question_id);
        $comment_statement->execute();

        $comments = $comment_statement->fetchAll(PDO::FETCH_ASSOC);

        if ($comments) {
            foreach ($comments as $comment) {
                echo "Comment: " . $comment["Comment"] . "<br>";
            }
        } else {
            echo "No comments yet.";
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null; // Close the connection
?>
