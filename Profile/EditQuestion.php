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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <!-- Include any necessary CSS files -->
</head>
<body>
    <h2>Edit Question</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="question_id" value="<?php echo $_POST['question_id']; ?>">
        <label for="question_name">Question Name:</label><br>
        <input type="text" id="question_name" name="question_name" value="<?php echo $_POST['question_name']; ?>"><br>
        <label for="question_detail">Question Detail:</label><br>
        <textarea id="question_detail" name="question_detail"><?php echo $_POST['question_detail']; ?></textarea><br>
        <input type="submit" name="update_question" value="Save Changes">
    </form>
</body>
</html>