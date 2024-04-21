<?php
// Start session

// Database connection parameters
$host = 'localhost';
$dbname = 'questionfield';
$username = 'root';
$password = '';

// Attempt to establish a database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection error
    die("Database connection failed: " . $e->getMessage());
}

// Fetch user profile data based on the logged-in user's session
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    // Prepare and execute query to fetch user profile data
    $profileQuery = "SELECT * FROM `user` WHERE `ID` = ?";
    $profileStatement = $pdo->prepare($profileQuery);
    $profileStatement->execute([$userId]);
    
    // Fetch profile data
    $profileData = $profileStatement->fetch(PDO::FETCH_ASSOC);
}

// Fetch questions data associated with the logged-in user
if (isset($userId)) {
    // Prepare and execute query to fetch questions data for the user
    $questionsQuery = "SELECT * FROM `questionfield` WHERE `UserID` = ?";
    $questionsStatement = $pdo->prepare($questionsQuery);
    $questionsStatement->execute([$userId]);
    
    // Fetch questions data
    $questions = $questionsStatement->fetchAll(PDO::FETCH_ASSOC);
}

// Close the database connection (optional, as PHP will automatically close it at the end of script execution)
// $pdo = null;
?>
