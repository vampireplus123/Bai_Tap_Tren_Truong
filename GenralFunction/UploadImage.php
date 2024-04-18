<?php
session_start();

function uploadImage($target_dir, $username_field, $avatar_field) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
        // Database connection settings
        $servername = "localhost";
        $username = 'root'; // Replace with your database username
        $password = ''; // Replace with your database password
        $dbname = "user"; 
        
        try {
            // Connect to the database
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // File details
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
    
            // Check if file is an image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                throw new Exception("File không phải là hình ảnh.");
            }
    
            // Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                throw new Exception("File của bạn quá lớn. Chỉ chấp nhận hình ảnh nhỏ hơn 5MB.");
            }
    
            // Move the uploaded file
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                throw new Exception("Có lỗi xảy ra khi upload file.");
            }
    
            // Update database with the image path
            $avatar_path = "/GenralFunction/images/" . basename($_FILES["image"]["name"]);
            $username = $_SESSION[$username_field]; // or retrieve username from database
            $stmt = $conn->prepare("UPDATE user SET $avatar_field=:avatar_path WHERE $username_field=:username");
            $stmt->bindParam(':avatar_path', $avatar_path);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
    
            // Redirect to profile page after successful upload
            header("Location: /Profile/ProfilePage.php");
            exit();
        } catch(PDOException $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        // Close the connection
        $conn = null;
    } else {
        echo "Có lỗi xảy ra khi upload file.";
    }
}

// Usage example:
$target_directory = $_SERVER['DOCUMENT_ROOT'] . "/GenralFunction/images/";
$username_field = 'username'; // Field name for username in your database
$avatar_field = 'Avatar'; // Field name for avatar path in your database
uploadImage($target_directory, $username_field, $avatar_field);
?>
