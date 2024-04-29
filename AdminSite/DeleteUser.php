<?php
// Check if user ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Retrieve user ID from the URL
    $userID = $_GET['id'];

    // Database connection
    $dsn = "mysql:host=localhost;dbname=coursework";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare a DELETE statement to delete the user
        $sql = "DELETE FROM `user` WHERE `ID` = :userID";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':userID', $userID);

        // Execute the statement
        $stmt->execute();

        // Redirect back to the page where users are listed
        header("Location: AdminHome.php");
        exit();
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    unset($pdo);
} else {
    // If user ID is not provided, redirect to the page where users are listed
    header("Location: AdminHome.php");
    exit();
}
?>
