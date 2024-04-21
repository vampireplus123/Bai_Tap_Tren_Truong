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

    public function uploadImage($tableName, $conditionField = null, $conditionValue = null, $avatarFieldName = null) {
        $avatarFieldName = $avatarFieldName ?? $this->avatarField;
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

            // Construct SQL query
            $sql = "UPDATE $tableName SET $avatarFieldName=:avatarPath";
            if ($conditionField && $conditionValue) {
                $sql .= " WHERE $conditionField=:conditionValue";
            }
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':avatarPath', $avatarPath);
            if ($conditionField && $conditionValue) {
                $stmt->bindParam(':conditionValue', $conditionValue);
            }
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

// Retrieve form data including the table name and avatar field name
$dbHost = $_POST['dbHost'] ?? 'localhost';
$dbName = $_POST['dbName'];
$dbUsername = $_POST['dbUsername'] ?? 'root';
$dbPassword = $_POST['dbPassword'] ?? '';
$tableName = $_POST['tableName'] ?? '';
$avatarField = $_POST['avatarField'] ?? '';

// Create an instance of ImageUploader and upload the image
$uploader = new ImageUploader($_SERVER['DOCUMENT_ROOT'] . "/GenralFunction/images/", 'username', $avatarField, $dbHost, $dbName, $dbUsername, $dbPassword);
$uploader->uploadImage($tableName);
?>