<?php
session_start();

class ImageUploader {
    private $targetDir;
    private $usernameField;
    private $avatarField;
    private $dbHost;
    private $dbName;
    private $dbUsername;
    private $dbPassword;

    public function __construct($targetDir, $usernameField, $avatarField, $dbHost, $dbName, $dbUsername, $dbPassword) {
        $this->targetDir = $targetDir;
        $this->usernameField = $usernameField;
        $this->avatarField = $avatarField;
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUsername = $dbUsername;
        $this->dbPassword = $dbPassword;
    }

    public function uploadImage() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
            try {
                // Connect to the database
                $conn = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // File details
                $targetFile = $this->targetDir . basename($_FILES["image"]["name"]);

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
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    throw new Exception("Có lỗi xảy ra khi upload file.");
                }

                // Update database with the image path
                $avatarPath = "/GenralFunction/images/" . basename($_FILES["image"]["name"]);
                $username = $_SESSION[$this->usernameField]; // or retrieve username from session
                $stmt = $conn->prepare("UPDATE user SET $this->avatarField=:avatarPath WHERE $this->usernameField=:username");
                $stmt->bindParam(':avatarPath', $avatarPath);
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
        } else {
            echo "Có lỗi xảy ra khi upload file.";
        }
    }
}

// Retrieve database connection details from the form
$dbHost = $_POST['dbHost'];
$dbName = $_POST['dbName']; 
$dbUsername = $_POST['dbUsername']; 
$dbPassword = $_POST['dbPassword']; 

// Usage example:
$uploader = new ImageUploader($_SERVER['DOCUMENT_ROOT'] . "/GenralFunction/images/", 'username', 'Avatar', $dbHost, $dbName, $dbUsername, $dbPassword);
$uploader->uploadImage();
?>
