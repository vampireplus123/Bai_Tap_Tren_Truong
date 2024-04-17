<?php
// Start or resume session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection information
$servername = "localhost";
$username = 'root'; // Change to your username
$password = ''; // Change to your password
$dbname = "user"; // Change to your database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user has uploaded a file
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
        // Check if there is an active session
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username']; // Get username from session

            // Directory path to store images
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check image size
            if ($_FILES["image"]["size"] > 500000) {
                echo "Image is too large.";
                $uploadOk = 0;
            }

            // Allow only specific image file formats
            $allowedFormats = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedFormats)) {
                echo "Only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Proceed if no errors occurred
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    echo "File " . htmlspecialchars(basename($_FILES["image"]["name"])) . " uploaded successfully.";

                    // Save file path in the database
                    $avatarPath = "images/" . basename($_FILES["image"]["name"]);

                    // Prepare and execute query to update image path in the database
                    $stmt = $conn->prepare("UPDATE user SET Avatar = :avatarPath WHERE UserName = :username");
                    $stmt->bindParam(':avatarPath', $avatarPath);
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();

                    // Set avatar profile path
                    $AvatarProfile = $avatarPath;
                } else {
                    echo "Error uploading image.";
                }
            }
        } else {
            echo "You must log in before uploading an image.";
        }
    }
} catch(PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}

// Close connection
$conn = null;
?>
