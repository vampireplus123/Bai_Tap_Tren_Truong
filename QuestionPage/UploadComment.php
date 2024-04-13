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
function getQuestion($pdo, $question_id) {
    $question_result = null;
    $question_query = "SELECT * FROM questionfield WHERE ID = :id";
    $question_statement = $pdo->prepare($question_query);
    $question_statement->bindParam(':id', $question_id);
    $question_statement->execute();
    $question_result = $question_statement->fetch(PDO::FETCH_ASSOC);
    return $question_result;
}

// Function to fetch comments for a question
function getCommentsForQuestion($pdo, $question_id) {
    $sql = "SELECT * FROM comment WHERE CommentID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$question_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Function to insert a comment into the database
function insertComment($pdo, $comment, $question_id) {
    $sql = "INSERT INTO comment (Content, CommentID) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$comment, $question_id]);
}
$pdo = connectToDatabase($host, $dbname, $username, $password);

if ($pdo && isset($_GET['id'])) {
    $question_id = $_GET['id'];
    $question = getQuestion($pdo, $question_id);
    $comments = getCommentsForQuestion($pdo, $question_id);
    $pdo = null;
} else {
    echo "<p>Invalid request.</p>";
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the comment and question ID are set
    if (isset($_POST["comment"]) && isset($_POST["question_id"])) {
        // Sanitize and validate input data
        $comment = htmlspecialchars($_POST["comment"]);
        $question_id = intval($_POST["question_id"]);

        // Establish connection to the database
        // If connection is successful, insert the comment
        if ($pdo) {
            if (insertComment($pdo, $comment, $question_id)) {
                // Redirect back to the QuestionPage.php after successful comment insertion
                header("Location: /Home/Home.php");
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
}
?>
