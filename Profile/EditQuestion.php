<?php
// Database configuration
session_start();
$servername = "localhost"; // Change this to your database server hostname
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "questionfield"; // Change this to your database name

// Create connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Display an error message if connection fails
    echo "Connection failed: " . $e->getMessage();
    exit(); // Terminate the script
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_question'])) {
    // Get the question ID and updated details from the form
    $question_id = $_POST['question_id'];
    $question_name = $_POST['question_name'];
    $question_detail = $_POST['question_detail'];

    try {
        // Update the question in the database
        $update_query = "UPDATE questionfield SET QuestionName = :question_name, QuestionDetail = :question_detail WHERE ID = :question_id";
        $update_statement = $pdo->prepare($update_query);
        $update_statement->bindParam(':question_name', $question_name, PDO::PARAM_STR);
        $update_statement->bindParam(':question_detail', $question_detail, PDO::PARAM_STR);
        $update_statement->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $update_statement->execute();

        // Redirect to the profile page after updating
        header("Location: ProfilePage.php");
        exit();
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error updating question: " . $e->getMessage();
    }
} else {
    // If the form is not submitted, redirect to the home page
    header("Location: /Home/Home.php");
    exit();
}
?>
