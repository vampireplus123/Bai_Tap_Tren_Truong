<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header("Location: /Login/Login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "localhost";
    $username = 'root'; // Replace with your database username
    $password = ''; // Replace with your database password
    $dbname = "questionfield"; // Replace with your database name
    
    try {
        // Create a connection to the database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement to insert a new question
        $sql = "INSERT INTO questionfield (QuestionName, Tag, Publisher, QuestionDetail, UserID) 
                VALUES (:questionName, :tag, :publisher, :questionDetail, :userID)";
        
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':questionName', $_POST['question_name']);
        $stmt->bindParam(':tag', $_POST['tag']);
        $stmt->bindParam(':publisher', $_SESSION['username']);
        $stmt->bindParam(':questionDetail', $_POST['question_detail']);
        $stmt->bindParam(':userID', $_SESSION['user_id']); // Assuming you have 'user_id' stored in session
        
        $stmt->execute();

        // Get the ID of the inserted question
        $questionId = $conn->lastInsertId();
        echo $questionId;
        // Set success message and redirect
        $_SESSION['success_message'] = "Question submitted successfully. Question ID: $questionId";
        header("Location: /QuestionPage/QuestionPage.php?id=".$questionId);
        exit();
    } catch(PDOException $e) {
        // Display error message
        echo "Error: " . $e->getMessage();
    }

    // Close database connection
    $conn = null;
}
?>
