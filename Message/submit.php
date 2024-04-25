<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "questionfield";

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve form data
    $title = $_POST['postTitle'];
    $content = $_POST['postContent'];
    $userID = $_SESSION['user_id']; // Assuming the user is logged in and their ID is stored in the session

    // Prepare SQL statement to insert data into the notification table
    $sql = "INSERT INTO notification (Title, Detail, UserID) VALUES (:title, :content, :userID)";
    $stmt = $conn->prepare($sql);
    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':userID', $userID);
    // Execute the statement
    $stmt->execute();

    // Notification successfully inserted
    header("Location: /Profile/ProfilePage.php");

} catch(PDOException $e) {
    // Error occurred while inserting notification
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
