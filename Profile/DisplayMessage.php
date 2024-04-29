<?php
$servername = "localhost"; // Change this to your database server hostname
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "coursework"; // Change this to your database name

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message if connection fails
    die("Connection failed: " . $e->getMessage());
}

// Fetch notifications for the current user
if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];

    try {
        // Prepare and execute SQL query to fetch notifications
        $stmt = $pdo->prepare("SELECT * FROM notification WHERE UserID = :userID");
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->execute();
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database error if query fails
        die("Error: " . $e->getMessage());
    }
}
?>