<?php
// Include the database connection file or define connection details here
$host = 'localhost';
$dbname = 'coursework';
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

// Function to retrieve a question from the database
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

// Function to handle form submission
function SubmitForm($pdo) {
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the comment and question ID are set
        if (isset($_POST["comment"]) && isset($_POST["question_id"])) {
            // Sanitize and validate input data
            $comment = htmlspecialchars($_POST["comment"]);
            $question_id = intval($_POST["question_id"]);

            // Check if the user is logged in
            if (!isset($_SESSION['username'])) {
                // If not logged in, redirect to the login page or show a message
                echo "Bạn phải đăng nhập trước khi comment.";
                return;
            }

            // Establish connection to the database
            // If connection is successful, insert the comment
            if ($pdo) {
                if (insertComment($pdo, $comment, $question_id)) {
                    // Redirect back to the QuestionPage.php after successful comment insertion
                    header("Location: QuestionPage.php?id=" . $question_id);
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
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$pdo = connectToDatabase($host, $dbname, $username, $password);

// Check if the question ID is provided via GET parameter
if ($pdo && isset($_GET['id'])) {
    $question_id = $_GET['id'];
    // Store the question ID in session
    $_SESSION['last_question_id'] = $question_id;
} else {
    // If the question ID is not provided via GET parameter and we have a stored last question ID in session
    if (isset($_SESSION['last_question_id'])) {
        $question_id = $_SESSION['last_question_id'];
    } else {
        // Handle the case when no question ID is provided and no last question ID is stored
        exit("Error: No question ID provided.");
    }
}

// Retrieve the question and comments
$question = getQuestion($pdo, $question_id);
$comments = getCommentsForQuestion($pdo, $question_id);

// Call the form submission function only if the user is logged in
if (isset($_SESSION['username'])) {
    SubmitForm($pdo);
}

// Close the database connection
$pdo = null;
?>