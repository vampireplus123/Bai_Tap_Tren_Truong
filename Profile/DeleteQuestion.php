<?php
// Start session
session_start();

// Database connection parameters
$host = 'localhost';
$dbname = 'questionfield';
$username = 'root';
$password = '';

// Check if the form is submitted and the question_id is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_question']) && isset($_POST['question_id'])) {
    // Get the question_id from the form submission
    $questionId = $_POST['question_id'];

    // Attempt to establish a database connection
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Set PDO to throw exceptions on error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare a delete query
        $deleteQuery = "DELETE FROM `questionfield` WHERE `ID` = ?";
        $statement = $pdo->prepare($deleteQuery);

        // Bind the question_id parameter
        $statement->bindParam(1, $questionId, PDO::PARAM_INT);

        // Execute the delete query
        $statement->execute();
        echo "Received Question ID: " . $questionId;
        // Redirect back to the profile page after deletion
        header("Location: ProfilePage.php");
        exit();
    } catch (PDOException $e) {
        // Handle database connection error or query execution error
        die("Error: " . $e->getMessage());
    }
} else {
    // Redirect to the profile page if the form is not submitted or question_id is not set
    header("Location: ProfilePage.php");
    exit();
}
?>
