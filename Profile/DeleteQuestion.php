<?php

// Function to delete a question
function deleteQuestion($questionId, $pdo) {
    try {
        // Prepare and execute SQL query to delete the question
        $query = "DELETE FROM questionfield WHERE ID = :questionId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
        $stmt->execute();
        
        return true; // Return true if deletion is successful
    } catch (PDOException $e) {
        // Handle the exception (e.g., log the error, display an error message)
        echo "Error: " . $e->getMessage();
        return false; // Return false if deletion fails
    }
}

// Check if the delete button is clicked
if (isset($_POST['delete_question'])) {
    // Get the question ID to delete
    $questionIdToDelete = $_POST['question_id']; // Assuming you pass the question ID via a form

    // Database connection parameters
    $servername = "localhost";
    $username = 'root';
    $password = '';
    $database = "questionfield";

    try {
        // Create a PDO instance
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Call the deleteQuestion function
        if (deleteQuestion($questionIdToDelete, $pdo)) {
            // Question deleted successfully
            echo "Question deleted successfully.";
        } else {
            // Failed to delete the question
            echo "Failed to delete the question.";
        }
    } catch (PDOException $e) {
        // Handle connection errors
        echo "Connection failed: " . $e->getMessage();
    }

    // Close the database connection
    $pdo = null;
}
?>
