<?php

$servername = "localhost";
$username = 'root';
$password = '';
$database = "questionfield";


    // Create a PDO instance
$pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
// Set the PDO error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/// Function to delete a question
function deleteQuestion($pdo, $questionId) {
    try {
        // Prepare and execute SQL query to delete the question
        $query = "DELETE FROM questionfield WHERE ID = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$questionId]);
        
        return true; // Return true if deletion is successful
    } catch (PDOException $e) {
        // Handle the exception (e.g., log the error, display an error message)
        error_log("Error deleting question: " . $e->getMessage());
        return false; // Return false if deletion fails
    }
}
function DisplayMessage($message)
{
    echo "<script>alert('$message');</script>";
    header("Location: ProfilePage.php");
}

// Check if the delete button is clicked
if (isset($_POST['delete_question'])) {
    // Get the question ID to delete
    $questionIdToDelete = isset($_POST['question_id']) ? $_POST['question_id'] : null;

    if ($questionIdToDelete !== null) {
        // Call the deleteQuestion function
        if (deleteQuestion($pdo, $questionIdToDelete)) {
            // Set a flag to indicate successful deletion
            $deleteSuccess = true;
            DisplayMessage('Done');
        } else {
            // Failed to delete the question
            $deleteSuccess = false;
            DisplayMessage('Failed');
        }
    } else {
        // Invalid question ID
        $deleteSuccess = false;
        DisplayMessage('Invalid Question');
    }
}
?>