<?php
// Include the database connection file or define connection details here
$host = 'localhost';
$dbname = 'questionfield';
$username = 'root';
$password = '';
// Function to establish a PDO connection
function connectToDatabase($host, $dbname, $username, $password) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

// Function to insert a comment into the database
function insertComment($pdo, $comment, $question_id) {
    $sql = "INSERT INTO comment (Content, CommentID) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$comment, $question_id]);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the comment and question ID are set
    if (isset($_POST["comment"]) && isset($_POST["question_id"])) {
        // Sanitize and validate input data
        $comment = htmlspecialchars($_POST["comment"]);
        $question_id = intval($_POST["question_id"]);

        // Establish connection to the database
        $pdo = connectToDatabase($host, $dbname, $username, $password);

        // If connection is successful, insert the comment
        if ($pdo) {
            if (insertComment($pdo, $comment, $question_id)) {
                // Redirect back to the QuestionPage.php after successful comment insertion
                header("Location: QuestionPage.php");
                exit();
            } else {
                echo "Error: Failed to insert comment.";
            }

            // Close connection
            $pdo = null;
        } else {
            echo "Error: Database connection failed.";
        }
    } else {
        echo "Error: Comment or question ID not provided.";
    }
} else {
    echo "Error: Form not submitted.";
}
?>
