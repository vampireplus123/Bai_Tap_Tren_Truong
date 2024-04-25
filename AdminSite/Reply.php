<?php
session_start();

if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate form data
        $notification_id = $_POST['notification_id'];
        $reply = $_POST['reply'];

        // Perform any additional validation if needed

        // Insert reply into the database
        $host = 'localhost'; // Replace with your host
        $dbname = 'questionfield'; // Replace with your database name
        $username = 'root'; // Replace with your username
        $password = ''; // Replace with your password

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("UPDATE notification SET Reply = :reply WHERE ID = :notification_id");
            $stmt->bindParam(':reply', $reply, PDO::PARAM_STR);
            $stmt->bindParam(':notification_id', $notification_id, PDO::PARAM_INT);
            $stmt->execute();

            // Set success session variable
            $_SESSION['reply_success'] = true;

            // Redirect back to the page
            header("Location: NoticePage.php");
            exit();
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Handle case where form was not submitted via POST method
        header("Location: NoticePage.php");
        exit();
    }
} else {
    // Handle case where admin is not logged in
    echo "Admin not logged in.";
}
?>
